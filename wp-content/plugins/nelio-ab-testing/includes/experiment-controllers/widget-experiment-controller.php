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

if ( !class_exists( 'NelioABWidgetExpAdminController' ) ) {

	class NelioABWidgetExpAdminController {

		public function __construct() {

			$exp_id = false;
			if ( isset( $_GET['nelioab_exp'] ) ) {
				$exp_id = absint( $_GET['nelioab_exp'] );
			}//end if

			$alt_id = false;
			if ( isset( $_GET['nelioab_alt'] ) ) {
				$alt_id = intval( $_GET['nelioab_alt'] );
			}//end if

			// Making sure we're accessing the proper page with proper params
			global $pagenow;
			if ( 'widgets.php' === $pagenow && $exp_id && $alt_id ) {

				$check = false;
				if ( isset( $_GET['nelioab_check'] ) ) {
					$check = sanitize_text_field( $_GET['nelioab_check'] );
				}//end if

				if ( md5( "$exp_id$alt_id" ) !== $check ) {
					wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
				}//end if

			}//end if

			// Adding some filters and hooks
			add_action( 'wp_ajax_nelioab_duplicate_original_widgets', array( &$this, 'duplicate_original_widgets' ) );

			add_filter( 'widget_update_callback', array( $this, 'widget_ajax_update_callback' ), 10, 4 );
			add_action( 'sidebar_admin_setup', array( $this, 'add_alt_info_in_hidden_fields' ) );
			add_action( 'widgets_admin_page', array( $this, 'add_code_to_hide_widgets_when_editing_widget_exp' ) );

			if ( 'customize.php' === $pagenow ) {
				add_action( 'customize_controls_print_footer_scripts', array( $this, 'add_code_to_hide_widgets_when_customizing_theme' ) );
			}//end if

		}//end __construct()

		public static function get_widgets_in_experiments() {
			return get_option( 'nelioab_widgets_in_experiments', array() );
		}

		public static function set_widgets_in_experiments( $widgets_in_exps ) {
			update_option( 'nelioab_widgets_in_experiments', $widgets_in_exps );
		}

		/**
		 * This function is used to save meta-information about those widgets that belong to an alternative
		 */
		public function widget_ajax_update_callback( $instance, $new_instance, $old_instance, $this_widget ) {

			if ( ! isset( $_POST['nelioab_exp'] ) ) {
				return $instance;
			}//end if

			if ( ! isset( $_POST['nelioab_alt'] ) ) {
				return $instance;
			}//end if

			$exp_id = absint( $_POST['nelioab_exp'] );
			$alt_id = intval( $_POST['nelioab_alt'] );

			$widgets_in_experiments = self::get_widgets_in_experiments();
			if ( $exp_id && $alt_id ) {
				self::link_widget_to_experiment( $this_widget->id, $exp_id, $alt_id, $widgets_in_experiments );
			} else {
				self::unlink_widget_to_experiment( $this_widget->id, $widgets_in_experiments );
			}//end if
			self::set_widgets_in_experiments( $widgets_in_experiments );

			return $instance;

		}

		/**
		 * This function makes it possible to insert some hidden fields (using another
		 * callback function). These fields contain information about the experiment
		 * and alternative the widget belongs to.
		 *
		 * We simply modify the widget's callback to one of our custom functions
		 * (in particular, `do_add_alt_info_in_hidden_fields'), but we keep a reference
		 * to the original callback.
		 */
		public function add_alt_info_in_hidden_fields() {
			global $wp_registered_widgets, $wp_registered_widget_controls;
			foreach ( $wp_registered_widgets as $id => $widget ) {
				if ( !isset( $wp_registered_widget_controls[$id] ) )
					wp_register_widget_control( $id, $widget['name'], array( $this, 'add_widget_empty_control' ) );
				$wp_registered_widget_controls[$id]['callback_nelioab_redirect'] = $wp_registered_widget_controls[$id]['callback'];
				$wp_registered_widget_controls[$id]['callback'] = array( $this, 'do_add_alt_info_in_hidden_fields' );
				array_push( $wp_registered_widget_controls[$id]['params'], $id );
			}
		}


		/**
		 * This function is used to insert hidden fields about the experiment and
		 * alternative the widget belongs to.
		 */
		public function do_add_alt_info_in_hidden_fields() {
			global $wp_registered_widget_controls;
			$params = func_get_args();
			$widget_id = array_pop( $params );
			$callback = $wp_registered_widget_controls[$widget_id]['callback_nelioab_redirect'];
			if ( is_callable( $callback ) )
				call_user_func_array( $callback, $params );

			$widgets_in_experiments = self::get_widgets_in_experiments();

			if ( isset( $widgets_in_experiments[$widget_id] ) ) {
				$widget = $widgets_in_experiments[$widget_id]; ?>
				<div class="<?php echo esc_attr( "nelioab-widget-$widget_id" ); ?>" style="display:none;"></div>
				<input type="hidden" name="nelioab_exp" value="<?php echo esc_attr( $widget['exp'] ); ?>" />
				<input type="hidden" name="nelioab_alt" value="<?php echo esc_attr( $widget['alt'] ); ?>" />
				<?php
			}
			else { ?>
				<input type="hidden" name="nelioab_exp" value="" />
				<input type="hidden" name="nelioab_alt" value="" />
				<?php
			}

		}


		/**
		 * This function adds just one script in the widgets.php page. The script
		 * checks if the user is currently editing a widget exp alternative and, if
		 * it is, it'll hide all widgets except the alternative's.
		 *
		 * If he's editing no alternative, then all widgets that belong to one will
		 * be hidden.
		 */
		public function add_code_to_hide_widgets_when_editing_widget_exp() {

			$exp_id = false;
			if ( isset( $_GET['nelioab_exp'] ) ) {
				$exp_id = absint( $_GET['nelioab_exp'] );
			}//end if

			$alt_id = false;
			if ( isset( $_GET['nelioab_alt'] ) ) {
				$alt_id = intval( $_GET['nelioab_alt'] );
			}//end if

			$viewing_original = ! ( $exp_id && $alt_id );

			$widgets_in_experiments = self::get_widgets_in_experiments();
			if ( $viewing_original ) {

				$widgets_to_hide = array_keys( $widgets_in_experiments );
				$exp_id = 'original';
				$alt_id = 'original';
				?>
				<script type="text/javascript">
				jQuery(document).ready(function() {
					var ids = <?php echo json_encode( $widgets_to_hide ); ?>;
					for ( i = 0; i < ids.length; ++i ) {
						jQuery('#widgets-right .nelioab-widget-' + ids[i]).closest('.widget').hide();
						jQuery('#wp_inactive_widgets .nelioab-widget-' + ids[i]).closest('.widget').hide();
					}
				});
				</script>
				<?php

			} else {

				$widgets_to_show = array();
				foreach ( $widgets_in_experiments as $widget_id => $exp_info )
					if ( $exp_info['exp'] == $exp_id && $exp_info['alt'] == $alt_id )
						array_push( $widgets_to_show, $widget_id );

				require_once( NELIOAB_UTILS_DIR . '/wp-helper.php' );
				$colorscheme = NelioABWpHelper::get_current_colorscheme();
				?>

				<div id="nelioab-edit-alt-widgets" class="widgets-holder-wrap" style="border: 1px solid #e5e5e5;-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);box-shadow: 0 1px 1px rgba(0,0,0,.04);">
					<div class="widgets-sortables ui-sortable">
						<div class="sidebar-name" style="<?php echo esc_attr( 'padding:0.2em 1em;background-color:' . $colorscheme['focus'] ); ?>">
							<div class="sidebar-name-arrow"><br></div>
							<h3 style="<?php echo esc_attr( 'color:' . $colorscheme['foreground'] ); ?>"><?php esc_html_e( 'Alternative Widget Set', 'nelio-ab-testing' ); ?></h3>
						</div>
					</div>

					<div class="content" style="padding:0em 1em 1em 1em;">
						<p><?php esc_html_e( 'Use the following button to duplicate the widgets you\'re currently using in your site.', 'nelio-ab-testing' ); ?></p>
						<a class="button button-primary" id="duplicate-widgets"><?php esc_html_e( 'Duplicate Widgets', 'nelio-ab-testing' ); ?></a>
						<span class="spinner" style="float:none;"></span>
						<p><strong><?php esc_html_e( 'Go back to...', 'nelio-ab-testing' ); ?></strong></p>
						<ul style="margin-left:1.5em;">
							<?php if ( isset( $_GET['back_to_edit'] ) ):
								$url = admin_url( 'admin.php?page=nelioab-experiments&action=edit&id=' . $exp_id . '&ctab=tab-alts&exp_type=' . NelioABExperiment::WIDGET_ALT_EXP );
								?>
								<li><a href="<?php echo $url; ?>"><?php esc_html_e( 'Editing this experiment', 'nelio-ab-testing' ); ?></a></li>
							<?php else:
								$url = admin_url( 'admin.php?page=nelioab-experiments&action=progress&id=' . $exp_id . '&exp_type=' . NelioABExperiment::WIDGET_ALT_EXP );
								?>
								<li><a href="<?php echo $url; ?>"><?php esc_html_e( 'The results of the related experiment', 'nelio-ab-testing' ); ?></a></li>
							<?php endif; ?>
							<?php $url = admin_url( 'admin.php?page=nelioab-experiments' ); ?>
							<li><a href="<?php echo $url; ?>"><?php esc_html_e( 'My list of experiments', 'nelio-ab-testing' ); ?></a></li>
						</ul>
					</div>

				</div>

				<script type="text/javascript">
				(function($) {
					var $content = jQuery('#nelioab-edit-alt-widgets .content');
					$('#nelioab-edit-alt-widgets .sidebar-name').on('click', function() {
						$content.toggle();
					});

					$('#duplicate-widgets').on('click', function() {
						if ( $(this).hasClass('disabled') )
							return;
						$(this).addClass('disabled');
						$('#nelioab-edit-alt-widgets .spinner').css('display', 'inline-block');
						$('#available-widgets,.inactive-sidebar,.widget-liquid-right').fadeOut();

						jQuery.ajax({
							type:  'POST',
							async: false,
							url:   ajaxurl,
							data: {
									action: 'nelioab_duplicate_original_widgets',
									exp_id: <?php echo json_encode( $exp_id ); ?>,
									alt_id: <?php echo json_encode( $alt_id ); ?>,
									check:  <?php echo json_encode( md5( "$exp_id$alt_id" ) ); ?>
								},
							success: function(data) {
								window.location.reload();
							},
							error: function(data) {
								// Something failed :-(
							},
						});

					});

					$(document).ajaxComplete(function() {
						if ( jQuery("#widgets-right .widget:visible").length > 0 )
							jQuery('#duplicate-widgets').addClass('disabled');
						else
							jQuery('#duplicate-widgets').removeClass('disabled');
					});

				})(jQuery);

				jQuery(document).ready(function() {
					// Hide all widgets and then show only those that belong to this alt/exp combo
					jQuery('#widgets-right .widget').hide();
					jQuery('#wp_inactive_widgets .widget').hide();
					var ids = <?php echo json_encode( $widgets_to_show ); ?>;
					for ( i = 0; i < ids.length; ++i )
						jQuery('.nelioab-widget-' + ids[i]).closest('.widget').css('display','');

					// Disable "duplicate widgets" button if there are widgets
					if ( jQuery("#widgets-right .widget:visible").length > 0 )
						jQuery('#duplicate-widgets').addClass('disabled');
					else
						jQuery('#duplicate-widgets').removeClass('disabled');

					// Add controls
					var $block = jQuery('#nelioab-edit-alt-widgets').detach();
					jQuery('#widgets-left').prepend($block);
					$block.show();
				});
				</script>
				<?php

			}//end if

			// Set the proper EXP/ALT combo values for new widgets
			?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('#available-widgets .widget input[name=nelioab_exp]').attr('value', <?php echo json_encode( $exp_id ); ?> );
				jQuery('#available-widgets .widget input[name=nelioab_alt]').attr('value', <?php echo json_encode( $alt_id ); ?> );
			});
			</script>
			<?php
		}


		/**
		 * This function adds just one script in the customize.php page. The script
		 * hides all alternative widgets.
		 */
		public function add_code_to_hide_widgets_when_customizing_theme() {
			$widgets_in_experiments = self::get_widgets_in_experiments();
			$widgets_to_hide = array_keys( $widgets_in_experiments );
			?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				var ids = <?php echo json_encode( $widgets_to_hide ); ?>;
				for ( i = 0; i < ids.length; ++i ) {
					jQuery('#widgets-right .nelioab-widget-' + ids[i]).closest('.widget').parent().hide();
					jQuery('#wp_inactive_widgets .nelioab-widget-' + ids[i]).closest('.widget').parent().hide();
				}
			});
			</script>
			<?php
		}

		public function add_widget_empty_control() {
			// Nothing in here
		}

		/**
		 *
		 */
		public static function duplicate_original_widgets() {

			$exp_id = false;
			if ( isset( $_POST['exp_id'] ) ) {
				$exp_id = absint( $_POST['exp_id'] );
			}//end if

			$alt_id = false;
			if ( isset( $_POST['alt_id'] ) ) {
				$alt_id = intval( $_POST['alt_id'] );
			}//end if

			if ( ! $exp_id || ! $alt_id ) {
				return;
			}//end if

			$widgets_in_experiments = self::get_widgets_in_experiments();
			$sidebars_widgets_ori = get_option( 'sidebars_widgets', array() );
			$sidebars_widgets = array();
			foreach ( $sidebars_widgets_ori as $sidebar => $widgets ) {
				if ( !is_array( $widgets ) ) {
					$sidebars_widgets[$sidebar] = $widgets;
					continue;
				}
				$sidebars_widgets[$sidebar] = array();
				foreach ( $widgets as $widget_name ) {
					array_push( $sidebars_widgets[$sidebar], $widget_name );
					if ( !self::is_widget_in_experiment( $widget_name, $widgets_in_experiments ) ) {
						$new_name = self::duplicate_widget_info( $widget_name );
						if ( $new_name ) {
							array_push( $sidebars_widgets[$sidebar], $new_name );
							self::link_widget_to_experiment( $new_name, $exp_id, $alt_id, $widgets_in_experiments );
						}
					}
				}
			}

			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( $widgets_in_experiments );
		}

		/**
		 *
		 */
		public static function duplicate_widgets( $exp_src, $alt_src, $exp_dest, $alt_dest ) {
			$widgets_in_experiments = self::get_widgets_in_experiments();

			$sidebars_widgets_ori = get_option( 'sidebars_widgets', array() );
			$sidebars_widgets = array();
			foreach ( $sidebars_widgets_ori as $sidebar => $widgets ) {
				if ( !is_array( $widgets ) ) {
					$sidebars_widgets[$sidebar] = $widgets;
					continue;
				}
				$sidebars_widgets[$sidebar] = array();
				foreach ( $widgets as $widget_name ) {
					array_push( $sidebars_widgets[$sidebar], $widget_name );
					$aux = self::is_widget_in_experiment( $widget_name, $widgets_in_experiments );
					if ( $aux && $aux['exp'] == $exp_src && $aux['alt'] == $alt_src ) {
						$new_name = self::duplicate_widget_info( $widget_name );
						if ( $new_name ) {
							array_push( $sidebars_widgets[$sidebar], $new_name );
							self::link_widget_to_experiment( $new_name, $exp_dest, $alt_dest, $widgets_in_experiments );
						}
					}
				}
			}

			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( $widgets_in_experiments );
		}

		private static function get_widget_kind_and_id( $widget_name ) {
			$data = explode( ':', preg_replace( '/^(.*)-([0-9]+)$/', '$1:$2', $widget_name ) );
			if ( count( $data ) != 2 )
				return false;
			else
				return array( 'kind' => $data[0], 'id' => $data[1] );
		}

		private static function duplicate_widget_info( $widget_name ) {
			$data = self::get_widget_kind_and_id( $widget_name );
			if ( !$data )
				return false;
			$widget_kind =  $data['kind'];
			$widget_kind_key = 'widget_' . $widget_kind;
			$widget_id = $data['id'];

			$instances = get_option( $widget_kind_key, array() );
			$new_index = max( array_keys( $instances ) ) + 1;
			$instances[$new_index] = $instances[$widget_id];
			update_option( $widget_kind_key, $instances );

			return $widget_kind . '-' . $new_index;
		}


		/**
		 *
		 */
		public static function update_alternatives_ids( $exp_id, $new_ids ) {

			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			$widgets_in_experiments = self::get_widgets_in_experiments();

			// Remove unused widgets.
			foreach ( $widgets_in_experiments as &$aux ) {
				$found = false;
				foreach ( $new_ids as $old_alt_id => $new_alt_id ) {
					if ( $aux['exp'] == $exp_id && $aux['alt'] == $old_alt_id ) {
						$found = true;
					}
				}//end foreach
				if ( ! $found && $aux['exp'] == $exp_id ) {
					$aux['exp'] = 'widget-to-remove';
				}//end if
			}//end foreach

			self::remove_widgets_in_experiment( $sidebars_widgets, $widgets_in_experiments, 'widget-to-remove' );


			// Fix widgets with new IDs.
			foreach ( $new_ids as $old_alt_id => $new_alt_id ) {

				if ( $old_alt_id == $new_alt_id ) {
					continue;
				}//end if

				foreach ( $widgets_in_experiments as &$aux ) {
					if ( $aux['exp'] == $exp_id && $aux['alt'] == $old_alt_id ) {
						$aux['alt'] = $new_alt_id;
					}//end if
				}//end foreach

			}//end foreach

			self::set_widgets_in_experiments( $widgets_in_experiments );
			update_option( 'sidebars_widgets', $sidebars_widgets );

		}//end update_alternatives_ids()


		/**
		 *
		 */
		private static function is_widget_in_experiment( $widget, &$arr ) {
			$widget = preg_replace( '/^widget-[0-9]+_/', '', $widget );
			if ( isset( $arr[$widget] ) ) {
				return array(
					'key' => $widget,
					'exp' => $arr[$widget]['exp'],
					'alt' => $arr[$widget]['alt']
				);
			}
			else {
				return false;
			}
		}


		/**
		 *
		 */
		public static function link_widget_to_experiment( $widget, $exp, $alt, &$arr ) {
			$widget = preg_replace( '/^widget-[0-9]+_/', '', $widget );
			$arr[$widget] = array( 'exp' => $exp, 'alt' => $alt );
		}


		/**
		 *
		 */
		private static function unlink_widget_to_experiment( $widget, &$arr ) {
			$widget = preg_replace( '/^widget-[0-9]+_/', '', $widget );
			unset( $arr[$widget] );
		}


		/**
		 *
		 */
		public static function apply_alternative_and_clean( $exp, $alt=false ) {
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			$widgets_in_experiments = self::get_widgets_in_experiments();

			// If there's an alternative
			if ( $alt ) {
				// We remove the original widgets
				self::remove_original_widgets( $sidebars_widgets, $widgets_in_experiments );

				// And we make that alternative's widgets the original's
				foreach ( $widgets_in_experiments as $key => $aux )
					if ( $aux['exp'] == $exp && $aux['alt'] == $alt )
						unset( $widgets_in_experiments[$key] );
			}

			// Finally, we just need to get rid of all other widgets
			self::remove_widgets_in_experiment( $sidebars_widgets, $widgets_in_experiments, $exp );

			// Commit changes
			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( $widgets_in_experiments );
		}


		/**
		 *
		 */
		private static function remove_original_widgets( &$sidebars_widgets, &$widgets_in_exps ) {
			foreach ( $sidebars_widgets as $sidebar => $widgets ) {
				if ( !is_array( $widgets ) ) continue;
				foreach ( $widgets as $key => $widget )
					if ( !self::is_widget_in_experiment( $widget, $widgets_in_exps ) )
						unset( $widgets[$key] );
				$sidebars_widgets[$sidebar] = $widgets;
			}
		}


		public static function clean_widgets_in_experiment( $exp_id ) {
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			$widgets_in_experiments = self::get_widgets_in_experiments();
			self::remove_widgets_in_experiment( $sidebars_widgets, $widgets_in_experiments, $exp_id );
			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( $widgets_in_experiments );
		}


		public static function remove_alternatives_not_in( $existing_ids ) {
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			$widgets_in_experiments = self::get_widgets_in_experiments();

			$old_ids = array();
			foreach ( $widgets_in_experiments as $aux )
				if ( !in_array( $aux['exp'], $existing_ids) && !in_array( $aux['exp'], $old_ids ) )
					array_push( $old_ids, $aux['exp'] );

			foreach ( $old_ids as $id )
				self::remove_widgets_in_experiment( $sidebars_widgets, $widgets_in_experiments, $id );

			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( $widgets_in_experiments );
		}


		/**
		 *
		 */
		private static function remove_widgets_in_experiment( &$sidebars_widgets, &$widgets_in_exps, $exp ) {
			foreach ( $sidebars_widgets as $sidebar => $widgets ) {
				if ( !is_array( $widgets ) ) continue;
				foreach ( $widgets as $key => $widget ) {
					$aux = self::is_widget_in_experiment( $widget, $widgets_in_exps );
					if ( $aux && $aux['exp'] == $exp )
						unset( $widgets[$key] );
				}
				$sidebars_widgets[$sidebar] = $widgets;
			}

			foreach ( $widgets_in_exps as $key => $info )
				if ( $info['exp'] == $exp )
					unset( $widgets_in_exps[$key] );
		}

		/**
		 * This function removes all alternative widgets. Useful when cleaning and
		 * deactivating the plugin.
		 */
		public static function clean_all_alternative_widgets() {
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			$widgets_in_experiments = self::get_widgets_in_experiments();
			$experiments = array();
			foreach ( $widgets_in_experiments as $widget_in_exp ) {
				$exp = $widget_in_exp['exp'];
				if ( !in_array( $exp, $experiments ) )
					array_push( $experiments, $exp );
			}

			foreach ( $experiments as $exp )
				self::remove_widgets_in_experiment( $sidebars_widgets, $widgets_in_experiments, $exp );

			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( $widgets_in_experiments );
		}

		/**
		 * This function stores all alternative widgets somewhere else. Specially useful
		 * during plugin deactivation.
		 */
		public static function backup_alternative_widgets() {
			$widgets_in_exps = self::get_widgets_in_experiments();
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			$widgets_to_backup = array();

			if ( count( $widgets_in_exps ) == 0 )
				return;

			foreach ( $sidebars_widgets as $sidebar => $widgets ) {
				if ( !is_array( $widgets ) ) continue;
				foreach ( $widgets as $key => $widget ) {
					$aux = self::is_widget_in_experiment( $widget, $widgets_in_exps );
					if ( $aux ) {
						$widgets_to_backup[$widget] = array(
							'exp'     => $aux['exp'],
							'alt'     => $aux['alt'],
							'sidebar' => $sidebar,
							'value'   => false,
						);
						unset( $widgets[$key] );
					}
				}
				$sidebars_widgets[$sidebar] = $widgets;
			}

			ksort( $widgets_to_backup );

			$current_kind = '';
			$widget_instances= array();
			foreach ( $widgets_to_backup as $widget_name => $widget_info ) {
				$aux = self::get_widget_kind_and_id( $widget_name );
				if ( !$aux ) continue;
				$id   = $aux['id'];
				$kind = $aux['kind'];
				if ( $current_kind != $kind ) {
					$current_kind = $kind;
					$widget_instances = get_option( 'widget_' . $kind, array() );
				}
				$aux = $widget_instances[$id];
				if ( !empty( $aux ) ) {
					$widget_info['value'] = $aux;
					$widgets_to_backup[$widget_name] = $widget_info;
				}
			}

			$aux = get_option( 'nelioab_widgets_in_experiments_backup', false );
			if ( !$aux )
				update_option( 'nelioab_widgets_in_experiments_backup', $widgets_to_backup );
			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( array() );
		}

		/**
		 * This function stores all alternative widgets somewhere else. Specially useful
		 * during plugin deactivation.
		 */
		public static function restore_alternative_widget_backup() {
			$widgets_in_exps = self::get_widgets_in_experiments();
			$sidebars_widgets = get_option( 'sidebars_widgets', array() );
			$backup_widgets = get_option( 'nelioab_widgets_in_experiments_backup', false );

			if ( !$backup_widgets )
				return;

			$widget_instances = array();
			$current_widget_kind = false;
			foreach ( $backup_widgets as $widget_name => $widget_info ) {
				$aux = self::get_widget_kind_and_id( $widget_name );
				if ( !$aux ) continue;
				if ( $aux['kind'] != $current_widget_kind ) {
					if ( $current_widget_kind && count( $widget_instances ) > 0 )
						update_option( 'widget_' . $current_widget_kind, $widget_instances );
					$current_widget_kind = $aux['kind'];
					$widget_instances = get_option( 'widget_' . $current_widget_kind, array() );
				}

				$new_index = max( array_keys( $widget_instances ) ) + 1;
				$new_widget_name = $current_widget_kind . '-' . $new_index;
				$widget_instances[$new_index] = $widget_info['value'];
				$widgets_in_exps[$new_widget_name] = array(
					'exp' => $widget_info['exp'],
					'alt' => $widget_info['alt']
				);
				if ( isset( $sidebars_widgets[$widget_info['sidebar']] ) )
					array_push( $sidebars_widgets[$widget_info['sidebar']], $new_widget_name );
			}
			if ( $current_widget_kind && count( $widget_instances ) > 0 )
				update_option( 'widget_' . $current_widget_kind, $widget_instances );

			update_option( 'nelioab_widgets_in_experiments_backup', false );
			update_option( 'sidebars_widgets', $sidebars_widgets );
			self::set_widgets_in_experiments( $widgets_in_exps );
		}

	}//NelioABWidgetExpAdminController
}

