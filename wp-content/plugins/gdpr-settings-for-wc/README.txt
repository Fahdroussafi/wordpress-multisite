=== GDPR Settings for WooCommerce ===
Contributors: tecnicorgpd, salonsoweb, marinabrocca
Tags: rgpd, gdpr, privacy, woocommerce, cookies, legal, consent, ecommerce, GDPR, RGPD, RGPD WooCommerce, GDPR WooCommerce, privacidad, legalidad 
Requires at least: 5.0
Tested up to: 5.8.2
Stable tag: 1.2.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adapt your e-commerce to the GDPR rules.

This plugin allows you to easily add a check box to the woocommerce checkout to obtain the consent of the users before sending them promotions.

In addition, you can add the first layer of privacy before completing the checkout, as required by the RGPD.

== Description ==

## GDPR Promo consent

With RGPD/GPDR Settings for WooCommerce you can include an optional checkbox in your checkout to obtain your user's consent for send news and promotions later.

You can check the user consent in the admin order details and also in the admin new order email.

## GDPR top privacy layer

According GDPR rules you need to include a simple extract about your privacy policies near to your place order button.

With RGPD/GPDR Settings for WC you can customize this first privacy layer easily too.

## GDPR Settings tab

You can customize your GDPR texts under a new setting tab in the WooCommerce settings page

## Plugin compatibility

RGPD/GPDR Settings for WC will work with WooCommerce and all WordPress themes or any visual builder like Divi, Elementor, WPBakery, etc.

This plugin use native WooCommerce hooks for total compatibility.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory or install the plugin through the WordPress plugin panel.

2. Activate the plugin in the "Plugins" WordPress section.

3. You can customize your GDPR texts under a new setting tab in the WooCommerce settings page

== Changelog ==

= 1.2.1 =
* Fix: duplicate field

= 1.2.0 =
* Fix: added suport for WordPress 5.5.1 and WooCommerce 4.5.x
* Fix: improve styles

= 1.1.0 =
* Fix: readme language bugs
* New: action wc_gdprpromo_after_user_consent added. When a user accept GDPR promo checkbox, execute custom actions in woo_commerce_checkout_update_order_meta 

= 1.0.0 =
* First version.
