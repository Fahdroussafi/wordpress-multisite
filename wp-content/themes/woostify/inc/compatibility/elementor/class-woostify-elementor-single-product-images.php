<?php
/**
 * Elementor Single Product Images
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 */
class Woostify_Elementor_Single_Product_Images extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		if ( defined( 'WOOSTIFY_PRO_VERSION' ) ) {
			return array( 'woostify-product', 'woocommerce-elements-single' );
		} else {
			return array( 'general' );
		}
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-default-product-images';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Default Product Images', 'woostify' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-images';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'store', 'image', 'product', 'gallery', 'lightbox' );
	}

	/**
	 * Add a style.
	 */
	public function get_style_depends() {
		return array( 'elementor-frontend' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'woostify' ),
			)
		);

		$this->add_control(
			'woostify_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Default single product image', 'woostify' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		global $product;
		if ( woostify_is_elementor_editor() ) {
			if ( ! woostify_get_last_product_id() ) {
				return;
			}

			$GLOBALS['product'] = wc_get_product( woostify_get_last_product_id() );
		}

		if ( empty( $product ) ) {
			return;
		}

		woocommerce_show_product_sale_flash();
		woocommerce_show_product_images();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Single_Product_Images() );
