<?php

namespace ES\Setup;

/**
 * Custom
 * use it to write your custom functions.
 */
class CustomPostTypes
{
  /**
     * register default hooks and actions for WordPress
     * @return
     */
  public function register()
  {
    add_action( 'init', array( $this, 'resources_post_type' ) );
    add_action( 'init', array( $this, 'locations_post_type' ) );
    add_action( 'init', array( $this, 'press_post_type' ) );
    add_action( 'init', array( $this, 'products_post_type' ) );
    add_action( 'init', array( $this, 'create_resources_category' ) );
    add_action( 'init', array( $this, 'create_products_category' ) );
    add_action( 'init', array( $this, 'create_press_category' ) );
    add_action( 'template_redirect', array( $this, 'redirect_resource' ));
    add_action( 'after_switch_theme', array( $this, 'rewrite_flush' ) );
  }

  public function create_resources_category() {

      register_taxonomy(
        'resource-category',
        'resource',
        array(
          'label' => __( 'Resources Categories' ),
          'rewrite'  =>  array('slug' => 'category', 'with_front' => false),
          'hierarchical' => true,
        )
      );
  }

  public function create_products_category() {

      register_taxonomy(
        'product-category',
        'product',
        array(
          'label' => __( 'Categories' ),
          'rewrite' => array( 'slug' => 'category', 'with_front' => false ),
          'hierarchical' => true,
        )
      );
  }

    public function create_press_category() {

      register_taxonomy(
        'press-category',
        'press',
        array(
          'label' => __( 'Press Categories' ),
          'rewrite'  =>  array('slug' => 'category', 'with_front' => false),
          'hierarchical' => true,
        )
      );
  }

  /**
   * Create Custom Post Types
   * @return The registered post type object, or an error object
   */

  public function products_post_type()
  {
    $labels = array(
      'name'               => _x( 'Product Updates', 'post type general name', 'es' ),
      'singular_name'      => _x( 'Product Updates', 'post type singular name', 'es' ),
      'menu_name'          => _x( 'Product Updates', 'admin menu', 'es' ),
      'name_admin_bar'     => _x( 'Product Updates', 'add new on admin bar', 'es' ),
      'add_new'            => _x( 'Add New', 'Product Updates', 'es' ),
      'add_new_item'       => __( 'Add New Product Updates', 'es' ),
      'new_item'           => __( 'New Product Updates', 'es' ),
      'edit_item'          => __( 'Edit Product Updates', 'es' ),
      'view_item'          => __( 'View Product Updates', 'es' ),
      'view_items'         => __( 'View Product Updates', 'es' ),
      'all_items'          => __( 'All Product Updates', 'es' ),
      'search_items'       => __( 'Search Product Updates', 'es' ),
      'parent_item_colon'  => __( 'Parent Product Updates:', 'es' ),
      'not_found'          => __( 'No Product Updates found.', 'es' ),
      'not_found_in_trash' => __( 'No Product Updates found in Trash.', 'es' )
    );

    $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'es' ),
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-admin-generic',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'product-updates', 'with_front' => false ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => 5, // below post
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );

    register_post_type( 'product', $args );
  }


  public function resources_post_type()
  {
    $labels = array(
      'name'               => _x( 'Resources', 'post type general name', 'es' ),
      'singular_name'      => _x( 'Resource', 'post type singular name', 'es' ),
      'menu_name'          => _x( 'Resources', 'admin menu', 'es' ),
      'name_admin_bar'     => _x( 'Resource', 'add new on admin bar', 'es' ),
      'add_new'            => _x( 'Add New', 'Resource', 'es' ),
      'add_new_item'       => __( 'Add New Resource', 'es' ),
      'new_item'           => __( 'New Resource', 'es' ),
      'edit_item'          => __( 'Edit Resource', 'es' ),
      'view_item'          => __( 'View Resource', 'es' ),
      'view_items'         => __( 'View Resources', 'es' ),
      'all_items'          => __( 'All Resources', 'es' ),
      'search_items'       => __( 'Search Resources', 'es' ),
      'parent_item_colon'  => __( 'Parent Resources:', 'es' ),
      'not_found'          => __( 'No resources found.', 'es' ),
      'not_found_in_trash' => __( 'No resources found in Trash.', 'es' )
    );

    $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'es' ),
      'public'             => true,
      // 'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-admin-generic',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'resources', 'with_front' => false ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => 5, // below post
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );

    register_post_type( 'resource', $args );
  }

  public function locations_post_type()
  {
    $labels = array(
      'name'               => _x( 'Locations', 'post type general name', 'es' ),
      'singular_name'      => _x( 'Location', 'post type singular name', 'es' ),
      'menu_name'          => _x( 'Locations', 'admin menu', 'es' ),
      'name_admin_bar'     => _x( 'Location', 'add new on admin bar', 'es' ),
      'add_new'            => _x( 'Add New', 'Location', 'es' ),
      'add_new_item'       => __( 'Add New Location', 'es' ),
      'new_item'           => __( 'New Location', 'es' ),
      'edit_item'          => __( 'Edit Location', 'es' ),
      'view_item'          => __( 'View Location', 'es' ),
      'view_items'         => __( 'View Locations', 'es' ),
      'all_items'          => __( 'All Locations', 'es' ),
      'search_items'       => __( 'Search Locations', 'es' ),
      'parent_item_colon'  => __( 'Parent Locations:', 'es' ),
      'not_found'          => __( 'No locations found.', 'es' ),
      'not_found_in_trash' => __( 'No locations found in Trash.', 'es' )
    );

    $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'es' ),
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-location',
      'query_var'          => true,
      'capability_type'    => 'post',
      'rewrite'            => array( 'with_front' => false ),
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => 5, // below post
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'Location', $args );
  }

  public function press_post_type()
  {
    $labels = array(
      'name'               => _x( 'Press', 'post type general name', 'es' ),
      'singular_name'      => _x( 'Press', 'post type singular name', 'es' ),
      'menu_name'          => _x( 'Press', 'admin menu', 'es' ),
      'name_admin_bar'     => _x( 'Press', 'add new on admin bar', 'es' ),
      'add_new'            => _x( 'Add New', 'Press', 'es' ),
      'add_new_item'       => __( 'Add New Press', 'es' ),
      'new_item'           => __( 'New Press', 'es' ),
      'edit_item'          => __( 'Edit Press', 'es' ),
      'view_item'          => __( 'View Press', 'es' ),
      'view_items'         => __( 'View Press', 'es' ),
      'all_items'          => __( 'All Press', 'es' ),
      'search_items'       => __( 'Search Press', 'es' ),
      'parent_item_colon'  => __( 'Parent Press:', 'es' ),
      'not_found'          => __( 'No Press found.', 'es' ),
      'not_found_in_trash' => __( 'No Press found in Trash.', 'es' )
    );

    $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'es' ),
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-media-spreadsheet',
      'query_var'          => true,
      'capability_type'    => 'post',
      'rewrite'            => array( 'with_front' => false ),
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => 5, // below post
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'Press', $args );
  }

  // redirect single resource posts back to resource archive
  public function redirect_resource() {
    if ( is_singular('resource') ) {
        global $post;
        $redirectLink = get_post_type_archive_link( 'resource' );
        wp_redirect( $redirectLink, 302 );
        exit;
    }
  }

  /**
   * Flush Rewrite on CPT activation
   * @return empty
   */
  public function rewrite_flush()
  {
    // call the CPT init function
    $this->resources_post_type();
    $this->locations_post_type();
    $this->press_post_type();
    $this->products_post_type();

    // Flush the rewrite rules only on theme activation
    flush_rewrite_rules();
  }
}
