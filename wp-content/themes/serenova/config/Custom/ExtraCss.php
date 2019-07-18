<?php

namespace ES\Custom;

/*
Adds a CSS textarea to add snippets
*/

class ExtraCss
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('add_extra_css', 'option') == true ) :
        add_action('wp_head',array( $this, 'es_admin_styles' ));
      endif;
    endif;
  }

  function es_admin_styles() {
    if ( get_field('add_extra_css', 'option') == true ) :
      echo '<style type="text/css">' . get_field('extra_css', 'option') . '</style>';
    endif;
  }

}
