<?php
/*
Plugin Name: Tapfiliate
Plugin URI: http://tapfiliate.com/
Description: Easily integrate the Tapfiliate tracking code.
Version: 1.1
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
  register_setting('tapfiliate', 'integrate_for');
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
  $integrate_for = get_option('integrate_for');
  $thank_you_page = get_option('thank_you_page');
  $query_parameter_transaction_id = get_option('query_parameter_transaction_id');
  $query_parameter_transaction_amount = get_option('query_parameter_transaction_amount');
?>
<script src="//tapfiliate.com/tapfiliate.js" type="text/javascript" async></script>
<script type="text/javascript">
  window['TapfiliateObject'] = i = 'tap';
  window[i] = window[i] || function () {
      (window[i].q = window[i].q || []).push(arguments);
  };

  tap('create', '<?php echo $tap_account_id ?>');
  <?php
    if ($integrate_for == 'wp') {
      if ($postName == $thank_you_page) {
        if (isset($_GET[$query_parameter_transaction_id]) && isset($_GET[$query_parameter_transaction_amount])) {
          echo "tap('transaction', '{$_GET[$query_parameter_transaction_id]}', {$_GET[$query_parameter_transaction_amount]});";
        }
      } else {
        echo "tap('detectClick');";
      }
    } elseif ($integrate_for == 'wc') {
      if (function_exists("is_order_received_page") && is_order_received_page() && isset($_GET['order-received'])) {
        $order_id  = apply_filters( 'woocommerce_thankyou_order_id', absint( $_GET['order-received'] ) );
        $order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

        if ( $order_id > 0 ) {
          $order = new WC_Order( $order_id );
          if ( $order->order_key != $order_key )
            unset( $order );
        }
        echo "tap('transaction', '{$order->id}', {$order->get_total()});";
      } else {
        echo "tap('detectClick');";
      }
    }
  ?>
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
