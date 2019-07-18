<?php

namespace ES\Custom;

/*
Adds a CSS textarea to add snippets
*/

class AdminStyles
{

  // Register
  function register() {
    add_action('admin_head',array( $this, 'es_acf_styles' ));
  }

  function es_acf_styles() {
      echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/assets/admin/admin-styles.css" />';
  }
}
