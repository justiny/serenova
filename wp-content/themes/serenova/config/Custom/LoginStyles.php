<?php

namespace ES\Custom;

/*
Adds Login Styles
*/

class LoginStyles
{

  // Register
  function register() {
    add_action('login_head',array( $this, 'es_admin_styles' ));
  }

  function es_admin_styles() {
    echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/assets/admin/login-styles.css" />';
  }
}
