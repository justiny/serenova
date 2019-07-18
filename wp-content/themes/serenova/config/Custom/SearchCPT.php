<?php

namespace ES\Custom;

/**
 * Extend WordPress search to include custom fields
 * Many thanks to Adam.
 * https://adambalee.com
 */

class SearchCPT
{

  // Register
  function register() {

    add_filter('posts_join', array( $this,'cf_search_join' ));
    add_filter( 'posts_where',array( $this, 'cf_search_where' ));
    add_filter( 'posts_distinct', array( $this,'cf_search_distinct' ));

  }

function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}

function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}

function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}



}
