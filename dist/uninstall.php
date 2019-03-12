<?php
/** Unstall the plugin after desactivation */

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN'))
  die;

global $wpdb;

$tb_surname         = 'custom_cta';
$tb_historic        = $wpdb->prefix . $tb_surname . '_historic';
$tb_current         = $wpdb->prefix . $tb_surname . '_current';

$wpdb->query("DROP TABLE IF EXISTS $tb_historic");
$wpdb->query("DROP TABLE IF EXISTS $tb_current");

?>