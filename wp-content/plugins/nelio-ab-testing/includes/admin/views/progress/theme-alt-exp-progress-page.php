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


if ( !class_exists( 'NelioABThemeAltExpProgressPage' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_ADMIN_DIR . '/views/progress/alt-exp-progress-page.php' );

	class NelioABThemeAltExpProgressPage extends NelioABAltExpProgressPage {

		private $ori;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
			$this->exp     = null;
			$this->results = null;
		}

		protected function print_experiment_details_title() {
			esc_html_e( 'Details of the Theme Experiment', 'nelio-ab-testing' );
		}

		protected function get_original_name() {
			// Original title
			$exp = $this->exp;
			$this->ori = $exp->get_original()->get_name();
			return $this->ori;
		}

		protected function get_original_value() {
			$exp = $this->exp;
			return $exp->get_original()->get_value();
		}

		protected function print_js_function_for_post_data_overwriting() { ?>
			function nelioab_confirm_overwriting(id, stylesheet, template) {
				jQuery("#apply_alternative #stylesheet").attr("value",stylesheet);
				jQuery("#apply_alternative #template").attr("value",template);
				nelioab_show_the_dialog_for_overwriting(id);
			}
			<?php
		}

		protected function print_winner_info() {
			// Winner (if any) details
			$the_winner            = $this->who_wins();

			$exp = $this->exp;
			if ( $exp->get_status() == NelioABExperiment::STATUS_RUNNING ) {
				if ( $the_winner == 0 )
					echo '<p><b>' . esc_html__( 'Right now, no alternative is beating the original theme.', 'nelio-ab-testing' ) . '</b></p>';
				if ( $the_winner > 0 )
					echo '<p><b>' . esc_html( sprintf( __( 'Right now, alternative %s is better than the original theme.', 'nelio-ab-testing' ), $the_winner ) ) . '</b></p>';
			}
			else {
				if ( $the_winner == 0 )
					echo '<p><b>' . esc_html__( 'No alternative was better the original theme.', 'nelio-ab-testing' ) . '</b></p>';
				if ( $the_winner > 0 )
					echo '<p><b>' . esc_html( sprintf( __( 'Alternative %s was better than the original theme.', 'nelio-ab-testing' ), $the_winner ) ) . '</b></p>';
			}
		}

		protected function get_digested_results() {

			$results = array();
			array_push( $results, $this->get_original_digested_results() );
			foreach ( $this->get_alternative_digested_results() as $r ) {
				array_push( $results, $r );
			}//end foreach

			return $results;

		}//end get_digested_results()

		private function get_original_digested_results() {
			// THE ORIGINAL
			// -----------------------------------------

			if( $this->results == null ) {
				$pageviews       = 0;
				$conversions     = 0;
				$conversion_rate = 0.0;
			} else {
				$alt_results     = $this->results->get_alternative_results();
				$ori_result      = $alt_results[0];
				$pageviews       = $ori_result->get_num_of_visitors();
				$conversions     = $ori_result->get_num_of_conversions();
				$conversion_rate = $ori_result->get_conversion_rate();
			}//end if

			$exp = $this->exp;
			$the_themes = wp_get_themes();
			$ori = $exp->get_original();

			if ( $this->is_winner( $this->exp->get_originals_id() ) ) {
				$winner = true;
				$icon = $this->get_winner_icon( $exp );
			} else {
				$winner = false;
				$icon = $this->get_experiment_icon( $exp );
			}//end if

			$image = false;
			foreach ( $the_themes as $t ) {
				if ( $t['Stylesheet'] == $ori->get_value() ) {
					$image = $t->get_screenshot();
					break;
				}
			}//end foreach

			return array(
				'winner'            => $winner,
				'alternativeNumber' => 0,
				'iconTag'           => $icon,
				'name'              => $this->trunk( $this->get_original_name() ),
				'image'             => $image,
				'preview'           => false,
				'graphicId'         => 'graphic-' . $ori->get_id(),
				'conversionViews'   => $conversions . ' / ' . $pageviews,
				'conversionRate'    => number_format_i18n( floatval( $conversion_rate ), 2 ) . ' %',
				'buttons'           => '',
				'pageviews'         => $pageviews,
				'conversions'       => $conversions,
				'labels' => array(
					'conversions' => __( 'Conversions', 'nelio-ab-testing' ),
					'pageviews'   => __( 'Page Views', 'nelio-ab-testing' ),
				),
			);

		}//end get_original_digested_results()

		private function get_alternative_digested_results() {
			// REAL ALTERNATIVES
			// -----------------------------------------
			$exp = $this->exp;
			$the_themes = wp_get_themes();

			if( $this->results == null ) {
				$alt_results     = null;
				$ori_conversions = 0;
			} else {
				$alt_results     = $this->results->get_alternative_results();
				$ori_conversions = $alt_results[0]->get_num_of_conversions();
				// in this function, the original alternative is NOT used
				$alt_results = array_slice( $alt_results, 1 );
			}


			$i = 0;
			$digested_results = array();
			foreach ( $exp->get_alternatives() as $alt ) {
				$i++;

				$name = $this->trunk( $alt->get_name() );

				if ( $alt_results != null ) {
					$alt_result         = $alt_results[ $i - 1 ];
					$pageviews          = $alt_result->get_num_of_visitors();
					$conversions        = $alt_result->get_num_of_conversions();
					$conversion_rate    = $alt_result->get_conversion_rate();
					$improvement_factor = $alt_result->get_improvement_factor();
				} else {
					$pageviews          = 0;
					$conversions        = 0;
					$conversion_rate    = 0.0;
					$improvement_factor = 0.0;
				}

				$aux = ( $ori_conversions * $this->goal->get_benefit() * $improvement_factor )/100;
				if ( $aux > 0 ) {
					$gain = sprintf( _x( '%1$s%2$s', 'money', 'nelio-ab-testing' ),
						NelioABSettings::get_conv_unit(),
						number_format_i18n( $aux, 2 )
					);
				} else {
					$gain = sprintf( _x( '%1$s%2$s', 'money', 'nelio-ab-testing' ),
						NelioABSettings::get_conv_unit(),
						number_format_i18n( $aux * -1, 2 )
					);
				}

				// format improvement factor
				if ( $improvement_factor < 0 ) {
					$print_improvement = true;
					$arrow = 'down';
					$improvement_factor = -$improvement_factor;
				} else if ( $improvement_factor > 0 ) {
					$print_improvement = true;
					$arrow = 'up';
				} else {
					$arrow = false;
					$print_improvement = false;
				}

				if ( $this->is_winner( $alt->get_id() ) ) {
					$winner = true;
					$icon = $this->get_winner_icon( $exp );
				} else {
					$winner = false;
					$icon = $this->get_experiment_icon( $exp );
				}

				$theme = null;
				$image = false;
				foreach ( $the_themes as $t ) {
					if ( $t['Stylesheet'] == $alt->get_value() ) {
						$theme = $t;
						$image = $t->get_screenshot();
						break;
					}
				}

				$action_links = array();

				if ( $theme != NULL && $exp->get_status() == NelioABExperiment::STATUS_FINISHED ) {
					$img = '<span id="loading-' . esc_attr( $alt->get_value() ) . '" class="dashicons dashicons-update fa-spin animated nelio-apply"></span>';

					if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
						$aux = sprintf(
							' <a class="apply-link button" href="%s">%s %s</a>',
							esc_attr( sprintf(
								'javascript:nelioab_confirm_overwriting(%s, %s, %s);',
								json_encode( $alt->get_value() ),
								json_encode( $theme['Stylesheet'] ),
								json_encode( $theme['Template'] )
							) ),
							$img,
							esc_html__( 'Apply', 'nelio-ab-testing' )
						);
					} else {
						$aux = sprintf(
							'<a class="apply-link button disabled" href="#">%s</a>',
							esc_html__( 'Apply', 'nelio-ab-testing' )
						);
					}

					array_push( $action_links, $aux );
				}

				array_push( $digested_results, array(
					'winner'            => $winner,
					'alternativeNumber' => $i,
					'iconTag'           => $icon,
					'name'              => $name,
					'image'             => $image,
					'preview'           => false,
					'graphicId'         => 'graphic-' . $alt->get_id(),
					'conversionViews'   => $conversions . ' / ' . $pageviews,
					'conversionRate'    => number_format_i18n( floatval( $conversion_rate ), 2 ) . ' %',
					'arrowDirection'    => $arrow,
					'buttons'           => implode( ' ', $action_links ),
					'showImprovement'   => $print_improvement,
					'improvementFactor' => number_format_i18n( floatval( $improvement_factor ), 2 ) . ' %',
					'moneyGain'         => $gain,
					'pageviews'         => $pageviews,
					'conversions'       => $conversions,
					'labels' => array(
						'conversions' => __( 'Conversions', 'nelio-ab-testing' ),
						'pageviews'   => __( 'Page Views', 'nelio-ab-testing' ),
					),
				) );

			}

			return $digested_results;

		}//end get_alternative_digested_results()

		protected function who_wins() {
			$exp = $this->exp;
			$winner_id = $this->who_wins_real_id();
			if ( $winner_id == $exp->get_originals_id() )
				return 0;
			$i = 1;
			foreach ( $exp->get_alternatives() as $alt ) {
				if ( $winner_id == $alt->get_id() )
					return $i;
				$i++;
			}
			return self::NO_WINNER;
		}

		protected function get_winning_gtest() {
			$res = $this->results;
			if ( $res == null )
				return false;

			$gtests = $res->get_gtests();

			if ( count( $gtests ) == 0 )
				return false;

			/** @var NelioABGTest $bestg */
			$bestg = $gtests[count( $gtests ) - 1];

			if ( $bestg->is_original_the_best() ) {
				if ( $bestg->get_type() == NelioABGTest::WINNER )
					return $bestg;
			}
			else {
				$aux = null;
				foreach ( $gtests as $gtest )
					if ( $gtest->get_min() == $this->exp->get_originals_id() )
						$aux = $gtest;
				if ( $aux )
					if ( $aux->get_type() == NelioABGTest::WINNER ||
					     $aux->get_type() == NelioABGTest::DROP_VERSION )
						return $aux;
			}

			return false;
		}

		protected function print_dialog_content() {
			$exp = $this->exp;
			?>
			<p><?php
				esc_html_e( 'You are about to overwrite the original theme with the alternative. Are you sure you want to overwrite your current theme?', 'nelio-ab-testing' );
			?></p>
			<form id="apply_alternative" method="post" action="<?php
				echo admin_url(
					'admin.php?page=nelioab-experiments&action=progress&id=' . $exp->get_id() ); ?>">
				<input type="hidden" name="apply_alternative" value="true" />
				<input type="hidden" name="nelioab_exp_type" value="<?php echo $exp->get_type(); ?>" />
				<input type="hidden" id="stylesheet" name="stylesheet" value="" />
				<input type="hidden" id="template" name="template" value="" />
			</form>
			<?php
		}

		protected function get_labels_for_conversion_rate_js() {
			$labels = array();
			$labels['title']    = __( 'Conversion Rates', 'nelio-ab-testing' );
			$labels['subtitle'] = __( 'for the original and the alternative themes', 'nelio-ab-testing' );
			$labels['xaxis']    = __( 'Alternatives', 'nelio-ab-testing' );
			$labels['yaxis']    = __( 'Conversion Rate (%)', 'nelio-ab-testing' );
			$labels['column']   = __( '{0}%', 'nelio-ab-testing' );
			$labels['detail']   = __( 'Conversions: {0}%', 'nelio-ab-testing' );
			return $labels;
		}

		protected function get_labels_for_improvement_factor_js() {
			$labels = array();
			$labels['title']    = __( 'Improvement Factors', 'nelio-ab-testing' );
			$labels['subtitle'] = __( 'with respect to the original theme', 'nelio-ab-testing' );
			$labels['xaxis']    = __( 'Alternatives', 'nelio-ab-testing' );
			$labels['yaxis']    = __( 'Improvement (%)', 'nelio-ab-testing' );
			$labels['column']   = __( '{0}%', 'nelio-ab-testing' );
			$labels['detail']   = __( '{0}% improvement', 'nelio-ab-testing' );
			return $labels;
		}

	}//NelioABThemeAltExpProgressPage

}
