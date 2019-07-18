<?php

namespace ES\Timber;
use Timber\Timber;

/**
 * Timber Init
 */
class Init
{

    public function register()
    {

      add_filter( 'timber/context', array( &$this, 'add_to_context' ) );

      // Gives a notice in admin if Timber isn't activated.
      if ( ! class_exists( 'Timber' ) ) {
        add_action( 'admin_notices', function() {
        echo '<div class="error"><p>Oh, wait.  Timber needs to be activated for all the goodness to happen. Go activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
      });

      // Gives a notice on homepage if Timber isn't activated.
      add_filter('template_include', function($template) {
        return get_stylesheet_directory() . '/static/no-timber.html';
      });
    }
  }

    public function add_to_context( $context ) {

      // Menus
      $context['menu'] = new \Timber\Menu('primary');

      // Widgets
      $context['footer1'] = Timber::get_widgets('footer1');
      $context['footer2'] = Timber::get_widgets('footer2');
      $context['footer3'] = Timber::get_widgets('footer3');
      $context['footer4'] = Timber::get_widgets('footer4');

    return $context;
    }
}
