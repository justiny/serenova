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

if ( ! class_exists( 'NelioABCoAuthorsPlus' ) ) {

	/**
	 * This class adds support for the WPML plugin.
	 *
	 * @since 4.2.4
	 * @package \NelioABTesting\Compatibility
	 */
	abstract class NelioABCoAuthorsPlus {

		public static function hook_to_wordpress() {
			add_action( 'nelioab_post_query_experiments', array( __CLASS__, 'post_query_experiments' ) );
			add_action( 'nelioab_pre_query_experiments', array( __CLASS__, 'pre_query_experiments' ) );
		}//end hook_to_wordpress()


		public static function pre_query_experiments() {
			global $coauthors_plus;
			if ( ! isset( $coauthors_plus ) ) {
				return;
			}//end if
			remove_filter( 'posts_selection', array( $coauthors_plus, 'fix_author_page' ) );
		}//end pre_query_experiments()

		public static function post_query_experiments() {
			global $coauthors_plus;
			if ( ! isset( $coauthors_plus ) ) {
				return;
			}//end if
			add_filter( 'posts_selection', array( $coauthors_plus, 'fix_author_page' ) );
		}//end post_query_experiments()

	}// NelioABCoAuthorsPlus

}

