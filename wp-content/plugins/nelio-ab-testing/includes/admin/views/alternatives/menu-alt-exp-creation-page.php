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
 * this program. If not, see <http://www.gnu.org/licenses/>.
 */


if ( !class_exists( 'NelioABMenuAltExpCreationPage' ) ) {

	require_once( NELIOAB_ADMIN_DIR . '/views/alternatives/menu-alt-exp-edition-page.php' );
	class NelioABMenuAltExpCreationPage extends NelioABMenuAltExpEditionPage {

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
			$this->set_form_name( 'nelioab_new_ab_menu_exp_form' );
		}

		protected function get_save_experiment_name() {
			return __( 'Create', 'nelio-ab-testing' );
		}

	}//NelioABMenuAltExpCreationPage

}

