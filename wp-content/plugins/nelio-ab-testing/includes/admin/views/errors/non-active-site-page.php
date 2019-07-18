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


if ( !class_exists( 'NelioABNonActiveSitePage' ) ) {

	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );

	class NelioABNonActiveSitePage extends NelioABAdminAjaxPage {

		public function __construct( $title = false ) {
			if ( !$title )
				$title = __( 'This site is not active', 'nelio-ab-testing' );
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
		}

		protected function do_render() {

			$style = 'font-size:130%;color:#555;max-width:450px;line-height:150%';

			printf( '<p style="%s">%s</p>\n',
				esc_attr( $style ),
				esc_html__( 'Appearently, your account information is properly configured, but this site has not been activated yet. In order to create, manage, and execute experiments, you have to activate the site in your account.', 'nelio-ab-testing' )
				);

			printf( '<p style="%s">%s</p>\n',
				esc_attr( $style ),
				esc_html__( 'Please, go to the «My Account» page and make sure you activate this site.', 'nelio-ab-testing' )
			);

			printf( '<br /><div style="%s">%s</div>',
				esc_attr( $style ),
				$this->make_button(
					__( 'Go to My Account', 'nelio-ab-testing' ),
					admin_url( 'admin.php?page=nelioab-account&nabmode=my-account' ),
					true
				)
			);

		}

	}//NelioABNonActiveSitePage

}

