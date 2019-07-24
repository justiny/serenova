<?php

namespace ES\Setup;

/**
 * Enqueue.
 */
class Enqueue
{
  /**
   * register default hooks and actions for WordPress
   * @return
   */
  public function register()
  {
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }

  public function enqueue_scripts()
  {
    // Deregister the built-in version of jQuery from WordPress
    // if ( ! is_customize_preview() ) {
    //   wp_deregister_script( 'jquery' );
    // }

    wp_deregister_script( 'jquery' );

    // CSS
    wp_enqueue_style( 'main-css', get_template_directory_uri() . '/assets/css/styles.css', array(), '1.0.0', 'all' );

    // JS
    wp_enqueue_script( 'polyfills', get_template_directory_uri() . '/assets/js/polyfills.js', array(), '1.0.0', false );
    wp_enqueue_script( 'manifest-js', get_template_directory_uri() . '/assets/js/manifest.js', array(), '1.0.0', true );
    wp_enqueue_script( 'vue-js', get_template_directory_uri() . '/assets/js/vendor.js', array(), '1.0.0', true );
    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/assets/js/app.js', array(), '1.0.0', true );

    // Extra
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
    }
  }
}
