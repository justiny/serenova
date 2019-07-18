<?php

namespace ES\Custom;

/* 
https://digwp.com/2009/07/remove-wordpress-version-number/
*/

class RemoveWpVersion
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('remove_wp_version', 'option') == true ) :
          // remove version from head
          remove_action('wp_head', 'wp_generator');
          // remove version from rss
          add_filter('the_generator', array( $this, '__return_empty_string'));
          add_filter('style_loader_src', array( $this, 'es_remove_version_scripts_styles'), 9999);
          add_filter('script_loader_src', array( $this, 'es_remove_version_scripts_styles'), 9999);
      endif; 
    endif;
  }

  // remove version from scripts and styles
  function es_remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
      $src = remove_query_arg('ver', $src);
    }
    return $src;
  }
}
