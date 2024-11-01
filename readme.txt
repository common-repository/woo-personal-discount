===WooCommerce Personal Discount===
Contributors: Applyke
Donate link: http://applyke.com/
Tags: woocommerce, discount, personal, customer, client, personally, user, percent, amount, checkout, calculate, decrease, cost, variable, simple, products, discounts, ecommerce, woo
Requires at least: 4.8
Tested up to: 4.8
Stable tag: 1.1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Personal discount for customer in WooCommerce.

== Description ==

Hi, there!

This WooCommerce Personal Discount plugin makes possible to add a personal discount for the WooCommerce client. After activating the plugin, edit the user profile.
In the "Additional information" part, specify the discount amount in percent (number from 0 to 100) and save the changes.
Discount will apply to goods are not participating in promotions.
During checkout, the discount will be calculated and displayed in the cart and the details of the order.

That's it!

== Installation ==

= Minimum Requirements =

* WordPress 4.8 or greater

= Automatic installation =

Use WordPress' plugin manager to find it in the WordPress plugin directory and directly install it from the WP plugin manager.
After installation, activate the plugin through the 'Plugins' menu in WordPress.

= Manual installation =

Use WordPress' plugin manager to upload the plugin's zip file.
After installation, activate the plugin through the 'Plugins' menu in WordPress.

= Updating =

Upgrades to new versions are automatically offered in the WordPress plugin page.

== Screenshots ==
1. WooCommerce Personal Discount section in Edit User page
2. Cart page with personal discount
3. Checkout page with personal discount

== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* Fixed issue with variable products

= 1.1.0 =
* Added icon and banner

= 1.1.1 =
* Updated version number

== Upgrade Notice ==

= 1.0.0 =
This is the first stable version.

= 1.0.1 =
Made changes in aplk_pd_woocommerce_custom_total() function, replaced calling wc_get_product_ids_on_sale() with comparing $product_data['sale_price'] and $product_data['price'].

= 1.1.0 =
* Added icon and banner

= 1.1.1 =
* Updated version number in woocommerce-personal-discount.php
