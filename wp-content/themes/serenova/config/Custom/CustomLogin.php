<?php

namespace ES\Custom;

/*
Adds a CSS textarea to add snippets
*/

class CustomLogin
{

  // Register
  function register() {

        add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );

  }

  public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
      // Create new message
      $msg  = __( 'Hello!', 'personalize-login' ) . "\r\n\r\n";
      $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'personalize-login' ), $user_login ) . "\r\n\r\n";
      $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'personalize-login' ) . "\r\n\r\n";
      $msg .= __( 'To reset your password, visit the following address:', 'personalize-login' ) . "\r\n\r\n";
      $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
      $msg .= __( 'Thanks!', 'personalize-login' ) . "\r\n";

      return $msg;
  }

}
