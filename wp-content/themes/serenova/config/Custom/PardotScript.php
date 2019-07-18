<?php

namespace ES\Custom;

/*
Adds a javascript textarea to add snippets
*/

class PardotScript
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('add_pardot_js', 'option') == true ) :
        add_action('wp_footer',array( $this, 'es_pardot_js' ));
      endif;
    endif;
  }

  function es_pardot_js() {
    if( class_exists('acf') ) :
      if ( get_field('add_pardot_js', 'option') == true ) :
        echo '<script type="text/javascript">' . get_field('pardot_js', 'option') . '</script>';
      endif;
    endif;
  }

}
