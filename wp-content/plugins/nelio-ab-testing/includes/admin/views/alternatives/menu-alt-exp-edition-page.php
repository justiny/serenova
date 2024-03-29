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
 * this program. If not, see <http://www.gnu.org/licenses/>.
 */


if ( !class_exists( 'NelioABMenuAltExpEditionPage' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );

	require_once( NELIOAB_ADMIN_DIR . '/views/alternatives/alt-exp-page.php' );
	class NelioABMenuAltExpEditionPage extends NelioABAltExpPage {

		protected $original_appengine_id;
		protected $original_menu_id;

		protected $alternatives;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
			$this->set_form_name( 'nelioab_edit_ab_menu_exp_form' );
			$this->alternatives = array();

			$this->is_global = true;

			$this->original_appengine_id = '';
			$this->original_menu_id = '';

			// Prepare tabs
			$this->add_tab( 'info', __( 'General', 'nelio-ab-testing' ), array( $this, 'print_basic_info' ) );
			$this->add_tab( 'alts', __( 'Alternatives', 'nelio-ab-testing' ), array( $this, 'print_alternatives' ) );
			$this->add_tab( 'goals', __( 'Goals', 'nelio-ab-testing' ), array( $this, 'print_goals' ) );
		}

		public function set_original( $original_appengine_id, $original_menu_id ) {
			$this->original_appengine_id = $original_appengine_id;
			$this->original_menu_id = $original_menu_id;
		}

		public function set_alternatives( $alternatives ) {
			$this->alternatives = $alternatives;
		}

		public function get_alt_exp_type() {
			return NelioABExperiment::MENU_ALT_EXP;
		}

		protected function get_save_experiment_name() {
			return __( 'Save', 'nelio-ab-testing' );
		}

		protected function get_basic_info_elements() {
			return array(
				array (
					'label'     => esc_html__( 'Name', 'nelio-ab-testing' ),
					'id'        => 'exp_name',
					'callback'  => array( &$this, 'print_name_field' ),
					'mandatory' => true ),
				array (
					'label'     => esc_html__( 'Description', 'nelio-ab-testing' ),
					'id'        => 'exp_descr',
					'callback'  => array( &$this, 'print_descr_field' ) ),
				array (
					'label'     => esc_html__( 'Original Menu', 'nelio-ab-testing' ),
					'id'        => 'exp_original',
					'callback'  => array ( &$this, 'print_ori_field' ),
					'mandatory' => true ),
				array (
					'label'     => esc_html__( 'Finalization Mode', 'nelio-ab-testing' ),
					'id'        => 'exp_finalization_mode',
					'callback'  => array( &$this, 'print_finalization_mode_field' ),
					'min_plan'  => NelioABAccountSettings::ENTERPRISE_SUBSCRIPTION_PLAN,
					'mandatory' => true ),
			);
		}

		protected function print_ori_field() { ?>
			<input style="display:none;"
				name="original_appengine_id" id="original_appengine_id"
				value="<?php echo esc_attr( $this->original_appengine_id ); ?>">
			<select name="exp_original" id="exp_original"><?php
			$nav_menus = wp_get_nav_menus();
			foreach ( $nav_menus as $nav ) {
				$selected = '';
				if ( $this->original_menu_id == $nav->term_id )
					$selected = ' selected="selected"';
				$option = '<option value="%d"%s>%s</option>';
				printf( $option, esc_attr( $nav->term_id ), $selected, esc_html( $nav->name ) );
			}
			?></select>
			<?php
		}

		protected function print_alternatives() { ?>
			<h2><?php

				printf( '<a onClick="%1$s" class="add-new-h2" href="#">%2$s</a>',
					esc_attr( sprintf( 'javascript:jQuery(".new-alt-form.inline-edit-row a.button.button-primary.save").text(%s);NelioABAltTable.showNewPageOrPostAltForm(jQuery("table#alt-table"), false);',
						json_encode( __( 'Create', 'nelio-ab-testing' ) )
					) ),
					__( 'New Alternative Menu <small>(empty)</small>', 'nelio-ab-testing' )
				);

				printf( '<a onClick="%1$s" class="add-new-h2" href="#">%2$s</a>',
					esc_attr( sprintf( 'javascript:jQuery(".new-alt-form.inline-edit-row a.button.button-primary.save").text(%s);NelioABAltTable.showNewPageOrPostAltForm(jQuery("table#alt-table"), true);',
						json_encode( __( 'Create', 'nelio-ab-testing' ) )
					) ),
					__( 'New Alternative Menu <small>(based on an existing one)</small>', 'nelio-ab-testing' )
				);

			?></h2><?php

			$wp_list_table = new NelioABMenuAlternativesTable( $this->alternatives );
			$wp_list_table->prepare_items();
			$wp_list_table->display();
		}

	}//NelioABMenuAltExpEditionPage

	require_once( NELIOAB_ADMIN_DIR . '/views/alternatives/alternatives-table.php' );
	class NelioABMenuAlternativesTable extends NelioABAlternativesTable {

		function __construct( $items ){
			parent::__construct( $items );
		}

		public function column_name( $alt ){

			//Build row actions
			$actions = array(
				'rename' => $this->make_quickedit_button( __( 'Rename', 'nelio-ab-testing' ) ),

				'edit-content' => sprintf( '<a style="cursor:pointer;" onClick="%s" href="#">%s</a>',
					esc_attr(
						'$nelioabClickedAlt = jQuery(this);' .
						'(function(){' .
							'var id=-9000; ' .
							'$nelioabClickedAlt.closest("tbody").find( "tr" ).each(function(){' .
							'if ( ! jQuery(this).hasClass("nelioab-quick-edit-row") && ' .
								'! jQuery(this).hasClass("deleted") && ' .
								'! jQuery(this).hasClass("new-alt-form") ) {' .
								'jQuery(this).data( "alt-id", id );' .
								'--id;' .
						'} } ); })();' .
						'NelioABAltTable.editContent($nelioabClickedAlt.closest("tr"));'
					),
					esc_html__( 'Save Experiment & Edit Menus' )
				),

				'delete' => sprintf( '<a style="cursor:pointer;" onClick="%s" href="#">%s</a>',
					esc_attr( 'javascript:NelioABAltTable.remove(jQuery(this).closest("tr"));' ),
					esc_html__( 'Delete' )
				),
			);

			//Return the title contents
			return sprintf(
				'<span class="row-title alt-name">%1$s</span>%2$s',
				esc_html( $alt['name'] ),
				$this->row_actions( $actions )
			);
		}

		public function extra_tablenav( $which ) {
			if ( 'top' == $which ){
				_e( 'Please, <b>add one or more</b> alternative menus.', 'nelio-ab-testing' );
			}
		}

		public function display_rows_or_placeholder() {
			$this->print_new_alt_form();

			$title = __( 'Original: Default Menu Set', 'nelio-ab-testing' );
			$expl = __( 'The original version of this experiment uses your current menu.', 'nelio-ab-testing' );
			?>
			<tr><td>
				<span class="row-title"><?php echo esc_html( $title ); ?></span>
				<div class="row-actions"><?php echo esc_html( $expl ); ?></div>
			</td></tr>
			<?php
			parent::display_rows();
		}

		protected function print_additional_info_for_new_alt_form() { ?>
			<label class="copying-content" style="padding-top:0.5em;">
				<span class="title"><?php esc_html_e( 'Source', 'nelio-ab-testing' ); ?> </span>
				<span class="input-text-wrap">
					<select name="based_on" id="based_on"><?php
					$nav_menus = wp_get_nav_menus();
					foreach ( $nav_menus as $nav ) {
						$selected = '';
						if ( $this->original_menu_id == $nav->term_id )
							$selected = ' selected="selected"';
						$option = '<option value="%d"%s>%s</option>';
						printf( $option, esc_attr( $nav->term_id ), $selected, esc_html( $nav->name ) );
					}
					?></select>
					<p class="description"><?php esc_html_e( 'The selected menu\'s items will be duplicated and used by this alternative.', 'nelio-ab-testing' ); ?></p>
				</span>
			</label><?php
		}

		protected function print_save_button_for_new_alt_form() { ?>
			<a class="button-primary save alignleft" <?php
				echo $this->make_form_javascript( $this->form_name, 'add_alt' );
				?> style="margin-right:0.4em;"><?php esc_html_e( 'Create', 'nelio-ab-testing' ); ?></a>
			<?php
		}

		protected function get_inline_edit_title() {
			return __( 'Rename CSS Alternative', 'nelio-ab-testing' );
		}

		protected function get_inline_name_field_label() {
			return __( 'Name', 'nelio-ab-testing' );
		}

	}// NelioABMenuAlternativesTable

}

