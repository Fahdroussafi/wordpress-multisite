<?php
/**
 * Woocommerce shop single customizer
 *
 * @package woostify
 */

if ( ! woostify_is_woocommerce_activated() ) {
	return;
}

// Default values.
$defaults = woostify_options();

// SHOP SINGLE STRUCTURE SECTION.
$wp_customize->add_setting(
	'shop_single_general_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'shop_single_general_section',
		array(
			'label'      => __( 'General', 'woostify' ),
			'section'    => 'woostify_shop_single',
			'dependency' => array(
				'woostify_setting[shop_single_breadcrumb]',
				'woostify_setting[shop_single_product_navigation]',
				'woostify_setting[shop_single_related_product]',
				'woostify_setting[shop_single_ajax_add_to_cart]',
				'woostify_setting[shop_single_stock_label]',
				'woostify_setting[shop_single_stock_product_limit]',
				'woostify_setting[shop_single_loading_bar]',
				'woostify_setting[shop_single_content_background]',
				'woostify_setting[shop_single_trust_badge_image]',
			),
		)
	)
);

// Breadcrumbs.
$wp_customize->add_setting(
	'woostify_setting[shop_single_breadcrumb]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_breadcrumb'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_breadcrumb]',
		array(
			'label'    => __( 'Breadcrumb', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_breadcrumb]',
		)
	)
);

// Product navigation.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_navigation]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_product_navigation'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_navigation]',
		array(
			'label'    => __( 'Product Navigation', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_product_navigation]',
		)
	)
);

// Ajax single add to cart.
$wp_customize->add_setting(
	'woostify_setting[shop_single_ajax_add_to_cart]',
	array(
		'default'           => $defaults['shop_single_ajax_add_to_cart'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_ajax_add_to_cart]',
		array(
			'label'    => __( 'Ajax Single Add To Cart', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_ajax_add_to_cart]',
		)
	)
);

// Stock label.
$wp_customize->add_setting(
	'woostify_setting[shop_single_stock_label]',
	array(
		'default'           => $defaults['shop_single_stock_label'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_stock_label]',
		array(
			'label'    => __( 'Stock Label', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_stock_label]',
		)
	)
);

// Loading Bar.
$wp_customize->add_setting(
	'woostify_setting[shop_single_loading_bar]',
	array(
		'default'           => $defaults['shop_single_loading_bar'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_loading_bar]',
		array(
			'label'    => __( 'Loading Bar', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_loading_bar]',
		)
	)
);

// Stock product limit.
$wp_customize->add_setting(
	'woostify_setting[shop_single_stock_product_limit]',
	array(
		'default'           => $defaults['shop_single_stock_product_limit'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_stock_product_limit]',
		array(
			'label'       => __( 'Min stock to show', 'woostify' ),
			'description' => __( 'Default = 0 show stock', 'woostify' ),
			'settings'    => 'woostify_setting[shop_single_stock_product_limit]',
			'section'     => 'woostify_shop_single',
			'type'        => 'number',
		)
	)
);

// Product content background.
$wp_customize->add_setting(
	'woostify_setting[shop_single_content_background]',
	array(
		'default'           => $defaults['shop_single_content_background'],
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[shop_single_content_background]',
		array(
			'label'    => __( 'Content Background', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => array(
				'woostify_setting[shop_single_content_background]',
			),
		)
	)
);

// Trust badge image.
$wp_customize->add_setting(
	'woostify_setting[shop_single_trust_badge_image]',
	array(
		'default'           => $defaults['shop_single_trust_badge_image'],
		'sanitize_callback' => 'esc_url_raw',
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'woostify_setting[shop_single_trust_badge_image]',
		array(
			'label'    => __( 'Trust Badge Image', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_trust_badge_image]',
		)
	)
);

// SHOP SINGLE PRODUCT IMAGE SECTION.
$wp_customize->add_setting(
	'shop_single_product_images_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'shop_single_product_images_section',
		array(
			'label'      => __( 'Product Images', 'woostify' ),
			'section'    => 'woostify_shop_single',
			'dependency' => array(
				'woostify_setting[shop_single_product_gallery_layout_select]',
				'woostify_setting[shop_single_gallery_layout]',
				'woostify_setting[shop_single_image_load]',
				'woostify_setting[shop_single_image_zoom]',
				'woostify_setting[shop_single_image_lightbox]',
				'woostify_setting[shop_single_product_sticky_top_space]',
				'woostify_setting[shop_single_product_sticky_bottom_space]',
			),
		)
	)
);

// Gallery Style.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_gallery_layout_select]',
	array(
		'default'           => $defaults['shop_single_product_gallery_layout_select'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_gallery_layout_select]',
		array(
			'label'    => __( 'Gallery Style', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_gallery_layout_select]',
			'section'  => 'woostify_shop_single',
			'type'     => 'select',
			'choices'  => array(
				'default' => __( 'Woocommerce Default', 'woostify' ),
				'theme'   => __( 'Theme', 'woostify' ),
			),
		)
	)
);

// Gallery layout.
$wp_customize->add_setting(
	'woostify_setting[shop_single_gallery_layout]',
	array(
		'default'           => $defaults['shop_single_gallery_layout'],
		'sanitize_callback' => 'woostify_sanitize_choices',
		'type'              => 'option',
	)
);

$wp_customize->add_control(
	new Woostify_Radio_Image_Control(
		$wp_customize,
		'woostify_setting[shop_single_gallery_layout]',
		array(
			'label'    => __( 'Gallery Layout', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_gallery_layout]',
			'choices'  => apply_filters(
				'woostify_setting_sidebar_default_choices',
				array(
					'vertical'   => WOOSTIFY_THEME_URI . 'assets/images/customizer/product-images/vertical.jpg',
					'horizontal' => WOOSTIFY_THEME_URI . 'assets/images/customizer/product-images/horizontal.jpg',
					'column'     => WOOSTIFY_THEME_URI . 'assets/images/customizer/product-images/column.jpg',
					'grid'       => WOOSTIFY_THEME_URI . 'assets/images/customizer/product-images/grid.jpg',
				)
			),
		)
	)
);

// Loading effect.
$wp_customize->add_setting(
	'woostify_setting[shop_single_image_load]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_image_load'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_image_load]',
		array(
			'label'    => __( 'Image Loading Effect', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_image_load]',
		)
	)
);

// Image zoom.
$wp_customize->add_setting(
	'woostify_setting[shop_single_image_zoom]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_image_zoom'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_image_zoom]',
		array(
			'label'    => __( 'Gallery Zoom Effect', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_image_zoom]',
		)
	)
);

// Image lightbox.
$wp_customize->add_setting(
	'woostify_setting[shop_single_image_lightbox]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_image_lightbox'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_image_lightbox]',
		array(
			'label'    => __( 'Gallery Lightbox Effect', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_image_lightbox]',
		)
	)
);

// Sticky top spacing.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_sticky_top_space]',
	array(
		'default'           => $defaults['shop_single_product_sticky_top_space'],
		'type'              => 'option',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_sticky_top_space]',
		array(
			'label'    => __( 'Top Space', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_sticky_top_space]',
			'section'  => 'woostify_shop_single',
			'type'     => 'number',
		)
	)
);

// Sticky bottom spacing.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_sticky_bottom_space]',
	array(
		'default'           => $defaults['shop_single_product_sticky_bottom_space'],
		'type'              => 'option',
		'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_sticky_bottom_space]',
		array(
			'label'    => __( 'Bottom Space', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_sticky_bottom_space]',
			'section'  => 'woostify_shop_single',
			'type'     => 'number',
		)
	)
);

// SHOP SINGLE PRODUCT META SECTION.
$wp_customize->add_setting(
	'shop_single_product_meta_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'shop_single_product_meta_section',
		array(
			'label'      => __( 'Product Meta', 'woostify' ),
			'section'    => 'woostify_shop_single',
			'dependency' => array(
				'woostify_setting[shop_single_skus]',
				'woostify_setting[shop_single_categories]',
				'woostify_setting[shop_single_tags]',
			),
		)
	)
);

// Sku.
$wp_customize->add_setting(
	'woostify_setting[shop_single_skus]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'default'           => $defaults['shop_single_skus'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_skus]',
		array(
			'label'    => __( 'SKU', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_skus]',
		)
	)
);

// Categories.
$wp_customize->add_setting(
	'woostify_setting[shop_single_categories]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'default'           => $defaults['shop_single_categories'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_categories]',
		array(
			'label'    => __( 'Categories', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_categories]',
		)
	)
);

// Tags.
$wp_customize->add_setting(
	'woostify_setting[shop_single_tags]',
	array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'default'           => $defaults['shop_single_tags'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_tags]',
		array(
			'label'    => __( 'Tags', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_tags]',
		)
	)
);

// SHOP SINGLE PRODUCT DATA TABS SECTION.
$wp_customize->add_setting(
	'shop_single_product_data_tabs_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'shop_single_product_data_tabs_section',
		array(
			'label'      => __( 'Product Data Tabs', 'woostify' ),
			'section'    => 'woostify_shop_single',
			'dependency' => array(
				'woostify_setting[shop_single_product_data_tabs_layout]',
				'woostify_setting[shop_single_product_data_tabs_pos]',
				'woostify_setting[shop_single_product_data_tabs_open]',
				'woostify_setting[shop_single_product_data_tabs_items]',
			),
		)
	)
);

// Product data tabs layout.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_data_tabs_layout]',
	array(
		'default'           => $defaults['shop_single_product_data_tabs_layout'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_data_tabs_layout]',
		array(
			'label'    => __( 'Layout', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_data_tabs_layout]',
			'section'  => 'woostify_shop_single',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_shop_single_product_data_tabs_layout_choices',
				array(
					'normal'    => __( 'Normal', 'woostify' ),
					'accordion' => __( 'Accordion', 'woostify' ),
				)
			),
		)
	)
);

// Product data tabs position.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_data_tabs_pos]',
	array(
		'default'           => $defaults['shop_single_product_data_tabs_pos'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_data_tabs_pos]',
		array(
			'label'    => __( 'Position', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_data_tabs_pos]',
			'section'  => 'woostify_shop_single',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_shop_single_product_data_tabs_pos_choices',
				array(
					'woocommerce_single_product_summary' => __( 'In Product Summary', 'woostify' ),
					'woocommerce_after_single_product_summary' => __( 'After Product Summary', 'woostify' ),
				)
			),
		)
	)
);

// Catalog mode.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_data_tabs_open]',
	array(
		'default'           => $defaults['shop_single_product_data_tabs_open'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_data_tabs_open]',
		array(
			'label'    => __( 'Open first tab by default', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_data_tabs_open]',
			'section'  => 'woostify_shop_single',
		)
	)
);


// Product data tabs items.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_data_tabs_items]',
	array(
		'default'           => $defaults['shop_single_product_data_tabs_items'],
		'sanitize_callback' => 'woostify_sanitize_json_string',
		'type'              => 'option',
	)
);

$wp_customize->add_control(
	new Woostify_Product_Data_Tabs_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_data_tabs_items]',
		array(
			'label'    => __( 'Items', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_product_data_tabs_items]',
		)
	)
);

// SHOP SINGLE RELATED PRODUCT SECTION.
$wp_customize->add_setting(
	'shop_single_product_related_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'shop_single_product_related_section',
		array(
			'label'      => __( 'Related Products', 'woostify' ),
			'section'    => 'woostify_shop_single',
			'dependency' => array(
				'woostify_setting[shop_single_related_product]',
				'woostify_setting[shop_single_product_related_total]',
				'woostify_setting[shop_single_product_related_columns]',
				'woostify_setting[shop_single_product_related_enable_carousel]',
				'woostify_setting[shop_single_product_related_carousel_arrows]',
				'woostify_setting[shop_single_product_related_carousel_dots]',
			),
		)
	)
);

// Product related.
$wp_customize->add_setting(
	'woostify_setting[shop_single_related_product]',
	array(
		'default'           => $defaults['shop_single_related_product'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_related_product]',
		array(
			'label'    => __( 'Display', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_related_product]',
			'section'  => 'woostify_shop_single',
		)
	)
);

// Related columns.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_related_columns]',
	array(
		'default'           => $defaults['shop_single_product_related_columns'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_choices',
	)
);

$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_related_columns]',
		array(
			'label'    => __( 'Columns', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_related_columns]',
			'section'  => 'woostify_shop_single',
			'type'     => 'select',
			'choices'  => apply_filters(
				'woostify_setting_shop_single_product_related_columns_choices',
				array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				)
			),
		)
	)
);

// Related product total.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_related_total]',
	array(
		'default'           => $defaults['shop_single_product_related_total'],
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_related_total]',
		array(
			'label'    => __( 'Total Products', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_related_total]',
			'section'  => 'woostify_shop_single',
			'type'     => 'number',
		)
	)
);

// Enable carousel.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_related_enable_carousel]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_product_related_enable_carousel'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_related_enable_carousel]',
		array(
			'label'    => __( 'Enable Carousel', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_product_related_enable_carousel]',
		)
	)
);

// Carousel arrows.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_related_carousel_arrows]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_product_related_carousel_arrows'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_related_carousel_arrows]',
		array(
			'label'    => __( 'Show Arrows', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_product_related_carousel_arrows]',
		)
	)
);

// Carousel dots.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_related_carousel_dots]',
	array(
		'type'              => 'option',
		'default'           => $defaults['shop_single_product_related_carousel_dots'],
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_related_carousel_dots]',
		array(
			'label'    => __( 'Show Dots', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_product_related_carousel_dots]',
		)
	)
);

// SHOP SINGLE RECENTLY VIEW SECTION.
$wp_customize->add_setting(
	'shop_single_recently_viewed_section',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'shop_single_recently_viewed_section',
		array(
			'label'      => __( 'Recently Viewed Products', 'woostify' ),
			'section'    => 'woostify_shop_single',
			'dependency' => array(
				'woostify_setting[shop_single_product_recently_viewed]',
				'woostify_setting[shop_single_recently_viewed_title]',
				'woostify_setting[shop_single_recently_viewed_count]',
			),
		)
	)
);

// Product recently viewed.
$wp_customize->add_setting(
	'woostify_setting[shop_single_product_recently_viewed]',
	array(
		'default'           => $defaults['shop_single_product_recently_viewed'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_checkbox',
	)
);
$wp_customize->add_control(
	new Woostify_Switch_Control(
		$wp_customize,
		'woostify_setting[shop_single_product_recently_viewed]',
		array(
			'label'    => __( 'Display', 'woostify' ),
			'settings' => 'woostify_setting[shop_single_product_recently_viewed]',
			'section'  => 'woostify_shop_single',
		)
	)
);

// Section title.
$wp_customize->add_setting(
	'woostify_setting[shop_single_recently_viewed_title]',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => $defaults['shop_single_recently_viewed_title'],
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_recently_viewed_title]',
		array(
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_recently_viewed_title]',
			'type'     => 'text',
			'label'    => __( 'Section Title', 'woostify' ),
		)
	)
);

// Total product.
$wp_customize->add_setting(
	'woostify_setting[shop_single_recently_viewed_count]',
	array(
		'sanitize_callback' => 'absint',
		'default'           => $defaults['shop_single_recently_viewed_count'],
		'type'              => 'option',
	)
);
$wp_customize->add_control(
	new WP_Customize_Control(
		$wp_customize,
		'woostify_setting[shop_single_recently_viewed_count]',
		array(
			'section'  => 'woostify_shop_single',
			'settings' => 'woostify_setting[shop_single_recently_viewed_count]',
			'type'     => 'number',
			'label'    => __( 'Total Product', 'woostify' ),
		)
	)
);

// SHOP SINGLE ADD TO CART.
$wp_customize->add_setting(
	'shop_single_product_button_cart',
	array(
		'sanitize_callback' => 'sanitize_text_field',
	)
);
$wp_customize->add_control(
	new Woostify_Section_Control(
		$wp_customize,
		'shop_single_product_button_cart',
		array(
			'label'      => __( 'Button Add To Cart', 'woostify' ),
			'section'    => 'woostify_shop_single',
			'dependency' => array(
				'woostify_setting[shop_single_button_cart_background]',
				'woostify_setting[shop_single_button_cart_color]',
				'woostify_setting[shop_single_button_background_hover]',
				'woostify_setting[shop_single_button_color_hover]',
				'woostify_setting[shop_single_button_border_radius]',
			),
		)
	)
);

// Button Background.
$wp_customize->add_setting(
	'woostify_setting[shop_single_button_cart_background]',
	array(
		'default'           => $defaults['shop_single_button_cart_background'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'transport'         => 'postMessage',
	)
);
// Button Hover Background.
$wp_customize->add_setting(
	'woostify_setting[shop_single_button_background_hover]',
	array(
		'default'           => $defaults['shop_single_button_background_hover'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[shop_single_button_cart_background]',
		array(
			'label'    => __( 'Background', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => array(
				'woostify_setting[shop_single_button_cart_background]',
				'woostify_setting[shop_single_button_background_hover]',
			),
			'tooltips' => array(
				'Normal',
				'Hover',
			),
		)
	)
);

// Button Color.
$wp_customize->add_setting(
	'woostify_setting[shop_single_button_cart_color]',
	array(
		'default'           => $defaults['shop_single_button_cart_color'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'transport'         => 'postMessage',
	)
);
// Button Hover Color.
$wp_customize->add_setting(
	'woostify_setting[shop_single_button_color_hover]',
	array(
		'default'           => $defaults['shop_single_button_color_hover'],
		'type'              => 'option',
		'sanitize_callback' => 'woostify_sanitize_rgba_color',
		'transport'         => 'postMessage',
	)
);
$wp_customize->add_control(
	new Woostify_Color_Group_Control(
		$wp_customize,
		'woostify_setting[shop_single_button_cart_color]',
		array(
			'label'    => __( 'Color', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => array(
				'woostify_setting[shop_single_button_cart_color]',
				'woostify_setting[shop_single_button_color_hover]',
			),
			'tooltips' => array(
				'Normal',
				'Hover',
			),
		)
	)
);

// Border radius.
$wp_customize->add_setting(
	'woostify_setting[shop_single_button_border_radius]',
	array(
		'default'           => $defaults['shop_single_button_border_radius'],
		'type'              => 'option',
		'sanitize_callback' => 'esc_html',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control(
	new Woostify_Range_Slider_Control(
		$wp_customize,
		'woostify_setting[shop_single_button_border_radius]',
		array(
			'label'    => __( 'Border Radius', 'woostify' ),
			'section'  => 'woostify_shop_single',
			'settings' => array(
				'desktop' => 'woostify_setting[shop_single_button_border_radius]',
			),
			'choices'  => array(
				'desktop' => array(
					'min'  => apply_filters( 'woostify_shop_single_button_border_radius_min_step', 0 ),
					'max'  => apply_filters( 'woostify_shop_single_button_border_radius_max_step', 50 ),
					'step' => 1,
					'edit' => true,
					'unit' => 'px',
				),
			),
		)
	)
);
