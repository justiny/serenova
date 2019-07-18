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


if ( !class_exists( 'NelioABExperimentsPage' ) ) {

	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );
	require_once( NELIOAB_UTILS_DIR . '/admin-ajax-page.php' );
	require_once( NELIOAB_UTILS_DIR . '/html-generator.php' );

	class NelioABExperimentsPage extends NelioABAdminAjaxPage {

		private $experiments;
		private $current_status;

		public function __construct( $title ) {
			parent::__construct( $title );
			$this->set_icon( 'icon-nelioab' );
			$this->add_title_action( __( 'Add New', 'nelio-ab-testing' ), '?page=nelioab-add-experiment' );
			$this->current_filter = 'none';
			$this->current_status = false;
		}

		public function set_experiments( $experiments ) {
			$this->experiments = $experiments;
		}

		public function filter_by_status( $status ) {
			$this->current_status = $status;
		}

		protected function do_render() {
			// If there are no experiments, tell the user to create one.
			if ( count( $this->experiments ) == 0 ) {
				echo "<div class='nelio-message'>";
				echo sprintf( '<img class="animated flipInY" src="%s" alt="%s" />',
					esc_url( nelioab_admin_asset_link( '/images/message-icon.png' ) ),
					esc_attr__( 'Information Notice', 'nelio-ab-testing' )
				);
				echo '<h2 style="max-width:750px;">';
				printf( '%1$s<br>%2$s<br><a class="button button-primary" href="%4$s">%3$s</a>',
					esc_html__( 'Find and manage all your experiments from this page.', 'nelio-ab-testing' ),
					esc_html__( 'Click the following button and create your first experiment!', 'nelio-ab-testing' ),
					esc_html( _x( 'Create Experiment', 'create-experiment', 'nelio-ab-testing' ) ),
					esc_url( admin_url( 'admin.php?page=nelioab-add-experiment' ) )
				);
				echo '</h2>';
				echo '</div>';

				return;
			}

			?>
			<script type="text/javascript">
			(function($) {
				$("#dialog-modal").dialog({
					dialogClass   : "wp-dialog",
					modal         : true,
					autoOpen      : false,
					closeOnEscape : true,
					buttons: [
						{
							text: <?php echo json_encode( esc_html__( 'Cancel', 'nelio-ab-testing' ) ); ?>,
							click: function() {
								$(this).dialog('close');
							}
						},
						{
							text: <?php echo json_encode( esc_html__( 'OK', 'nelio-ab-testing' ) ); ?>,
							"class": "button button-primary",
							click: function() {
								$(this).dialog("close");
								window.location.href = $(this).data( "href" );
							}
						}
					]
				});
			})(jQuery);
			function nelioabValidateClick(msg_id, href) {
				var $dialog = jQuery("#dialog-modal");
				$dialog.data( "href", href );
				switch (msg_id) {

					case 0:<?php
						$title = esc_html__( 'Start Experiment', 'nelio-ab-testing' );
						$msg = esc_html__( 'You are about to start an experiment. Once the experiment has started, you cannot edit it. Are you sure you want to start the experiment?', 'nelio-ab-testing' );
						?>
						jQuery("#dialog-content").html(<?php echo json_encode( $msg ); ?>);
						$dialog.dialog("option", "title", <?php echo json_encode( $title ); ?>);
						$dialog.parent().find(".button-primary .ui-button-text").text(<?php echo json_encode( esc_html__( 'Start', 'nelio-ab-testing' ) ); ?>);
						$dialog.dialog("open");
						break;

					case 1:<?php
						$title = esc_html__( 'Stop Experiment', 'nelio-ab-testing' );
						$msg = esc_html__( 'You are about to stop an experiment. Once the experiment is stopped, you cannot resume it. Are you sure you want to stop the experiment?', 'nelio-ab-testing' );
						?>
						jQuery("#dialog-content").html(<?php echo json_encode( $msg ); ?>);
						$dialog.dialog("option", "title", <?php echo json_encode( $title ); ?>);
						$dialog.parent().find(".button-primary .ui-button-text").text(<?php echo json_encode( esc_html__( 'Stop', 'nelio-ab-testing' ) ); ?>);
						$dialog.dialog("open");
						break;

				}
			}
			</script>
			<form id="nelioab_experiment_list_form" method="POST" >
				<input type="hidden" name="nelioab_experiment_list_form" value="true" />
				<input type="hidden" id="action" name="action" value="" />
				<input type="hidden" id="experiment_id" name="experiment_id" value="" />
			</form>
			<?php

			$status_draft     = NelioABExperiment::STATUS_DRAFT;
			$status_ready     = NelioABExperiment::STATUS_READY;
			$status_scheduled = NelioABExperiment::STATUS_SCHEDULED;
			$status_running   = NelioABExperiment::STATUS_RUNNING;
			$status_finished  = NelioABExperiment::STATUS_FINISHED;
			$status_trash     = NelioABExperiment::STATUS_TRASH;
			NelioABHtmlGenerator::print_filters(
				admin_url( 'admin.php?page=nelioab-experiments' ),
				array (
					array ( 'value' => 'none',
					        'label' => __( 'All' ),
					        'count' => count( $this->filter_experiments() ) ),
					array ( 'value' => $status_draft,
					        'label' => NelioABExperiment::get_label_for_status( $status_draft ),
					        'count' => count( $this->filter_experiments( $status_draft ) ) ),
					array ( 'value' => $status_ready,
					        'label' => NelioABExperiment::get_label_for_status( $status_ready ),
					        'count' => count( $this->filter_experiments( $status_ready ) ) ),
					array ( 'value' => $status_scheduled,
					        'label' => NelioABExperiment::get_label_for_status( $status_scheduled ),
					        'count' => count( $this->filter_experiments( $status_scheduled ) ) ),
					array ( 'value' => $status_running,
					        'label' => NelioABExperiment::get_label_for_status( $status_running ),
					        'count' => count( $this->filter_experiments( $status_running ) ) ),
					array ( 'value' => $status_finished,
					        'label' => NelioABExperiment::get_label_for_status( $status_finished ),
					        'count' => count( $this->filter_experiments( $status_finished ) ) ),
					array ( 'value' => $status_trash,
					        'label' => NelioABExperiment::get_label_for_status( $status_trash ),
					        'count' => count( $this->filter_experiments( $status_trash ) ) ),
				),
				'status',
				$this->current_status
			);

			$wp_list_table = new NelioABExperimentsTable( $this->filter_experiments( $this->current_status ) );
			$wp_list_table->prepare_items();
			echo '<div id="nelioab-experiment-list-table">';
			$wp_list_table->display();
			echo '</div>';

			// Code for duplicating experiments.
			$this->insert_duplicate_dialog();

			// Code for scheduling experiments.
			if ( NelioABAccountSettings::is_plan_at_least( NelioABAccountSettings::ENTERPRISE_SUBSCRIPTION_PLAN ) )
				$this->insert_schedule_dialog();
		}

		private function filter_experiments( $status = false ) {
			if ( !$status ) {
				$result = array();
				$filter_finished = NelioABSettings::show_finished_experiments();
				foreach ( $this->experiments as $exp ) {
					if ( $exp->get_status() == NelioABExperiment::STATUS_FINISHED ) {
						if ( NelioABSettings::FINISHED_EXPERIMENTS_HIDE_ALL == $filter_finished )
							continue;
						if ( NelioABSettings::FINISHED_EXPERIMENTS_SHOW_RECENT == $filter_finished &&
						     $exp->get_days_since_finalization() > 7 )
							continue;
					}
					if ( $exp->get_status() != NelioABExperiment::STATUS_TRASH )
						array_push( $result, $exp );
				}
				return $result;
			}
			else {
				$result = array();
				foreach ( $this->experiments as $exp )
					if ( $exp->get_status() == $status )
						array_push( $result, $exp );
				return $result;
			}
		}

		private function insert_duplicate_dialog() { ?>
			<div id="nelioab-scheduling-dialog" class="nelio-sect" title="<?php
				esc_attr_e( 'Experiment Duplication', 'nelio-ab-testing' );
			?>">
				<p style="margin-top:0"><?php esc_html_e( 'You are about to duplicate an experiment.', 'nelio-ab-testing' ); ?><br><?php esc_html_e( 'New name:', 'nelio-ab-testing' ); ?></p>
				<input type="text" id="duplicate-name" style="width:100%;" />
			</div>
			<script>
				jQuery(function($) {<?php
					$ts = time() + 86400; ?>
					var TOMORROW_DAY   = <?php echo json_encode( date( 'd', $ts ) ); ?>;
					var TOMORROW_MONTH = <?php echo json_encode( date( 'm', $ts ) ); ?>;
					var TOMORROW_YEAR  = <?php echo json_encode( date( 'Y', $ts ) ); ?>;
					var $dupDialog = $("#nelioab-scheduling-dialog").dialog({
						"dialogClass"   : "wp-dialog",
						"modal"         : true,
						"autoOpen"      : false,
						"closeOnEscape" : true,
						buttons: [
							{
								text: <?php echo json_encode( esc_html__( 'Cancel', 'nelio-ab-testing' ) ); ?>,
								click: function() {
									$(this).dialog("close");
								}
							},
							{
								text: <?php echo json_encode( esc_html__( 'Duplicate', 'nelio-ab-testing' ) ); ?>,
								"class": "button button-primary",
								click: function() {
									if ( $okButton.hasClass("disabled") ) return;
									window.location = $(this).data("url") + "&name=" + encodeURIComponent( $input.val() );
								}
							},
						],
					});
					var $okButton = $dupDialog.closest(".ui-dialog").find(".button-primary");
					var $input = $dupDialog.find("input");
					$(".row-actions .duplicate > a").click(function(event) {
						event.preventDefault();
						$("#duplicate-name").val("");
						$dupDialog.data("url", $(this).attr("href"));
						$okButton.addClass("disabled");
						$input.removeClass("error");
						$dupDialog.dialog( "open" );
					});
					$input.on("keyup change focusout", function() {
						var name = $(this).val().trim();
						if ( "" == name ) {
							$okButton.addClass("disabled");
							$(this).addClass("error");
							return;
						}
						var names = <?php
							$names = array();
							foreach ( $this->experiments as $exp )
								array_push( $names, trim( $exp->get_name() ) );
							echo json_encode( $names );
						?>;
						for ( var i=0; i<names.length; ++i ) {
							if ( names[i] == name ) {
								$okButton.addClass("disabled");
								$(this).addClass("error");
								return;
							}
						}
						$okButton.removeClass("disabled");
						$(this).removeClass("error");
					});
				});
				</script><?php
		}

		private function insert_schedule_dialog() { ?>
			<div id="nelioab-scheduling-dialog" title="<?php
				esc_attr_e( 'Experiment Scheduling', 'nelio-ab-testing' );
			?>">
				<p><?php esc_html_e( 'Schedule experiment start for:', 'nelio-ab-testing' ); ?></p>
				<?php
				require_once( NELIOAB_UTILS_DIR . '/html-generator.php' );
				NelioABHtmlGenerator::print_scheduling_picker();
				?>
				<p class="error" style="color:red;display:none;"><?php
					esc_html_e( 'Please, specify a full date (month, day, and year) in the future.', 'nelio-ab-testing' );
				?></p>
			</div>
			<script>
				jQuery(function($) {<?php
					$ts = time() + 86400; ?>
					var TOMORROW_DAY   = <?php echo json_encode( date( 'd', $ts ) ); ?>;
					var TOMORROW_MONTH = <?php echo json_encode( date( 'm', $ts ) ); ?>;
					var TOMORROW_YEAR  = <?php echo json_encode( date( 'Y', $ts ) ); ?>;
					var $info = $("#nelioab-scheduling-dialog");
					$info.dialog({
						"dialogClass"   : "wp-dialog",
						"modal"         : true,
						"autoOpen"      : false,
						"closeOnEscape" : true,
						buttons: [
							{
								text: <?php echo json_encode( esc_html__( 'Cancel', 'nelio-ab-testing' ) ); ?>,
								click: function() {
									$(this).dialog("close");
								}
							},
							{
								text: <?php echo json_encode( esc_html__( 'Schedule', 'nelio-ab-testing' ) ); ?>,
								"class": "button button-primary",
								click: function() {
									try {
										var day   = $("#nelioab-scheduling-dialog input.jj").attr("value");
										var month = $("#nelioab-scheduling-dialog select.mm").attr("value");
										var year  = $("#nelioab-scheduling-dialog input.aa").attr("value");

										if ( day == undefined ) day = "00";
										if ( year == undefined ) year = "0000";
										while ( day.length < 2 ) day = "0" + day;
										while ( year.length < 4 ) year = "0" + year;
										if ( year < TOMORROW_YEAR )
											throw new Exception();
										if ( year == TOMORROW_YEAR && month < TOMORROW_MONTH )
											throw new Exception();
										else if ( year == TOMORROW_YEAR && month == TOMORROW_MONTH && day < TOMORROW_DAY )
											throw new Exception();

										var res = year + "-" + month + "-" + day;
										$( "#nelioab-scheduling-dialog .error").hide();
										$(this).dialog("close");
										window.location = $(this).data("url") + "&schedule_date=" + res;
									}
									catch ( e ) {
										$( "#nelioab-scheduling-dialog .error").show();
									}
								}
							}
						],
					});
					$(".row-actions .schedule > a").click(function(event) {
						event.preventDefault();
						$("#nelioab-scheduling-dialog input.jj").attr("value", TOMORROW_DAY);
						$("#nelioab-scheduling-dialog select.mm").attr("value", TOMORROW_MONTH);
						$("#nelioab-scheduling-dialog input.aa").attr("value", TOMORROW_YEAR);
						$info.data("url", $(this).attr("href"));
						$info.dialog( "open" );
					});
				});
			</script><?php
		}
	}//NelioABExperimentsPage


	require_once( NELIOAB_UTILS_DIR . '/admin-table.php' );
	class NelioABExperimentsTable extends NelioABAdminTable {

		function __construct( $experiments ){
			parent::__construct( array(
				'singular'  => __( 'experiment', 'nelio-ab-testing' ),
				'plural'    => __( 'experiments', 'nelio-ab-testing' ),
				'ajax'      => false
			)	);
			$this->set_items( $experiments );
			add_action( 'admin_head', array( &$this, 'admin_header' ) );
			add_filter( 'list_table_primary_column', array( $this, 'set_list_table_primary_column' ), 10, 2 );
		}

		function get_columns(){
			return array(
				'type'          => '',
				'name'          => __( 'Name', 'nelio-ab-testing' ),
				'status'        => __( 'Status', 'nelio-ab-testing' ),
				'relevant_date' => __( 'Relevant Date', 'nelio-ab-testing' ),
			);
		}

		public function set_list_table_primary_column( $type, $screen ) {
			if ( $screen === 'nelio-ab-testing' ) {
				return 'name';
			} else {
				return $type;
			}
		}

		public function get_table_id() {
			return 'list-of-experiments-table';
		}

		public function get_jquery_sortable_columns() {
			return array( 'name', 'status', 'relevant_date' );
		}

		function get_display_functions() {
			return array(
				// 'description' => 'get_description',
			);
		}

		function column_name( $exp ) {

			$url = add_query_arg( array(
				'page'     => 'nelioab-experiments',
				'id'       => $exp->get_id(),
				'exp_type' => $exp->get_type(),
			), admin_url( 'admin.php' ) );

			if ( isset( $_REQUEST['status'] ) ) {
				$url = add_query_arg( 'status', absint( $_REQUEST['status'] ), $url );
			}//end if

			$actions = array();

			$actions['edit'] = sprintf( '<a href="%s">%s</a>',
				esc_url( add_query_arg( 'action', 'edit', $url ) ),
				esc_html__( 'Edit' )
			);

			$actions['start'] = sprintf( '<a href="%s">%s</a>',
				esc_attr( sprintf(
					'javascript:nelioabValidateClick( 0, %s )',
					json_encode( add_query_arg( 'action', 'start', $url ) )
				) ),
				esc_html__( 'Start', 'nelio-ab-testing' )
			);

			$actions['stop'] = sprintf( '<a href="%s">%s</a>',
				esc_attr( sprintf(
					'javascript:nelioabValidateClick( 1, %s )',
					json_encode( add_query_arg( 'action', 'stop', $url ) )
				) ),
				esc_html__( 'Stop', 'nelio-ab-testing' )
			);

			$actions['duplicate'] = sprintf( '<a href="%s">%s</a>',
				esc_url( add_query_arg( array(
					'action' => 'duplicate',
					'_nonce' => nelioab_onetime_nonce( 'duplicate-' . $exp->get_id() ),
				), $url ) ),
				esc_html__( 'Duplicate', 'nelio-ab-testing' )
			);

			$actions['trash'] = sprintf( '<a href="%s">%s</a>',
				esc_url( add_query_arg( 'action', 'trash', $url ) ),
				esc_html__( 'Trash' )
			);

			$actions['delete'] = sprintf( '<a href="%s">%s</a>',
				esc_url( add_query_arg( 'action', 'delete', $url ) ),
				esc_html__( 'Delete Permanently' )
			);

			$actions['restore'] = sprintf( '<a href="%s">%s</a>',
				esc_url( add_query_arg( 'action', 'restore', $url ) ),
				esc_html__( 'Restore' )
			);

			if ( $exp->get_type() == NelioABExperiment::HEATMAP_EXP ) {
				$progress_url = add_query_arg( array(
					'nelioab-page' => 'heatmaps',
					'id'           => $exp->get_id(),
					'exp_type'     => $exp->get_type(),
				), admin_url( 'admin.php' ) );
			} else {
				$progress_url = add_query_arg( array(
					'page'     => 'nelioab-experiments',
					'action'   => 'progress',
					'id'       => $exp->get_id(),
					'exp_type' => $exp->get_type(),
				), admin_url( 'admin.php' ) );
			}//end if

			$actions['theprogress'] = sprintf( '<a href="%s">%s</a>',
				esc_url( $progress_url ),
				esc_html__( 'View' )
			);

			$action_names = array();
			switch( $exp->get_status() ) {

				case NelioABExperiment::STATUS_DRAFT:
					$action_names = array( 'edit', 'trash' );
					break;

				case NelioABExperiment::STATUS_READY:
					$action_names = array( 'edit', 'start', 'duplicate', 'trash' );
					break;

				case NelioABExperiment::STATUS_SCHEDULED:
					$action_names = array( 'start', 'duplicate' );
					break;

				case NelioABExperiment::STATUS_RUNNING:
					$action_names = array( 'theprogress', 'stop', 'duplicate' );
					break;

				case NelioABExperiment::STATUS_FINISHED:
					$action_names = array( 'theprogress', 'duplicate', 'delete' );
					break;

				case NelioABExperiment::STATUS_TRASH:
				default:
					$action_names = array( 'restore', 'delete' );
					break;

			}

			$filtered_actions = array();
			foreach ( $action_names as $action_name ) {
				$filtered_actions[ $action_name ] = $actions[ $action_name ];
			}//end foreach
			$actions = $filtered_actions;

			$related_post = $exp->get_related_post_id();
			if ( isset( $actions['start'] ) && $related_post && $related_post > 0 && get_post_status( $related_post ) !== 'publish' ) {
				$label = sprintf( '<span style="cursor:default;" title="%s">%s</span>',
					esc_attr( __( 'The experiment cannot be started because the tested element has not been published yet', 'nelio-ab-testing' ) ),
					esc_html__( 'Start', 'nelio-ab-testing' ) );
				$actions['start'] = $label;
			}

			if ( !NelioABAccountSettings::is_plan_at_least( NelioABAccountSettings::PROFESSIONAL_SUBSCRIPTION_PLAN ) ) {
				$expl = __( 'Feature only available in the Professional Plan', 'nelio-ab-testing' );
				// No actions available to Professional Plans only
			}

			if ( !NelioABAccountSettings::is_plan_at_least( NelioABAccountSettings::ENTERPRISE_SUBSCRIPTION_PLAN ) ) {
				$expl = __( 'Feature only available in the Enterprise Plan', 'nelio-ab-testing' );
				if ( isset( $actions['schedule'] ) )
					$actions['schedule'] = sprintf( '<span title="%s">%s</span>', esc_attr( $expl ), esc_html__( 'Schedule', 'nelio-ab-testing' ) );
			}

			if ( ! NelioABExperimentsManager::current_user_can( $exp ) ) {
				foreach ( $actions as $key => $value ) {
					if ( $key === 'edit' ) {
						$actions[ $key ] = esc_html__( 'Edit' );
					} else if ( $key === 'trash' ) {
						$actions[ $key ] = esc_html__( 'Trash' );
					} else if ( $key === 'delete' ) {
						$actions[ $key ] = esc_html__( 'Delete Permanently' );
					} else if ( $key === 'start' ) {
						$actions[ $key ] = esc_html__( 'Start', 'nelio-ab-testing' );
					} else if ( $key === 'stop' ) {
						$actions[ $key ] = esc_html__( 'Stop', 'nelio-ab-testing' );
					} else if ( $key === 'schedule' ) {
						$actions[ $key ] = esc_html__( 'Schedule', 'nelio-ab-testing' );
					} else if ( $key === 'duplicate' ) {
						$actions[ $key ] = esc_html__( 'Duplicate', 'nelio-ab-testing' );
					} else if ( $key !== 'theprogress' ) {
						unset( $actions[ $key ] );
					}//end if
				}//end foreach
			}//end if

			//Build row actions
			return sprintf(
				'<span class="row-title">%s</span>%s',
				esc_html( $exp->get_name() ),
				$this->row_actions( $actions )
			);
		}

		public function column_relevant_date( $exp ) {
			include_once( NELIOAB_UTILS_DIR . '/formatter.php' );
			$date = '<span style="display:none;">%s</span><span title="%s">%s</span>';

			switch ( $exp->get_status() ) {

			case NelioABExperiment::STATUS_FINISHED:
					$res = sprintf( $date,
						esc_html( strtotime( $exp->get_end_date() ) ),
						esc_attr__( 'Finalization Date', 'nelio-ab-testing' ),
						esc_html( NelioABFormatter::format_date( $exp->get_end_date() ) )
					);
					break;

			case NelioABExperiment::STATUS_RUNNING:
					$res = sprintf( $date,
						esc_html( strtotime( $exp->get_start_date() ) ),
						esc_attr__( 'Start Date', 'nelio-ab-testing' ),
						esc_html( NelioABFormatter::format_date( $exp->get_start_date() ) )
					);
					break;

			case NelioABExperiment::STATUS_SCHEDULED:
					$res = sprintf( $date,
						esc_html( strtotime( $exp->get_start_date() ) ),
						esc_attr__( 'Scheduled Date', 'nelio-ab-testing' ),
						esc_html( NelioABFormatter::format_date( $exp->get_start_date() ) )
					);
					break;

				default:
					$res = sprintf( $date,
						esc_html( strtotime( $exp->get_creation_date() ) ),
						esc_attr__( 'Creation Date', 'nelio-ab-testing' ),
						esc_html( NelioABFormatter::format_date( $exp->get_creation_date() ) )
					);
					break;

			}

			return $res;
		}

		public function column_status( $exp ){
			$str = NelioABExperiment::get_label_for_status( $exp->get_status() );
			switch( $exp->get_status() ) {
			case NelioABExperiment::STATUS_DRAFT:
					return $this->make_label( $str, '#999999', '#eeeeee' );
				case NelioABExperiment::STATUS_PAUSED:
					return $this->make_label( $str, '#999999', '#eeeeee' );
				case NelioABExperiment::STATUS_READY:
					return $this->make_label( $str, '#e96500', '#fff6ad' );
				case NelioABExperiment::STATUS_SCHEDULED:
					return $this->make_label( $str, '#fff6ad', '#e96500' );
				case NelioABExperiment::STATUS_RUNNING:
					return $this->make_label( $str, '#266529', '#d1ffd3' );
				case NelioABExperiment::STATUS_FINISHED:
					return $this->make_label( $str, '#103269', '#BED6FC' );
				case NelioABExperiment::STATUS_TRASH:
					return $this->make_label( $str, '#802a28', '#ffe0df' );
				default:
					return $this->make_label( $str, '#999999', '#eeeeee' );
			}
		}

		function column_type( $exp ){
			$img = '<div class="tab-type tab-type-%1$s" alt="%2$s" title="%2$s"></div>';

			switch( $exp->get_type() ) {
				case NelioABExperiment::PAGE_ALT_EXP:
					$page_on_front = get_option( 'page_on_front' );
					$aux = $exp->get_original();
					if ( $page_on_front == $aux->get_value() )
						return sprintf( $img, 'landing-page', esc_attr__( 'Landing Page', 'nelio-ab-testing' ) );
					else
						return sprintf( $img, 'page', esc_attr__( 'Page', 'nelio-ab-testing' ) );

				case NelioABExperiment::POST_ALT_EXP:
					return sprintf( $img, 'post', esc_attr__( 'Post', 'nelio-ab-testing' ) );

				case NelioABExperiment::CPT_ALT_EXP:
					return sprintf( $img, 'cpt', esc_attr__( 'Custom Post Type', 'nelio-ab-testing' ) );

				case NelioABExperiment::HEADLINE_ALT_EXP:
					return sprintf( $img, 'title', esc_attr__( 'Headline', 'nelio-ab-testing' ) );

				case NelioABExperiment::WC_PRODUCT_SUMMARY_ALT_EXP:
					return sprintf( $img, 'wc-product-summary', esc_attr__( 'WooCommerce Product Summary', 'nelio-ab-testing' ) );

				case NelioABExperiment::THEME_ALT_EXP:
					return sprintf( $img, 'theme', esc_attr__( 'Theme', 'nelio-ab-testing' ) );

				case NelioABExperiment::CSS_ALT_EXP:
					return sprintf( $img, 'css', esc_attr__( 'CSS', 'nelio-ab-testing' ) );

				case NelioABExperiment::HEATMAP_EXP:
					return sprintf( $img, 'heatmap', esc_attr__( 'Heatmap', 'nelio-ab-testing' ) );

				case NelioABExperiment::WIDGET_ALT_EXP:
					return sprintf( $img, 'widget', esc_attr__( 'Widget', 'nelio-ab-testing' ) );

				case NelioABExperiment::MENU_ALT_EXP:
					return sprintf( $img, 'menu', esc_attr__( 'Menu', 'nelio-ab-testing' ) );

				default:
					return '';
			}
		}

		private function make_label( $label, $color, $bgcolor ) {
			$style = sprintf( 'color:%s; background-color:%s; padding: 1px 5px; position: inherit; font-size: 90%%;',
				$color, $bgcolor
			);

			return sprintf( '<div style="padding-top:5px;"><span class="add-new-h2" style="%s">%s</span></div>',
				esc_attr( $style ),
				esc_html( $label )
			);

		}

	}// NelioABExperimentsTable
}

