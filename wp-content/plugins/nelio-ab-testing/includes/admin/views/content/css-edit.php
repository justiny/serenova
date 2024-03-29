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


require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );
class NelioABCssEditPage extends NelioABAdminAjaxPage {

	public static function build() {
		$view = new NelioABCssEditPage();
		$view->set_icon( 'icon-tools' );
		if ( isset( $_GET['exp_id'] ) ) {
			$view->keep_request_param( 'exp_id', absint( $_GET['exp_id'] ) );
		}//end if

		if ( isset( $_GET['css_id'] ) ) {
			$view->keep_request_param( 'css_id', intval( $_GET['css_id'] ) );
		}//end if

		if ( isset( $_POST['css_save_alt'] ) ) {
			$view->keep_request_param( 'css_save_alt', sanitize_text_field( $_POST['css_save_alt'] ) );
		}//end if

		if ( isset( $_POST['post_title'] ) ) {
			$view->keep_request_param( 'css_alt_name', sanitize_text_field( stripslashes( $_POST['post_title'] ) ) );
		}//end if

		if ( isset( $_POST['content'] ) ) {
			$view->keep_request_param( 'css_alt_value', sanitize_textarea_field( stripslashes( $_POST['content'] ) ) );
		}//end if

		$view->get_content_with_ajax_and_render( __FILE__, __CLASS__ );
	}

	public static function generate_html_content() {
		require_once( NELIOAB_MODELS_DIR . '/experiments-manager.php' );
		$view   = new NelioABCssEditPage();
		$exp_id = 0;
		$css_id = 0;

		if ( isset( $_REQUEST['exp_id'] ) ) {
			$exp_id = absint( $_REQUEST['exp_id'] );
		}//end if

		if ( isset( $_REQUEST['css_id'] ) ) {
			$css_id = intval( $_REQUEST['css_id'] );
		}//end if

		try {
			$exp = NelioABExperimentsManager::get_experiment_by_id( $exp_id, NelioABExperiment::CSS_ALT_EXP );
			$view->experiment = $exp;
		}
		catch ( Exception $e  ){
			require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
			NelioABErrorController::build( $e );
		}

		if ( isset( $_REQUEST['css_save_alt'] ) &&
		     isset( $_REQUEST['css_alt_name'] ) &&
		     isset( $_REQUEST['css_alt_value'] )
			) {
			require_once( NELIOAB_MODELS_DIR . '/alternatives/css-alternative-experiment.php' );
			$exp->update_css_alternative(
				$css_id,
				sanitize_text_field( stripslashes( $_REQUEST['css_alt_name'] ) ),
				sanitize_textarea_field( stripslashes( $_REQUEST['css_alt_value'] ) )
			);
		}

		foreach( $view->experiment->get_alternatives() as $alt )
			if ( $alt->get_id() == $css_id )
				$view->css_alt = $alt;

		$view->do_render();
		die();
	}

	public function __construct() {
		$title = __( 'Edit CSS', 'nelio-ab-testing' );
		parent::__construct( $title );
	}

	private $css_alt;
	private $experiment;

	protected function do_render() { ?>

		<div id="post-body" class="metabox-holder columns-2">
		<form id="css_edit_value" method="POST" action="#">
			<input type="hidden" name="css_save_alt" id="css_save_alt" value="true"></input>
			<div id="post-body-content">

				<div id="titlediv">

					<div id="titlewrap">
						<label class="screen-reader-text" id="title-prompt-text" for="title">Enter title here</label>
						<input type="text" name="post_title" size="30" value="<?php
							echo esc_attr( $this->css_alt->get_name() );
						?>" id="title" autocomplete="off"></input>
					</div>

					<div class="inside">
						<div id="postdivrich" class="postarea edit-form-section">
							<div id="wp-content-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
								<div class="wp-editor-tabs"></div>
							</div>
								<input type="hidden" id="content" name="content"></input>
								<pre id="editor"><?php echo $this->css_alt->get_value() ?></pre>
								<script type="text/javascript" src="<?php echo esc_url( nelioab_admin_asset_link( '/lib/ace-editor/ace.js' ) ); ?>"></script>
								<script type="text/javascript">
									var nelioabCssEditor = ace.edit('editor');
									nelioabCssEditor.setTheme('ace/theme/tomorrow');
									nelioabCssEditor.session.setMode('ace/mode/css');
									nelioabCssEditor.setAutoScrollEditorIntoView(true);
									nelioabCssEditor.setOption('minLines', 20);
									nelioabCssEditor.setOption('maxLines', 100);
									nelioabCssEditor.setFontSize(15);
								</script>
						</div>
					</div>

				</div>
			</div><!-- /post-body-content -->

			<div id="postbox-container-1" class="postbox-container">

				<div id="save_nelioab_alternative_box" class="meta-box-sortables ui-sortable">
					<div id="submitdiv" class="postbox"><?php
						require_once( NELIOAB_UTILS_DIR . '/wp-helper.php' );
						$colorscheme = NelioABWpHelper::get_current_colorscheme();
						?>
						<script>
							var nelioab_style_metabox = document.createElement("style");
							nelioab_style_metabox.setAttribute("type", "text/css");
							nelioab_style_metabox.innerHTML = <?php
								echo json_encode(
									'#save_nelioab_alternative_box h3.hndle { ' .
										'color:' . $colorscheme['foreground'] . '; ' .
										'background:' . $colorscheme['focus'] . '; ' .
										'border: 1px solid' . $colorscheme['focus'] . '; ' .
									'}'
								);
							?> +
							"#favorite-actions, #message, .add-new-h2, .tablenav, #edit-slug-box { display:none; }" +
							"#save_nelioab_alternative_box .handlediv, #save_nelioab_alternative_box .handlediv:hover { visibility:visible; color:white; }";
							document.getElementsByTagName('head')[0].appendChild(nelioab_style_metabox);
						</script>

						<h3 style="cursor:auto;" class="hndle"><span><?php esc_html_e( 'Update' ); ?></span></h3>
						<div class="inside">
							<div class="submitbox" id="submitpost">

								<div style="margin:1em;">
									<p><?php esc_html_e( 'Select a page or post for preview', 'nelio-ab-testing' ) ?></p>
									<?php
										require_once( NELIOAB_UTILS_DIR . '/html-generator.php' );
										NelioABHtmlGenerator::print_full_searcher( 'post-options', false, 'show-drafts' );
									?>
								</div>

								<div class="misc-pub-section" style="min-height:4em;">
									<div style="float:right;margin-top:1em;">
										<input name="original_publish" type="hidden" id="original_publish" value="Update">
										<input name="save" type="submit"
											class="button button-primary" id="publish"
											tabindex="5"
											value="<?php esc_attr_e( 'Update' ); ?>" />
										<script type="text/javascript">
										jQuery( "#css_edit_value" ).on( 'submit', function() {
											jQuery("#content").attr('value', nelioabCssEditor.getValue());
										});
										</script>
									</div>
									<div style="float:right;margin-top:1em;margin-right:1em;">
										<div id="preview-action">
											<a id="preview-button" class="preview button" target="wp-preview" id="post-preview" tabindex="4"><?php esc_html_e( 'Preview' ); ?></a>
											<input type="hidden" name="wp-preview" id="wp-preview" value="" />
										</div>
									</div>
								</div>
								<?php
									$url = admin_url( 'admin.php' );
									$url = add_query_arg( array(
										'nelioab-page' => 'save-css',
									), $url );
								?>
								<script>
									jQuery("#preview-button").click(function(e) {
										var aux = jQuery("#post-options").attr('value');
										if ( aux == -1 || aux == "" || aux == undefined ) return;
										if ( aux == <?php echo json_encode( NelioABController::FRONT_PAGE__YOUR_LATEST_POSTS ); ?> ) aux = "";
										else aux = "&p=" + aux;
										jQuery("#css_edit_value").attr('action', <?php echo json_encode( $url ); ?> + aux);
										jQuery("#css_edit_value").attr('target', '_blank');
										jQuery("#css_edit_value").submit();
										jQuery("#css_edit_value").attr('action', '#');
										jQuery("#css_edit_value").attr('target', '');
									});
								</script>

								<div style="margin:10px;">
									<b><?php esc_html_e( 'Go back to...', 'nelio-ab-testing' ); ?></b>
									<?php
									$url        = admin_url( 'admin.php?page=nelioab-experiments' );
									$exp_id     = $this->experiment->get_id();
									$exp_status = $this->experiment->get_status();
									?>
									<ul style="margin-left:1.5em;">
										<?php
										require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
										switch( $exp_status ){
											case NelioABExperiment::STATUS_DRAFT:
											case NelioABExperiment::STATUS_READY:
												?><li><a href="<?php
													echo esc_url( $url . '&action=edit&ctab=tab-alts&id=' . $exp_id . '&exp_type=' . $this->experiment->get_type() ); ?>"><?php
													 esc_html_e( 'Editing this experiment', 'nelio-ab-testing' ); ?></a></li><?php
												break;
											case NelioABExperiment::STATUS_RUNNING:
											case NelioABExperiment::STATUS_FINISHED:
												?><li><a href="<?php
													echo esc_url( $url . '&action=progress&id=' . $exp_id . '&exp_type=' . $this->experiment->get_type() ); ?>"><?php
													esc_html_e( 'The results of the related experiment', 'nelio-ab-testing' ); ?></a></li><?php
												break;
											case NelioABExperiment::STATUS_TRASH:
											case NelioABExperiment::STATUS_PAUSED:
											default:
												// Nothing here
										}
										?>
									   <li><a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'My list of experiments', 'nelio-ab-testing' ); ?></a></li>
									   <li><a href="<?php echo admin_url( 'admin.php?page=nelioab-dashboard' ); ?>"><?php esc_html_e( 'The Dashboard', 'nelio-ab-testing' ); ?></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END OF SIDEBAR -->

			</div>
		</form>
		</div>
		<?php
	}

}

