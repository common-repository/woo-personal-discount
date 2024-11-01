<?php
/*
Plugin Name: WooCommerce Personal Discount
Description: Personal discount for customer in WooCommerce.
Version:   1.1.1
Author: Applyke
Author URI: https://applyke.com
*/

if (!defined('ABSPATH')) exit;

define('APLK_PD_NAME', 'Personal discount');
define('APLK_PD_NONCE', 'personal_discount_nonce');
define('APLK_PD_LANGUAGE_DOMAIN', 'woocommerce_personal_discount');

if (!in_array('woocommerce/woocommerce.php', (array)get_option('active_plugins', array()))) {
    add_action('admin_notices', 'aplk_plugin_activation_error');
    return;
} else {
    add_action('show_user_profile', 'aplk_pd_show_extra_profile_fields');
    add_action('edit_user_profile', 'aplk_pd_show_extra_profile_fields');
    add_action('personal_options_update', 'aplk_pd_save_extra_profile_fields');
    add_action('edit_user_profile_update', 'aplk_pd_save_extra_profile_fields');
    add_filter('user_profile_update_errors', 'aplk_pd_discount_profile_errors');
    add_action('woocommerce_after_checkout_billing_form', 'aplk_pd_discount_field');
    add_action('woocommerce_cart_calculate_fees', 'aplk_pd_woocommerce_custom_total');

    load_plugin_textdomain(APLK_PD_LANGUAGE_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

/**
 * Show personal discount profile field.
 *
 * @param WP_User $user
 */

function aplk_pd_show_extra_profile_fields(WP_User $user)
{
    if (current_user_can('edit_user', $user->ID)) { ?>
      <h3><?php _e('Additional Information', APLK_PD_LANGUAGE_DOMAIN) ?></h3>
      <table class="form-table">
        <tr>
          <th>
            <label for="discount"><?php _e('Personal discount on goods', APLK_PD_LANGUAGE_DOMAIN) ?></label>
          </th>
          <td>
            <input type="text" name="personal_discount" id="personal_discount"
                   value="<?php echo esc_attr(get_the_author_meta('discount', $user->ID)); ?>"
                   class="regular-text"/><br/>
            <span
              class="description"><?php _e('Please enter a number between 0 and 100 (%). Discount applies to non-participating products', APLK_PD_LANGUAGE_DOMAIN) ?></span>
              <?php wp_nonce_field('woocommerce_personal_discount', APLK_PD_NONCE); ?>
          </td>
        </tr>
      </table>
    <?php } ?>
<?php }


function aplk_pd_save_extra_profile_fields($user_id)
{
    if (!current_user_can('edit_user', $user_id))
        return false;
    if (!isset($_POST[APLK_PD_NONCE]) || !wp_verify_nonce($_POST[APLK_PD_NONCE], 'woocommerce_personal_discount')) {
        return false;
    }
    if ((isset($_POST['personal_discount']) && (float)$_POST['personal_discount'] >= 0 && (float)$_POST['personal_discount'] < 100)) {
        return update_user_meta($user_id, 'discount', (float)$_POST['personal_discount']);
    }
    return false;
}


function aplk_pd_discount_profile_errors($errors)
{
    if (!isset($_POST[APLK_PD_NONCE]) || !wp_verify_nonce($_POST[APLK_PD_NONCE], 'woocommerce_personal_discount')) {
        $errors->add('empty_missing_', '<strong>' . __('Security check failed!', APLK_PD_LANGUAGE_DOMAIN) . '</strong>:');
        return $errors;
    }
    if ((isset($_POST['personal_discount']) && (float)$_POST['personal_discount'] >= 0 && (float)$_POST['personal_discount'] < 100)) {
        return false;
    } else {
        $errors->add('empty_missing_', '<strong>' . __('Input error (Personalized goods discount field)', APLK_PD_LANGUAGE_DOMAIN) . '</strong>:' . __('Please enter a number from 0 to 100.', APLK_PD_LANGUAGE_DOMAIN) . '');
        return $errors;
    }
}


function aplk_pd_discount_field()
{
    $current_user = wp_get_current_user();
    $discount = $current_user->discount;
    if (!empty($discount) || $discount != 0) {
        echo '<div id="personal_discount">';
        echo '<strong>' . __('Your personal discount', APLK_PD_LANGUAGE_DOMAIN) . ': ' . $discount . '%</strong>';
        echo '</div>';
    }
}


function aplk_pd_woocommerce_custom_total()
{
    global $woocommerce;
    $current_user = wp_get_current_user();
    $discount = $current_user->discount;
    if (!empty($discount)) {
        if (!empty($woocommerce->cart->get_cart())) {
            $discount_total = 0;
            foreach ($woocommerce->cart->get_cart() as $product) {
                $product_data = $product['data']->get_data();
                if (!isset($product_data['sale_price']) || $product_data['sale_price'] != $product_data['price']) {
                    if ($product['line_total'] != 0) {
                        $discount_total += (-$product['line_total'] * $discount / 100);
                    }
                }
            }
            if (!empty($discount_total)) {
                $woocommerce->cart->add_fee(__('Personal discount', APLK_PD_LANGUAGE_DOMAIN), $discount_total, true, '');
            }
        }
        return true;
    }
    return false;
}


function aplk_pd_plugin_activation_error()
{
    $message = '<div class="error"><p>Woocommerce need to be at least 4.0 to activate and work  plugin "' . APLK_PD_NAME . '"</p></div>';
    echo $message;
}
