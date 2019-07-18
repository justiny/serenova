<?php
/**
 * Copyright 2013 Nelio Software S.L.
 * This script is distributed under the terms of the GNU General Public
 * License.
 *
 * This script is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if ( !class_exists( 'NelioABPostAltExpCreationPage' ) ) {

	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );
	abstract class NelioABAltExpPage extends NelioABAdminAjaxPage {

		private $tabs;

		protected $basic_info;

		protected $alternatives;
		protected $goals;

		protected $form_name;

		protected $is_global;
		protected $tests_a_page;

		protected $finalization_mode;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->tabs = array();
			$this->set_icon( 'icon-nelioab' );
			$this->alternatives = array();
			$this->goals = array();
			$this->basic_info = array(
				'id'          => -1,
				'key_id'      => -1,
				'name'        => '',
				'description' => '',
				'otherNames'  => array(),
				'_form_id'     => time(),
			);
			$this->is_global = false;
		}

		protected abstract function get_alt_exp_type();
		protected abstract function print_alternatives();
		protected abstract function get_save_experiment_name();
		protected abstract function get_basic_info_elements();

		protected function set_form_name( $form_name ) {
			$this->form_name = $form_name;
		}

		public function add_another_experiment_name( $name ) {
			array_push( $this->basic_info['otherNames'], $name );
		}

		public function get_form_name() {
			return $this->form_name;
		}

		public function set_basic_info( $id, $key_id, $name, $description, $fin_mode, $fin_value ) {
			$this->basic_info['id'] = $id;
			$this->basic_info['key_id'] = $key_id;
			$this->basic_info['name'] = $name;
			$this->basic_info['description'] = $description;
			$this->basic_info['finalization_mode'] = $fin_mode;
			$this->basic_info['finalization_value'] = $fin_value;
		}

		public function set_alternatives( $alternatives ) {
			$this->alternatives = $alternatives;
		}

		public function add_goal( $goal ) {
			array_push( $this->goals, $goal );
		}

		public function add_tab( $id, $name, $callback ) {
			array_push( $this->tabs, array(
					'id'       => $id,
					'name'     => $name,
					'callback' => $callback,
				) );
		}

		protected function do_render() { ?>
			<script type="text/javascript">
				NelioABHomeUrl = <?php echo json_encode( home_url() ); ?>;
			</script>
			<form id="<?php echo esc_attr( $this->get_form_name() ); ?>" method="post" class="nelio-exp-form">
				<input type="hidden" name="nelioab_save_exp_post" value="true" />
				<input type="hidden" name="<?php echo esc_attr( $this->get_form_name() ); ?>" value="true" />
				<?php wp_nonce_field( 'nelioab-form-' . $this->basic_info['_form_id'], '_nelioab_form_nonce' ); ?>
				<input type="hidden" name="_nelioab_form" value="<?php echo esc_attr( $this->basic_info['_form_id'] ); ?>" />
				<input type="hidden" name="exp_id" id="exp_id" value="<?php echo esc_attr( $this->basic_info['id'] ); ?>" />
				<input type="hidden" name="exp_key_id" id="exp_id" value="<?php echo esc_attr( $this->basic_info['key_id'] ); ?>" />
				<input type="hidden" name="nelioab_exp_type" value="<?php echo esc_attr( $this->get_alt_exp_type() ); ?>" />
				<input type="hidden" name="content_to_edit" id="content_to_edit" />
				<input type="hidden" name="other_names" id="other_names" value="<?php
						$aux = $this->basic_info;
						echo esc_attr( json_encode( $aux['otherNames'] ) );
					?>" />
				<input type="hidden" name="action" id="action" value="none" />

				<script type="text/javascript">var nelioabBasicInfo = <?php
					echo json_encode( $this->basic_info );
				?>;</script>

				<input type="hidden" name="nelioab_alternatives" id="nelioab_alternatives" value="<?php
						echo esc_attr( json_encode( $this->alternatives ) );
					?>" />

				<input type="hidden" name="nelioab_goals" id="nelioab_goals" value="<?php
						echo esc_attr( json_encode( $this->goals ) );
					?>" />

				<div id="alt-editor-progress" class="tabs-num-<?php echo count( $this->tabs ); ?>">
					<div class="bar-holder">
						<div class="bar" style="width:0;"></div>
					</div>
					<div class="tab-holder">
						<?php
							$active = ' active-tab';
							$i = 1;
							foreach ( $this->tabs as $tab ) { ?>
								<div id="tab-<?php echo esc_attr( $tab['id'] ); ?>" class="tab tab-<?php echo esc_attr( $i . $active ); ?>">
									<div class="tab-indicator"></div>
									<div class="tab-name"><?php echo esc_html( $tab['name'] ); ?></div>
								</div>
								<?php
								$active = '';
								++$i;
							}//end foreach
						?>
					</div>
				</div>

				<div>
					<div id="set-of-content-blocks"><?php
						$invisible = '';
						foreach( $this->tabs as $tab ) {
							printf( '<div id="content-%s" style="%s" class="alt-exp-content-block">', esc_attr( $tab['id'] ), esc_attr( $invisible ) );
								call_user_func( $tab['callback'] );
							echo '</div>';
							$invisible = 'display:none;';
						} ?>
					</div>

					<div id="controllers" style="height:4em;">
						<a href="javascript:;" class="button previous" style="float:left;"><?php
							esc_html_e( 'Previous', 'nelio-ab-testing' ); ?></a>
						<a href="javascript:;" class="button next" style="float:right;"><?php
							esc_html_e( 'Next', 'nelio-ab-testing' ); ?></a>
						<a href="javascript:;" class="button button-primary save" style="float:right;display:none;"><?php
							echo esc_html( $this->get_save_experiment_name() ); ?></a>
					</div>
				</div>
				<?php
				require_once( NELIOAB_UTILS_DIR . '/html-generator.php' );
				NelioABHtmlGenerator::print_unsaved_changes_control( '#controllers .button-primary.save, .row-actions .edit-content' );
				?>
				<script type="text/javascript">
					<?php $this->print_params_for_required_scripts(); ?>
					(function() {
						// Prepare the scripts
						var scripts = [];
						<?php foreach ( $this->get_required_scripts() as $script ) { ?>
							scripts.push(<?php echo json_encode( $script ); ?>);
						<?php } ?>

						// Prepare a function for setting up the alternatives table
						var setupAlternativesTable = function() {
							<?php $this->print_code_for_setup_alternative_table(); ?>
							NelioABEditExperiment.useTab(jQuery('#alt-editor-progress .active-tab').attr('id'));
						};

						// Load scripts one by one and, once they're ready, prepare the table
						var loadScript = function( current ) {
							if ( current == scripts.length ) {
								setupAlternativesTable();
							}
							else {
								jQuery.getScript(scripts[current], function() {
									++current;
									loadScript( current );
								});
							}
						};
						loadScript( 0 );
					})();
				</script>
			</form>

			<?php
		}

		protected function print_params_for_required_scripts() {
			// Nothing to print here
		}

		protected function get_required_scripts() {
			return array(
				nelioab_admin_asset_link( '/js/tabbed-experiment-setup.min.js' ),
				nelioab_admin_asset_link( '/js/admin-table.min.js' ),
				nelioab_admin_asset_link( '/js/alt-table.min.js' )
			);
		}

		protected function print_code_for_setup_alternative_table() { ?>
			// PRINTING THE LIST OF ALTERNATIVES
			var alts = JSON.parse( jQuery("#nelioab_alternatives").val() );
			var altsTable = NelioABAltTable.getTable();
			if ( altsTable ) {
				NelioABAltTable.init(alts);
				for ( var i = 0; i < NelioABAltTable.alts.length; ++i ) {
					var newRow = NelioABAltTable.createRow(NelioABAltTable.alts[i].id)
					newRow.find(".row-title").first().text(NelioABAltTable.alts[i].name);
					newRow.show();
					altsTable.append(newRow);
				}
				NelioABAdminTable.repaint( NelioABAltTable.getTable() );
			}
			<?php
		}

		public function print_name_field() { ?>
			<input name="exp_name" type="text" id="exp_name" maxlength="250"
				class="regular-text" />
			<span class="description" style="display:block;"><?php
				esc_html_e( 'Set a meaningful, descriptive name for the experiment.', 'nelio-ab-testing' );
			?> <small><a href="http://support.nelioabtesting.com/support/solutions/articles/1000129190" target="_blank"><?php
				esc_html_e( 'Help', 'nelio-ab-testing' );
			?></a></small></span><?php
		}

		public function print_descr_field() { ?>
			<textarea id="exp_descr" style="width:280px;" maxlength="450"
				name="exp_descr" cols="45" rows="3"></textarea>
			<span class="description" style="display:block;"><?php
					esc_html_e( 'In a few words, describe what this experiment aims to test.', 'nelio-ab-testing' );
			?> <small><a href="http://support.nelioabtesting.com/support/solutions/articles/1000129192" target="_blank"><?php
				esc_html_e( 'Help', 'nelio-ab-testing' );
			?></a></small></span><?php
		}

		public function print_finalization_mode_field() {
			require_once( NELIOAB_UTILS_DIR . '/html-generator.php' );
			NelioABHtmlGenerator::print_finalization_mode_field(
				$this->basic_info['finalization_mode'],
				$this->basic_info['finalization_value'] );
		}

		protected function print_basic_info() {
			$this->make_section(
				__( 'Basic Information', 'nelio-ab-testing' ),
				$this->get_basic_info_elements(),
				'<label class="mandatory">Required field</label>'
			);
		}

		/**
		 * This function prints the list of goals (a set of cards). It also
		 * prepares the "card" template, which is used by the JavaScript code
		 * for adding new cards.
		 *
		 * Each kind of possible goal (the form for adding it) is also printed
		 * by this function.
		 */
		protected function print_goals() {
			$handler = '<i class="handler dashicons-before dashicons-menu"><br></i>';
			?>
			<div id="new-action-templates">
				<?php require_once( NELIOAB_UTILS_DIR . '/html-generator.php' ); ?>
				<div class="action page" style="display:none;">
					<?php
						echo $handler;
						$options = NelioABHtmlGenerator::get_page_searcher( 'new-action-page-searcher', false, 'no-drafts', array(), false );
						$this->print_post_or_form_action( 'page', $options ); ?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
				<div class="action post" style="display:none;">
					<?php
						echo $handler;
						$options = NelioABHtmlGenerator::get_post_searcher( 'new-action-post-searcher', false, 'no-drafts', array(), false );
						$this->print_post_or_form_action( 'post', $options ); ?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
				<div class="action wc-order-completed" style="display:none;">
					<?php
						echo $handler;
						$options = NelioABHtmlGenerator::get_post_searcher_based_on_type( 'new-wc-order-completed-searcher', false, 'no-drafts', array(), false, array( 'product' ) );
						$text = esc_html__( 'An order containing the product %1$s is completed.', 'nelio-ab-testing' );
						printf( $text, $options );
					?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
				<div class="action external-page" style="display:none;">
					<?php
						echo $handler;
						$name = sprintf(
							'<input type="text" class="name" placeholder="%s" style="max-width:120px;">',
							esc_attr__( 'Name', 'nelio-ab-testing' ) );

						$url = sprintf(
							'<input type="text" class="url" placeholder="%s" style="max-width:200px;">',
							esc_attr__( 'URL', 'nelio-ab-testing' ) );

						$options = '<select class="url_mode">';
						$options .= sprintf( '<option value="exact">%s</option>',
							esc_html__( 'whose URL is', 'nelio-ab-testing' ) );
						$options .= sprintf( '<option value="starts-with">%s</option>',
							esc_html__( 'whose URL starts with', 'nelio-ab-testing' ) );
						$options .= sprintf( '<option value="contains">%s</option>',
							esc_html__( 'whose URL contains', 'nelio-ab-testing' ) );
						$options .= sprintf( '<option value="ends-with">%s</option>',
							esc_html__( 'whose URL ends with', 'nelio-ab-testing' ) );
						$options .= '</select>';

						printf(
							esc_html__( 'A visitor accesses the page %1$s, %2$s %3$s', 'nelio-ab-testing' ),
							$name, $options, $url );
						echo '<input type="hidden" class="direct" value="0" />';
					?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
				<div class="action form-submit cf7" style="display:none;"><?php
					echo $handler;
					$options = NelioABHtmlGenerator::get_form_searcher( 'new-cf7-form-searcher', false, array(), false );
					$this->print_post_or_form_action( 'cf7-submit', $options ); ?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
				<div class="action form-submit gf" style="display:none;"><?php
					echo $handler;
					$options = NelioABHtmlGenerator::get_form_searcher( 'new-gf-form-searcher', false, array(), false );
					$this->print_post_or_form_action( 'gf-submit', $options ); ?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
				<div class="action phone-call" style="display:none;"><?php
					echo $handler;
					esc_html_e( 'A visitor calls to your ResponseTap account.', 'nelio-ab-testing' ); ?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
				<div class="action click-element" style="display:none;"><?php
					echo $handler;
					$name = sprintf(
						'<input type="text" class="value" placeholder="%s" style="max-width:200px;">',
						esc_attr__( 'Matcher', 'nelio-ab-testing' ) );

					require_once( NELIOAB_MODELS_DIR . '/goals/actions/click-element-action.php' );
					$options = '<select class="mode">';
					$options .= sprintf( '<option value="%s">%s</option>',
						NelioABClickElementAction::ID_MODE, __( 'whose ID is', 'nelio-ab-testing' ) );
					$options .= sprintf( '<option value="%s">%s</option>',
						NelioABClickElementAction::CSS_MODE, __( 'that matches the CSS Selector rule', 'nelio-ab-testing' ) );
					$options .= sprintf( '<option value="%s">%s</option>',
						NelioABClickElementAction::TEXT_MODE, __( 'whose text exactly is', 'nelio-ab-testing' ) );
						$options .= '</select>';

						printf(
						esc_html__( 'A visitor clicks on an element %1$s %2$s', 'nelio-ab-testing' ),
						$options, $name );
					?>
					<a href="javascript:;" class="delete"><?php esc_html_e( 'Delete' ); ?></a>
				</div>
			</div>

			<div style="display:none;">
				<span id="defaultNameForMainGoal" style="display:none;"><?php esc_html_e( 'Default', 'nelio-ab-testing' ); ?></span>
				<?php
					$this->print_beautiful_box(
						'goal-template',
						'<span class="form" style="font-weight:normal;">' .
							'  <input type="text" class="new-name">' .
							'  <a class="button rename">' . esc_html__( 'Save' ) . '</a>' .
							'</span>' .
							'<span class="name" style="display:none;">' .
							'  <span style="width:25%;max-width:120px;float:right">' .
							'    <input style="width:100%;" type="number" min="1" class="benefit" placeholder="' .
							esc_attr( sprintf(
								esc_html__( 'Benefit - %1$s%2$s', 'nelio-ab-testing' ),
								NelioABSettings::get_conv_unit(),
								NelioABSettings::get_def_conv_value()
							) ) . '" />' .
							'  </span>' .
							'  <span class="value">' . esc_html__( 'Unnamed Goal', 'nelio-ab-testing' ) . '</span>' .
							'  <small class="isMain" style="display:none;font-weight:normal;">' . esc_html__( '[Main Goal]', 'nelio-ab-testing' ) . '</small><br>' .
							'  <div class="row-actions">' .
							'    <span class="rename"><a href="javascript:;">' . esc_html__( 'Rename' ) . '</a></span>' .
							'    <span class="sep">|</span>' .
							'    <span class="delete"><a href="javascript:;">' . esc_html__( 'Delete' ) . '</a></span>' .
							'  </div>' .
							'</span>',
							array( $this, 'print_new_card_content' )
						);
				?>
			</div>
			<div id="goal-list"></div>
			<h2 style="text-align:center;margin-top:-10px;"><a class="add-new-h2" href="javascript:;" onClick="javascript:NelioABGoalCards.create();"><?php
				esc_html_e( 'Add Additional Goal', 'nelio-ab-testing' );
			?></a></h2>
			<?php
		}

		private function print_post_or_form_action( $type, $options ) {

			switch( $type ) {

				case 'page':
					printf( esc_html__( 'A visitor accesses page %1$s', 'nelio-ab-testing' ), $options );
					echo '<input type="hidden" class="direct" value="0" />';
					return;

				case 'post':
					$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
					if ( count( $post_types ) > 0 ) {
						printf( esc_html__( 'A visitor accesses (custom) post %1$s', 'nelio-ab-testing' ), $options );
					} else {
						printf( esc_html__( 'A visitor accesses post %1$s', 'nelio-ab-testing' ), $options );
					}//end if
					echo '<input type="hidden" class="direct" value="0" />';
					return;

				case 'cf7-submit':
					printf( esc_html__( 'A visitor submits the Contact Form 7 %1$s', 'nelio-ab-testing' ), $options );
					echo '<input type="hidden" class="any-page" value="1" />';
					return;

				case 'gf-submit':
					printf( esc_html__( 'A visitor submits the Gravity Form %1$s', 'nelio-ab-testing' ), $options );
					echo '<input type="hidden" class="any-page" value="1" />';
					return;

			}
		}

		/**
		 * This function prints the contents of a new card. It essentially contains
		 * a small text for an empty card (which is hidden by JS when actions are
		 * added) and the list of actions that contribute to the goal this card
		 * represents.
		 */
		public function print_new_card_content() { ?>
			<p><?php
				esc_html_e( 'A conversion is counted for this goal whenever any of the following actions occur:', 'nelio-ab-testing' );
			?></p>
			<div class="empty">
				<em><?php _e( '<strong>Add one or more conversion actions</strong> using the following buttons.', 'nelio-ab-testing' ); ?></em>
			</div>
			<div class="actions sortable" style="display:none;">
			</div>
			<div class="new-actions">
				<div class="wrapper">
					<strong><?php esc_html_e( 'Available Conversion Actions', 'nelio-ab-testing' ); ?></strong><br>
					<small><?php esc_html_e( '(Hover over each action for help)', 'nelio-ab-testing' ); ?></small><br>
					<?php
					$pattern = '<a class="%1$s action" title="%2$s - %3$s" href="javascript:;"></a>';
					if ( NelioABWooCommerceSupport::is_plugin_active() ) {
						printf( $pattern, 'wc-order-completed', esc_attr__( 'WooCommerce Order', 'nelio-ab-testing' ),
							esc_attr__( 'A WooCommerce order that contains a certain product is completed', 'nelio-ab-testing' )
						);
					}

					printf( $pattern, 'page', esc_attr__( 'Page', 'nelio-ab-testing' ),
						esc_attr__( 'A visitor accesses a page in your WordPress site', 'nelio-ab-testing' )
					);

					printf( $pattern, 'post', esc_attr__( 'Post', 'nelio-ab-testing' ),
						esc_attr__( 'A visitor accesses a post in your WordPress site', 'nelio-ab-testing' )
					);

					printf( $pattern, 'external-page', esc_attr__( 'External Page', 'nelio-ab-testing' ),
						esc_attr__( 'A visitor tries to access an external page (either by clicking a link or by sutbmitting a form) specified using its URL', 'nelio-ab-testing' )
					);

					printf( $pattern, 'click-element', esc_attr__( 'Click', 'nelio-ab-testing' ),
						esc_attr__( 'A visitor clicks on an element in your site (for instance, a button, a link, or an image)', 'nelio-ab-testing' )
					);

					$cf7 = is_plugin_active( 'contact-form-7/wp-contact-form-7.php' );
					$gf = is_plugin_active( 'gravityforms/gravityforms.php' );
					$cf7_action_lbl = __( 'Form Submit', 'nelio-ab-testing' );
					$gf_action_lbl = __( 'Form Submit', 'nelio-ab-testing' );
					if ( $cf7 && $gf ) {
						$cf7_action_lbl = __( 'Contact Form 7', 'nelio-ab-testing' );
						$gf_action_lbl = __( 'Gravity Form', 'nelio-ab-testing' );
					}
					if ( $cf7 ) {
						printf( $pattern, 'form-submit cf7', esc_attr( $cf7_action_lbl ),
							esc_attr__( 'A visitor successfully submits a «Contact Form 7» form', 'nelio-ab-testing' )
						);
					}
					if ( $gf ) {
						printf( $pattern, 'form-submit gf', esc_attr( $gf_action_lbl ),
							esc_attr__( 'A visitor successfully submits a «Gravity Forms» form', 'nelio-ab-testing' )
						);
					}

					if ( NelioABSettings::get_call_tracking_service() == NelioABSettings::CALL_TRACKING_RESPONSETAP ) {
						printf( $pattern, 'phone-call', esc_attr__( 'Phone Call', 'nelio-ab-testing' ),
							esc_attr__( 'A visitor calls you with ResponseTap', 'nelio-ab-testing' )
						);
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * This function prints a search box that can be used for searching posts
		 * and/or pages in WordPress. The box is useful for selecting original posts,
		 * goals, the source for duplicating content...
		 */
		protected function print_search_box( $id, $visible_value = '', $real_value = false ) {
			if ( !$real_value )
				$real_value = $visible_value;
			echo '<input type="text" id="' . esc_attr( $id ) . '_search"' .
				' style="width:280px;" maxlength="250"' .
				' class="regular-text" value="' . esc_attr( $visible_value ) . '" />';
			echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '"' .
				' value="' . esc_attr( $real_value ) . '"/>';
		}

	}//NelioABAltExpPage

}

