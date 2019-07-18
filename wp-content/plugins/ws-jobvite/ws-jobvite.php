<?php
/**
	Plugin Name: WS Jobvite
	Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
	Description: Joblist using jobvite API.
	Version:     1.0
	Author:      Ravi Kumar
	Author URI:  http://URI_Of_The_Plugin_Author
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Domain Path: /languages
	Text Domain: my-toolset
 */

// Create custom plugin settings menu.
add_action( 'admin_menu', 'ws_jobvite_create_menu' );

/**
 * Add a admin menu to jobvite settings.
 */
function ws_jobvite_create_menu() {
	// Create new top-level menu.
	add_menu_page( 'WS Jobvite Settings', 'Jobvite Settings', 'administrator', 'jobvite', 'ws_jobvite_settings_page' , 'dashicons-admin-generic' );

	// Call register settings function.
	add_action( 'admin_init', 'register_ws_jobvite_plugin_settings' );
}
/**
 * Register settings function.
 */
function register_ws_jobvite_plugin_settings() {
	// Register our settings.
	register_setting( 'ws-jobvite-settings-group', 'jobvite_api_key' );
	register_setting( 'ws-jobvite-settings-group', 'jobvite_secret_key' );
	register_setting( 'ws-jobvite-settings-group', 'jobvite_company_id' );
}

/**
 * Displays the page content for the jobvite admin menu page.
 */
function ws_jobvite_settings_page() {
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Jobvite Config' ); ?></h2>
	<form method="post" action="options.php">
    <?php settings_fields( 'ws-jobvite-settings-group' ); ?>
    <?php do_settings_sections( 'ws-jobvite-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php esc_html_e( 'Jobvite API Key' ); ?></th>
        <td><input type="text" name="jobvite_api_key" value="<?php echo esc_attr( get_option( 'jobvite_api_key' ) ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php esc_html_e( 'Jobvite Secret Key' ); ?></th>
        <td><input type="text" name="jobvite_secret_key" value="<?php echo esc_attr( get_option( 'jobvite_secret_key' ) ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php esc_html_e( 'Jobvite Company ID' ); ?></th>
        <td><input type="text" name="jobvite_company_id" value="<?php echo esc_attr( get_option( 'jobvite_company_id' ) ); ?>" /></td>
        </tr>
    </table>    
    <?php submit_button(); ?>
</form>
</div>
<?php }

/**
 * Display the content of jobvite Shortcode.
 *
 * @param array $atts containing attributes of shortcode.
 * @return string $output containing html of joblists
 */
function ws_jobvite_shortcode( $atts ) {
	$output = '';
	$region = $atts['region'];
	if ( 'All' === $region ) {
		$output = ws_jobvite_list( 'All' );
	} else {
		$output = ws_jobvite_list( $region );
	}
	return $output;
}
// Add hook for shortcode tag.
add_shortcode( 'ws-jobvite', 'ws_jobvite_shortcode' );

/**
 * Jobvite API for the given region.
 *
 * @param string $region containing name of region.
 */
function ws_jobvite_query_api( $region = '' ) {
	$jobvite_api = get_option( 'jobvite_api_key' );
	$jobvite_sc = get_option( 'jobvite_secret_key' );
	$jobvite_company_id = get_option( 'jobvite_company_id' );
	$query = array(
		'api' => $jobvite_api,
		'sc' => $jobvite_sc,
		'companyId' => $jobvite_company_id,
	);
	if ( 'All' !== $region && '' !== $region ) {
		$query['region'] = urlencode( $region );
	}
	$jobvite_url = add_query_arg(
		$query,
		'https://api.jobvite.com/v1/jobFeed'
	);
	$response = wp_remote_get( $jobvite_url );

	// Is the API up?
	if ( ! 200 === wp_remote_retrieve_response_code( $response ) ) {
			return false;
	}
	$body = wp_remote_retrieve_body( $response );

	// Decode json.
	$data = json_decode( wp_remote_retrieve_body( $response ) );

	// Ensure that the region exists.
	if ( isset( $data->error ) ) {
		return false;
	}
	$category = array();
	foreach ( $data->jobs as $job ) {
		if ( 'All' !== $region ) {
			$category[ $job->category ][] = $job;
		} else {
			$category[ $job->region ][ $job->category ][] = $job;
		}
	}
	return $category;
}

/**
 * Structures Jobvite data for display.
 *
 * @param string $region containg name of region.
 *
 * @return array
 *   A prepared render array of available jobs.
 */
function ws_jobvite_list( $region = 'All' ) {
	$jobs_list = '';
	$api_data = ws_jobvite_query_api( $region );
	$jobs_list .= '<div class="jobs_wrap">';
	$jobs_list .= '<h1>Open Position</h1>';
	if ( 'All' !== $region && '' !== $region ) {
		foreach ( $api_data as $category => $jobs ) {
			$jobs_list .= '<h3>' . $category . '</h3><ul class= "jobs_list">';
			foreach ( $jobs as $key => $job ) {
				$jobs_list .= '<li>';
				$jobs_list .= '<span><a href="'.$job->detailUrl . '">' . $job->title . ' </a></span>';
				$jobs_list .= '<span class="joblocation">' . $job->location . '</span>';
				$jobs_list .= '</li>';
			}
			$jobs_list .= '</ul>';
		}
	} else {
		foreach ( $api_data as $region => $categories ) {
			$jobs_list .= '<h2>' . $region . '</h2><ul class= "categories">';
			foreach ( $categories as $category => $jobs ) {
				$jobs_list .= '<h3>' . $category . '</h3><ul class= "jobs_list">';
				foreach ( $jobs as $key => $job ) {
					$jobs_list .= '<li>';
					$jobs_list .= '<span><a href="'.$job->detailUrl . '">' . $job->title . ' </a></span>';
					$jobs_list .= '<span class="joblocation">' . $job->location . '</span>';
					$jobs_list .= '</li>';
				}
				$jobs_list .= '</ul>';
			}
			$jobs_list .= '</ul>';
		}
	}
	$jobs_list .= '</div>';
	return $jobs_list;
}
