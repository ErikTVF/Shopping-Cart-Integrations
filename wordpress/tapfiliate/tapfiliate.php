<?php
/*
Plugin Name: Tapfiliate
Plugin URI: http://tapfiliate.com/
Description: Easily integrate the Tapfiliate tracking code.
Version: 1.0
Author: Tapfiliate
Author URI: http://tapfiliate.com/
*/

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');

function activate_tapfiliate() {
  add_option('tap_account_id', '1-123abc');
}

function deactive_tapfiliate() {
  delete_option('tap_account_id');
}

function admin_init_tapfiliate() {
  register_setting('tapfiliate', 'tap_account_id');
  register_setting('tapfiliate', 'thank_you_page');
  register_setting('tapfiliate', 'query_parameter_transaction_id');
  register_setting('tapfiliate', 'query_parameter_transaction_amount');
}

function admin_menu_tapfiliate() {
  add_options_page('Tapfiliate', 'Tapfiliate', 'manage_options', 'tapfiliate', 'options_page_tapfiliate');
}

function options_page_tapfiliate() {
  include(WP_PLUGIN_DIR.'/tapfiliate/options.php');
}

function tapfiliate() {
  global $post;
  $postName = ($post) ? $post->post_name : null;
  $tap_account_id = get_option('tap_account_id');
  $thank_you_page = get_option('thank_you_page');
  $query_parameter_transaction_id = get_option('query_parameter_transaction_id');
  $query_parameter_transaction_amount = get_option('query_parameter_transaction_amount');
?>
<script type="text/javascript">
  var _tap = _tap || {};
  _tap.account = '<?php echo $tap_account_id ?>';
<?php
  if ($postName == $thank_you_page) {
    if (isset($_GET[$query_parameter_transaction_id])) {
      echo "_tap.transaction_id = '$_GET[$query_parameter_transaction_id]';\r\n";
    }
    if (isset($_GET[$query_parameter_transaction_amount])) {
      echo "_tap.transaction_ammount = {$_GET[$query_parameter_transaction_amount]};\r\n";
    } else {
      echo "_tap.transaction_ammount = 0;\r\n";
    }
  }
?>
  (function() {
      var tapjs = document.createElement('script'); tapjs.type = 'text/javascript'; tapjs.async = true;
      tapjs.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'tapfiliate.com/tap.js';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(tapjs, s);
  })();
</script>
<?php
}

register_activation_hook(__FILE__, 'activate_tapfiliate');
register_deactivation_hook(__FILE__, 'deactive_tapfiliate');

if (is_admin()) {
  add_action('admin_init', 'admin_init_tapfiliate');
  add_action('admin_menu', 'admin_menu_tapfiliate');
}

if (!is_admin()) {
  add_action('wp_footer', 'tapfiliate');
}

?>
