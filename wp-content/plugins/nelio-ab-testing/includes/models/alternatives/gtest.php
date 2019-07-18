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


if ( !class_exists( 'NelioABGTest' ) ) {

	/**
	 * PHPDOC
	 *
	 * @package \NelioABTesting\Models\Results
	 * @since PHPDOC
	 */
	class NelioABGTest {

		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		const UNKNOWN = 1;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		const NO_CLEAR_WINNER = 2;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		const NOT_ENOUGH_VISITS = 3;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		const DROP_VERSION = 4;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		const WINNER = 5;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		const WINNER_WITH_CONFIDENCE = 6;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		private $type;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		private $original;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		private $min;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var string
		 */
		private $min_name;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var string
		 */
		private $min_short_name;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		private $max;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var string
		 */
		private $max_name;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var string
		 */
		private $max_short_name;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var int
		 */
		private $gtest;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var float
		 */
		private $pvalue;


		/**
		 * PHPDOC
		 *
		 * @since PHPDOC
		 * @var float
		 */
		private $certainty;


		/**
		 * Creates a new instance of this class.
		 *
		 * @param int $type     PHPDOC
		 * @param int $original PHPDOC
		 *
		 * @return NelioABGTest a new instance of this class.
		 *
		 * @since PHPDOC
		 */
		public function __construct( $type, $original ) {
			$this->type     = NelioABGTest::get_result_status_from_str( $type );
			$this->original = $original;
		}


		/**
		 * Returns PHPDOC
		 *
		 * @return boolean PHPDOC
		 *
		 * @since PHPDOC
		 */
		public function is_original_the_best() {
			return $this->original == $this->max;
		}


		/**
		 * Returns PHPDOC
		 *
		 * @return int PHPDOC
		 *
		 * @since PHPDOC
		 */
		public function get_type() {
			return $this->type;
		}


		/**
		 * PHPDOC
		 *
		 * @param int $min PHPDOC
		 *
		 * @return void
		 *
		 * @since PHPDOC
		 */
		public function set_min( $min ) {
			$this->min = $min;
		}


		/**
		 * Returns PHPDOC
		 *
		 * @return int PHPDOC
		 *
		 * @since PHPDOC
		 */
		public function get_min() {
			return $this->min;
		}


		/**
		 * PHPDOC
		 *
		 * @param string         $min_short_name PHPDOC
		 * @param string|boolean $min_name       PHPDOC
		 *
		 * @return void
		 *
		 * @since PHPDOC
		 */
		public function set_min_name( $min_short_name, $min_name = false ) {
			$this->min_short_name = $min_short_name;
			$this->min_name = $min_name;
		}


		/**
		 * PHPDOC
		 *
		 * @param int $max PHPDOC
		 *
		 * @return void
		 *
		 * @since PHPDOC
		 */
		public function set_max( $max ) {
			$this->max = $max;
		}


		/**
		 * Returns PHPDOC
		 *
		 * @return int PHPDOC
		 *
		 * @since PHPDOC
		 */
		public function get_max() {
			return $this->max;
		}


		/**
		 * PHPDOC
		 *
		 * @param string         $max_short_name PHPDOC
		 * @param string|boolean $max_name       PHPDOC
		 *
		 * @return void
		 *
		 * @since PHPDOC
		 */
		public function set_max_name( $max_short_name, $max_name = false ) {
			$this->max_short_name = $max_short_name;
			$this->max_name = $max_name;
		}


		/**
		 * PHPDOC
		 *
		 * @param int $gtest PHPDOC
		 *
		 * @return void
		 *
		 * @since PHPDOC
		 */
		public function set_gtest( $gtest ) {
			$this->gtest = $gtest;
		}


		/**
		 * PHPDOC
		 *
		 * @param float $pvalue PHPDOC
		 *
		 * @return void
		 *
		 * @since PHPDOC
		 */
		public function set_pvalue( $pvalue ) {
			$this->pvalue = $pvalue;
		}


		/**
		 * PHPDOC
		 *
		 * @param float $certainty PHPDOC
		 *
		 * @return void
		 *
		 * @since PHPDOC
		 */
		public function set_certainty( $certainty ) {
			$this->certainty = $certainty;
		}


		/**
		 * Returns PHPDOC
		 *
		 * @return float PHPDOC
		 *
		 * @since PHPDOC
		 */
		public function get_certainty() {
			return $this->certainty;
		}


		/**
		 * Returns PHPDOC
		 *
		 * @param string $name  PHPDOC
		 * @param string $popup PHPDOC
		 *
		 * @return string PHPDOC
		 *
		 * @since PHPDOC
		 */
		private function prepare_name( $name, $popup ) {
			if ( $popup ) {
				$popup = str_replace( '"', '\'\'', $popup );
				$aux = "<span title=\"$popup\">$name</span>";
			}
			else {
				$aux = $name;
			}
			return $aux;
		}


		/**
		 * Returns PHPDOC
		 *
		 * @param string $status PHPDOC
		 *
		 * @return int PHPDOC
		 *
		 * @since PHPDOC
		 */
		public static function get_result_status_from_str( $status ) {
			switch ( $status ) {
				case 'NO_CLEAR_WINNER':
					return NelioABGTest::NO_CLEAR_WINNER;
				case 'NOT_ENOUGH_VISITS':
					return NelioABGTest::NOT_ENOUGH_VISITS;
				case 'DROP_VERSION':
					return NelioABGTest::DROP_VERSION;
				case 'WINNER':
					return NelioABGTest::WINNER;
				default:
					return NelioABGTest::UNKNOWN;
			}
		}


		/**
		 * Returns PHPDOC
		 *
		 * @param int $status PHPDOC
		 *
		 * @return string PHPDOC
		 *
		 * @since PHPDOC
		 */
		public static function generate_status_light( $status ) {
			$cb = '';
			if ( NelioABSettings::use_colorblind_palette() )
				$cb = ' status-colorblind';

			$light = '<div class="status-icon status-%s" title="%s"></div>';
			switch ( $status ) {
				case NelioABGTest::WINNER_WITH_CONFIDENCE:
					$light = sprintf( $light, esc_attr( 'tick' . $cb ),
						esc_attr( sprintf(
							__( 'There is a clear winner, with a confidence greater than %s%%', 'nelio-ab-testing' ),
							NelioABSettings::get_min_confidence_for_significance() ) )
						);
					break;
				case NelioABGTest::WINNER:
					$light = sprintf( $light, esc_attr( 'star' . $cb ),
						esc_attr( sprintf(
							__( 'There is a possible winner, but keep in mind the confidence does not reach %s%%', 'nelio-ab-testing' ),
							NelioABSettings::get_min_confidence_for_significance() ) )
						);
					break;
				case NelioABGTest::NO_CLEAR_WINNER:
					$light = sprintf( $light, esc_attr( 'clock' . $cb ),
						esc_attr__( 'There is not enough data to determine any winner', 'nelio-ab-testing' ) );
					break;
				case NelioABGTest::NOT_ENOUGH_VISITS:
					$light = sprintf( $light, esc_attr( 'clock' . $cb ),
						esc_attr__( 'There are not enough visits', 'nelio-ab-testing' ) );
					break;
				case NelioABGTest::UNKNOWN:
				default:
					$light = sprintf( $light, esc_attr( 'gray' . $cb ),
						esc_attr__( 'There are not enough visits', 'nelio-ab-testing' ) );
			}

			return $light;
		}

		/**
		 * Returns PHPDOC
		 *
		 * @param int $status PHPDOC
		 *
		 * @return string PHPDOC
		 *
		 * @since PHPDOC
		 */
		public static function generate_status_message( $status ) {

			$message = '';
			switch ( $status ) {
				case NelioABGTest::WINNER_WITH_CONFIDENCE:
					$message = sprintf(
							__( 'There is a clear winner, with a confidence greater than %s%%', 'nelio-ab-testing' ),
							NelioABSettings::get_min_confidence_for_significance() );
					break;
				case NelioABGTest::WINNER:
					$message = sprintf(
							__( 'There is a possible winner, but keep in mind the confidence does not reach %s%%', 'nelio-ab-testing' ),
							NelioABSettings::get_min_confidence_for_significance() );
					break;
				case NelioABGTest::NO_CLEAR_WINNER:
					$message = __( 'There is not enough data to determine any winner', 'nelio-ab-testing' );
					break;
				case NelioABGTest::NOT_ENOUGH_VISITS:
					$message = __( 'There are not enough visits', 'nelio-ab-testing' );
					break;
				case NelioABGTest::UNKNOWN:
				default:
					$message = __( 'There are not enough visits', 'nelio-ab-testing' );
			}

			return $message;
		}

	}//NelioABGTest

}

