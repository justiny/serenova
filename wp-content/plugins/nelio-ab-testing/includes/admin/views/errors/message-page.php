<?php
/**
 * Copyright 2015 Nelio Software S.L.
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


if ( !class_exists( 'NelioABMessagePage' ) ) {

	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );

	class NelioABMessagePage extends NelioABAdminAjaxPage {

		private $html_msg;
		private $html_explanation;

		public function __construct( $html_msg, $html_explanation = '' ) {
			parent::__construct( __( 'Information', 'nelio-ab-testing' ) );
			$this->html_msg = $html_msg;
			$this->html_explanation = $html_explanation;
		}

		protected function do_render() { ?>
			<div class='nelio-message'>
				<img class="animated flipInY" src="<?php
					echo esc_url( nelioab_admin_asset_link( '/images/message-icon.png' ) );
				?>" alt="<?php
					esc_attr_e( 'Information Notice', 'nelio-ab-testing' )
				?>" />
				<h2><?php echo $this->html_msg; ?></h2>
				<p class='nelio-admin-explanation'><?php echo $this->html_explanation; ?></p>
			</div>
		<?php
		}

	}//NelioABMessagePage

}
