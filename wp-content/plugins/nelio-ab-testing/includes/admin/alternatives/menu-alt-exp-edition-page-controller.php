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

if ( !class_exists( 'NelioABMenuAltExpEditionPageController' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiments-manager.php' );
	require_once( NELIOAB_MODELS_DIR . '/alternatives/menu-alternative-experiment.php' );
	require_once( NELIOAB_MODELS_DIR . '/goals/alternative-experiment-goal.php' );

	require_once( NELIOAB_ADMIN_DIR . '/views/alternatives/menu-alt-exp-edition-page.php' );

	require_once( NELIOAB_ADMIN_DIR . '/alternatives/alt-exp-super-controller.php' );
	class NelioABMenuAltExpEditionPageController extends NelioABAltExpSuperController {

		public static function get_instance() {
			return new NelioABMenuAltExpEditionPageController();
		}

		public static function build() {
			// Check settings
			require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
			$error = NelioABErrorController::build_error_page_on_invalid_settings();
			if ( $error ) return;

			$aux  = NelioABMenuAltExpEditionPageController::get_instance();
			$view = $aux->do_build();
			$view->render();
		}

		public static function generate_html_content() {
			$aux  = NelioABMenuAltExpEditionPageController::get_instance();
			$view = $aux->do_build();
			$view->render_content();
			die();
		}

		protected function do_build() {
			$title = __( 'Edit Experiment', 'nelio-ab-testing' );

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
				$experiment = new NelioABMenuAlternativeExperiment( -time() );
				$experiment->clear();
			}

			// ...and we also recover other experiment names (if any)
			$other_names = array();
			foreach( NelioABExperimentsManager::get_experiments() as $aux ) {
				if ( $aux->get_id() != $experiment->get_id() )
					array_push( $other_names, $aux->get_name() );
			}

			// If everything is OK, we keep going!
			// ---------------------------------------------------

			// Creating the view
			$view = $this->create_view();

			// Experiment information
			$view->set_basic_info(
				$experiment->get_id(),
				$experiment->get_key_id(),
				$experiment->get_name(),
				$experiment->get_description(),
				$experiment->get_finalization_mode(),
				$experiment->get_finalization_value()
			);

			// Experiment alternatives
			$ori = $experiment->get_original();
			$view->set_original( $ori->get_id(), $ori->get_value() );
			$view->set_alternatives( $experiment->get_json4js_alternatives() );

			// Goals
			$goals = $experiment->get_goals();
			foreach ( $goals as $goal )
				$view->add_goal( $goal->json4js() );

			if ( count( $goals ) == 0 ) {
				$new_goal = new NelioABAltExpGoal( $experiment );
				$new_goal->set_name( __( 'Default', 'nelio-ab-testing' ) );
				$view->add_goal( $new_goal->json4js() );
			}

			return $view;
		}

		public function create_view() {
			$title = __( 'Edit Menu Experiment', 'nelio-ab-testing' );
			return new NelioABMenuAltExpEditionPage( $title );
		}

		public function validate() {
			$ok_parent = parent::validate();

			// Check whatever is needed
			$ok = true;

			return $ok_parent && $ok;
		}

		public function edit_alternative_content() {
			// 1. Save any local changes
			global $nelioab_admin_controller;
			$experiment = $nelioab_admin_controller->data;
			try {
				$experiment->save();
				$id = $experiment->get_id();
				NelioABExperimentsManager::refresh();
				$experiment = NelioABExperimentsManager::get_experiment_by_id( $id );
			}
			catch ( Exception $e ) {
				require_once( NELIOAB_ADMIN_DIR . '/error-controller.php' );
				NelioABErrorController::build( $e );
			}

			// 2. Redirect to the edit page
			$exp_id =  $experiment->get_id();
			$menu_id = false;
			if ( isset( $_POST['content_to_edit'] ) ) {
				$menu_alt_id = intval( $_POST['content_to_edit'] );
				foreach ( $experiment->get_alternatives() as $alt ) {
					if ( $alt->get_id() == $menu_alt_id ) {
						$menu_id = $alt->get_value();
					}
				}
			}

			$url = add_query_arg( array(
				'nelioab_exp'   => $exp_id,
				'menu'          => $menu_id,
				'nelioab_check' => md5( "$exp_id$menu_id" ),
				'back_to_edit'  => 1,
			), admin_url( 'nav-menus.php' ) );
			wp_send_json( '[NELIOAB_LINK]' . $url . '[/NELIOAB_LINK]' );
		}

		public function build_experiment_from_post_data() {

			if ( ! isset( $_POST['exp_id'] ) || ! isset( $_POST['exp_original'] ) || ! isset( $_POST['original_appengine_id'] ) ) {
				wp_die();
			}//end if

			$exp = new NelioABMenuAlternativeExperiment( absint( $_POST['exp_id'] ) );
			$exp = $this->compose_basic_alt_exp_using_post_data( $exp );
			$exp->set_originals_id( sanitize_text_field( $_POST['original_appengine_id'] ), absint( $_POST['exp_original'] ) );
			global $nelioab_admin_controller;
			$nelioab_admin_controller->data = $exp;
		}

		public function manage_actions() {

			if ( ! isset( $_POST['action'] ) ) {
				return;
			}//end if

			parent::manage_actions();

			$action = sanitize_text_field( $_POST['action'] );
			if ( 'edit_alt_content' == $action ) {
				if ( $this->validate() ) {
					$this->edit_alternative_content();
				}//end if
			}//end if

		}

	}//NelioABMenuAltExpEditionPageController

}

if ( isset( $_POST['nelioab_edit_ab_menu_exp_form'] ) ) {
	$controller = NelioABMenuAltExpEditionPageController::get_instance();
	$controller->manage_actions();
	if ( !$controller->validate() )
		$controller->print_ajax_errors();
}

