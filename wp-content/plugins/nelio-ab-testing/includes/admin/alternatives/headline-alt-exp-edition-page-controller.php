<?php
/**
 * Copyright 2013 Nelio Software S.L.
 * This script is distributed under the terms of the GNU General Public License.
 *
 * This script is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License.
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if ( !class_exists( 'NelioABHeadlineAltExpEditionPageController' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_MODELS_DIR . '/experiments-manager.php' );
	require_once( NELIOAB_MODELS_DIR . '/alternatives/headline-alternative-experiment.php' );
	require_once( NELIOAB_MODELS_DIR . '/goals/alternative-experiment-goal.php' );

	require_once( NELIOAB_ADMIN_DIR . '/views/alternatives/headline-alt-exp-edition-page.php' );

	require_once( NELIOAB_ADMIN_DIR . '/alternatives/alt-exp-super-controller.php' );
	class NelioABHeadlineAltExpEditionPageController extends NelioABAltExpSuperController {

		public static function get_instance() {
			return new NelioABHeadlineAltExpEditionPageController();
		}

		public static function build() {
			// Check settings
			require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
			$error = NelioABErrorController::build_error_page_on_invalid_settings();
			if ( $error ) return;

			$aux  = NelioABHeadlineAltExpEditionPageController::get_instance();
			$view = $aux->do_build();
			$view->render();
		}

		public static function generate_html_content() {
			$aux  = NelioABHeadlineAltExpEditionPageController::get_instance();
			$view = $aux->do_build();
			$view->render_content();
			die();
		}

		protected function do_build() {
			$title = __( 'Edit Headline Experiment', 'nelio-ab-testing' );

			// Check settings
			require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
			$error = NelioABErrorController::build_error_page_on_invalid_settings();
			if ( $error ) return;

			// We recover the experiment (if any)
			// ----------------------------------------------

			global $nelioab_admin_controller;
			$experiment = NULL;
			if ( !empty( $nelioab_admin_controller->data ) ) {
				$experiment = $nelioab_admin_controller->data;
			}
			else {
				$experiment = new NelioABHeadlineAlternativeExperiment( -time() );
				$experiment->clear();
			}

			// ...and we also recover other experiment names (if any)
			$other_names = array();
			foreach( NelioABExperimentsManager::get_experiments() as $aux ) {
				if ( $aux->get_id() != $experiment->get_id() )
					array_push( $other_names, $aux->get_name() );
			}

			// Get id of Original page or post
			// ----------------------------------------------
			if ( isset( $_GET['post-id'] ) ) {
				$experiment->set_original( intval( $_GET['post-id'] ) );
			}//end if


			// Checking whether there are pages or posts available
			// ---------------------------------------------------

			// ...pages...
			$list_of_pages = get_pages();
			$options_for_posts = array(
				'posts_per_page' => 1 );
			$list_of_posts = get_posts( $options_for_posts );
			require_once( NELIOAB_UTILS_DIR . '/data-manager.php' );
			NelioABArrays::sort_posts( $list_of_posts );

			if ( count( $list_of_pages ) + count( $list_of_posts ) == 0) {
				require_once( NELIOAB_ADMIN_DIR . '/views/errors/message-page.php' );
				$view = new NelioABMessagePage(
					__( 'There are no posts available.', 'nelio-ab-testing' ),
					__( 'Please, create one post and try again.', 'nelio-ab-testing' ) );
				return $view;
			}


			// If everything is OK, we keep going!
			// ---------------------------------------------------

			// Creating the view
			$view = $this->create_view();
			foreach ( $other_names as $name )
				$view->add_another_experiment_name( $name );

			// Experiment information
			$view->set_basic_info(
				$experiment->get_id(),
				$experiment->get_key_id(),
				$experiment->get_name(),
				$experiment->get_description(),
				$experiment->get_finalization_mode(),
				$experiment->get_finalization_value()
			);

			// Experiment specific variables and alternatives
			$view->set_original_id( $experiment->get_originals_id() );
			$view->set_alternatives( $experiment->get_json4js_alternatives() );

			return $view;
		}

		public function create_view() {
			$title = __( 'Edit Experiment', 'nelio-ab-testing' );
			return new NelioABHeadlineAltExpEditionPage( $title );
		}

		public function validate() {
			$ok_parent = parent::validate();

			// Check whatever is needed
			$ok = true;

			return $ok_parent && $ok;
		}

		public function build_experiment_from_post_data() {

			if ( ! isset( $_POST['exp_id'] ) || ! isset( $_POST['exp_original'] ) ) {
				wp_die();
			}//end if

			$exp = new NelioABHeadlineAlternativeExperiment( absint( $_POST['exp_id'] ) );
			$exp->set_original( intval( $_POST['exp_original'] ) );
			$exp = $this->compose_basic_alt_exp_using_post_data( $exp );

			global $nelioab_admin_controller;
			$nelioab_admin_controller->data = $exp;

		}

	}//NelioABHeadlineAltExpEditionPageController

}

if ( isset( $_POST['nelioab_edit_ab_title_exp_form'] ) ) {
	$controller = NelioABHeadlineAltExpEditionPageController::get_instance();
	$controller->manage_actions();
	if ( !$controller->validate() )
		$controller->print_ajax_errors();
}

