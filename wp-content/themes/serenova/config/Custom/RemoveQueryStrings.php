<?php

namespace ES\Custom;

/* 
https://kinsta.com/knowledgebase/remove-query-strings-static-resources/
*/

class RemoveQueryStrings
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('remove_query_strings', 'option') == true ) :
        add_filter( 'style_loader_src', array ($this, 'remove_cssjs_ver'), 10, 2 );
        add_filter( 'script_loader_src',array ($this, 'remove_cssjs_ver'), 10, 2 );
      endif; 
    endif;
  }

  function remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) )
      $src = remove_query_arg( 'ver', $src );
    return $src;
    }

}
