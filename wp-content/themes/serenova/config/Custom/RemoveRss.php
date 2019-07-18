<?php

namespace ES\Custom;

/* 
Removes RSS Feed  
*/

class RemoveRss
{

  // Register
  function register() {
    if( class_exists('acf') ) :
      if ( get_field('remove_rss', 'option') == true ) :
        add_action('do_feed', array( $this, 'es_disable_feed') , 1);
        add_action('do_feed_rdf', array( $this,'es_disable_feed'), 1);
        add_action('do_feed_rss', array( $this,'es_disable_feed'), 1);
        add_action('do_feed_rss2', array( $this,'es_disable_feed'), 1);
        add_action('do_feed_atom', array( $this,'es_disable_feed'), 1);
        add_action('do_feed_rss2_comments', array( $this,'es_disable_feed'), 1);
        add_action('do_feed_atom_comments', array( $this,'es_disable_feed'), 1);
        remove_action( 'wp_head', 'feed_links_extra', 3 );
        remove_action( 'wp_head', 'feed_links', 2 );
      endif; 
    endif;
  }

  function es_disable_feed() {
    if( class_exists('acf') ) :
      if ( get_field('remove_rss', 'option') == true ) :
      wp_die( __( 'No feed available, please visit the <a href="'. esc_url( home_url( '/' ) ) .'">homepage</a>!' ) );
      endif;
    endif;
  }

}
