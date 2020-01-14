<?php
/**
 * Plugin Name: Custom CTA
 * Plugin URI:  https://github.com/wandersonpjbkn/custom-cta-posts
 * Description: Plugin para adição de call to action nos posts de um website
 * Version:     1.1.2
 * Author:      Wanderson PJ
 * Author URI:  https://github.com/wandersonpjbkn
 * License:     Apache 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: thutor.com
 * Domain Path: thutor.com
 */

/** ------------------------------------------------------------ */

/**
 * Class Custom_CTA
 */
class Custom_CTA {
  /**
   * Variables
   */
  private static $instance            = null;
  private static $tb_surname          = 'custom_cta';
  private static $script_admin        = 'adminCtaScripts';

  /** ------------------------------------------------------------ */

  /**
   * Constructor
   */
  public static function get_instance() {
    /** Create a new instance of this class if isn't already created */
    if ( null == self::$instance )
      self::$instance = new self;

    return self::$instance;
  }

  /** Constructor will autoexecute */
  private function __construct() {
    /** Register a new table historic */
    register_activation_hook(
      __FILE__,
      array( $this, 'add_tables' )
    );
    
    /** Register a new menu item */
    add_action(
      'admin_menu',
      array( $this, 'add_custom_admin_menu_item' )
    );

    /** Register admin styles */
    add_action(
      'admin_enqueue_scripts',
      array( $this, 'add_stylesheet_admin' )
    );

    /** Register WPMedia */
    add_action(
      'admin_enqueue_scripts',
      array( $this, 'add_wordpress_media' )
    );

    /** Register admin scripts */
    add_action(
      'admin_enqueue_scripts',
      array( $this, 'add_script_admin' )
    );

    /** Register ajax listener to publish */
    add_action(
      'wp_ajax_publishCta',
      array( $this, 'publish_cta' )
    );

    /** Add cta on post */
    add_filter(
      'the_content',
      array( $this, 'generate_cta_on_post' )
    );
  }

  /** ------------------------------------------------------------ */

  /**
   * Functions
   */

  /** GET SECTION */
  /** Return the id of the last historic */
  private function get_last_historic() {
    return $this->search_last_item( 'historic' );
  }

  /** Return the current item data */
  private function get_current_data() {
    return $this->search_last_item( 'current' );
  }

  /** Return default table name @return string */
  private function get_table_name( $sufix ) {
    global $wpdb;

    $surname      = self::$tb_surname;
    $fullname     = $wpdb->prefix . $surname . '_' . $sufix;

    return $fullname;
  }

  /** Return plugin data @return string|array */
  private function get_plugin_dt( $key ) {
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
      'domainPath'  => $data[ 'DomainPath' ],
      'title'       => $data[ 'Title' ],
      'authorName'  => $data[ 'AuthorName' ]
    );

    if ( $key == 'all' )
      return var_dump( $response );

    if ( !empty( $response[ $key ] ) )
      return esc_html( $response[ $key ] );
    
    return 'Wrong call';
  }

  /** Return current image url or -1 @return string|number */
  private function get_current_image () {
    $current = $this->get_current_data();

    if ( intval( $current->img_id ) == 0 )
      return -1;

    $img = wp_get_attachment_image_src(
      intval( $current->img_id ),
      'large'
    );

    return $img;
  }
  
  /** ADD SECTION */  
  /** Add Admin Stylesheet */
  public function add_stylesheet_admin() {
    wp_enqueue_style(
      'stylesheet',
      plugins_url( 'admin/css/custom-cta-admin.css', __FILE__ )
    );
  }

  /** Add Admin JS */
  public function add_script_admin() {
    wp_enqueue_script(
      self::$script_admin,
      plugins_url( 'admin/js/custom-cta-admin.js', __FILE__ )
    );
  }

  /** Add WordPress Media Uploader */
  public function add_wordpress_media() {
    wp_enqueue_media();
  }  

  /** Register a new menu item */
  public function add_custom_admin_menu_item() {
    /** Set default data for this plugin menu */
    $title        = $this->get_plugin_dt( 'name' );
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

  /** Register all tables */
  public function add_tables() {
    $this->add_table_historic();
    $this->add_table_current();
  }

  /** Register the table historic */
  private function add_table_historic() {
    global $wpdb;

    $tb_name      = $this->get_table_name( 'historic' );
    $tb_collate   = $wpdb->get_charset_collate();

    // Prevent table creation if already exists
    if ( $wpdb->get_var( "SHOW TABLES LIKE '$tb_name'" ) == $tb_name )
		  return;

    // Create table if not exists
    $sql = "CREATE TABLE $tb_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      user_id mediumint(9) NOT NULL,
      img_id mediumint(9) NOT NULL,
      page_destiny varchar(120),
      PRIMARY KEY  (id)
    ) $tb_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // Register table
    add_option(
      'jal_db_version',
      $this->get_plugin_dt( 'version' )
    );
  }

  /** Register the table historic */
  private function add_table_current() {
    global $wpdb;

    $tb_name      = $this->get_table_name( 'current' );
    $tb_collate   = $wpdb->get_charset_collate();

    // Prevent table creation if already exists
    if ( $wpdb->get_var( "SHOW TABLES LIKE '$tb_name'" ) == $tb_name )
		  return;

    // Create table if not exists
    $sql = "CREATE TABLE $tb_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      history_id mediumint(9) NOT NULL,
      img_id mediumint(9) NOT NULL,
      page_destiny varchar(120),
      PRIMARY KEY  (id),
      KEY history_id (history_id)
    ) $tb_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // Register table
    add_option(
      'jal_db_version',
      $this->get_plugin_dt( 'version' )
    );

    // Add default value
    $tb_data      = array(
      'history_id'  => 0,
      'img_id'      => 0
    );
    $wpdb->insert( $tb_name, $tb_data );
  }

  /** Insert data into historic table */
  private function add_data_to_historic( $img_id, $text ) {
    global $wpdb;
    
    $tb_name      = $this->get_table_name( 'historic' );
    $tb_data      = array(
      'time'            => current_time( 'mysql' ),
      'user_id'         => get_current_user_id(),
      'page_destiny'    => $text,
      'img_id'          => $img_id
    );
    
    $wpdb->insert( $tb_name, $tb_data );
    
    if ( $wpdb->last_error !== '' ) {
      return json_encode(
        array( 'historic' => $wpdb->last_error ),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    } else {
      return $tb_data;
    }
  }

  /** Updated data into current table */
  private function add_data_to_current( $img_id, $page_destiny ) {
    global $wpdb;

    $last         = $this->get_last_historic();
    $tb_name      = $this->get_table_name( 'current' );
    $tb_data      = array(
      'history_id'      => $last->id,
      'page_destiny'    => $page_destiny,
      'img_id'          => $img_id
    );
    $tb_where     = array( 'id' => 1 );
    
    $wpdb->update( $tb_name, $tb_data, $tb_where );
    
    if ( $wpdb->last_error !== '' ) {
      return json_encode(
        array( 'current' => $wpdb->last_error ),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    } else {
      return $tb_data;
    }
  }

  /** OTHERS */
  /** Return the last register of the table informed */
  private function search_last_item( $table ) {
    global $wpdb;

    $tb_name      = $this->get_table_name( $table );
    $res          = $wpdb->get_results(
      "SELECT * 
      FROM $tb_name 
      ORDER BY id DESC 
      LIMIT 1",
      OBJECT
    );

    if ( $wpdb->last_error !== '' ) {
      return json_encode(
        array( 'search_last' => $wpdb->last_error ),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
      );
    } else if ( array_key_exists( '0', $res ) ) {
      return $res[0];
    } else {
      return '';
    }
  }

  /** Handle publication option */
  public function publish_cta() {
    $img_id         = intval( $_POST[ 'imgId' ] );
    $page_destiny   = $_POST[ 'pageDestiny' ];
    $current        = $this->get_current_data();

    if ( $page_destiny == $current->page_destiny && $img_id == intval( $current->img_id ) ) {
      echo json_encode( array( 'warning' => 'Imagem e destino são os mesmo atualmente em uso' ) );
      wp_die();
    }

    $res1       = $this->add_data_to_historic( $img_id, $page_destiny );
    $res2       = $this->add_data_to_current( $img_id, $page_destiny );

    echo json_encode(
      array(
        'historic' => $res1,
        'current' => $res2
      ),
      JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
    );
    wp_die();
  }

  /** Draw header section */
  private function draw_header_section() {
    ?>
      <div class="custom-cta__header">

        <h1>
          <?= esc_html( $this->get_plugin_dt( 'name' ) ); ?>
          <small>v<?= esc_html( $this->get_plugin_dt( 'version' ) ); ?></small>
        </h1>

        <p><?= html_entity_decode( $this->get_plugin_dt( 'description' ) ); ?></p>

      </div>
    <?php
  }

  /** Draw drag section */
  private function draw_content_drag_section() {
    $current      = $this->get_current_data();
    $img          = $this->get_current_image();
    ?>
      <div
        class='custom-cta__content__drag'
        onclick='mediaUploader.openMediaUploaderImage()'>
        <p>Clique para selecionar uma imagem</p>
      </div>

      <div class="custom-cta__content__input">
        <input
            type="text"
            placeholder="Definir página de destino"
            value="<?= esc_html( $current->page_destiny ) ?>">
      </div>

      <div class='custom-cta__content__box'>

        <div class='custom-cta__content__img'>
        <?php if ( $img != -1 ) : ?>
          <div
            id="target"
            data-id="<?= esc_html( $current->img_id ) ?>"
            style="background-image: url(' <?= esc_url( $img[0] ) ?> ')"></div>
        <?php else : ?>
          <div id="target"></div>
        <?php endif ?>
        </div>

        <div class='custom-cta__content__ctrls'>
          <button
            class='button button-primary'
            onclick='mediaUploader.publish()'>Publicar</button>
          <button
            class='button button-secondary'
            onclick='mediaUploader.cleanTargetBackgroud()'>Remover</button>
        </div>

      </div>
    <?php
  }

  /** Draw history table section */
  private function draw_content_history_section() {
    global $wpdb;

    $last         = $this->get_last_historic();
    $tb_name      = $this->get_table_name( 'historic' );

    if ( empty( $last ) ) :
      ?>
        <div class="custom-cta__content__historic">
          <table>
            <thead><tr><th>Sem dados</th></tr></thead>
            <tbody><tr><td>No data to show</td></tr></tbody>
          </table>
        </div>
      <?php
    else :
      $historic = $wpdb->get_results(
        "SELECT * 
        FROM $tb_name 
        ORDER BY id DESC
        LIMIT 10",
        OBJECT
      );
      ?>
      <div class="custom-cta__content__historic">
        <table class="active">
          <thead>
            <tr>
              <th>Código</th>
              <th>Imagem</th>
              <th>Destino</th>
              <th>Usuário</th>
              <th>Publicado em</th>
              <th>Pick</th>
            </tr>
          </thead>
          <tbody>
          <?php
            foreach ( $historic as $value ) {
              ?>
              <tr>
                <td><?= esc_html( $value->id ) ?></td>
                <td>
                  <?php
                    $thumb = wp_get_attachment_image_src( $value->img_id );
                    $img = wp_get_attachment_image_src( $value->img_id, 'large' );
                  ?>
                  <img src="<?= esc_url( $thumb[0] ) ?>">
                </td>
                <td><?= esc_html( $value->page_destiny ) ?></td>
                <td>
                  <?php
                    $user = get_user_by( 'id', $value->user_id );
                    echo esc_html( $user->first_name );                
                  ?>
                </td>              
                <td><?= esc_html( $value->time ) ?></td>
                <td>
                  <?php
                  $data = json_encode(
                    array(
                      "id" => esc_html( $value->img_id ),
                      "url" => esc_html( $img[0] ),
                      "destiny" => esc_url( $value->page_destiny )
                    ),
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
                  );
                  ?>
                  <button
                    class="button button-primary"
                    onclick="mediaUploader.writeImageOnSelectField( <?= esc_html( $data ) ?> )">
                    <span class="dashicons dashicons-yes"></span>
                  </button>
                </td>
              </tr>
              <?php
            }
          ?>
          </tbody>
          </table>
        </div>
      <?php
    endif;
  }

  /** Draw the dashboard */
  public function generate_dashboard() {    
    ?>
      <div class="custom-cta__wrap">

        <?php $this->draw_header_section(); ?>

        <div class="custom-cta__content">

          <?php $this->draw_content_drag_section(); ?>

          <?php $this->draw_content_history_section(); ?>

        </div>

      </div>
    <?php
  }

  /** Draw CTA on posts */
  public function generate_cta_on_post( $content ) {
    $current_data       = $this->get_current_data();
    $current_image      = $this->get_current_image();

    if ( $current_data->img_id == 0 )
      return $content;

    if ( is_single() ) {

      if ( function_exists( 'is_product' ) ) {
        if ( is_product() ) 
          return $content;
      }

      $cta = "<div class=\"custom-cta__post-target\">
        <a href='$current_data->page_destiny' target=\"_blank\">
          <img src='$current_image[0]'>
        </a>
      </div>";

      return $content . $cta;
    }

    return $content;
  }

}

/** Call class instance */
Custom_CTA::get_instance();
