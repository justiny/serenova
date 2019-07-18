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

if ( !class_exists( 'NelioABHeadlineAltExpProgressPageController' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_MODELS_DIR . '/experiments-manager.php' );

	require_once( NELIOAB_ADMIN_DIR . '/views/progress/headline-alt-exp-progress-page.php' );
	require_once( NELIOAB_ADMIN_DIR . '/progress/alt-exp-progress-super-controller.php' );

	class NelioABHeadlineAltExpProgressPageController extends NelioABAltExpProgressSuperController {

		public static function build() {
			// Check settings
			require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
			$error = NelioABErrorController::build_error_page_on_invalid_settings();
			if ( $error ) return;

			$title = __( 'Results of the Experiment', 'nelio-ab-testing' );
			$view  = new NelioABHeadlineAltExpProgressPage( $title );

			if ( isset( $_GET['id'] ) ) {
				// The ID of the experiment to which the action applies
				$view->keep_request_param( 'exp_id', absint( $_GET['id'] ) );
			}//end if

			if ( isset( $_GET['exp_type'] ) ) {
				$view->keep_request_param( 'exp_type', absint( $_GET['exp_type'] ) );
			}//end if

			if ( isset( $_GET['actual_exp_type'] ) ) {
				$view->keep_request_param( 'exp_type', absint( $_GET['actual_exp_type'] ) );
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
			}
			else {
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
			$view  = new NelioABHeadlineAltExpProgressPage( $title );
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

			if ( ! isset( $_POST['original'] ) ) {
				return;
			}//end if

			if ( ! isset( $_POST['alternative_title'] ) ) {
				return;
			}//end if

			if ( ! isset( $_POST['alternative_excerpt'] ) ) {
				return;
			}//end if

			if ( ! isset( $_POST['alternative_image'] ) ) {
				return;
			}//end if

			$ori_id = absint( $_POST['original'] );
			$alt_title = trim( sanitize_text_field( stripslashes( $_POST['alternative_title'] ) ) );
			$alt_excerpt = trim( sanitize_text_field( stripslashes( $_POST['alternative_excerpt'] ) ) );
			$alt_image = intval( $_POST['alternative_image'] );

			$post = get_post( $ori_id, ARRAY_A );
			if ( $post ) {

				$post['post_title'] = $alt_title;

				if ( strlen( $alt_excerpt ) > 0 ) {
					$post['post_excerpt'] = $alt_excerpt;
				}//end if

				wp_update_post( $post );

				if ( $alt_image > 0 ) {
					update_post_meta( $ori_id, '_thumbnail_id', $alt_image );
				}//end if

				wp_send_json( 'OK' );

			}//end if

		}

	}//NelioABHeadlineAltExpProgressPageController

}

$aux = new NelioABHeadlineAltExpProgressPageController();
$aux->manage_actions();

