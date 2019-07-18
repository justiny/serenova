<?php

namespace ES\Core;

/**
 * Sidebar.
 */
class Sidebar
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        // hide login plugin
        // add_action('pre_current_active_plugins', array($this,'es_hide_login_plugin'));
    }

    /*
        Define the sidebar
    */
    public function widgets_init()
    {
        // register_sidebar( array(
        //     'name' => esc_html__('Sidebar', 'es'),
        //     'id' => 'es_sidebar',
        //     'description' => esc_html__('Add a custom menu here.  Select Navigation Menu and choose your menu.', 'es'),
        //     'before_widget' => '<div id="%1$s" class="widget %2$s">',
        //     'after_widget' => '</div>',
        //     'before_title' => '<h2 class="widget-title">',
        //     'after_title' => '</h2>',
        // ) );

    }

    // function es_hide_login_plugin() {
    //   global $wp_list_table;
    //   $hidearr = array('wps-hide-login/wps-hide-login.php');
    //   $myplugins = $wp_list_table->items;
    //   foreach ($myplugins as $key => $val) {
    //     if (in_array($key,$hidearr)) {
    //       unset($wp_list_table->items[$key]);
    //     }
    //   }
    // }

}
