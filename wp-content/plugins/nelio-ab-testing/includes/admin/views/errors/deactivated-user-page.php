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


if ( !class_exists( 'NelioABDeactivatedUserPage' ) ) {

	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );

	class NelioABDeactivatedUserPage extends NelioABAdminAjaxPage {

		public function __construct( $title = false ) {
			if ( !$title )
				$title = __( 'User Account Deactivated', 'nelio-ab-testing' );
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
		}

		protected function do_render() {
			$style = 'font-size:130%;color:#555;max-width:450px;line-height:150%';

			printf( '<p style="%s">%s</p>',
				esc_attr( $style ),
				esc_html__( 'The user account has been deactivated. Normally, this occurs once you unsubscribed from our Nelio A/B Testing service. If you want to use the service, you may want to consider subscribing to one of our packages again.', 'nelio-ab-testing' )
				);

			printf( '<p style="%s">%s</p>',
				esc_attr( $style ),
				esc_html__( 'Please, go to the settings page and check your subscription details.', 'nelio-ab-testing' )
			);

			printf( '<br /><div style="%s">%s</div>',
				esc_attr( $style ),
				$this->make_button(
					esc_html__( 'Go to My Account', 'nelio-ab-testing' ),
					admin_url( 'admin.php?page=nelioab-account&nabmode=my-account' ),
					true
				)
			);
		}

	}//NelioABDeactivatedUserPage

}

