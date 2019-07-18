<?php
namespace ES\Custom;
/* 
Show admin bar option / Moves it from the general options to the Site Options panel (for convenience) 
*/
class ShowAdminBar
{
  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('turn_on_admin_bar', 'option') == true ) :
        add_filter('show_admin_bar','__return_true');
        add_action('admin_bar_menu',array($this, 'es_custom_admin_bar_item'), 1000);
      endif; 
    endif;
  }
function es_custom_admin_bar_item()
  {
    global $wp_admin_bar;
    if(!is_super_admin() || !is_admin_bar_showing()) return;
    // Add Parent Menu
    $argsParent = array(
        'id' => 'es_custom_admin_bar_item',
        'title' => '<span class="ab-icon dashicons dashicons-layout" style="margin-top:2px"></span>' . _( 'Site Options' ),
        'href' => get_admin_url() . '/admin.php?page=acf-options-site-options',
    );
    $wp_admin_bar->add_menu($argsParent);
  }
}
