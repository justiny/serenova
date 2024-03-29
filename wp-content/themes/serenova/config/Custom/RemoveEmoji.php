<?php

namespace ES\Custom;

/* 
Removes Emoji from editor.  
*/

class RemoveEmoji
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('remove_emoji', 'option') == true ) :
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
      endif; 
    endif; 
  }

}
