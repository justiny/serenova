<?php
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) :
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
endif;

if ( class_exists( 'ES\\Init' ) ) :
  ES\Init::register_services();
endif;

// remove timber caching
add_filter( 'timber/cache/mode', function () {
    return 'none';
} );

// Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css() {
    wp_dequeue_style( 'wp-block-library' ); // Wordpress core
    wp_dequeue_style( 'wp-block-library-theme' ); // Wordpress core
    wp_dequeue_style( 'wc-block-style' ); // WooCommerce
    wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
}

// if ( class_exists( 'Timber' ) ){
//     Timber::$cache = true;
// }

// Order Search Results by Post Type
// https://wordpress.stackexchange.com/questions/177650/sort-search-results-by-post-type

add_filter( 'posts_orderby', 'order_search_by_posttype', 10, 2 );
function order_search_by_posttype( $orderby, $wp_query ){
    if( ! $wp_query->is_admin && $wp_query->is_search ) :
        global $wpdb;
        $orderby =
            "
            CASE WHEN {$wpdb->prefix}posts.post_type = 'product' THEN '1'
                 WHEN {$wpdb->prefix}posts.post_type = 'resources' THEN '2'
                 WHEN {$wpdb->prefix}posts.post_type = 'press' THEN '3'
                 WHEN {$wpdb->prefix}posts.post_type = 'post' THEN '4'
            ELSE {$wpdb->prefix}posts.post_type END ASC,
            {$wpdb->prefix}posts.post_title ASC";
    endif;
    return $orderby;
}
