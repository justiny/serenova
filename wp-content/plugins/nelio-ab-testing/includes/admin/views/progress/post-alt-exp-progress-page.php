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

if ( !class_exists( 'NelioABPostAltExpProgressPage' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_ADMIN_DIR . '/views/progress/alt-exp-progress-page.php' );

	class NelioABPostAltExpProgressPage extends NelioABAltExpProgressPage {

		protected $ori;
		protected $post_type;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
			$this->exp          = null;
			$this->results      = null;
			$this->post_type = array(
				'name'     => 'page',
				'singular' => 'Page',
				'plural'   => 'Pages'
			);
			$this->graphic_delay = 500;
		}

		public function set_experiment( $exp ) {
			parent::set_experiment( $exp );

			$aux = get_post( $exp->get_originals_id() );
			$this->is_ori_page = false;
			if ( $aux ) {
				if ( 'page' === $aux->post_type ) {
					$this->is_ori_page = true;
				}//end if
			}//end if

			switch ( $exp->get_post_type() ) {

				case 'page':
					$this->post_type = array(
						'name'     => 'page',
						'singular' => __( 'Page', 'nelio-ab-testing' ),
						'plural'   => __( 'Pages', 'nelio-ab-testing' )
					);
					break;

				case 'post';
					$this->post_type = array(
						'name'     => 'post',
						'singular' => __( 'Post', 'nelio-ab-testing' ),
						'plural'   => __( 'Posts', 'nelio-ab-testing' )
					);
					break;

				default:
					require_once( NELIOAB_UTILS_DIR . '/wp-helper.php' );
					$ptn = $exp->get_post_type();
					$pt = NelioABWpHelper::get_custom_post_types( $ptn );
					$this->post_type = array(
						'name'     => $pt->name,
						'singular' => __( $pt->labels->singular_name, 'nelio-ab-testing' ),
						'plural'   => __( $pt->labels->name, 'nelio-ab-testing' )
					);

			}
		}

		protected function get_original_name() {
			// Original title
			$exp = $this->exp;
			$aux = get_post( $exp->get_originals_id() );
			$this->ori = sprintf( __( 'Unknown (post_id is %s)', 'nelio-ab-testing' ), $exp->get_originals_id() );
			if ( $aux ) {
				$this->ori = trim( $aux->post_title );
			}
			return $this->ori;
		}

		protected function get_original_value() {
			return $this->exp->get_originals_id();
		}

		protected function print_js_function_for_post_data_overwriting() { ?>
			function nelioab_confirm_overwriting(id) {
				jQuery("#apply_alternative #alternative").attr("value",id);
				nelioab_show_the_dialog_for_overwriting(id);
			}
			<?php
		}

		private function make_link_for_heatmap( $exp, $id, $primary = false ) {
			include_once( NELIOAB_UTILS_DIR . '/wp-helper.php' );
			$url = sprintf(
				admin_url( 'admin.php?nelioab-page=heatmaps&id=%1$s&exp_type=%2$s&post=%3$s' ),
				esc_attr( $exp->get_id() ),
				esc_attr( $exp->get_type() ),
				esc_attr( $id )
			);

			if ( $primary ) {
				if ( current_user_can( 'edit_post', $id ) ) {
					return sprintf(
						' <a class="button button-primary" href="%1$s">%2$s</a>',
						esc_attr( $url ),
						esc_html__( 'View Heatmap', 'nelio-ab-testing' )
					);
				} else {
					return sprintf(
						' <a class="button button-primary disabled" href="#">%s</a>',
						esc_html__( 'View Heatmap', 'nelio-ab-testing' )
					);
				}
			} else {
				if ( current_user_can( 'edit_post', $id ) ) {
					return sprintf(
						' <a class="button" href="%1$s">%2$s</a>',
						esc_attr( $url ),
						esc_html__( 'View Heatmap', 'nelio-ab-testing' )
					);
				} else {
					return sprintf(
						' <a class="button disabled" href="#">%s</a>',
						esc_html__( 'View Heatmap', 'nelio-ab-testing' )
					);
				}
			}
		}

		private function make_link_for_edit( $exp, $id, $primary = false ) {
			if ( $primary ) {
				$extra_classes = ' button-primary';
			} else {
				$extra_classes = '';
			}//end if

			if ( current_user_can( 'edit_post', $id ) ) {
				return sprintf( ' <a class="button%s" href="%s">%s</a>',
					$extra_classes,
					esc_attr( sprintf(
						'javascript:nelioabConfirmEditing(%s,"dialog");',
						json_encode( admin_url( 'post.php?post=' . $id . '&action=edit' ) )
					) ),
					esc_html__( 'Edit' ) );
			} else {
				return sprintf( ' <a class="button%s disabled" href="#">%s</a>',
					$extra_classes,
					esc_html__( 'Edit' )
				);
			}//end if
		}

		protected function get_action_links( $exp, $alt_id, $primary = false ) {
			$action_links = array();

			if ( $exp->are_heatmaps_tracked() )
				array_push( $action_links, $this->make_link_for_heatmap( $exp, $alt_id, $primary ) );
			switch ( $exp->get_status() ) {
				case NelioABExperiment::STATUS_RUNNING:
					array_push( $action_links, $this->make_link_for_edit( $exp, $alt_id, $primary ) );
					break;
				case NelioABExperiment::STATUS_FINISHED:
					if ( $alt_id == $exp->get_originals_id() )
						break;

					$img = '<span id="loading-' . esc_attr( $alt_id ) . '" class="dashicons dashicons-update fa-spin animated nelio-apply"></span>';

					if ( $primary ) {
						if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
							$aux = sprintf(
								' <a class="apply-link button button-primary" href="%1$s">%2$s %3$s</a>',
								esc_attr( sprintf(
									'javascript:nelioab_confirm_overwriting(%s);',
									json_encode( $alt_id )
								) ),
								$img,
								esc_html__( 'Apply', 'nelio-ab-testing' )
							);
						} else {
							$aux = sprintf(
								'<a class="apply-link button button-primary disabled" href="#">%s</a>',
								esc_html__( 'Apply', 'nelio-ab-testing' )
							);
						}
					} else {
						if ( NelioABExperimentsManager::current_user_can( $exp, 'apply' ) ) {
							$aux = sprintf(
								' <a class="apply-link button" href="%1$s">%2$s %3$s</a>',
								esc_attr( sprintf(
									'javascript:nelioab_confirm_overwriting(%s);',
									json_encode( $alt_id )
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
					}

					array_push( $action_links, $aux );
					break;
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

			if ( $this->results == null ) {
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
			$ori = $exp->get_original();

			$link = get_permalink( $exp->get_originals_id() );
			if ( $link ) {
				$name = $this->trunk( $this->ori );
			} else {
				$name = __( '(Not found)', 'nelio-ab-testing' );
			}//end if

			if ( $this->is_winner( $this->exp->get_originals_id() ) ) {
				$winner = true;
				$icon = $this->get_winner_icon( $exp );
			} else {
				$winner = false;
				$icon = $this->get_experiment_icon( $exp );
			}//end if

			$action_links = $this->get_action_links( $exp, $exp->get_originals_id(), $winner );
			$aux = sprintf(
				' <a class="button" href="%s" target="_blank">%s</a>',
				esc_attr( $link ),
				esc_html__( 'View Content', 'nelio-ab-testing' )
			);
			array_unshift( $action_links, $aux );

			return array(
				'winner'            => $winner,
				'alternativeNumber' => 0,
				'iconTag'           => $icon,
				'name'              => $name,
				'image'             => false,
				'preview'           => esc_url( get_permalink( $exp->get_originals_id() ) ),
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
			$colorscheme = NelioABWpHelper::get_current_colorscheme();
			foreach ( $exp->get_alternatives() as $alt ) {
				$i++;

				$link = get_permalink( $alt->get_value() );
				if ( $this->is_ori_page ) {
					$link = add_query_arg( array(
							'preview' => 'true',
						), $link
					);
				}//end if

				if ( $link ) {
					$name = $this->trunk( $alt->get_name() );
				} else {
					$name = __( '(Not found)', 'nelio-ab-testing' );
				}//end if

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

				if ( $this->is_winner( $alt->get_value() ) ) {
					$winner = true;
					$icon  = $this->get_winner_icon( $exp );
				} else {
					$winner = false;
					$icon = $this->get_experiment_icon( $exp );
				}

				$action_links = $this->get_action_links( $exp, $alt->get_value(), $winner );
				if ( current_user_can( 'edit_post', $alt->get_value() ) ) {
					$aux = sprintf(
						' <a class="button" href="%s" target="_blank">%s</a>',
						esc_url( $link ),
						esc_html__( 'View Content', 'nelio-ab-testing' ) );
				} else {
					$aux = sprintf(
						' <a class="button disabled" href="#">%s</a>',
						esc_html__( 'View Content', 'nelio-ab-testing' )
					);
				}
				array_unshift( $action_links, $aux );

				array_push( $digested_results, array(
					'winner'            => $winner,
					'alternativeNumber' => $i,
					'iconTag'           => $icon,
					'name'              => $name,
					'image'             => false,
					'preview'           => esc_url( get_permalink( $alt->get_value() ) ),
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
				printf( __( 'You are about to overwrite the original %s with the content of an alternative. Please, remember <strong>this operation cannot be undone</strong>. Are you sure you want to overwrite it?', 'nelio-ab-testing' ), $this->post_type['name'] );
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
				<p><input type="checkbox" id="copy_content" name="copy_content" checked="checked" disabled="disabled" /><?php
					esc_html_e( 'Override title and content', 'nelio-ab-testing' ); ?></p>
				<p><input type="checkbox" id="copy_meta" name="copy_meta" <?php
					if ( NelioABSettings::is_copying_metadata_enabled() ) echo 'checked="checked" ';
				?>/><?php esc_html_e( 'Override all metadata', 'nelio-ab-testing' ); ?></p>
				<?php
				if ( ! 'page' == $this->post_type['name'] ) { ?>
					<p><input type="checkbox" id="copy_categories" name="copy_categories" <?php
						if ( NelioABSettings::is_copying_categories_enabled() ) echo 'checked="checked" ';
					?>/><?php esc_html_e( 'Override categories', 'nelio-ab-testing' ); ?></p>
					<p><input type="checkbox" id="copy_tags" name="copy_tags" <?php
						if ( NelioABSettings::is_copying_tags_enabled() ) echo 'checked="checked" ';
					?>/><?php esc_html_e( 'Override tags', 'nelio-ab-testing' ); ?></p><?php
				} ?>
			</form>
			<?php
		}

		protected function get_labels_for_conversion_rate_js() {
			$labels = array();
			$labels['title']    = __( 'Conversion Rates', 'nelio-ab-testing' );
			$labels['subtitle'] = sprintf( __( 'for the original and the alternative %s', 'nelio-ab-testing' ), strtolower( $this->post_type['plural'] ) );
			$labels['xaxis']    = __( 'Alternatives', 'nelio-ab-testing' );
			$labels['yaxis']    = __( 'Conversion Rate (%)', 'nelio-ab-testing' );
			$labels['column']   = __( '{0}%', 'nelio-ab-testing' );
			$labels['detail']   = __( 'Conversions: {0}%', 'nelio-ab-testing' );
			return $labels;
		}

		protected function get_labels_for_improvement_factor_js() {
			$labels = array();
			$labels['title']    = __( 'Improvement Factors', 'nelio-ab-testing' );
			$labels['subtitle'] = sprintf( __( 'with respect to the original %s', 'nelio-ab-testing' ), $this->post_type['name'] );
			$labels['xaxis']    = __( 'Alternatives', 'nelio-ab-testing' );
			$labels['yaxis']    = __( 'Improvement (%)', 'nelio-ab-testing' );
			$labels['column']   = __( '{0}%', 'nelio-ab-testing' );
			$labels['detail']   = __( '{0}% improvement', 'nelio-ab-testing' );
			return $labels;
		}

	}//NelioABPostAltExpProgressPage

}
