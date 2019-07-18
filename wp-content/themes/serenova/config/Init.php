<?php
/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `config/custom/custom.php` to write your custom functions
 *
 * @package es
 */

namespace ES;

final class Init
{
  /**
   * Store all the classes inside an array
   * @return array Full list of classes
   */
  public static function get_services()
  {
    return [
      Setup\Setup::class,
      Setup\Enqueue::class,
      Setup\Menus::class,
      Setup\CustomPostTypes::class,
      Core\Sidebar::class,
      Custom\RemoveQueryStrings::class,
      Custom\RemoveEmoji::class,
      Custom\ExtraJS::class,
      Custom\PardotScript::class,
      Custom\RemoveComments::class,
      Custom\RemoveWpVersion::class,
      Custom\RemoveRss::class,
      Custom\AdminStyles::class,
      Custom\RemoveXML::class,
      Custom\ShowAdminBar::class,
      Custom\LoginStyles::class,
      Custom\SearchCPT::class,
      Timber\Init::class,
      Acf\Toolbar::class,
      Acf\SiteOptions::class
    ];
  }

  /**
   * Loop through the classes, Initialize them, and call the register() method if it exists
   * @return
   */
  public static function register_services()
  {
    foreach ( self::get_services() as $class ) {
      $service = self::instantiate( $class );
      if ( method_exists( $service, 'register') ) {
        $service->register();
      }
    }
  }

  /**
   * Initialize the class
   * @param  class $class     class from the services array
   * @return class instance     new instance of the class
   */
  private static function instantiate( $class )
  {
    return new $class();
  }

}
