<?php

namespace ES\Acf;

/**
 * ACF Toolbar
 */
class Toolbar
{
  /**
     * register default hooks and actions for WordPress
     * @return
     */
  public function register()
  {
    add_filter( 'es_toolbars', array( $this, 'es_toolbars' ) );
    add_filter( 'acf/fields/wysiwyg/toolbars', array( $this, 'es_toolbars' ) );
  }

  public function es_toolbars( $toolbars )
  {

  $toolbars['Emerson Stone Toolbar' ] = array();
  $toolbars['Emerson Stone Toolbar' ][1] = array('link', 'unlink','bullist' );   

  unset( $toolbars['Basic' ] );
  
  return $toolbars;
  }
}
