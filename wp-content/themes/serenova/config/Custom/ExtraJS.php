<?php

namespace ES\Custom;

/*
Adds a javascript textarea to add snippets
*/

class ExtraJS
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('add_extra_js', 'option') == true ) :
        add_action('wp_head',array( $this, 'es_admin_js' ), -1);
      endif;
    endif;
  }

  function es_admin_js() {
    if( class_exists('acf') ) :
      if ( get_field('add_extra_js', 'option') == true ) :
        echo "<script><!-- Google Tag Manager -->" .
          "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({" .
            "'gtm.start': new Date().getTime(),event:'gtm.js'});" .
            "var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';" .
            "j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);" .
          "})(window,document,'script','dataLayer','". get_field('extra_js', 'option') ."');" .
        "</script>";
      endif;
    endif;
  }

}
