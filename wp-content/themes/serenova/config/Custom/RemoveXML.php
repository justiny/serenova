<?php

namespace ES\Custom;

/* 
Removes XML-RPC 
*/

class RemoveXML
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('remove_xml_rpc', 'option') == true ) :
        
        // Disable xmlrpc
        add_filter('xmlrpc_enabled', array($this, '__return_false'));
        add_filter( 'wp_headers', array($this, 'es_remove_x_pingback' ));
        
        // Remove Links
        remove_action('wp_head','wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
      
      endif; 
    endif;
  }

  function es_remove_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
  }

}
