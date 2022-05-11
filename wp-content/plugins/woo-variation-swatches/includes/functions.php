<?php

defined( 'ABSPATH' ) or die( 'Keep Quit' );

//-------------------------------------------------------------------------------
// Detecting IE 11 Browser
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_is_ie11' ) ):
	function wvs_is_ie11() {
		global $is_IE;

		if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return false;
		}

		$ua   = $_SERVER['HTTP_USER_AGENT'];
		$is11 = preg_match( "/Trident\/7.0;(.*)rv:11.0/", $ua, $match ) !== false;

		return $is_IE && $is11;
		//return TRUE;
	}
endif;

/**
 * Returns the translated object ID (post_type or term) or original if missing
 *
 * @param $object_id integer|string|array The ID/s of the objects to check and return
 * @param $type      the object type: post, page, {custom post type name}, nav_menu, nav_menu_item, category, tag etc.
 *
 * @return string or array of object ids
 */

// https://wpml.org/wpml-hook/wpml_object_id/
if ( ! function_exists( 'wvs_wpml_object_id' ) ) {
	function wvs_wpml_object_id( $object_id, $type = 'post', $language = null ) {
		$current_language = apply_filters( 'wpml_current_language', $language );

		return apply_filters( 'wpml_object_id', $object_id, $type, true, $current_language );
	}
}

//-------------------------------------------------------------------------------
// Get All Image Sizes if wp_get_registered_image_subsizes function not available
//-------------------------------------------------------------------------------
/**
 * Returns a normalized list of all currently registered image sub-sizes.
 *
 * @return array Associative array of the registered image sub-sizes.
 * @since WP 5.3.0
 *
 */
if ( ! function_exists( 'wp_get_registered_image_subsizes' ) ):
	function wp_get_registered_image_subsizes() {
		$additional_sizes = wp_get_additional_image_sizes();
		$all_sizes        = array();

		foreach ( get_intermediate_image_sizes() as $size_name ) {
			$size_data = array(
				'width'  => 0,
				'height' => 0,
				'crop'   => false,
			);

			if ( isset( $additional_sizes[ $size_name ]['width'] ) ) {
				// For sizes added by plugins and themes.
				$size_data['width'] = intval( $additional_sizes[ $size_name ]['width'] );
			} else {
				// For default sizes set in options.
				$size_data['width'] = intval( get_option( "{$size_name}_size_w" ) );
			}

			if ( isset( $additional_sizes[ $size_name ]['height'] ) ) {
				$size_data['height'] = intval( $additional_sizes[ $size_name ]['height'] );
			} else {
				$size_data['height'] = intval( get_option( "{$size_name}_size_h" ) );
			}

			if ( empty( $size_data['width'] ) && empty( $size_data['height'] ) ) {
				// This size isn't set.
				continue;
			}

			if ( isset( $additional_sizes[ $size_name ]['crop'] ) ) {
				$size_data['crop'] = $additional_sizes[ $size_name ]['crop'];
			} else {
				$size_data['crop'] = get_option( "{$size_name}_crop" );
			}

			if ( ! is_array( $size_data['crop'] ) || empty( $size_data['crop'] ) ) {
				$size_data['crop'] = wc_string_to_bool( $size_data['crop'] );
			}

			$all_sizes[ $size_name ] = $size_data;
		}

		return $all_sizes;
	}
endif;


if ( ! function_exists( 'wvs_get_all_image_sizes' ) ):
	function wvs_get_all_image_sizes() {

		$image_subsizes = wp_get_registered_image_subsizes();

		return apply_filters(
			'wvs_get_all_image_sizes', array_reduce(
				array_keys( $image_subsizes ), function ( $carry, $item ) use ( $image_subsizes ) {

				$title  = ucwords( str_ireplace( array( '-', '_' ), ' ', $item ) );
				$width  = $image_subsizes[ $item ]['width'];
				$height = $image_subsizes[ $item ]['height'];

				$carry[ $item ] = sprintf( '%s (%d &times; %d)', $title, $width, $height );

				return $carry;
			}, array()
			)
		);
	}
endif;


//-------------------------------------------------------------------------------
// Available Product Attribute Types
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_available_attributes_types' ) ):
	function wvs_available_attributes_types( $type = false ) {
		$types = array();

		$types['color'] = array(
			'title'   => esc_html__( 'Color', 'woo-variation-swatches' ),
			'output'  => 'wvs_color_variation_attribute_options',
			'preview' => 'wvs_color_variation_attribute_preview'
		);

		$types['image'] = array(
			'title'   => esc_html__( 'Image', 'woo-variation-swatches' ),
			'output'  => 'wvs_image_variation_attribute_options',
			'preview' => 'wvs_image_variation_attribute_preview'
		);

		$types['button'] = array(
			'title'   => esc_html__( 'Button', 'woo-variation-swatches' ),
			'output'  => 'wvs_button_variation_attribute_options',
			'preview' => 'wvs_button_variation_attribute_preview'
		);

		$types = apply_filters( 'wvs_available_attributes_types', $types );

		if ( $type ) {
			return isset( $types[ $type ] ) ? $types[ $type ] : array();
		}

		return $types;
	}
endif;

//-------------------------------------------------------------------------------
// Color Variation Preview
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_color_variation_attribute_preview' ) ):
	function wvs_color_variation_attribute_preview( $term_id, $attribute, $fields ) {

		$key   = $fields[0]['id'];
		$value = sanitize_hex_color( get_term_meta( $term_id, $key, true ) );

		printf( '<div class="wvs-preview wvs-color-preview" style="background-color:%s;"></div>', esc_attr( $value ) );
	}
endif;

//-------------------------------------------------------------------------------
// Image Variation Preview
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_image_variation_attribute_preview' ) ):
	function wvs_image_variation_attribute_preview( $term_id, $attribute, $fields ) {

		$key           = $fields[0]['id'];
		$attachment_id = absint( get_term_meta( $term_id, $key, true ) );
		$image         = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
		if ( is_array( $image ) ) {
			printf( '<img src="%s" alt="" width="%d" height="%d" class="wvs-preview wvs-image-preview" />', esc_url( $image[0] ), $image[1], $image[2] );
		}
	}
endif;

//-------------------------------------------------------------------------------
// Add attribute types on WooCommerce taxonomy
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_product_attributes_types' ) ):
	function wvs_product_attributes_types( $selector ) {

		foreach ( wvs_available_attributes_types() as $key => $options ) {
			$selector[ $key ] = $options['title'];
		}

		return $selector;
	}
endif;

//-------------------------------------------------------------------------------
// Tutorials Tab Contents
//-------------------------------------------------------------------------------
if ( ! function_exists( 'wvs_tutorial_tab_contents' ) ):
	function wvs_tutorial_tab_contents() {
		ob_start();
		include_once woo_variation_swatches()->include_path( 'tutorials.php' );

		return ob_get_clean();
	}
endif;

//-------------------------------------------------------------------------------
// Enable Ajax Variation
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_ajax_variation_threshold' ) ):
	function wvs_ajax_variation_threshold() {
		return absint( woo_variation_swatches()->get_option( 'threshold' ) );
	}
endif;


/*function wvs_get_available_product_variations() {

	if ( is_ajax() && isset( $_POST['product_id'] ) ) {
		$product_id = absint( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );

		if ( $product && $product->is_visible() && $product->is_type( 'variable' ) ) {
			$available_variations = apply_filters( 'wvs_get_available_product_variations', $product->get_available_variations(), $product );
		} else {
			$available_variations = array();
		}

		wp_send_json_success( wp_json_encode( $available_variations ) );
	} else {
		wp_send_json_error();
	}
}*/


// Tutorials TAB

add_action(
	'after_wvs_settings', function ( $swatches ) {
	$swatches->add_setting(
		'tutorial', esc_html__( 'Tutorials', 'woo-variation-swatches' ), array(
		array(
			'pro'    => true,
			'title'  => esc_html__( 'How to tutorials', 'woo-variation-swatches' ),
			'desc'   => esc_html__( 'How to setup and use this plugin', 'woo-variation-swatches' ),
			'fields' => apply_filters(
				'wvs_pro_large_catalog_setting_fields', array(
					array(
						'pro'  => true,
						'html' => wvs_tutorial_tab_contents(),
					),
				)
			)
		)
	), apply_filters( 'wvs_tutorial_setting_default_active', false )
	);
}, 50
);

//-------------------------------------------------------------------------------
// Add settings
// Add Theme Support:
// add_theme_support( 'woo-variation-swatches', array( 'tooltip' => FALSE, 'stylesheet' => FALSE, 'style'=>'rounded' ) );
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_settings' ) ):
	function wvs_settings() {

		do_action( 'before_wvs_settings', woo_variation_swatches() );

		woo_variation_swatches()->add_setting(
			'simple', esc_html__( 'Simple', 'woo-variation-swatches' ), apply_filters(
			'wvs_simple_settings_section', array(
				array(
					'title'  => esc_html__( 'Visual Section', 'woo-variation-swatches' ),
					'desc'   => esc_html__( 'Simple change some visual styles', 'woo-variation-swatches' ),
					'fields' => apply_filters(
						'wvs_simple_setting_fields', array(
							array(
								'id'      => 'tooltip',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Tooltip', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Enable tooltip on each product attribute.', 'woo-variation-swatches' ),
								'default' => true
							),
							array(
								'id'      => 'stylesheet',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Enable Stylesheet', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Enable default stylesheet', 'woo-variation-swatches' ),
								'default' => true
							),
							array(
								'id'      => 'style',
								'type'    => 'radio',
								'title'   => esc_html__( 'Shape style', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Attribute Shape Style', 'woo-variation-swatches' ),
								'options' => array(
									'rounded' => esc_html__( 'Rounded Shape', 'woo-variation-swatches' ),
									'squared' => esc_html__( 'Squared Shape', 'woo-variation-swatches' )
								),
								'default' => 'squared'
							),
							array(
								'id'      => 'default_to_button',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Auto Dropdowns to Button', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Convert default dropdowns to button type', 'woo-variation-swatches' ),
								'default' => true
							),
						)
					)
				)
			)
		), apply_filters( 'wvs_simple_setting_default_active', true )
		);

		woo_variation_swatches()->add_setting(
			'advanced', esc_html__( 'Advanced', 'woo-variation-swatches' ), apply_filters(
			'wvs_advanced_settings_section', array(
				array(
					'title'  => esc_html__( 'Visual Section', 'woo-variation-swatches' ),
					'desc'   => esc_html__( 'Advanced change some visual styles', 'woo-variation-swatches' ),
					'fields' => apply_filters(
						'wvs_advanced_setting_fields', array(
							array(
								'id'      => 'clear_on_reselect',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Clear on Reselect', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Clear selected attribute on select again', 'woo-variation-swatches' ),
								'default' => false
							),
							array(
								'id'      => 'threshold',
								'type'    => 'number',
								'title'   => esc_html__( 'Ajax variation threshold', 'woo-variation-swatches' ),
								'desc'    => __( 'Control the number of enable ajax variation threshold, If you set <code>1</code> all product variation will be load via ajax. Default value is <code>30</code>, <br><span style="color: red">Note: Disable ajax threshold doesn\'t follow attribute behaviour. It\'s recommended to keep this number between 30 - 40.</span>', 'woo-variation-swatches' ),
								'default' => 30,
								'min'     => 1,
								'max'     => 80,
								'require' => array( 'disable_threshold' => array( 'type' => 'empty' ) )
							),

							array(
								'id'      => 'show_variation_label',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Show selected attribute', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Show selected attribute variation name beside the title', 'woo-variation-swatches' ),
								'default' => true,
								'is_new'  => true
							),

							array(
								'id'      => 'variation_label_separator',
								'type'    => 'text',
								'title'   => esc_html__( 'Variation label separator', 'woo-variation-swatches' ),
								'desc'    => sprintf( __( 'Variation label separator. Default: %s.', 'woo-variation-swatches' ), '<code>:</code>' ),
								'default' => ':',
								'is_new'  => true,
								'require' => array( 'show_variation_label' => array( 'type' => '==', 'value' => '1' ) )
							),

							array(
								'id'      => 'attribute_behavior',
								'type'    => 'radio',
								'title'   => esc_html__( 'Attribute behavior', 'woo-variation-swatches' ),
								'desc'    => sprintf( __( 'Disabled attribute will be hide / blur. %sNote: Disable ajax threshold doesn\'t apply this feature.%s', 'woo-variation-swatches' ), '<br><span style="color: red">', '</span>' ),
								'options' => array(
									'blur'          => esc_html__( 'Blur with cross', 'woo-variation-swatches' ),
									'blur-no-cross' => esc_html__( 'Blur without cross', 'woo-variation-swatches' ),
									'hide'          => esc_html__( 'Hide', 'woo-variation-swatches' ),
								),
								'default' => 'blur'
							),

							array(
								'id'      => 'attribute_image_size',
								'type'    => 'select',
								'title'   => esc_html__( 'Attribute image size', 'woo-variation-swatches' ),
								'desc'    => has_filter( 'wvs_product_attribute_image_size' ) ? __( '<span style="color: red">Attribute image size changed by <code>wvs_product_attribute_image_size</code> hook. So this option will not apply any effect.</span>', 'woo-variation-swatches' ) : __( sprintf( 'Choose attribute image size. <a target="_blank" href="%s">Media Settings</a>', esc_url( admin_url( 'options-media.php' ) ) ), 'woo-variation-swatches' ),
								'options' => wvs_get_all_image_sizes(),
								'default' => 'thumbnail'
							),
							array(
								'id'      => 'width',
								'type'    => 'number',
								'title'   => esc_html__( 'Width', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Variation item width', 'woo-variation-swatches' ),
								'default' => 30,
								'min'     => 10,
								'max'     => 200,
								'suffix'  => 'px'
							),
							array(
								'id'      => 'height',
								'type'    => 'number',
								'title'   => esc_html__( 'Height', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Variation item height', 'woo-variation-swatches' ),
								'default' => 30,
								'min'     => 10,
								'max'     => 200,
								'suffix'  => 'px'
							),
							array(
								'id'      => 'single_font_size',
								'type'    => 'number',
								'title'   => esc_html__( 'Font Size', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Single product variation item font size', 'woo-variation-swatches' ),
								'default' => 16,
								'min'     => 8,
								'max'     => 24,
								'suffix'  => 'px'
							)
						)
					)
				)
			)
		), apply_filters( 'wvs_advanced_setting_default_active', false )
		);

		woo_variation_swatches()->add_setting(
			'performance', esc_html__( 'Performance', 'woo-variation-swatches' ), apply_filters(
			'wvs_performance_settings_section', array(
				array(
					'title'  => esc_html__( 'Performance Section', 'woo-variation-swatches' ),
					'desc'   => esc_html__( 'Change for Performance', 'woo-variation-swatches' ),
					'fields' => apply_filters(
						'wvs_performance_setting_fields', array(
							array(
								'id'      => 'defer_load_js',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Defer Load JS', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Defer Load JS for PageSpeed Score. If you use any caching plugin or your server have HTTP2 support you do not have to use it', 'woo-variation-swatches' ),
								'default' => false
							),
							array(
								'id'      => 'use_transient',
								'type'    => 'checkbox',
								'title'   => esc_html__( 'Use Transient Cache', 'woo-variation-swatches' ),
								'desc'    => esc_html__( 'Use Transient Cache for PageSpeed Score. If you use any caching plugin you do not have to use it', 'woo-variation-swatches' ),
								'default' => false
							)
						)
					)
				)
			)
		), apply_filters( 'wvs_advanced_setting_default_active', false )
		);

		if ( ! woo_variation_swatches()->is_pro_active() ) {
			woo_variation_swatches()->add_setting(
				'style', esc_html__( 'Style', 'woo-variation-swatches' ), array(
				array(
					'pro'    => true,
					'title'  => esc_html__( 'Visual Styling', 'woo-variation-swatches' ),
					'desc'   => esc_html__( 'Change some visual styles', 'woo-variation-swatches' ),
					'fields' => apply_filters(
						'wvs_pro_style_setting_fields', array(
							array(
								'pro'          => true,
								'width'        => 'auto',
								'screen_shot'  => woo_variation_swatches()->wp_images_uri( 'red-style-preview.png' ),
								'product_link' => woo_variation_swatches()->get_pro_link( 'style-tab' ),
							),
						)
					)
				)
			), apply_filters( 'wvs_pro_style_setting_default_active', false ), true
			);
		}

		if ( ! woo_variation_swatches()->is_pro_active() ) {
			woo_variation_swatches()->add_setting(
				'archive', esc_html__( 'Archive / Shop', 'woo-variation-swatches' ), array(
				array(
					'pro'    => true,
					'title'  => esc_html__( 'Visual Section', 'woo-variation-swatches' ),
					'desc'   => esc_html__( 'Advanced change some visual styles on shop / archive page', 'woo-variation-swatches' ),
					'fields' => apply_filters(
						'wvs_pro_archive_setting_fields', array(
							array(
								'pro'          => true,
								'width'        => 'auto',
								'screen_shot'  => woo_variation_swatches()->wp_images_uri( 'red-archive-preview.png' ),
								'product_link' => woo_variation_swatches()->get_pro_link( 'archive-tab' ),
							),
						)
					)
				)
			), apply_filters( 'wvs_pro_archive_setting_default_active', false ), true
			);
		}

		if ( ! woo_variation_swatches()->is_pro_active() ) {
			woo_variation_swatches()->add_setting(
				'special', esc_html__( 'Special Attribute', 'woo-variation-swatches' ), array(
				array(
					'pro'    => true,
					'title'  => esc_html__( 'Catalog mode', 'woo-variation-swatches' ),
					'desc'   => esc_html__( 'Show single attribute as catalog mode on shop / archive pages', 'woo-variation-swatches' ),
					'fields' => apply_filters(
						'wvs_pro_large_catalog_setting_fields', array(
							array(
								'pro'          => true,
								'width'        => 'auto',
								'screen_shot'  => woo_variation_swatches()->wp_images_uri( 'red-special-preview.png' ),
								'product_link' => woo_variation_swatches()->get_pro_link( 'special-tab' ),
							),
						)
					)
				)
			), apply_filters( 'wvs_pro_special_setting_default_active', false ), true
			);
		}

		do_action( 'after_wvs_settings', woo_variation_swatches() );
	}
endif;

//-------------------------------------------------------------------------------
// WooCommerce taxonomy Meta Field Settings
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_taxonomy_meta_fields' ) ):
	function wvs_taxonomy_meta_fields( $field_id = false ) {

		$fields = array();

		$fields['color'] = array(
			array(
				'label' => esc_html__( 'Color', 'woo-variation-swatches' ), // <label>
				'desc'  => esc_html__( 'Choose a color', 'woo-variation-swatches' ), // description
				'id'    => 'product_attribute_color', // name of field
				'type'  => 'color'
			)
		);

		$fields['image'] = array(
			array(
				'label' => esc_html__( 'Image', 'woo-variation-swatches' ), // <label>
				'desc'  => esc_html__( 'Choose an Image', 'woo-variation-swatches' ), // description
				'id'    => 'product_attribute_image', // name of field
				'type'  => 'image'
			)
		);

		$fields = apply_filters( 'wvs_product_taxonomy_meta_fields', $fields );

		if ( $field_id ) {
			return isset( $fields[ $field_id ] ) ? $fields[ $field_id ] : array();
		}

		return $fields;

	}
endif;

//-------------------------------------------------------------------------------
// Is Color Attribute
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_is_color_attribute' ) ):
	function wvs_is_color_attribute( $attribute ) {
		if ( ! is_object( $attribute ) ) {
			return false;
		}

		return $attribute->attribute_type == 'color';
	}
endif;

//-------------------------------------------------------------------------------
// Is Image Attribute
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_is_image_attribute' ) ):
	function wvs_is_image_attribute( $attribute ) {
		if ( ! is_object( $attribute ) ) {
			return false;
		}

		return $attribute->attribute_type == 'image';
	}
endif;

//-------------------------------------------------------------------------------
// Is Button Attribute
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_is_button_attribute' ) ):
	function wvs_is_button_attribute( $attribute ) {
		if ( ! is_object( $attribute ) ) {
			return false;
		}

		return $attribute->attribute_type == 'button';
	}
endif;

//-------------------------------------------------------------------------------
// Is Radio Attribute
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_is_radio_attribute' ) ):
	function wvs_is_radio_attribute( $attribute ) {
		if ( ! is_object( $attribute ) ) {
			return false;
		}

		return $attribute->attribute_type == 'radio';
	}
endif;

//-------------------------------------------------------------------------------
// Is Select Attribute
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_is_select_attribute' ) ):
	function wvs_is_select_attribute( $attribute ) {
		if ( ! is_object( $attribute ) ) {
			return false;
		}

		return $attribute->attribute_type == 'select';
	}
endif;

//-------------------------------------------------------------------------------
// Get Color Attribute Value
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_get_product_attribute_color' ) ):
	function wvs_get_product_attribute_color( $term ) {
		if ( ! is_object( $term ) ) {
			return false;
		}

		return get_term_meta( $term->term_id, 'product_attribute_color', true );
	}
endif;

if ( ! function_exists( 'wvs_get_product_attribute_dual_color' ) ):
	function wvs_get_product_attribute_dual_color( $term ) {
		if ( ! is_object( $term ) ) {
			return false;
		}

		$is_dual_color   = wc_string_to_bool( get_term_meta( $term->term_id, 'is_dual_color', true ) );
		$primary_color   = sanitize_hex_color( get_term_meta( $term->term_id, 'product_attribute_color', true ) );
		$secondary_color = sanitize_hex_color( get_term_meta( $term->term_id, 'secondary_color', true ) );

		if ( $is_dual_color ) {
			return array(
				'primary_color'   => $primary_color,
				'secondary_color' => $secondary_color
			);
		}

		return $primary_color;
	}
endif;

//-------------------------------------------------------------------------------
// Get Image Attribute Value
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_get_product_attribute_image' ) ):
	function wvs_get_product_attribute_image( $term ) {
		if ( ! is_object( $term ) ) {
			return false;
		}

		return get_term_meta( $term->term_id, 'product_attribute_image', true );
	}
endif;

//-------------------------------------------------------------------------------
// Add WooCommerce taxonomy Meta
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_add_product_taxonomy_meta' ) ) {
	function wvs_add_product_taxonomy_meta() {

		$fields         = wvs_taxonomy_meta_fields();
		$meta_added_for = apply_filters( 'wvs_product_taxonomy_meta_for', array_keys( $fields ) );

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ):

			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( $attribute_taxonomies ) :
				foreach ( $attribute_taxonomies as $tax ) :
					$product_attr      = wc_attribute_taxonomy_name( $tax->attribute_name );
					$product_attr_type = $tax->attribute_type;
					if ( in_array( $product_attr_type, $meta_added_for ) ) :
						woo_variation_swatches()->add_term_meta( $product_attr, 'product', $fields[ $product_attr_type ] );

						do_action( 'wvs_wc_attribute_taxonomy_meta_added', $product_attr, $product_attr_type );
					endif; //  in_array( $product_attr_type, array( 'color', 'image' ) )
				endforeach; // $attribute_taxonomies
			endif; // $attribute_taxonomies
		endif; // function_exists( 'wc_get_attribute_taxonomies' )
	}
}

//-------------------------------------------------------------------------------
// Extra Product Option Terms
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_product_option_terms_old' ) ) :
	function wvs_product_option_terms_old( $attribute_taxonomy, $i ) {
		// $attribute_taxonomy, $i
		// $tax, $i
		global $post, $thepostid, $product_object;
		if ( in_array( $attribute_taxonomy->attribute_type, array_keys( wvs_available_attributes_types() ) ) ) {

			$taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );

			$product_id = $thepostid;

			if ( is_null( $thepostid ) && isset( $_POST['post_id'] ) ) {
				$product_id = absint( $_POST['post_id'] );
			}

			$args = array(
				'orderby'    => 'name',
				'hide_empty' => 0,
			);

			?>
			<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'woo-variation-swatches' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
				<?php
				$all_terms = get_terms( $taxonomy, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
				if ( $all_terms ) :
					foreach ( $all_terms as $term ) :
						echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
					endforeach;
				endif;
				?>
			</select>
			<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'woo-variation-swatches' ); ?></button>
			<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'woo-variation-swatches' ); ?></button>

			<?php
			$fields = wvs_taxonomy_meta_fields( $attribute_taxonomy->attribute_type );

			if ( ! empty( $fields ) ): ?>
				<button disabled="disabled" class="button fr plus wvs_add_new_attribute" data-dialog_title="<?php printf( esc_html__( 'Add new %s', 'woo-variation-swatches' ), esc_attr( $attribute_taxonomy->attribute_label ) ) ?>"><?php esc_html_e( 'Add new', 'woo-variation-swatches' ); ?></button>
			<?php else: ?>
				<button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'woo-variation-swatches' ); ?></button>
			<?php endif; ?>
			<?php
		}
	}
endif;

//-------------------------------------------------------------------------------
// Dokan Support - OLD WC Style
//-------------------------------------------------------------------------------
if ( ! function_exists( 'dokan_support_wvs_product_option_terms' ) ) :
	function dokan_support_wvs_product_option_terms( $attribute_taxonomy, $i ) {
		// $attribute_taxonomy, $i
		// $tax, $i
		global $post, $thepostid, $product_object;
		if ( in_array( $attribute_taxonomy->attribute_type, array_keys( wvs_available_attributes_types() ) ) ) {

			$taxonomy = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );

			$product_id = $thepostid;

			if ( is_null( $thepostid ) && isset( $_POST['post_id'] ) ) {
				$product_id = absint( $_POST['post_id'] );
			}

			$args = array(
				'orderby'    => 'name',
				'hide_empty' => 0,
			);

			?>
			<select multiple="multiple" style="width:100%" data-placeholder="<?php esc_attr_e( 'Select terms', 'woo-variation-swatches' ); ?>" class="dokan_attribute_values dokan-select2" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
				<?php
				$all_terms = get_terms( $taxonomy, apply_filters( 'dokan_product_attribute_terms', $args ) );
				if ( $all_terms ) :
					foreach ( $all_terms as $term ) :
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
					endforeach;
				endif;
				?>
			</select>

			<div class="dokan-pre-defined-attribute-btn-group">
				<button class="dokan-btn dokan-btn-default plus dokan-select-all-attributes"><?php esc_html_e( 'Select all', 'woo-variation-swatches' ); ?></button>
				<button class="dokan-btn dokan-btn-default minus dokan-select-no-attributes"><?php esc_html_e( 'Select none', 'woo-variation-swatches' ); ?></button>
			</div>
			<?php
		}
	}
endif;

//-------------------------------------------------------------------------------
// Extra Product Option Terms for WC 3.6+
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_product_option_terms' ) ) :
	function wvs_product_option_terms( $attribute_taxonomy, $i, $attribute ) {
		if ( in_array( $attribute_taxonomy->attribute_type, array_keys( wvs_available_attributes_types() ) ) ) {

			?>
			<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'woo-variation-swatches' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
				<?php
				$args      = array(
					'orderby'    => 'name',
					'hide_empty' => 0,
				);
				$all_terms = get_terms( $attribute->get_taxonomy(), apply_filters( 'woocommerce_product_attribute_terms', $args ) );
				if ( $all_terms ) {
					foreach ( $all_terms as $term ) {
						$options = $attribute->get_options();
						$options = ! empty( $options ) ? $options : array();
						echo '<option value="' . esc_attr( $term->term_id ) . '"' . wc_selected( $term->term_id, $options ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
					}
				}
				?>
			</select>
			<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'woo-variation-swatches' ); ?></button>
			<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'woo-variation-swatches' ); ?></button>

			<?php
			$fields = wvs_taxonomy_meta_fields( $attribute_taxonomy->attribute_type );

			if ( ! empty( $fields ) ): ?>
				<button disabled="disabled" class="button fr plus wvs_add_new_attribute" data-dialog_title="<?php printf( esc_html__( 'Add new %s', 'woo-variation-swatches' ), esc_attr( $attribute_taxonomy->attribute_label ) ) ?>"><?php esc_html_e( 'Add new', 'woo-variation-swatches' ); ?></button>
			<?php else: ?>
				<button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'woo-variation-swatches' ); ?></button>
			<?php endif; ?>
			<?php
		}
	}
endif;

//-------------------------------------------------------------------------------
// Get a Attribute taxonomy values
//-------------------------------------------------------------------------------

// @TODO: See wc_attribute_taxonomy_id_by_name function and wc_get_attribute or wc_get_attribute_taxonomies

if ( ! function_exists( 'wvs_get_wc_attribute_taxonomy' ) ):
	function wvs_get_wc_attribute_taxonomy( $attribute_name ) {

		$transient_name = sprintf( 'wvs_attribute_taxonomy_%s', $attribute_name );

		$cache = new Woo_Variation_Swatches_Cache( $transient_name, 'wvs_attribute_taxonomy' );

		if ( isset( $_GET['wvs_clear_transient'] ) ) {
			$cache->delete_transient();
		}

		if ( false === ( $attribute_taxonomy = $cache->get_transient() ) ) {

			global $wpdb;

			$attribute_name = str_replace( 'pa_', '', wc_sanitize_taxonomy_name( $attribute_name ) );

			$attribute_taxonomy = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name='{$attribute_name}'" );

			$cache->set_transient( $attribute_taxonomy );
		}

		return apply_filters( 'wvs_get_wc_attribute_taxonomy', $attribute_taxonomy, $attribute_name );
	}
endif;

//-------------------------------------------------------------------------------
// Check has attribute type like color or image etc.
//-------------------------------------------------------------------------------
if ( ! function_exists( 'wvs_wc_product_has_attribute_type' ) ):
	function wvs_wc_product_has_attribute_type( $type, $attribute_name ) {

		$attributes           = wc_get_attribute_taxonomies();
		$attribute_name_clean = str_replace( 'pa_', '', wc_sanitize_taxonomy_name( $attribute_name ) );

		// Created Attribute
		if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {

			$attribute = array_values(
				array_filter(
					$attributes, function ( $attribute ) use ( $type, $attribute_name_clean ) {
					return $attribute_name_clean === $attribute->attribute_name;
				}
				)
			);

			if ( ! empty( $attribute ) ) {
				$attribute = apply_filters( 'wvs_get_wc_attribute_taxonomy', $attribute[0], $attribute_name );
			} else {
				$attribute = wvs_get_wc_attribute_taxonomy( $attribute_name );
			}

			return apply_filters( 'wvs_wc_product_has_attribute_type', ( isset( $attribute->attribute_type ) && ( $attribute->attribute_type == $type ) ), $type, $attribute_name, $attribute );
		} else {
			return apply_filters( 'wvs_wc_product_has_attribute_type', false, $type, $attribute_name, null );
		}
	}
endif;

//-------------------------------------------------------------------------------
// Variation attribute options wrapper
//-------------------------------------------------------------------------------
if ( ! function_exists( 'wvs_variable_items_wrapper' ) ):
	function wvs_variable_items_wrapper( $contents, $type, $args, $saved_attribute = array() ) {

		$attribute = $args['attribute'];
		$options   = $args['options'];

		$css_classes = apply_filters( 'wvs_variable_items_wrapper_class', array( "{$type}-variable-wrapper" ), $type, $args, $saved_attribute );

		$clear_on_reselect = woo_variation_swatches()->get_option( 'clear_on_reselect' ) ? 'reselect-clear' : '';

		array_push( $css_classes, $clear_on_reselect );

		// <div aria-live="polite" aria-atomic="true" class="screen-reader-text">%1$s: <span data-default=""></span></div>
		$data = sprintf( '<ul role="radiogroup" aria-label="%1$s"  class="variable-items-wrapper %2$s" data-attribute_name="%3$s" data-attribute_values="%4$s">%5$s</ul>', esc_attr( wc_attribute_label( $attribute ) ), trim( implode( ' ', array_unique( $css_classes ) ) ), esc_attr( wc_variation_attribute_name( $attribute ) ), wc_esc_json( wp_json_encode( array_values( $options ) ) ), $contents );

		return apply_filters( 'wvs_variable_items_wrapper', $data, $contents, $type, $args, $saved_attribute );
	}
endif;

//-------------------------------------------------------------------------------
// Variation variable item
//-------------------------------------------------------------------------------
if ( ! function_exists( 'wvs_variable_item' ) ):
	function wvs_variable_item( $type, $options, $args, $saved_attribute = array() ) {

		$product   = $args['product'];
		$attribute = $args['attribute'];
		$data      = '';

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
				$name  = uniqid( wc_variation_attribute_name( $attribute ) );
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {

						// aria-checked="false"
						$option = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) );

						$is_selected    = ( sanitize_title( $args['selected'] ) == $term->slug );
						$selected_class = $is_selected ? 'selected' : '';
						$tooltip        = trim( apply_filters( 'wvs_variable_item_tooltip', $option, $term, $args ) );

						$tooltip_html_attr       = ! empty( $tooltip ) ? sprintf( ' data-wvstooltip="%s"', esc_attr( $tooltip ) ) : '';
						$screen_reader_html_attr = $is_selected ? ' aria-checked="true"' : ' aria-checked="false"';

						if ( wp_is_mobile() ) {
							$tooltip_html_attr .= ! empty( $tooltip ) ? ' tabindex="2"' : '';
						}

						$data .= sprintf( '<li %1$s class="variable-item %2$s-variable-item %2$s-variable-item-%3$s %4$s" title="%5$s" data-title="%5$s" data-value="%3$s" role="radio" tabindex="0"><div class="variable-item-contents">', $screen_reader_html_attr . $tooltip_html_attr, esc_attr( $type ), esc_attr( $term->slug ), esc_attr( $selected_class ), $option );

						switch ( $type ):
							case 'color':

								$color = sanitize_hex_color( wvs_get_product_attribute_color( $term ) );
								$data  .= sprintf( '<span class="variable-item-span variable-item-span-%s" style="background-color:%s;"></span>', esc_attr( $type ), esc_attr( $color ) );
								break;

							case 'image':

								$attachment_id = apply_filters( 'wvs_product_global_attribute_image_id', absint( wvs_get_product_attribute_image( $term ) ), $term, $args );
								$image_size    = woo_variation_swatches()->get_option( 'attribute_image_size' );
								$image         = wp_get_attachment_image_src( $attachment_id, apply_filters( 'wvs_product_attribute_image_size', $image_size, $attribute, $product ) );

								$data .= sprintf( '<img class="variable-item-image" aria-hidden="true" alt="%s" src="%s" width="%d" height="%d" />', esc_attr( $option ), esc_url( $image[0] ), esc_attr( $image[1] ), esc_attr( $image[2] ) );

								break;


							case 'button':
								$data .= sprintf( '<span class="variable-item-span variable-item-span-%s">%s</span>', esc_attr( $type ), $option );
								break;

							case 'radio':
								$id   = uniqid( $term->slug );
								$data .= sprintf( '<input name="%1$s" id="%2$s" class="wvs-radio-variable-item" %3$s  type="radio" value="%4$s" data-title="%5$s" data-value="%4$s" /><label for="%2$s">%5$s</label>', $name, $id, checked( sanitize_title( $args['selected'] ), $term->slug, false ), esc_attr( $term->slug ), $option );
								break;

							default:
								$data .= apply_filters( 'wvs_variable_default_item_content', '', $term, $args, $saved_attribute );
								break;
						endswitch;
						$data .= '</div></li>';
					}
				}
			}
		}

		return apply_filters( 'wvs_variable_item', $data, $type, $options, $args, $saved_attribute );
	}
endif;

if ( ! function_exists( 'wvs_default_variable_item' ) ):
	function wvs_default_variable_item( $type, $options, $args, $saved_attribute = array() ) {

		$product   = $args['product'];
		$attribute = $args['attribute'];
		$assigned  = $args['assigned'];

		/*$product_variations = new WC_Product_Variable( $product->get_id() );
		$product_variations = $product_variations->get_available_variations();
		print_r($product_variations); die;*/

		$is_archive           = ( isset( $args['is_archive'] ) && $args['is_archive'] );
		$show_archive_tooltip = wc_string_to_bool( woo_variation_swatches()->get_option( 'show_tooltip_on_archive' ) );

		$data = '';

		if ( isset( $args['fallback_type'] ) && $args['fallback_type'] === 'select' ) {
			//	return '';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
				$name  = uniqid( wc_variation_attribute_name( $attribute ) );
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {

						$option = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) );

						$is_selected = ( sanitize_title( $args['selected'] ) == $term->slug );

						$selected_class = $is_selected ? 'selected' : '';
						$tooltip        = trim( apply_filters( 'wvs_variable_item_tooltip', $option, $term, $args ) );

						if ( $is_archive && ! $show_archive_tooltip ) {
							$tooltip = false;
						}

						$tooltip_html_attr       = ! empty( $tooltip ) ? sprintf( ' data-wvstooltip="%s"', esc_attr( $tooltip ) ) : '';
						$screen_reader_html_attr = $is_selected ? ' aria-checked="true"' : ' aria-checked="false"';


						if ( wp_is_mobile() ) {
							$tooltip_html_attr .= ! empty( $tooltip ) ? ' tabindex="2"' : '';
						}

						$type = isset( $assigned[ $term->slug ] ) ? $assigned[ $term->slug ]['type'] : $type;

						if ( ! isset( $assigned[ $term->slug ] ) || empty( $assigned[ $term->slug ]['image_id'] ) ) {
							$type = 'button';
						}

						$data .= sprintf( '<li %1$s class="variable-item %2$s-variable-item %2$s-variable-item-%3$s %4$s" title="%5$s" data-title="%5$s"  data-value="%3$s" role="radio" tabindex="0"><div class="variable-item-contents">', $screen_reader_html_attr . $tooltip_html_attr, esc_attr( $type ), esc_attr( $term->slug ), esc_attr( $selected_class ), $option );

						switch ( $type ):

							case 'image':
								$attachment_id = $assigned[ $term->slug ]['image_id'];
								$image_size    = sanitize_text_field( woo_variation_swatches()->get_option( 'attribute_image_size' ) );
								$image         = wp_get_attachment_image_src( $attachment_id, apply_filters( 'wvs_product_attribute_image_size', $image_size, $attribute, $product ) );

								$data .= sprintf( '<img class="variable-item-image" aria-hidden="true" alt="%s" src="%s" width="%d" height="%d" />', esc_attr( $option ), esc_url( $image[0] ), esc_attr( $image[1] ), esc_attr( $image[2] ) );
								// $data .= $image_html;
								break;


							case 'button':
								$data .= sprintf( '<span class="variable-item-span variable-item-span-%s">%s</span>', esc_attr( $type ), $option );
								break;

							default:
								$data .= apply_filters( 'wvs_variable_default_item_content', '', $term, $args, $saved_attribute );
								break;
						endswitch;
						$data .= '</div></li>';
					}
				}
			} else {

				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.

					$option = esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) );

					$is_selected = ( sanitize_title( $option ) == sanitize_title( $args['selected'] ) );

					$selected_class = $is_selected ? 'selected' : '';
					$tooltip        = trim( apply_filters( 'wvs_variable_item_tooltip', $option, $options, $args ) );


					if ( $is_archive && ! $show_archive_tooltip ) {
						$tooltip = false;
					}

					$tooltip_html_attr       = ! empty( $tooltip ) ? sprintf( 'data-wvstooltip="%s"', esc_attr( $tooltip ) ) : '';
					$screen_reader_html_attr = $is_selected ? ' aria-checked="true"' : ' aria-checked="false"';

					if ( wp_is_mobile() ) {
						$tooltip_html_attr .= ! empty( $tooltip ) ? ' tabindex="2"' : '';
					}

					$type = isset( $assigned[ $option ] ) ? $assigned[ $option ]['type'] : $type;

					if ( ! isset( $assigned[ $option ] ) || empty( $assigned[ $option ]['image_id'] ) ) {
						$type = 'button';
					}

					$data .= sprintf( '<li %1$s class="variable-item %2$s-variable-item %2$s-variable-item-%3$s %4$s" title="%5$s" data-title="%5$s"  data-value="%3$s" role="radio" tabindex="0"><div class="variable-item-contents">', $screen_reader_html_attr . $tooltip_html_attr, esc_attr( $type ), esc_attr( $option ), esc_attr( $selected_class ), esc_html( $option ) );

					switch ( $type ):

						case 'image':
							$attachment_id = $assigned[ $option ]['image_id'];
							$image_size    = sanitize_text_field( woo_variation_swatches()->get_option( 'attribute_image_size' ) );
							$image         = wp_get_attachment_image_src( $attachment_id, apply_filters( 'wvs_product_attribute_image_size', $image_size, $attribute, $product ) );

							$data .= sprintf( '<img class="variable-item-image" aria-hidden="true" alt="%s" src="%s" width="%d" height="%d" />', esc_attr( $option ), esc_url( $image[0] ), esc_attr( $image[1] ), esc_attr( $image[2] ) );
							// $data .= $image_html;
							break;


						case 'button':
							$data .= sprintf( '<span class="variable-item-span variable-item-span-%s">%s</span>', esc_attr( $type ), esc_html( $option ) );
							break;

						default:
							$data .= apply_filters( 'wvs_variable_default_item_content', '', $option, $args, array() );
							break;
					endswitch;
					$data .= '</div></li>';
				}
			}
		}

		return apply_filters( 'wvs_default_variable_item', $data, $type, $options, $args, array() );
	}
endif;

//-------------------------------------------------------------------------------
// Color Variation Attribute Options
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_color_variation_attribute_options' ) ) :
	function wvs_color_variation_attribute_options( $args = array() ) {

		$args = wp_parse_args(
			$args, array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'type'             => '',
				'show_option_none' => esc_html__( 'Choose an option', 'woo-variation-swatches' )
			)
		);

		$type                  = $args['type'];
		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : wc_variation_attribute_name( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = $args['show_option_none'] ? true : false;
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( $product && taxonomy_exists( $attribute ) ) {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide woo-variation-raw-select woo-variation-raw-type-' . esc_attr( $type ) . '" style="display:none" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		} else {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		}

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
				}
			}
		}

		echo '</select>';

		$content = wvs_variable_item( $type, $options, $args );

		echo wvs_variable_items_wrapper( $content, $type, $args );

	}
endif;

//-------------------------------------------------------------------------------
// Image Variation Attribute Options
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_image_variation_attribute_options' ) ) :
	function wvs_image_variation_attribute_options( $args = array() ) {

		$args = wp_parse_args(
			$args, array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'type'             => '',
				'show_option_none' => esc_html__( 'Choose an option', 'woo-variation-swatches' )
			)
		);

		$type                  = $args['type'];
		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : wc_variation_attribute_name( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = $args['show_option_none'] ? true : false;
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}


		if ( $product && taxonomy_exists( $attribute ) ) {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide woo-variation-raw-select woo-variation-raw-type-' . esc_attr( $type ) . '" style="display:none" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		} else {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		}


		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
				}
			}
		}

		echo '</select>';

		$content = wvs_variable_item( $type, $options, $args );

		echo wvs_variable_items_wrapper( $content, $type, $args );
	}
endif;

//-------------------------------------------------------------------------------
// Button Variation Attribute Options
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_button_variation_attribute_options' ) ) :
	function wvs_button_variation_attribute_options( $args = array() ) {

		$args = wp_parse_args(
			$args, array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'type'             => '',
				'show_option_none' => esc_html__( 'Choose an option', 'woo-variation-swatches' )
			)
		);

		$type                  = $args['type'];
		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : wc_variation_attribute_name( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = $args['show_option_none'] ? true : false;
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( $product && taxonomy_exists( $attribute ) ) {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide woo-variation-raw-select woo-variation-raw-type-' . esc_attr( $type ) . '" style="display:none" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		} else {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		}

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
				}
			}
		}

		echo '</select>';

		$content = wvs_variable_item( $type, $options, $args );

		echo wvs_variable_items_wrapper( $content, $type, $args );
	}
endif;

// Default Button Variation Attribute Options
if ( ! function_exists( 'wvs_default_button_variation_attribute_options' ) ) :
	function wvs_default_button_variation_attribute_options( $args = array() ) {

		$args = wp_parse_args(
			$args, array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'type'             => '',
				'assigned'         => '',
				'show_option_none' => esc_html__( 'Choose an option', 'woo-variation-swatches' )
			)
		);

		// $type                  = $args[ 'type' ];
		$type                  = $args['type'] ? $args['type'] : 'button';
		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : wc_variation_attribute_name( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = $args['show_option_none'] ? true : false;
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( $product ) {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide woo-variation-raw-select woo-variation-raw-type-' . $type . '" style="display:none" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		}

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
				}
			}
		}

		echo '</select>';

		$content = wvs_default_variable_item( $type, $options, $args );

		echo wvs_variable_items_wrapper( $content, $type, $args );
	}
endif;

// Default Image Variation Attribute Options
if ( ! function_exists( 'wvs_default_image_variation_attribute_options' ) ) :
	function wvs_default_image_variation_attribute_options( $args = array() ) {

		$args = wp_parse_args(
			$args, array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'type'             => '',
				'assigned'         => '',
				'show_option_none' => esc_html__( 'Choose an option', 'woo-variation-swatches' )
			)
		);

		$type = $args['type'];
		// $fallback_type         = $args[ 'fallback_type' ];
		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : wc_variation_attribute_name( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = $args['show_option_none'] ? true : false;
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( $product ) {

			if ( $type === 'select' ) {
				echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
			} else {
				echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide woo-variation-raw-select woo-variation-raw-type-' . $type . '" style="display:none" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
			}
		}

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
				}
			}
		}

		echo '</select>';

		if ( $type === 'select' ) {
			return '';
		}

		$content = wvs_default_variable_item( $type, $options, $args );

		echo wvs_variable_items_wrapper( $content, $type, $args );
	}
endif;

//-------------------------------------------------------------------------------
// Radio Variation Attribute Options
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_radio_variation_attribute_options' ) ) :
	function wvs_radio_variation_attribute_options( $args = array() ) {

		$args = wp_parse_args(
			$args, array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'type'             => '',
				'show_option_none' => esc_html__( 'Choose an option', 'woo-variation-swatches' )
			)
		);

		$type                  = $args['type'];
		$options               = $args['options'];
		$product               = $args['product'];
		$attribute             = $args['attribute'];
		$name                  = $args['name'] ? $args['name'] : wc_variation_attribute_name( $attribute );
		$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class                 = $args['class'];
		$show_option_none      = $args['show_option_none'] ? true : false;
		$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : esc_html__( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( $product && taxonomy_exists( $attribute ) ) {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . ' hide woo-variation-raw-select woo-variation-raw-type-' . esc_attr( $type ) . '" style="display:none" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		} else {
			echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="' . esc_attr( wc_variation_attribute_name( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
		}

		if ( $args['show_option_none'] ) {
			echo '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product ) ) . '</option>';
					}
				}
			} else {
				foreach ( $options as $option ) {
					// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
					$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
					echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) ) . '</option>';
				}
			}
		}

		echo '</select>';

		$content = wvs_variable_item( $type, $options, $args );

		echo wvs_variable_items_wrapper( $content, $type, $args );
	}
endif;

//-------------------------------------------------------------------------------
// Generate Option HTML
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_variation_attribute_options_html' ) ):
	function wvs_variation_attribute_options_html( $html, $args ) {

		if ( apply_filters( 'default_wvs_variation_attribute_options_html', false, $args, $html ) ) {
			return $html;
		}

		// WooCommerce Product Bundle Fixing
		if ( isset( $_POST['action'] ) && $_POST['action'] === 'woocommerce_configure_bundle_order_item' ) {
			return $html;
		}

		$product = $args['product'];

		// For bundle Product static item
		$args['show_option_none'] = esc_html__( 'Choose an option', 'woo-variation-swatches' );

		$is_default_to_image          = apply_filters( 'wvs_is_default_to_image', ! ! ( woo_variation_swatches()->get_option( 'default_to_image' ) ), $args );
		$is_default_to_button         = apply_filters( 'wvs_is_default_to_button', ! ! ( woo_variation_swatches()->get_option( 'default_to_button' ) ), $args );
		$default_image_type_attribute = apply_filters( 'wvs_default_image_type_attribute', woo_variation_swatches()->get_option( 'default_image_type_attribute' ), $args );

		$is_default_to_image_button = ( $is_default_to_image || $is_default_to_button );

		$use_transient  = wc_string_to_bool( woo_variation_swatches()->get_option( 'use_transient' ) );
		$currency       = get_woocommerce_currency();
		$transient_name = sprintf( 'wvs_variation_attribute_options_html_%s_%s_%s', $product->get_id(), ( wc_variation_attribute_name( $args['attribute'] ) . $args['selected'] ), $currency );
		$cache          = new Woo_Variation_Swatches_Cache( $transient_name, 'wvs_variation_attribute_options_html' );

		// Clear cache
		if ( isset( $_GET['wvs_clear_transient'] ) ) {
			$cache->delete_transient();
		}

		// Return cache. We already cache full template on pro so we don't have to cache when pro is active.
		if ( $use_transient && ! woo_variation_swatches()->is_pro_active() ) {
			$transient_html = $cache->get_transient( $transient_name );
			if ( ! empty( $transient_html ) ) {
				return $transient_html . '<!-- from wvs_variation_attribute_options_html  -->';
			}
		}

		ob_start();

		if ( apply_filters( 'wvs_no_individual_settings', true, $args, $is_default_to_image, $is_default_to_button ) ) {

			$attributes = $product->get_variation_attributes();
			$variations = $product->get_available_variations();

			$available_type_keys = array_keys( wvs_available_attributes_types() );
			$available_types     = wvs_available_attributes_types();
			$default             = true;

			foreach ( $available_type_keys as $type ) {
				if ( wvs_wc_product_has_attribute_type( $type, $args['attribute'] ) ) {

					$output_callback = apply_filters( 'wvs_variation_attribute_options_callback', $available_types[ $type ]['output'], $available_types, $type, $args, $html );
					$output_callback(
						apply_filters(
							'wvs_variation_attribute_options_args', wp_parse_args(
								$args, array(
									'options'    => $args['options'],
									'attribute'  => $args['attribute'],
									'product'    => $product,
									'selected'   => $args['selected'],
									'type'       => $type,
									'is_archive' => ( isset( $args['is_archive'] ) && $args['is_archive'] )
								)
							)
						)
					);
					$default = false;
				}
			}

			if ( $default && $is_default_to_image_button ) {

				if ( $default_image_type_attribute === '__max' ) {

					$attribute_counts = array();
					foreach ( $attributes as $attr_key => $attr_values ) {
						$attribute_counts[ $attr_key ] = count( $attr_values );
					}

					$max_attribute_count = max( $attribute_counts );
					$attribute_key       = array_search( $max_attribute_count, $attribute_counts );

				} elseif ( $default_image_type_attribute === '__min' ) {
					$attribute_counts = array();
					foreach ( $attributes as $attr_key => $attr_values ) {
						$attribute_counts[ $attr_key ] = count( $attr_values );
					}
					$min_attribute_count = min( $attribute_counts );
					$attribute_key       = array_search( $min_attribute_count, $attribute_counts );

				} elseif ( $default_image_type_attribute === '__first' ) {
					$attribute_keys = array_keys( $attributes );
					$attribute_key  = current( $attribute_keys );
				} else {
					$attribute_key = $default_image_type_attribute;
				}

				$selected_attribute_name = wc_variation_attribute_name( $attribute_key );


				$default_attribute_keys = array_keys( $attributes );
				$default_attribute_key  = current( $default_attribute_keys );
				$default_attribute_name = wc_variation_attribute_name( $default_attribute_key );

				$current_attribute      = $args['attribute'];
				$current_attribute_name = wc_variation_attribute_name( $current_attribute );


				if ( $is_default_to_image ) {

					$assigned = array();

					foreach ( $variations as $variation_key => $variation ) {

						$attribute_name = isset( $variation['attributes'][ $selected_attribute_name ] ) ? $selected_attribute_name : $default_attribute_name;

						$attribute_value = esc_html( $variation['attributes'][ $attribute_name ] );

						$assigned[ $attribute_name ][ $attribute_value ] = array(
							'image_id'     => $variation['image_id'],
							'variation_id' => $variation['variation_id'],
							'type'         => ( empty( $variation['image_id'] ) ? 'button' : 'image' ),
						);
					}

					$type     = ( empty( $assigned[ $current_attribute_name ] ) ? 'button' : 'image' );
					$assigned = ( isset( $assigned[ $current_attribute_name ] ) ? $assigned[ $current_attribute_name ] : array() );

					if ( $type === 'button' && ! $is_default_to_button ) {
						$type = 'select';
					}

					wvs_default_image_variation_attribute_options(
						apply_filters(
							'wvs_variation_attribute_options_args', wp_parse_args(
								$args, array(
									'options'    => $args['options'],
									'attribute'  => $args['attribute'],
									'product'    => $product,
									'selected'   => $args['selected'],
									'assigned'   => $assigned,
									'type'       => $type,
									'is_archive' => ( isset( $args['is_archive'] ) && $args['is_archive'] )
								)
							)
						)
					);

				} elseif ( $is_default_to_button ) {

					wvs_default_button_variation_attribute_options(
						apply_filters(
							'wvs_variation_attribute_options_args', wp_parse_args(
								$args, array(
									'options'    => $args['options'],
									'attribute'  => $args['attribute'],
									'product'    => $product,
									'selected'   => $args['selected'],
									'is_archive' => ( isset( $args['is_archive'] ) && $args['is_archive'] )
								)
							)
						)
					);
				} else {
					echo $html;
				}
			} elseif ( $default && ! $is_default_to_image_button ) {
				echo $html;
			}

		}

		$data = ob_get_clean();

		// Set cache
		if ( $use_transient && ! woo_variation_swatches()->is_pro_active() ) {
			$cache->set_transient( $data, HOUR_IN_SECONDS );
		}

		$html = apply_filters( 'wvs_variation_attribute_options_html', $data, $args, $is_default_to_image, $is_default_to_button );

		return $html;
	}
endif;

//-------------------------------------------------------------------------------
// Preview TAB
//-------------------------------------------------------------------------------

if ( ! function_exists( 'add_wvs_pro_preview_tab' ) ):
	function add_wvs_pro_preview_tab( $tabs ) {
		$tabs['woo-variation-swatches-pro'] = array(
			'label'    => esc_html__( 'Swatches Settings', 'woo-variation-swatches' ),
			'target'   => 'wvs-pro-product-variable-swatches-options',
			'class'    => array( 'show_if_variable', 'variations_tab', 'pro-inactive' ),
			'priority' => 65,
		);

		return $tabs;
	}
endif;

//-------------------------------------------------------------------------------
// Preview TAB Panel
//-------------------------------------------------------------------------------

if ( ! function_exists( 'add_wvs_pro_preview_tab_panel' ) ):
	function add_wvs_pro_preview_tab_panel() {
		ob_start();
		include_once 'preview-tab-tutorials.php';
		echo ob_get_clean();
	}
endif;

//-------------------------------------------------------------------------------
// Swatch Locate Template
//-------------------------------------------------------------------------------

if ( ! function_exists( 'wvs_locate_template' ) ):
	function wvs_locate_template( $template, $template_name, $template_path ) {

		$default_template = $template;

		if ( ! $template_path ) {
			$template_path = WC()->template_path();
		}

		// Swatch Plugin Path
		$plugin_path = woo_variation_swatches()->plugin_path() . '/woocommerce/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);

		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		// Use default template
		if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
			$template = $default_template;
		}

		// Return what we found
		return $template;
	}
endif;


//-------------------------------------------------------------------------------
// Swatch clear transient
//-------------------------------------------------------------------------------
function wvs_clear_transient() {

	// Increments the transient version to invalidate cache.
	if ( method_exists( 'WC_Cache_Helper', 'get_transient_version' ) ) {
		WC_Cache_Helper::get_transient_version( 'wvs_template', true );
		WC_Cache_Helper::get_transient_version( 'wvs_attribute_taxonomy', true );
		WC_Cache_Helper::get_transient_version( 'wvs_archive_template', true );
		WC_Cache_Helper::get_transient_version( 'wvs_variation_attribute_options_html', true );
	}

	if ( method_exists( 'WC_Cache_Helper', 'invalidate_cache_group' ) ) {
		WC_Cache_Helper::invalidate_cache_group( 'wvs_template' );
		WC_Cache_Helper::invalidate_cache_group( 'wvs_attribute_taxonomy' );
		WC_Cache_Helper::invalidate_cache_group( 'wvs_archive_template' );
		WC_Cache_Helper::invalidate_cache_group( 'wvs_variation_attribute_options_html' );
	}
}

//-------------------------------------------------------------------------------
// Defer Script Load
//-------------------------------------------------------------------------------

function wvs_defer_script_load( $tag, $handle, $src ) {
	$defer_load_js = wc_string_to_bool( woo_variation_swatches()->get_option( 'defer_load_js' ) );

	if ( $defer_load_js ) {
		$handles = array( 'woo-variation-swatches-pro', 'wc-add-to-cart-variation', 'woo-variation-swatches' );

		if ( ! wp_is_mobile() && in_array( $handle, $handles ) && ( strpos( $tag, 'plugins' . DIRECTORY_SEPARATOR . 'woo-variation-swatches' ) !== false ) ) {
			return str_ireplace( ' src=', ' defer src=', $tag );
		}
	}

	return $tag;

}

//-------------------------------------------------------------------------------
// Install Gallery
//-------------------------------------------------------------------------------

function wvs_install_woo_variation_gallery_notice( $loop, $variation_data, $variation ) {
	if ( ! woo_variation_swatches()->is_gallery_active() && current_user_can( 'install_plugins' ) && apply_filters( 'wvs_install_woo_variation_gallery_notice', true ) ) {
		?>
		<div class="form-row form-row-full woo-variation-gallery-message"
			 data-nonce="<?php echo wp_create_nonce( 'install-woo-variation-gallery' ) ?>"
			 data-installing="<?php esc_attr_e( 'Installing Plugin...', 'woo-variation-swatches' ); ?>"
			 data-activated="<?php esc_attr_e( 'Plugin Installed. Please refresh this page.', 'woo-variation-swatches' ); ?>">
			<?php printf( '%s <a class="install-woo-variation-gallery-action" target="_blank" href="#">%s</a> plugin', esc_html__( 'Want to add more image? Install', 'woo-variation-swatches' ), esc_html__( 'Additional Variation Images Gallery for WooCommerce', 'woo-variation-swatches' ) ) ?>
		</div>
		<?php
	}
}

function wvs_install_woo_variation_gallery() {
	if ( is_ajax() && current_user_can( 'install_plugins' ) && wp_verify_nonce( $_POST['nonce'], 'install-woo-variation-gallery' ) ) {

		$plugin_slug = 'woo-variation-gallery/woo-variation-gallery.php';
		$plugin_zip  = 'https://downloads.wordpress.org/plugin/woo-variation-gallery.zip';

		if ( wvs_is_plugin_installed( $plugin_slug ) ) {
			$installed = true;
			wvs_upgrade_plugin( $plugin_slug );
		} else {
			$installed = wvs_install_plugin( $plugin_zip );
		}

		if ( ! is_wp_error( $installed ) && $installed ) {
			activate_plugin( $plugin_slug );
		}
	}

	exit;
}

function wvs_is_plugin_installed( $slug ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins = get_plugins();

	if ( ! empty( $all_plugins[ $slug ] ) ) {
		return true;
	} else {
		return false;
	}
}

function wvs_install_plugin( $plugin_zip ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	wp_cache_flush();

	$upgrader  = new Plugin_Upgrader();
	$installed = $upgrader->install( $plugin_zip );

	return $installed;
}

function wvs_upgrade_plugin( $plugin_slug ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	wp_cache_flush();

	$upgrader = new Plugin_Upgrader();
	$upgraded = $upgrader->upgrade( $plugin_slug );

	return $upgraded;
}

// It's useful for plugin or theme documentation and for developer who want to know
// how many add_action attached with a specific do_action hook, which file with line number.
if ( ! function_exists( 'storepress_hook_info' ) ):

	function storepress_hook_info( $hook_name ) {
		global $wp_filter;

		$docs     = array();
		$template = "\t - %s Priority - %s.\n\tin file %s #%s\n\n";

		echo '<pre>';
		echo "\t# Hook Name \"" . $hook_name . "\"";
		echo "\n\n";
		if ( isset( $wp_filter[ $hook_name ] ) ) {
			foreach ( $wp_filter[ $hook_name ] as $pri => $fn ) {

				foreach ( $fn as $fnname => $fnargs ) {

					if ( is_array( $fnargs['function'] ) ) {
						$reflClass = new ReflectionClass( $fnargs['function'][0] );
						$reflFunc  = $reflClass->getMethod( $fnargs['function'][1] );
						$class     = $reflClass->getName();
						$function  = $reflFunc->name;
					} else {
						$reflFunc  = new ReflectionFunction( $fnargs['function'] );
						$class     = false;
						$function  = $reflFunc->name;
						$isClosure = (bool) $reflFunc->isClosure();
					}

					if ( $class ) {
						$functionName = sprintf( 'Class "%s::%s"', $class, $function );
					} else {
						$functionName = ( $isClosure ) ? "Anonymous Function $function" : "Function \"$function\"";
					}

					printf( $template, $functionName, $pri, str_ireplace( ABSPATH, '', $reflFunc->getFileName() ), $reflFunc->getStartLine() );

					$docs[] = array( $functionName, $pri );
				}
			}

			echo "\tAction Hook Commenting\n\t----------------------\n\n";
			echo "\t/**\n\t* " . $hook_name . " hook\n\t*\n";
			foreach ( $docs as $doc ) {
				echo "\t* @hooked " . $doc[0] . " - " . $doc[1] . "\n";
			}
			echo "\t*/";
			echo "\n\n";
			echo "\tdo_action( '" . $hook_name . "' );";

		}
		echo '</pre>';
	}
endif;
