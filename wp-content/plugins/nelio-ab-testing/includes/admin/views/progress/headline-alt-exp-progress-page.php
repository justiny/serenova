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

if ( !class_exists( 'NelioABHeadlineAltExpProgressPage' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_ADMIN_DIR . '/views/progress/post-alt-exp-progress-page.php' );

	class NelioABHeadlineAltExpProgressPage extends NelioABPostAltExpProgressPage {

		public function __construct( $title ) {
			parent::__construct( $title );
		}

		protected function print_experiment_details_title() {
			esc_html_e( 'Details of the Headline Experiment', 'nelio-ab-testing' );
		}

		protected function print_js_function_for_post_data_overwriting() { ?>
			function nelioab_confirm_overwriting(id, title, excerpt, image) {
				jQuery("#alternative_alternative").attr("value",id);
				jQuery("#alternative_title").attr("value",title);
				jQuery("#alternative_image").attr("value",image);
				jQuery("#alternative_excerpt").attr("value",excerpt);
				nelioab_show_the_dialog_for_overwriting(id);
			}
			<?php
		}

		protected function who_wins() {
			$exp = $this->exp;
			$winner_id = $this->who_wins_real_id();

			// Workaround for WC Experiments
			if ( $exp->get_type() != NelioABExperiment::HEADLINE_ALT_EXP
				&& $winner_id == -1 ) {
				$winner_id = $exp->get_originals_id();
			}
			// END

			if ( $winner_id == $exp->get_originals_id() )
				return 0;
			$i = 1;
			foreach ( $exp->get_alternatives() as $alt ) {
				// Workaround for WC Experiments
				if ( $exp->get_type() == NelioABExperiment::HEADLINE_ALT_EXP ) {
					$value = $alt->get_value();
					$alt_id = $value['id'];
				} else {
					$alt_id = $alt->get_id();
				}
				// END
				if ( $winner_id == $alt_id ) {
					return $i;
				}
				$i++;
			}
			return self::NO_WINNER;
		}

		// Workaround for WC Experiments
		protected function get_original_value() {
			$exp = $this->exp;
			if ( $exp->get_type() == NelioABExperiment::HEADLINE_ALT_EXP ) {
				return parent::get_original_value();
			} else {
				return -1;
			}
		}
		// END

		// Workaround for WC Experiments
		protected function get_winning_gtest() {
			$res = $this->results;
			if ( $res == null )
				return false;

			$gtests = $res->get_gtests();

			if ( count( $gtests ) == 0 )
				return false;

			/** @var NelioABGTest $bestg */
			$bestg = $gtests[count( $gtests ) - 1];

			$is_original_the_best = $bestg->get_max() == $this->get_original_value();
			if ( $is_original_the_best ) {
				if ( $bestg->get_type() == NelioABGTest::WINNER )
					return $bestg;
			}
			else {
				$aux = null;
				foreach ( $gtests as $gtest )
					if ( $gtest->get_min() == $this->get_original_value() )
						$aux = $gtest;
				if ( $aux )
					if ( $aux->get_type() == NelioABGTest::WINNER ||
					     $aux->get_type() == NelioABGTest::DROP_VERSION )
						return $aux;
			}

			return false;
		}
		// END

		public function set_experiment( $exp ) {
			NelioABAltExpProgressPage::set_experiment( $exp );
		}

		public function do_render() {
			parent::do_render(); ?>
			<div id="preview-dialog-modal" title="<?php echo esc_attr_x( 'Preview', 'noun', 'nelio-ab-testing' ); ?>" style="display:none;">
				<div class="nelioab-row">
					<div class="nelioab-image">
						<img src="" />
					</div>
					<div class="nelioab-text">
						<p class="nelioab-title"></p>
						<p class="nelioab-excerpt"></p>
					</div>
				</div>
			</div>
			<script type="text/javascript">
			var $nelioabPreviewDialog;
			jQuery(document).ready(function() {
				$nelioabPreviewDialog = jQuery('#preview-dialog-modal').dialog({
					dialogClass   : 'wp-dialog',
					modal         : true,
					autoOpen      : false,
					closeOnEscape : true,
					width         : 600,
					buttons : [
						{
							text: <?php echo json_encode( __( 'Close', 'nelio-ab-testing' ) ); ?>,
							click: function() {
								jQuery(this).dialog('close');
							}
						},
					]
				});
			});
			function nelioabPreviewLink(title, excerpt, imageUrl) {
				jQuery('#preview-dialog-modal img').attr('src', imageUrl);
				jQuery('#preview-dialog-modal .nelioab-title').html(title);
				jQuery('#preview-dialog-modal .nelioab-excerpt').html(excerpt);
				$nelioabPreviewDialog.dialog('open');
			}
			</script>
			<?php
		}

		protected function get_action_links( $exp, $alt_id, $primary = false ) {
			$action_links = array();

			$alternative = false;
			$the_value = array();

			if ( $alt_id == $exp->get_originals_id() ) {
				$post      = get_post( $alt_id );
				$excerpt   = $post->post_excerpt;
				$name      = $post->post_title;
				$image_id  = get_post_thumbnail_id( $alt_id );
				$aux       = wp_get_attachment_image_src( $image_id );
				$image_src = ( count( $aux ) > 0 ) ? $aux[0] : '';
			}
			else {
				foreach( $exp->get_alternatives() as $alt ) {
					$value = $alt->get_value();
					if ( is_array( $value ) && $alt->get_id() == $alt_id ) {
						$alternative = $alt;
						$the_value = $value;
					}
				}

				$name = $alternative->get_name();
				$excerpt = $the_value['excerpt'];
				$image_id = $the_value['image_id'];
				$attach = wp_get_attachment_image_src( $image_id );
				$image_src = ( count( $attach ) > 0 ) ? $attach[0] : '';
			}

			$aux = sprintf(
				' <a class="button" href="%s">%s</a>',
				esc_attr( sprintf(
					'javascript:nelioabPreviewLink(%s, %s, %s);',
					json_encode( $name ),
					json_encode( $excerpt ),
					json_encode( $image_src )
				) ),
				esc_html__( 'Preview', 'nelio-ab-testing' )
			);
			array_push( $action_links, $aux );

			if ( $exp->get_status() == NelioABExperiment::STATUS_FINISHED ) {

				$img = '<span id="loading-' . esc_attr( $alt_id ) . '" class="dashicons dashicons-update fa-spin animated nelio-apply"></span>';

				if ( $primary ) {
					if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
						$aux = sprintf(
							' <a class="apply-link button button-primary" href="%s">%s %s</a>',
							esc_attr( sprintf(
								'javascript:nelioab_confirm_overwriting(%s, %s, %s, %s);',
								json_encode( $alt_id ),
								json_encode( $name ),
								json_encode( $excerpt ),
								json_encode( $image_id )
							) ),
							$img,
							esc_html__( 'Apply', 'nelio-ab-testing' )
						);
					} else {
						$aux = sprintf(
							' <a class="apply-link button button-primary disabled" href="#">%s</a>',
							esc_html__( 'Apply', 'nelio-ab-testing' )
						);
					}
				} else {
					if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
						$aux = sprintf(
							' <a class="apply-link button" href="%s">%s %s</a>',
							esc_attr( sprintf(
								'javascript:nelioab_confirm_overwriting(%s, %s, %s, %s);',
								json_encode( $alt_id ),
								json_encode( $name ),
								json_encode( $excerpt ),
								json_encode( $image_id )
							) ),
							$img,
							esc_html__( 'Apply', 'nelio-ab-testing' )
						);
					} else {
						$aux = sprintf(
							' <a class="apply-link button disabled" href="#">%s</a>',
							esc_html__( 'Apply', 'nelio-ab-testing' )
						);
					}
				}
				array_push( $action_links, $aux );
			}

			return $action_links;
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
			$ori_id = $exp->get_originals_id();

			$post = get_post( $ori_id );
			if ( $post ) {
				$name = $this->trunk( $this->ori );
				$excerpt = $post->post_excerpt;
			} else {
				$name = esc_html__( '(Not found)', 'nelio-ab-testing' );
				$excerpt = '';
			}

			if ( $this->is_winner( $this->exp->get_originals_id() ) ) {
				$winner = true;
				$icon = $this->get_winner_icon( $exp );
			} else {
				$winner = false;
				$icon = $this->get_experiment_icon( $exp );
			}//end if

			$action_links = $this->get_action_links( $exp, $exp->get_originals_id(), $winner );

			return array(
				'winner'            => $winner,
				'alternativeNumber' => 0,
				'iconTag'           => $icon,
				'name'              => $name,
				'image'             => false,
				'preview'           => false,
				'graphicId'         => 'graphic-' . $ori->get_id(),
				'conversionViews'   => $conversions . ' / ' . $pageviews,
				'conversionRate'    => number_format_i18n( floatval( $conversion_rate ), 2 ) . ' %',
				'buttons'           => implode( ' ', $action_links ),
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
			foreach ( $exp->get_alternatives() as $alt ) {
				$i++;

				$id = $alt->get_id();

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

				$name = $alt->get_name();

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
					$stats_color = 'red';
					$improvement_factor = -$improvement_factor;
				} else if ( $improvement_factor > 0 ) {
					$print_improvement = true;
					$arrow = 'up';
					$stats_color = 'green';
				} else { // $improvement_factor = 0.0
					$arrow = false;
					$stats_color = 'black';
					$print_improvement = false;
				}

				if ( $this->is_winner( $alt->get_value() ) ) {
					$winner = true;
					$icon  = $this->get_winner_icon( $exp );
				} else {
					$winner = false;
					$icon = $this->get_experiment_icon( $exp );
				}

				$action_links = $this->get_action_links( $exp, $id, $winner );

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
			}//end foreach

			return $digested_results;

		}//end get_alternative_digested_results()

		protected function print_dialog_content() {
			$exp = $this->exp;
			?>
			<p><?php
				esc_html_e( 'You are about to overwrite the original version with an alternative. Are you sure you want overwrite it?', 'nelio-ab-testing' );
			?></p>
			<form id="apply_alternative" method="post" action="<?php
				echo admin_url(
					'admin.php?page=nelioab-experiments&action=progress&id=' . $exp->get_id() ); ?>">
				<input type="hidden" name="apply_alternative" value="true" />
				<input type="hidden" id="alternative_title" name="alternative_title" value="" />
				<input type="hidden" id="alternative_image" name="alternative_image" value="" />
				<input type="hidden" id="alternative_excerpt" name="alternative_excerpt" value="" />
				<input type="hidden" id="original" name="original" value="<?php echo $exp->get_originals_id(); ?>" />
				<input type="hidden" id="alternative" name="alternative" value="" />
				<input type="hidden" name="nelioab_exp_type" value="<?php echo NelioABExperiment::HEADLINE_ALT_EXP; ?>" />
			</form>
			<?php
		}

		protected function get_labels_for_conversion_rate_js() {
			$labels = parent::get_labels_for_conversion_rate_js();
			$labels['subtitle'] = __( 'for the original and the alternative versions', 'nelio-ab-testing' );
			return $labels;
		}

		protected function get_labels_for_improvement_factor_js() {
			$labels = parent::get_labels_for_improvement_factor_js();
			$labels['subtitle'] = __( 'with respect to the original version', 'nelio-ab-testing' );
			return $labels;
		}

	}//NelioABHeadlineAltExpProgressPage

}
