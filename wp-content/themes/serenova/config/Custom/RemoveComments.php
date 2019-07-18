<?php

namespace ES\Custom;

/* 
Removes Comment System.
Still need re-directs set...
*/

class RemoveComments
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('remove_comments_system', 'option') == true ) :

          add_action('admin_menu', array($this,'es_remove_comment_support'));

          // Remove from Columns in Admin
          add_filter('manage_edit-post_columns',array($this,'es_remove_post_columns'),10,1);

          // Remove Post Type Comments
          add_action('admin_init', array($this,'es_disable_comments_post_types_support'));

          // Disable Comments in Dashboard
          add_action('admin_init', array($this,'es_disable_comments_dashboard'));
          add_action('admin_menu', array($this,'es_disable_comments_admin_menu'));
          
          // Close comments on frontend
          add_filter('comments_open', array($this, 'es_disable_comments_status'), 20, 2);
          add_filter('pings_open', array($this,'es_disable_comments_status'), 20, 2);

          add_action( 'wp_before_admin_bar_render', array($this,'my_admin_bar_render' ));
          add_action( 'admin_head-index.php', array($this,'es_remove_comments_dash' ));
      
      endif; 
    endif;
  }

  function es_disable_comments_status() {
  return false;
  }

  function es_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
  }

  function es_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
  }

  function my_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
  }

  function es_remove_comments_dash () {
    print '<style>#latest-comments,li.comment-count{ display:none; }</style>';
  }

  function es_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
      if(post_type_supports($post_type, 'comments')) {
        remove_post_type_support($post_type, 'comments');
        remove_post_type_support($post_type, 'trackbacks');
      }
    }
  }

  function es_remove_comment_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'post', 'trackbacks' );
    remove_post_type_support( 'page', 'trackbacks' );
    remove_post_type_support( 'page', 'comments' );
  }

  function es_remove_post_columns($columns) {
    unset($columns['comments']);
    return $columns;
}


// end class
}
