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


if ( !class_exists( 'NelioABPostAltExpProgressPageController' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_MODELS_DIR . '/experiments-manager.php' );

	require_once( NELIOAB_ADMIN_DIR . '/views/progress/post-alt-exp-progress-page.php' );
	require_once( NELIOAB_ADMIN_DIR . '/progress/alt-exp-progress-super-controller.php' );

	class NelioABPostAltExpProgressPageController extends NelioABAltExpProgressSuperController {

		public static function build() {
			// Check settings
			require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
			$error = NelioABErrorController::build_error_page_on_invalid_settings();
			if ( $error ) return;

			$title = __( 'Results of the Experiment', 'nelio-ab-testing' );
			$view  = new NelioABPostAltExpProgressPage( $title );

			if ( isset( $_GET['id'] ) ) {
				// The ID of the experiment to which the action applies
				$view->keep_request_param( 'exp_id', absint( $_GET['id'] ) );
			}//end if

			if ( isset( $_GET['exp_type'] ) ) {
				$view->keep_request_param( 'exp_type', absint( $_GET['exp_type'] ) );
			}//end if

			if ( isset( $_GET['goal'] ) ) {
				$view->keep_request_param( 'goal', sanitize_text_field( $_GET['goal'] ) );
			}//end if

			$view->get_content_with_ajax_and_render( __FILE__, __CLASS__ );
		}

		public static function generate_html_content() {

			global $nelioab_admin_controller;
			if ( isset( $nelioab_admin_controller->data ) ) {
				$exp    = $nelioab_admin_controller->data;
				$exp_id = $exp->get_id();
			} else {
				$exp_id = -time();
				if ( isset( $_REQUEST['exp_id'] ) ) {
					$exp_id = absint( $_REQUEST['exp_id'] );
				}//end if

				$exp_type = -1;
				if ( isset( $_POST['exp_type'] ) ) {
					$exp_type = absint( $_POST['exp_type'] );
				}//end if

				$exp = null;

				try {
					$exp = NelioABExperimentsManager::get_experiment_by_id( $exp_id, $exp_type );
				}
				catch ( Exception $e ) {
					require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
					NelioABErrorController::build( $e );
				}
			}

			$title = __( 'Results of the Experiment', 'nelio-ab-testing' );
			$view  = new NelioABPostAltExpProgressPage( $title );
			$view->set_experiment( $exp );

			$goals = $exp->get_goals();
			$view->set_goals( $goals );

			$goal_id = -1;
			if ( isset( $_REQUEST['goal'] ) ) {
				$goal_id = sanitize_text_field( $_REQUEST['goal'] );
			}//end if
			$view->set_current_selected_goal( $goal_id );

			$view->render_content();

			die();
		}

		public function apply_alternative() {
			if ( isset( $_POST['original'] ) && isset( $_POST['alternative'] ) ) {
				$ori_id = absint( $_POST['original'] );
				$alt_id = absint( $_POST['alternative'] );

				$categories = isset( $_POST['copy_categories'] ) && 'on' === sanitize_text_field( $_POST['copy_categories'] );
				$tags       = isset( $_POST['copy_tags'] ) && 'on' === sanitize_text_field( $_POST['copy_tags'] );
				$meta       = isset( $_POST['copy_meta'] ) && 'on' === sanitize_text_field( $_POST['copy_meta'] );

				NelioABSettings::set_copy_metadata( $meta );
				NelioABSettings::set_copy_categories( $categories );
				NelioABSettings::set_copy_tags( $tags );

				require_once( NELIOAB_UTILS_DIR . '/wp-helper.php' );
				NelioABWPHelper::overwrite( $ori_id, $alt_id, $meta, $categories, $tags );
				wp_send_json( 'OK' );
			}
		}

	}//NelioABPostAltExpProgressPageController

}

$aux = new NelioABPostAltExpProgressPageController();
$aux->manage_actions();

