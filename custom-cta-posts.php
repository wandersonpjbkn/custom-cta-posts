<?php
/**
 * Plugin Name: Custom CTA
 * Plugin URI:  https://github.com/wandersonpjbkn
 * Description: Plugin para adição de call to action nos posts de um website
 * Version:     0.0.1
 * Author:      Wanderson PJ
 * Author URI:  https://github.com/wandersonpjbkn
 * License:     Apache 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: thutor.com
 * Domain Path: thutor.com
 */

/** ------------------------------------------------------------ */

/**
 * Fonts:
 * - (WP Plugin Intro)[https://developer.wordpress.org/plugins/intro/]
 * - (Incorpore WP Uploader)[https://www.inkthemes.com/code-to-integrate-wordpress-media-uploader-in-plugintheme/]
 * - (Array + this + fn)[https://stackoverflow.com/questions/14553623/what-does-mean-arraythis-some-method-string]
 * - (Media Uploader)[http://qnimate.com/adding-a-single-image-using-wordpress-media-uploader/]
 */

/** ------------------------------------------------------------ */

/**
 * Class Custom_CTA
 */
class Custom_CTA {
  /**
   * Variables
   */
  private static $instance = null;

  /** ------------------------------------------------------------ */

  /**
   * Constructor
   */
  public static function get_instance() {
    /** Create a new instance of this class if isn't already created */
    if (null == self::$instance)
      self::$instance = new self;

    return self::$instance;
  }

  /** Constructor will autoexecute */
  private function __construct() {
    /** WordPress action to register a new menu item */
    add_action(
      'admin_menu',
      array( $this, 'add_custom_admin_menu_item' )
    );

    /** WordPress action to register admin styles */
    add_action(
      'admin_enqueue_scripts',
      array( $this, 'add_stylesheet_admin' )
    );

    /** WordPress action to register WPMedia */
    add_action(
      'admin_enqueue_scripts',
      array( $this, 'add_wordpress_media' )
    );

    /** WordPress action to register admin scripts */
    add_action(
      'admin_enqueue_scripts',
      array( $this, 'add_script_admin' )
    );
  }

  /** ------------------------------------------------------------ */

  /**
   * Functions
   */

  /** Add Admin Stylesheet */
  public function add_stylesheet_admin() {
    wp_enqueue_style(
      'stylesheet',
      plugins_url( 'admin/css/custom-cta-admin.css', __FILE__ ) );
  }

  /** Add Admin JS */
  public function add_script_admin() {
    wp_enqueue_script(
      'script',
      plugins_url( 'admin/js/custom-cta-admin.js', __FILE__ ) );
  }

  /** Add WordPress Media Uploader */
  public function add_wordpress_media() {
    wp_enqueue_media();
  }  

  /** Register a new menu item */
  public function add_custom_admin_menu_item() {
    /** Set default data for this plugin menu */
    $title        = $this->plugin_data('name');
    $capability   = 'manage_options';
    $slug         = sanitize_title( $title );
    $icon_url     = 'dashicons-carrot';

    /** WordPress core fn to add menu itens */
    add_menu_page(
      $title,
      $title,
      $capability,
      $slug,
      array( $this, 'generate_dashboard' ),
      $icon_url
    );
  }

  /**
   * Return plugin data
   * @return string|array
   */
  private function plugin_data($key) {
    $data         = get_plugin_data( __FILE__ );
    $response     = array(
      'name'        => $data[ 'Name' ],
      'pluginUri'   => $data[ 'PluginURI' ],
      'version'     => $data[ 'Version' ],
      'description' => $data[ 'Description' ],
      'title'       => $data[ 'Title' ],
      'author'      => $data[ 'Author' ],
      'authorUri'   => $data[ 'AuthorURI' ],
      'domainText'  => $data[ 'TextDomain' ],
      'domainPath'  => $data[ 'DomainPath '],
      'title'       => $data[ 'Title' ],
      'authorName'  => $data[ 'AuthorName' ]
    );

    if( $key == 'all' )
      return var_dump( $response );

    if( !empty( $response[ $key ] ) )
      return esc_html( $response[ $key ] );
    
    return 'Wrong call';
  }

  /** Draw header of dashboard */
  public function draw_header_section() {
    ?>
    <div class="custom-cta__wrap">
      <div class="custom-cta__header">
        <h1>
          <?= esc_html( $this->plugin_data('name') ) ?>
          <small>v<?= esc_html( $this->plugin_data('version') ) ?></small>
        </h1>
        <p>
          <?= html_entity_decode( $this->plugin_data('description') ) ?>
        </p>
      </div>
    <?php
  }

  /** Draw drag section of the plugin */
  public function draw_content_drag_section() {
    ?>
      <div class="custom-cta__content">

        <div
          class="custom-cta__content__drag"
          onclick='open_media_uploader_image()'>

          <p>Clique para selecionar uma imagem</p>

        </div>

        <div class="custom-cta__content__selected"></div>

        <div class="custom-cta__content__ctrls"></div>

      </div>
    </div>
    <?php
  }

  /** Draw the dashboard */
  public function generate_dashboard() {
    $this->draw_header_section();
    $this->draw_content_drag_section();
  }

}

/** Call class instance */
$Custom_CTA = Custom_CTA::get_instance();
