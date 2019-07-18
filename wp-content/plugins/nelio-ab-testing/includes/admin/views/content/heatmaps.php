<?php
/**
 * Copyright 2013 Nelio Software S.L.
 * This script is distributed under the terms of the GNU General Public
 * License.
 *
 * This script is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License.
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program. If not, see <http://www.gnu.org/licenses/>.
 */


	require_once( NELIOAB_MODELS_DIR . '/experiment.php' );

	$exp_id = false;
	if ( isset( $_GET['id'] ) ) {
		$exp_id = absint( $_GET['id'] );
	}//end if

	$exp_type = false;
	if ( isset( $_GET['exp_type'] ) ) {
		$exp_type = absint( $_GET['exp_type'] );
	}//end if


	if ( isset( $_POST['load_from_appengine'] ) ) {
		try {
			require_once( NELIOAB_UTILS_DIR . '/backend.php' );
			require_once( NELIOAB_MODELS_DIR . '/experiments-manager.php' );
			if ( ! $exp_id || ! $exp_type ) {
				$err = NelioABErrCodes::TOO_FEW_PARAMETERS;
				throw new Exception( NelioABErrCodes::to_string( $err ), $err );
			}

			// Get the Heatmap
			$exp = NelioABExperimentsManager::get_experiment_by_id( $exp_id, $exp_type );

			if ( NelioABExperiment::HEATMAP_EXP == $exp_type ) {
				$url = sprintf( NELIOAB_BACKEND_URL . '/exp/hm/%s/result', $exp->get_key_id() );
				$post_id = $exp->get_post_id();
				$nelioab_controller = NelioABController::instance();
				if ( $post_id == NelioABController::FRONT_PAGE__YOUR_LATEST_POSTS )
					$post_id = false;
			} else {
				if ( ! isset( $_GET['post'] ) ) {
					$err = NelioABErrCodes::TOO_FEW_PARAMETERS;
					throw new Exception( NelioABErrCodes::to_string( $err ), $err );
				}//end if
				$post_id = absint( $_GET['post'] );
				$url = sprintf( NELIOAB_BACKEND_URL . '/exp/post/%s/hm/%s/result', $exp->get_key_id(), $post_id );
			}

			$result = NelioABBackend::remote_get( $url );
			$result = json_decode( $result['body'] );

			// NELIO LOCAL EXPS UPDATE. Auto stop experiment (if needed).
			if ( $exp->get_status() === NelioABExperiment::STATUS_RUNNING ) {
				if ( isset( $result->expStatus ) &&
						$result->expStatus === NelioABExperiment::STATUS_FINISHED ) {
					$exp->stop();
				}//end if
			}//end if

			if ( $exp_type == NelioABExperiment::HEATMAP_EXP ) {
				$summary = array();
				if ( isset( $result->data ) ) {
					foreach ( $result->data as $item ) {
						if ( ! $item->click ) {
							$summary[ $item->resolution ] = $item->views;
						}//end if
					}//end if
				}//end if
				update_post_meta( $exp->get_id(), 'nelioab_hm_summary', $summary );
			}//end if

			$counter = 0;
			if ( isset( $result->data ) ) {
				foreach ( $result->data as $heatmap ) {
					if ( isset( $heatmap->value ) ) {
						$value = json_decode( $heatmap->value );
						if ( isset( $value->max ) ) {
							$counter += $value->max;
						}
					}
				}
			}
			if ( $counter == 0 ) {
				if ( $exp->get_status() == NelioABExperiment::STATUS_RUNNING ) {
					$err = NelioABErrCodes::NO_HEATMAPS_AVAILABLE;
					throw new Exception( NelioABErrCodes::to_string( $err ), $err );
				}
				else {
					$err = NelioABErrCodes::NO_HEATMAPS_AVAILABLE_FOR_NON_RUNNING_EXPERIMENT;
					throw new Exception( NelioABErrCodes::to_string( $err ), $err );
				}
			}
		}
		catch ( Exception $e ) {
			printf( '<img src="%s" alt="%s" style="margin-top:50px;"/>',
				esc_url( nelioab_asset_link( '/admin/images/white-error-icon.png' ) ),
				esc_html__( 'Funny image to graphically notify of an error.', 'nelio-ab-testing' )
			);
			?>
			<p id="ajax-loader-label1" style="margin-top:10px;color:white;font-size:20px;"><?php
				echo $e->getMessage();
			?></p><?php
			die();
		}

		// Prepare the content
		$page_on_front = nelioab_get_page_on_front();
		if ( !$page_on_front && !$post_id ) // if the home page is the list of posts and the experiment is for the home page
			$url = get_option( 'home' ); // the url should be the home page
		else  // otherwise (1 - the heatmaps is NOT for the home page or 2 - the home page is a specific page, the heatmaps should display that page
			$url = get_permalink( $post_id );

		$aux = get_post_type( $post_id );
		if ( !$url ) {
			if ( 'page' == $aux )
				$url = esc_url( add_query_arg( array( 'page_id' => $post_id ), get_option( 'home' ) ) );
			else
				$url = esc_url( add_query_arg( array( 'p' => $post_id ), get_option( 'home' ) ) );
		}
		$url = esc_url( add_query_arg( array( 'nelioab_show_heatmap' => 'true' ), $url ) );
		$url = preg_replace( '/^https?:/', '', $url );

		$url = apply_filters( 'nelio_ab_testing_heatmap_url', $url );

		?>
		<script type="text/javascript">
			window.onerror = function(msg, url, line, col, error) {
				var url = document.URL;
				if ( /^http:\/\//.test( url ) && msg.indexOf('SecurityError') >= 0 && url.indexOf( 'retry-with-https=true' ) === -1 ) {
					window.location.href = 'https://' + url.replace('http://','') + '&retry-with-https=true';
					return true;
				}
				return false;
			};
		</script>
		<div id="phantom" style="width:0px;height:0px;"></div>
		<div id="wrapper" style="width:100%;height:100%;">
			<div id="builder" style="
					display:none;
					z-index:11;
					background-color:#32363f;
					color:white;
					font-size:15px;
					text-align:center;
					position:relative;
					top:0px;
					left:0px;
					width:100%;
					height:100%;
					min-height:100%;
				">
				<br><br>
				<div style="text-align:center;height:50px;">
					<div class="nelioab_spinner white_spinner"></div>
				</div>
				<p><?php
					_e( 'Building heatmap...<br>This might take a while. Please, wait.', 'nelio-ab-testing' );
				?></p>
			</div>

			<script type="text/javascript">
				var nelioab__framekiller = true;
				window.onbeforeunload = function() {
					if ( nelioab__framekiller ) {
						return <?php echo json_encode( __( 'Apparently, there\'s a script that\'s trying to overwrite the location in the address bar and take you somewhere else. Please, in order to see the Heatmaps and Clickmaps of this experiment, make sure you stay in this page and don\'t leave.', 'nelio-ab-testing' ) ); ?>;
					}
				};
			</script>
			<iframe id="content" name="content" frameborder="0"
				src="<?php echo $url; ?>"
				style="background-color:white;width:0px;height:0px;"></iframe>
			<script type="text/javascript">document.getElementById('content').onload = function() { nelioab__framekiller = false; }</script>

		</div>
		<script>
			var NelioABHeatmapLabels = { hm:{}, cm:{} };<?php ?>

			NelioABHeatmapLabels.hm.view      = <?php echo json_encode( __( 'View Heatmap', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.phone     = <?php echo json_encode( __( 'Smartphone', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.phoneNo   = <?php echo json_encode( __( 'Smartphone (no Heatmap available)', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.tablet    = <?php echo json_encode( __( 'Tablet', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.tabletNo  = <?php echo json_encode( __( 'Tablet (no Heatmap available)', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.desktop   = <?php echo json_encode( __( 'Laptop Monitor', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.desktopNo = <?php echo json_encode( __( 'Laptop Monitor (no Heatmap available)', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.hd        = <?php echo json_encode( __( 'Regular Desktop Monitor', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.hm.hdNo      = <?php echo json_encode( __( 'Regular Desktop Monitor (no Heatmap available)', 'nelio-ab-testing' ) ); ?>;

			NelioABHeatmapLabels.cm.view      = <?php echo json_encode( __( 'View Clickmap', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.phone     = <?php echo json_encode( __( 'Smartphone', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.phoneNo   = <?php echo json_encode( __( 'Smartphone (no Clickmap available)', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.tablet    = <?php echo json_encode( __( 'Tablet', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.tabletNo  = <?php echo json_encode( __( 'Tablet (no Clickmap available)', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.desktop   = <?php echo json_encode( __( 'Laptop Monitor', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.desktopNo = <?php echo json_encode( __( 'Laptop Monitor (no Clickmap available)', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.hd        = <?php echo json_encode( __( 'Regular Desktop Monitor', 'nelio-ab-testing' ) ); ?>;
			NelioABHeatmapLabels.cm.hdNo      = <?php echo json_encode( __( 'Regular Desktop Monitor (no Clickmap available)', 'nelio-ab-testing' ) ); ?>;

			<?php
			foreach ( $result->data as $heatmap ) {

				$name = $heatmap->resolution;

				if ( $heatmap->click ) {
					$name .= '_click';
					$views = sprintf(
						_n( 'Clickmap built using data from only one page view', 'Clickmap built using data from %s page views',
							$heatmap->views, 'nelio-ab-testing' ),
						$heatmap->views );
				} else {
					$views = sprintf(
						_n( 'Heatmap built using data from only one page view', 'Heatmap built using data from %s page views',
							$heatmap->views, 'nelio-ab-testing' ),
						$heatmap->views );
				}//end if

				$value = json_decode( $heatmap->value, true );
				$value[ 'views' ] = $views;
				?>var nelioab__pre_<?php echo $name . ' = ' . json_encode( $value ); ?>;
			<?php
			}
			?>

			$("#content").load(function() {
				if ( nelioab__pre_desktop.max + nelioab__pre_desktop_click.max > 0 ) nelioab__current_type = 'desktop';
				else if ( nelioab__pre_hd.max + nelioab__pre_hd_click.max > 0 ) nelioab__current_type = 'hd';
				else if ( nelioab__pre_tablet.max + nelioab__pre_tablet_click.max > 0 ) nelioab__current_type = 'tablet';
				else if ( nelioab__pre_phone.max + nelioab__pre_phone_click.max > 0 ) nelioab__current_type = 'phone';
				highlightData(false);
				$("#" + nelioab__current_type).addClass("active");
				var size = $("#" + nelioab__current_type).attr('data-viewport').split('x');
				scaleTo( size[0], size[1], nelioabHeatmapScale );
				switchHeatmap();
			});

		</script>
		<?php
		die();
	}
?>
<html style="opacity:1;" class="complete">

<head>
	<title><?php esc_html_e( 'Nelio AB Testing &mdash; Heatmaps Viewer', 'nelio-ab-testing' ); ?></title>
	<link rel="stylesheet" href="<?php echo esc_url( nelioab_admin_asset_link( '/css/resizer.min.css' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( nelioab_admin_asset_link( '/css/nelioab-generic.min.css' ) ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( nelioab_admin_asset_link( '/css/nelioab-heatmap.min.css' ) ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9, chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta charset="utf-8">
</head>

<body>
	<script src="<?php echo esc_url( nelioab_admin_asset_link( '/js/jquery4hm.min.js' ) ); ?>"></script>
	<script src="<?php echo esc_url( nelioab_admin_asset_link( '/js/heatmap-viewer.min.js' ) ); ?>"></script>

	<div id="toolbar" data-resizer="basic">
		<ul id="devices">
			<?php $name = __( 'Smartphone (no Heatmap available)', 'nelio-ab-testing' ); ?>
			<li><a id='mobile' data-viewport="360x640" data-icon="mobile" title="<?php echo esc_url( $name ); ?>" class="portrait"><?php echo esc_html( $name ); ?></a></li>
			<?php $name = __( 'Tablet (no Heatmap available)', 'nelio-ab-testing' ); ?>
			<li><a id='tablet' data-viewport="768x1024" data-icon="tablet" title="<?php echo esc_url( $name ); ?>" class="portrait"><?php echo esc_html( $name ); ?></a></li>
			<?php $name = __( 'Laptop Monitor (no Heatmap available)', 'nelio-ab-testing' ); ?>
			<li><a id='desktop' data-viewport="1024x768" data-icon="notebook" title="<?php echo esc_url( $name ); ?>" class="landscape"><?php echo esc_html( $name ); ?></a></li>
			<?php $name = __( 'Regular Desktop Monitor (no Heatmap available)', 'nelio-ab-testing' ); ?>
			<li><a id='hd' data-viewport="1440x900" data-icon="display" title="<?php echo esc_url( $name ); ?>" class="landscape"><?php echo esc_html( $name ); ?></a></li>
		</ul>
		<ul id="hm-additional-controls">
			<li style="color:white;font-size:12px;font-weight:bold;" id="visitors-count"><?php printf( esc_html__( 'Heatmap built using data from %s page views', 'nelio-ab-testing' ), 0 ); ?></li>
			<?php
			if ( NelioABExperiment::PAGE_ALT_EXP == $exp_type || NelioABExperiment::POST_ALT_EXP == $exp_type ) {
				$link = admin_url( 'admin.php?page=nelioab-experiments&action=progress' );
				$link = add_query_arg( array(
					'id'       => $exp_id,
					'exp_type' => $exp_type,
				), $link );
				?>
				<li>|</li>
				<li><a style="font-size:12px;" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'Back', 'nelio-ab-testing' ); ?></a></li>
			<?php
			} ?>
			<li>|</li>
			<li><a style="font-size:12px;" id="show-dropdown-controls">Settings</a></li>
		</ul>
		<ul id="hm-dropdown-controls" style="display:none;">
			<li style="font-size:12px;text-align:center;"><input id="hm-opacity" type="range" min="1" max="10" value="10" /></li>
			<li><a id="view-clicks" style="font-size:12px;"><?php esc_html_e( 'View Clickmap', 'nelio-ab-testing' ); ?></a></li>
			<li><a style="font-size:12px;" href="<?php echo admin_url( 'admin.php?page=nelioab-experiments' ); ?>"><?php esc_html_e( 'List of experiments', 'nelio-ab-testing' ); ?></a></li>
		</ul>
	</div>

	<div id="container" style="color:rgb(255, 255, 255);" class="auto transition">
		<div style="text-align:center;height:50px;margin-top:80px;">
			<div class="nelioab_spinner white_spinner"></div>
		</div>
		<p id="ajax-loader-label1" style="color:white;font-size:20px;"><?php _e( 'Please, wait a moment<br /> while we are retrieving the heatmaps...', 'nelio-ab-testing' ); ?></p>
	</div>

	<script>
		(function(){
			var dd = jQuery('#hm-dropdown-controls');
			jQuery('#show-dropdown-controls').on('click', function() { dd.toggle(); });
			jQuery('#hm-opacity').on('change input', function() {
				try {
					document.getElementById('content').contentWindow.nelioabModifyHeatmapOpacity(
						jQuery(this).val()
					);
				}
				catch(e){}
			});
			jQuery('#hm-dropdown-controls').mouseup(function() {
				dd.hide();
			});
		})();
		jQuery.ajax({
			type:'POST',
			async:true,
			url:document.URL,
			data: {load_from_appengine:'true'},
			success: function(data) {
				jQuery("#container").html(data);
			}
		});
	</script>

</body>
</html>
