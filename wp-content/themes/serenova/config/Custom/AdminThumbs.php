<?php

namespace ES\Custom;

/* 
Adds Thumbnails to Posts in Admin
*/

class AdminThumbs
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('add_thumbnail_to_posts_admin', 'option') == true ) :
        add_filter('manage_posts_columns',array($this,'custom_columns'));
        add_action( 'manage_posts_custom_column',array($this, 'custom_columns_data' ), 10, 2  );
      endif; 
    endif;
  }
  
  // Makes all ACF Options available throughout site
  function custom_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'featured_image' => 'Image',
        'title' => 'Title',
        'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble">Comments</div></span>',
        'date' => 'Date'
     );
    return $columns;
  }

  function custom_columns_data( $column, $post_id ) {
    switch ( $column ) {
    case 'featured_image':
        the_post_thumbnail( 'thumbnail' );
        break;
    }
  }
}
