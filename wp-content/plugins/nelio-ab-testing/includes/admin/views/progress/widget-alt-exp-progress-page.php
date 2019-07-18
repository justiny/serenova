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

if ( !class_exists( 'NelioABWidgetAltExpProgressPage' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_ADMIN_DIR . '/views/progress/alt-exp-progress-page.php' );

	class NelioABWidgetAltExpProgressPage extends NelioABAltExpProgressPage {

		private $alts_to_apply;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
			$this->exp           = null;
			$this->results       = null;
			$this->alts_to_apply = false;
		}

		public function set_there_are_alternatives_to_apply( $alts_to_apply ) {
			$this->alts_to_apply = $alts_to_apply;
		}

		protected function print_experiment_details_title() {
			esc_html_e( 'Details of the Widget Experiment', 'nelio-ab-testing' );
		}

		protected function get_original_name() {
			return __( 'Default Widget Set', 'nelio-ab-testing' );
		}

		protected function get_original_value() {
			return $this->exp->get_originals_id();
		}

		protected function print_js_function_for_post_data_overwriting() { ?>
			function nelioab_confirm_overwriting(id, elem) {
				if ( 'apply-ori-and-clean' == elem ) {
					jQuery('#dialog-content p.apply-ori-and-clean').show();
					jQuery('#dialog-content p.apply-alt-and-clean').hide();
				}
				else {
					jQuery('#dialog-content p.apply-alt-and-clean').show();
					jQuery('#dialog-content p.apply-ori-and-clean').hide();
				}
				jQuery("#apply_alternative #alternative").attr("value",id);
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
					echo '<p><b>' . esc_html__( 'Right now, no alternative Widget set is helping to improve your site.', 'nelio-ab-testing' ) . '</b></p>';
				if ( $the_winner > 0 )
					echo '<p><b>' . esc_html( sprintf( __( 'Right now, the alternative %s is better than none to improve your site.', 'nelio-ab-testing' ), $the_winner ) ) . '</b></p>';
			}
			else {
				if ( $the_winner == 0 )
					echo '<p><b>' . esc_html__( 'No alternative Widget set helped to improve your site.', 'nelio-ab-testing' ) . '</b></p>';
				if ( $the_winner > 0 )
					echo '<p><b>' . esc_html( sprintf( __( 'The alternative Widget set %s was better than the original set.', 'nelio-ab-testing' ), $the_winner ) ) . '</b></p>';
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
			}

			$exp = $this->exp;
			$ori = $exp->get_original();

			$colorscheme = NelioABWpHelper::get_current_colorscheme();
			if ( $this->is_winner( $this->exp->get_originals_id() ) ) {
				$winner = true;
				$icon = $this->get_winner_icon( $exp );
			} else {
				$winner = false;
				$icon = $this->get_experiment_icon( $exp );
			}//end if

			$action_links = array();

			switch ( $exp->get_status() ) {

				case NelioABExperiment::STATUS_RUNNING:
					if ( NelioABExperimentsManager::current_user_can( $exp, 'edit' ) ) {
						$aux = sprintf(
							'<a class="apply-link button" href="%s">%s</a>',
							esc_attr( sprintf(
								'javascript:nelioabConfirmEditing(%s,"dialog");',
								json_encode( admin_url( 'widgets.php' ) )
							) ),
							esc_html__( 'Edit' )
						);
					} else {
						$aux = sprintf( '<a class="apply-link button disabled" href="#">%s</a>', esc_html__( 'Edit' ) );
					}
					array_push( $action_links, $aux );
					break;

				case NelioABExperiment::STATUS_FINISHED:
					if ( $this->alts_to_apply ) {
						$img = '<span id="loading-' . esc_attr( $exp->get_originals_id() ) . '" class="dashicons dashicons-update fa-spin animated nelio-apply"></span>';

						if ( $winner ) {
							if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
								$aux = sprintf(
									'<a class="apply-link button button-primary" href="%s">%s %s</a>',
									esc_attr( sprintf(
										'javascript:nelioab_confirm_overwriting(%s,"apply-ori-and-clean");',
										json_encode( $exp->get_originals_id() )
									) ),
									$img,
									esc_html__( 'Apply and Clean', 'nelio-ab-testing' ) );
							} else {
								$aux = sprintf(
									'<a class="apply-link button button-primary disabled" href="#">%s</a>',
									esc_html__( 'Apply and Clean', 'nelio-ab-testing' )
								);
							}
						} else {
							if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
								$aux = sprintf(
									'<a class="apply-link button" href="%s">%s %s</a>',
									esc_attr( sprintf(
										'javascript:nelioab_confirm_overwriting(%s,"apply-ori-and-clean");',
										json_encode( $exp->get_originals_id() )
									) ),
									$img,
									esc_html__( 'Apply and Clean', 'nelio-ab-testing' ) );
							} else {
								$aux = sprintf(
									'<a class="apply-link button disabled" href="#">%s</a>',
									esc_html__( 'Apply and Clean', 'nelio-ab-testing' )
								);
							}
						}

						array_push( $action_links, $aux );
					}
					break;
			}
			$buttons = implode( ' ', $action_links );

			return array(
				'winner'            => $winner,
				'alternativeNumber' => 0,
				'iconTag'           => $icon,
				'name'              => $this->trunk( $this->get_original_name() ),
				'image'             => false,
				'preview'           => false,
				'graphicId'         => 'graphic-' . $ori->get_id(),
				'conversionViews'   => $conversions . ' / ' . $pageviews,
				'conversionRate'    => number_format_i18n( floatval( $conversion_rate ), 2 ) . ' %',
				'buttons'           => $buttons,
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
			$colorscheme = NelioABWpHelper::get_current_colorscheme();
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
					$icon  = $this->get_winner_icon( $exp );
				} else {
					$winner = false;
					$icon = $this->get_experiment_icon( $exp );
				}

				$action_links = array();

				switch ( $exp->get_status() ) {

					case NelioABExperiment::STATUS_RUNNING:
						if ( NelioABExperimentsManager::current_user_can( $exp, 'edit' ) ) {
							$auxl = sprintf(
								'<a class="apply-link button" href="%s">%s</a>',
								esc_attr( sprintf(
									'javascript:nelioabConfirmEditing(%s, "dialog" );',
									json_encode( admin_url( 'widgets.php?nelioab_exp=' . esc_attr( $exp->get_id() ) .
										'&nelioab_alt=' . esc_attr( $alt->get_id() ) . '&nelioab_check=' .
										md5( $exp->get_id() . $alt->get_id() ) ) )
								) ),
								esc_html__( 'Edit' ) );
						} else {
							$auxl = sprintf( '<a class="apply-link button disabled" href="#">%s</a>', esc_html__( 'Edit' ) );
						}
						array_push( $action_links, $auxl );
						break;

					case NelioABExperiment::STATUS_FINISHED:
						if ( $this->alts_to_apply ) {
							$img = '<span id="loading-' . esc_attr( $alt->get_id() ) . '" class="dashicons dashicons-update fa-spin animated nelio-apply"></span>';

							if ( $winner ) {
								if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
									$auxl = sprintf(
										'<a class="apply-link button button-primary" href="%s">%s %s</a>',
										esc_attr( sprintf(
											'javascript:nelioab_confirm_overwriting(%s, "apply-alt-and-clean" );',
											json_encode( $alt->get_id() )
										) ),
										$img,
										esc_html__( 'Apply and Clean', 'nelio-ab-testing' )
									);
								} else {
									$auxl = sprintf(
										'<a class="apply-link button button-primary disabled" href="#">%s</a>',
										esc_html__( 'Apply and Clean', 'nelio-ab-testing' )
									);
								}
							} else {
								if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
									$auxl = sprintf(
										'<a class="apply-link button" href="%s">%s %s</a>',
										esc_attr( sprintf(
											'javascript:nelioab_confirm_overwriting(%s, "apply-alt-and-clean" );',
											json_encode( $alt->get_id() )
										) ),
										$img,
										esc_html__( 'Apply and Clean', 'nelio-ab-testing' ) );
								} else {
									$auxl = sprintf(
										'<a class="apply-link button disabled" href="#">%s</a>',
										esc_html__( 'Apply and Clean', 'nelio-ab-testing' )
									);
								}
							}

							array_push( $action_links, $auxl );
						}
						break;
				}

				array_push( $digested_results, array(
					'winner'            => $winner,
					'alternativeNumber' => $i,
					'iconTag'           => $icon,
					'name'              => $name,
					'image'             => false,
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

		protected function get_labels_for_conversion_rate_js() {
			$labels = array();
			$labels['title']    = __( 'Conversion Rates', 'nelio-ab-testing' );
			$labels['subtitle'] = __( 'for default and alternative Widget sets', 'nelio-ab-testing' );
			$labels['xaxis']    = __( 'Alternatives', 'nelio-ab-testing' );
			$labels['yaxis']    = __( 'Conversion Rate (%)', 'nelio-ab-testing' );
			$labels['column']   = __( '{0}%', 'nelio-ab-testing' );
			$labels['detail']   = __( 'Conversions: {0}%', 'nelio-ab-testing' );
			return $labels;
		}

		protected function get_labels_for_improvement_factor_js() {
			$labels = array();
			$labels['title']    = __( 'Improvement Factors', 'nelio-ab-testing' );
			$labels['subtitle'] = __( 'with respect to the original Widget set', 'nelio-ab-testing' );
			$labels['xaxis']    = __( 'Alternatives', 'nelio-ab-testing' );
			$labels['yaxis']    = __( 'Improvement (%)', 'nelio-ab-testing' );
			$labels['column']   = __( '{0}%', 'nelio-ab-testing' );
			$labels['detail']   = __( '{0}% improvement', 'nelio-ab-testing' );
			return $labels;
		}

		protected function print_dialog_content() {
			$exp = $this->exp;
			?>
			<p class="apply-ori-and-clean" style="display:none;"><?php
				_e( 'You are about to <strong>make your current widgets permanent</strong>.<br><br>Please note alternative widget sets will be removed. <strong>This operation cannot be undone</strong>.<br><br>Are you sure you want to make them permanent?', 'nelio-ab-testing' );
			?></p>
			<p class="apply-alt-and-clean" style="display:none;"><?php
				_e( 'You are about to <strong>replace your current widgets with the alternative ones</strong>.<br><br>Please note alternative widget sets will be removed. <strong>This operation cannot be undone</strong>.<br><br>Are you sure you want to replace your current set of widgets?', 'nelio-ab-testing' );
			?></p>
			<form id="apply_alternative" method="post" action="<?php
				echo admin_url(
					'admin.php?page=nelioab-experiments&action=progress&' .
					'id=' . $exp->get_id() . '&' .
					'type=' . $exp->get_type() ); ?>">
				<input type="hidden" name="apply_alternative" value="true" />
				<input type="hidden" name="nelioab_exp_type" value="<?php echo $exp->get_type(); ?>" />
				<input type="hidden" id="original" name="original" value="<?php echo $exp->get_originals_id(); ?>" />
				<input type="hidden" id="alternative" name="alternative" value="" />
			</form>
			<?php
		}

	}//NelioABWidgetAltExpProgressPage

}
