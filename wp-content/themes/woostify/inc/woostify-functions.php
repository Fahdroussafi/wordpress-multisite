<?php
/**
 * Woostify functions.
 *
 * @package woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Backwards compatibility for site WP < 5.2
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if ( ! function_exists( 'woostify_version' ) ) {
	/**
	 * Woostify Version
	 *
	 * @return string Woostify Version.
	 */
	function woostify_version() {
		return esc_attr( WOOSTIFY_VERSION );
	}
}

if ( ! function_exists( 'woostify_info' ) ) {
	/**
	 * Woostify Information.
	 *
	 * @param string $output The output.
	 */
	function woostify_info( $output ) {
		$output .= ' data-woostify-version="' . woostify_version() . '"';
		$output .= defined( 'WOOSTIFY_PRO_VERSION' ) ? ' data-woostify-pro-version="' . esc_attr( WOOSTIFY_PRO_VERSION ) . '"' : '';

		return $output;
	}
}

if ( ! function_exists( 'woostify_suffix' ) ) {
	/**
	 * Define Script debug.
	 *
	 * @return     string $suffix
	 */
	function woostify_suffix() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		return $suffix;
	}
}

if ( ! function_exists( 'woostify_get_pro_url' ) ) {
	/**
	 * Generate a URL to our pro add-ons.
	 * Allows the use of a referral ID and campaign.
	 *
	 * @param string $url URL to pro page.
	 *
	 * @return string The URL to woostify.com.
	 */
	function woostify_get_pro_url( $url = 'https://woostify.com' ) {
		$url = trailingslashit( $url );

		$args = apply_filters(
			'woostify_premium_url_args',
			array(
				'ref'      => null,
				'campaign' => null,
			)
		);

		// Set up our URL if we have an ID.
		if ( isset( $args['ref'] ) ) {
			$url = add_query_arg( 'ref', absint( $args['ref'] ), $url );
		}

		// Set up our URL if we have a campaign.
		if ( isset( $args['campaign'] ) ) {
			$url = add_query_arg( 'campaign', sanitize_text_field( $args['campaign'] ), $url );
		}

		return esc_url( $url );
	}
}

if ( ! function_exists( 'woostify_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function woostify_is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

if ( ! function_exists( 'woostify_is_elementor_activated' ) ) {
	/**
	 * Check Elementor active
	 *
	 * @return     bool
	 */
	function woostify_is_elementor_activated() {
		return defined( 'ELEMENTOR_VERSION' );
	}
}

if ( ! function_exists( 'woostify_is_elementor_page' ) ) {
	/**
	 * Detect Elementor Page editor with current page
	 *
	 * @param int $page_id The page id.
	 *
	 * @return     bool
	 */
	function woostify_is_elementor_page( $page_id = false ) {
		if ( ! woostify_is_elementor_activated() ) {
			return false;
		}

		if ( ! $page_id ) {
			$page_id = woostify_get_page_id();
		}

		$is_elementor_page = get_post_meta( $page_id, '_elementor_edit_mode', true );
		$is_elementor_page = 'builder' === $is_elementor_page ? true : false;

		// Priority first.
		if ( in_array( get_post_type( $page_id ), array( 'hf_builder', 'mega_menu' ), true ) ) {
			return $is_elementor_page;
		}

		if ( ! $page_id || is_tax() || is_singular( 'product' ) ) {
			$is_elementor_page = false;
		}

		return $is_elementor_page;
	}
}

if ( ! function_exists( 'woostify_get_product_id' ) ) {
	/**
	 * Get product id
	 */
	function woostify_get_product_id() {
		$last_product_id = woostify_get_last_product_id();
		$post            = isset( $_REQUEST['post'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ) : false; // phpcs:ignore
		$editor_post_id  = isset( $_REQUEST['editor_post_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['editor_post_id'] ) ) : false; // phpcs:ignore
		$post_id         = $post ? $post : $editor_post_id;
		if ( $post_id ) {
			$selected_id = get_post_meta( $post_id, 'woostify_woo_builder_select_product_preview', true );

			if ( $selected_id ) {
				$last_product_id = $selected_id;
			}
		}

		$product_id = woostify_is_elementor_editor() ? $last_product_id : woostify_get_page_id();

		return apply_filters( 'woostify_get_product_id', $product_id );
	}
}

if ( ! function_exists( 'woostify_elementor_has_location' ) ) {
	/**
	 * Detect if a page has Elementor location template.
	 *
	 * @param string $location The location.
	 *
	 * @return     boolean
	 */
	function woostify_elementor_has_location( $location ) {
		if ( ! did_action( 'elementor_pro/init' ) ) {
			return false;
		}

		$conditions_manager = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'theme-builder' )->get_conditions_manager();
		$documents          = $conditions_manager->get_documents_for_location( $location );

		return ! empty( $documents );
	}
}

if ( ! function_exists( 'woostify_is_elementor_editor' ) ) {
	/**
	 * Condition if Current screen is Edit mode || Preview mode.
	 */
	function woostify_is_elementor_editor() {
		if ( ! woostify_is_elementor_activated() ) {
			return false;
		}

		$editor = ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() );

		return $editor;
	}
}

if ( ! function_exists( 'woostify_is_divi_page' ) ) {
	/**
	 * Get Divi page content
	 *
	 * @param int $id The page id.
	 */
	function woostify_is_divi_page( $id = false ) {
		if ( ! defined( 'ET_BUILDER_PLUGIN_VERSION' ) ) {
			return false;
		}

		if ( ! $id ) {
			$id = woostify_get_page_id();
		}

		if ( ! $id || is_tax() ) {
			return false;
		}

		$content_post = get_post( $id );
		$content      = $content_post->post_content;
		if ( false !== strpos( $content, '<!-- wp:divi/placeholder -->' ) || false !== strpos( $content, '[et_pb_' ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'woostify_sanitize_array' ) ) {
	/**
	 * Sanitize integer value
	 *
	 * @param array $value The array.
	 */
	function woostify_sanitize_array( $value ) {
		$data = array();
		foreach ( $value as $key ) {
			$data[] = sanitize_text_field( $key );
		}

		return $data;
	}
}

if ( ! function_exists( 'woostify_sanitize_choices' ) ) {
	/**
	 * Sanitizes choices (selects / radios)
	 * Checks that the input matches one of the available choices
	 *
	 * @param array $input the available choices.
	 * @param array $setting the setting object.
	 */
	function woostify_sanitize_choices( $input, $setting ) {
		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

if ( ! function_exists( 'woostify_sanitize_checkbox' ) ) {
	/**
	 * Checkbox sanitization callback.
	 *
	 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
	 * as a boolean value, either TRUE or FALSE.
	 *
	 * @param bool $checked Whether the checkbox is checked.
	 *
	 * @return bool Whether the checkbox is checked.
	 */
	function woostify_sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true === $checked ) ? true : false );
	}
}

if ( ! function_exists( 'woostify_sanitize_variants' ) ) {
	/**
	 * Sanitize our Google Font variants
	 *
	 * @param string $input sanitize variants.
	 *
	 * @return     sanitize_text_field( $input )
	 */
	function woostify_sanitize_variants( $input ) {
		if ( is_array( $input ) ) {
			$input = implode( ',', $input );
		}

		return sanitize_text_field( $input );
	}
}

if ( ! function_exists( 'woostify_sanitize_rgba_color' ) ) {
	/**
	 * Sanitize color || rgba color
	 *
	 * @param string $color The color.
	 */
	function woostify_sanitize_rgba_color( $color ) {
		if ( empty( $color ) || is_array( $color ) ) {
			return '';
		}

		// If string does not start with 'rgba', then treat as hex sanitize the hex color and finally convert hex to rgba.
		if ( false === strpos( $color, 'rgba' ) ) {
			return sanitize_hex_color( $color );
		}

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
}

if ( ! function_exists( 'woostify_sanitize_int' ) ) {
	/**
	 * Sanitize integer value
	 *
	 * @param integer $value The integer number.
	 */
	function woostify_sanitize_int( $value ) {
		return intval( $value );
	}
}

if ( ! function_exists( 'woostify_sanitize_raw_html' ) ) {
	/**
	 * Sanitize raw html value
	 *
	 * @param string $value The raw html value.
	 */
	function woostify_sanitize_raw_html( $value ) {
		$kses_defaults = wp_kses_allowed_html( 'post' );
		$image         = array(
			'img' => array(
				'class'  => array(),
				'alt'    => array(),
				'width'  => array(),
				'height' => array(),
				'src'    => array(),
			),
		);

		return wp_kses( $value, array_merge( $kses_defaults, $image ) );
	}
}

if ( ! function_exists( 'woostify_is_blog' ) ) {
	/**
	 * Woostify detect blog page
	 *
	 * @return boolean $is_blog
	 */
	function woostify_is_blog() {
		global $post;

		$post_type = get_post_type( $post );

		$is_blog = ( 'post' === $post_type && ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) ) ? true : false;

		return apply_filters( 'woostify_is_blog', $is_blog );
	}
}

if ( ! function_exists( 'woostify_options' ) ) {
	/**
	 * Theme option
	 * If ( $defaults = true ) return Default value
	 * Else return all theme option
	 *
	 * @param bool $defaults Condition check output.
	 *
	 * @return     array $options         All theme options
	 */
	function woostify_options( $defaults = true ) {
		$default_settings = Woostify_Customizer::woostify_get_woostify_default_setting_values();
		$default_fonts    = Woostify_Fonts_Helpers::woostify_get_default_fonts();
		$default_options  = array_merge( $default_settings, $default_fonts );

		if ( $defaults ) {
			return $default_options;
		}

		$options = wp_parse_args(
			get_option( 'woostify_setting', array() ),
			$default_options
		);

		return $options;
	}
}

if ( ! function_exists( 'woostify_image_alt' ) ) {

	/**
	 * Get image alt
	 *
	 * @param bolean $id The image id.
	 * @param string $alt The alternate.
	 * @param bolean $placeholder The bolean.
	 *
	 * @return     string  The image alt
	 */
	function woostify_image_alt( $id = null, $alt = '', $placeholder = false ) {
		if ( ! $id ) {
			if ( $placeholder ) {
				return esc_attr__( 'Placeholder image', 'woostify' );
			}

			return esc_attr__( 'Error image', 'woostify' );
		}

		$data    = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$img_alt = ! empty( $data ) ? $data : $alt;

		return $img_alt;
	}
}

if ( ! function_exists( 'woostify_hex_to_rgba' ) ) {
	/**
	 * Convert HEX to RGBA color
	 *
	 * @param string  $hex The hexadecimal color.
	 * @param integer $alpha The alpha.
	 *
	 * @return     string  The rgba color.
	 */
	function woostify_hex_to_rgba( $hex, $alpha = 1 ) {
		$hex = str_replace( '#', '', $hex );

		if ( 3 === strlen( $hex ) ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		$rgba = array( $r, $g, $b, $alpha );

		return 'rgba(' . implode( ',', $rgba ) . ')';
	}
}

if ( ! function_exists( 'woostify_browser_detection' ) ) {
	/**
	 * Woostify broswer detection
	 */
	function woostify_browser_detection() {
		global $is_IE, $is_edge, $is_safari, $is_iphone;

		$class = '';

		if ( $is_iphone ) {
			$class = 'iphone';
		} elseif ( $is_IE ) {
			$class = 'ie';
		} elseif ( $is_edge ) {
			$class = 'edge';
		} elseif ( $is_safari ) {
			$class = 'safari';
		}

		return $class;
	}
}

if ( ! function_exists( 'woostify_dequeue_scripts_and_styles' ) ) {
	/**
	 * Dequeue scripts and style no need
	 */
	function woostify_dequeue_scripts_and_styles() {
		// What is 'sb-font-awesome'?
		wp_deregister_style( 'sb-font-awesome' );
		wp_dequeue_style( 'sb-font-awesome' );
	}
}

if ( ! function_exists( 'woostify_narrow_data' ) ) {
	/**
	 * Get dropdown data
	 *
	 * @param string $type The type 'post' || 'term'.
	 * @param string $terms The terms post, category, product, product_cat, custom_post_type...
	 * @param intval $total The total.
	 *
	 * @return     array
	 */
	function woostify_narrow_data( $type = 'post', $terms = 'category', $total = - 1 ) {
		$output = array();
		switch ( $type ) {
			case 'post':
				$args = array(
					'post_type'           => $terms,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page'      => $total,
				);

				$qr = new WP_Query( $args );
				if ( $qr->have_posts() ) {
					$output = wp_list_pluck( $qr->posts, 'post_title', 'ID' );
				}
				break;

			case 'term':
				$terms = get_terms( $terms );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$output = wp_list_pluck( $terms, 'name', 'term_id' );
				}
				break;
		}

		return $output;
	}
}

if ( ! function_exists( 'woostify_get_metabox' ) ) {
	/**
	 * Get metabox option
	 *
	 * @param int    $page_id The page ID.
	 * @param string $metabox_name Metabox option name.
	 */
	function woostify_get_metabox( $page_id = false, $metabox_name = '' ) {
		$page_id             = $page_id ? intval( $page_id ) : woostify_get_page_id();
		$metabox             = get_post_meta( $page_id, $metabox_name, true );
		$is_product_category = class_exists( 'woocommerce' ) && is_product_category();
		if ( ! $metabox || $is_product_category ) {
			$metabox = 'default';
		}

		return $metabox;
	}
}

if ( ! function_exists( 'woostify_header_transparent' ) ) {
	/**
	 * Detect header transparent on current page
	 */
	function woostify_header_transparent() {
		$options             = woostify_options( false );
		$transparent         = $options['header_transparent'];
		$archive_transparent = $options['header_transparent_disable_archive'];
		$index_transparent   = $options['header_transparent_disable_index'];
		$page_transparent    = $options['header_transparent_disable_page'];
		$post_transparent    = $options['header_transparent_disable_post'];
		$shop_transparent    = $options['header_transparent_disable_shop'];
		$product_transparent = $options['header_transparent_disable_product'];
		$metabox_transparent = woostify_get_metabox( false, 'site-header-transparent' );

		// Disable header transparent on Shop page.
		if ( class_exists( 'woocommerce' ) && is_shop() && $shop_transparent ) {
			$transparent = false;
		} elseif ( class_exists( 'woocommerce' ) && is_product() && $product_transparent ) {
			// Disable header transparent on Product page.
			$transparent = false;
		} elseif ( ( ( is_archive() && ( class_exists( 'woocommerce' ) && ! is_shop() ) ) || is_404() || is_search() ) && $archive_transparent ) {
			// Disable header transparent on Archive, 404 and Search page NOT Shop page.
			$transparent = false;
		} elseif ( is_home() && $index_transparent ) {
			// Disable header transparent on Blog page.
			$transparent = false;
		} elseif ( is_page() && $page_transparent ) {
			// Disable header transparent on Pages.
			$transparent = false;
		} elseif ( is_singular( 'post' ) && $post_transparent ) {
			// Disable header transparent on Posts.
			$transparent = false;
		}

		// Metabox option for single post or page. Priority highest.
		if ( 'default' !== $metabox_transparent ) {
			if ( 'enabled' === $metabox_transparent ) {
				$transparent = true;
			} else {
				$transparent = false;
			}
		}

		return $transparent;
	}
}

if ( ! function_exists( 'woostify_meta_charset' ) ) {
	/**
	 * Meta charset
	 */
	function woostify_meta_charset() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<?php
	}
}

if ( ! function_exists( 'woostify_meta_viewport' ) ) {
	/**
	 * Meta viewport
	 */
	function woostify_meta_viewport() {
		?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
		<?php
	}
}

if ( ! function_exists( 'woostify_rel_profile' ) ) {
	/**
	 * Rel profile
	 */
	function woostify_rel_profile() {
		?>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php
	}
}

if ( ! function_exists( 'woostify_pingback' ) ) {
	/**
	 * Pingback
	 */
	function woostify_pingback() {
		if ( ! is_singular() || ! pings_open( get_queried_object() ) ) {
			return;
		}
		?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php
	}
}

if ( ! function_exists( 'woostify_facebook_social' ) ) {
	/**
	 * Get Title and Image for Facebook share
	 */
	function woostify_facebook_social() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		$id        = woostify_get_page_id();
		$title     = get_the_title( $id );
		$image     = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );
		$image_src = $image ? $image[0] : wc_placeholder_img_src();
		?>

		<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
		<meta property="og:image" content="<?php echo esc_attr( $image_src ); ?>">
		<?php
	}
}

if ( ! function_exists( 'woostify_array_insert' ) ) {
	/**
	 * Insert an array into another array before/after a certain key
	 *
	 * @param array  $array The initial array.
	 * @param array  $pairs The array to insert.
	 * @param string $key The certain key.
	 * @param string $position Wether to insert the array before or after the key.
	 *
	 * @return array
	 */
	function woostify_array_insert( $array, $pairs, $key, $position = 'after' ) {
		$key_pos = array_search( $key, array_keys( $array ), true );
		if ( 'after' === $position ) {
			$key_pos ++;
			if ( false !== $key_pos ) {
				$result = array_slice( $array, 0, $key_pos );
				$result = array_merge( $result, $pairs );
				$result = array_merge( $result, array_slice( $array, $key_pos ) );
			}
		} else {
			$result = array_merge( $array, $pairs );
		}

		return $result;
	}
}

if ( ! function_exists( 'woostify_support_wishlist_plugin' ) ) {
	/**
	 * Detect wishlist plugin
	 */
	function woostify_support_wishlist_plugin() {
		if ( ! woostify_is_woocommerce_activated() ) {
			return false;
		}

		$options = woostify_options( false );
		$plugin  = $options['shop_page_wishlist_support_plugin'];

		// Ti plugin or YITH plugin.
		if ( ( defined( 'TINVWL_URL' ) && 'ti' === $plugin ) || ( defined( 'YITH_WCWL' ) && 'yith' === $plugin ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'woostify_wishlist_page_url' ) ) {
	/**
	 * Get wishlist page url
	 */
	function woostify_wishlist_page_url() {
		if ( ! woostify_support_wishlist_plugin() ) {
			return '#';
		}

		$options   = woostify_options( false );
		$shortcode = '[yith_wcwl_wishlist]';

		if ( 'ti' === $options['shop_page_wishlist_support_plugin'] ) {
			$shortcode = '[ti_wishlistsview]';
		}

		global $wpdb;
		$id = $wpdb->get_results( 'SELECT ID FROM ' . $wpdb->prefix . 'posts WHERE post_content LIKE "%' . $shortcode . '%" AND post_parent = 0' ); // phpcs:ignore

		if ( $id ) {
			$id  = intval( $id[0]->ID );
			$url = get_the_permalink( $id );

			return $url;
		}

		return '#';
	}
}

if ( ! function_exists( 'woostify_get_social_icon_list' ) ) {
	/**
	 * Supported render icon list by link in custom html
	 *
	 * @return array
	 */
	function woostify_get_social_icon_list() {
		$list = array(
			array(
				'href' => 'twitter.com',
				'icon' => 'twitter',
			),
			array(
				'href' => 'facebook.com',
				'icon' => 'facebook',
			),
			array(
				'href' => 'plus.google.com',
				'icon' => 'google',
			),
			array(
				'href' => 'instagram.com',
				'icon' => 'instagram',
			),
			array(
				'href' => 'vimeo.com',
				'icon' => 'vimeo',
			),
			array(
				'href' => 'youtube.com',
				'icon' => 'youtube',
			),
			array(
				'href' => 'github.com',
				'icon' => 'github',
			),
			array(
				'href' => 'linkedin.com',
				'icon' => 'linkedin',
			),
			array(
				'href' => 'pinterest.com',
				'icon' => 'pinterest-alt',
			),
			array(
				'href' => 'flickr.com',
				'icon' => 'flickr',
			),
			array(
				'href' => 'tumblr.com',
				'icon' => 'tumblr',
			),
			array(
				'href' => 'mailto',
				'icon' => 'email',
			),
			array(
				'href' => 'whatsapp',
				'icon' => 'themify-favicon',
			),
		);

		return apply_filters( 'woostify_social_icon_list', $list );
	}
}

if ( ! function_exists( 'woostify_allow_tags_svg' ) ) {
	/**
	 * Allow svg tags
	 *
	 * @return mixed|void
	 */
	function woostify_allow_tags_svg() {
		$kses_defaults = wp_kses_allowed_html( 'post' );

		$svg_args = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'    => true,
				'fill' => true,
			),
		);

		return array_merge( $kses_defaults, $svg_args );
	}
}

if ( ! function_exists( 'woostify_render_css_space' ) ) {
	/**
	 * Render css property with value
	 *
	 * @param string $option option.
	 * @param string $property property.
	 * @param string $unit unit.
	 *
	 * @return string
	 */
	function woostify_render_css_spacing( $option, $property = '', $unit = 'px' ) {
		if ( '' === $option ) {
			return '';
		}
		$css    = '' !== $property ? $property . ': ' : '';
		$values = explode( ' ', $option );
		foreach ( $values as $val ) {
			$css .= '0' !== $val ? $val . $unit : $val;
			$css .= ' ';
		}

		return trim( $css );
	}
}

if ( ! function_exists( 'woostify_custom_search_form' ) ) {
	/**
	 * Override search with get form html
	 *
	 * @param string $form Form html.
	 * @param string $args Arguments.
	 *
	 * @return string
	 */
	function woostify_custom_search_form( $form, $args ) {
		// Build a string containing an aria-label to use for the search form.
		if ( $args['aria_label'] ) {
			$aria_label = 'aria-label="' . esc_attr( $args['aria_label'] ) . '" ';
		} else {
			/*
			 * If there's no custom aria-label, we can set a default here. At the
			 * moment it's empty as there's uncertainty about what the default should be.
			 */
			$aria_label = '';
		}
		$format = current_theme_supports( 'html5', 'search-form' ) ? 'html5' : 'xhtml';

		if ( 'html5' === $format ) {
			$form = '<form role="search" ' . $aria_label . 'method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
                <label>
                    <span class="screen-reader-text">' . _x( 'Search for:', 'label', 'woostify' ) . '</span>
                    <input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'woostify' ) . '" value="' . get_search_query() . '" name="s" />
                </label>
                <input type="submit" class="search-submit" value="' . esc_attr_x( 'Search', 'submit button', 'woostify' ) . '" />
                <span class="search-form-icon">' . Woostify_Icon::fetch_svg_icon( 'search', false ) . '</span>
            </form>';
		} else {
			$form = '<form role="search" ' . $aria_label . 'method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">
                <div>
                    <label class="screen-reader-text" for="s">' . _x( 'Search for:', 'label', 'woostify' ) . '</label>
                    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
                    <input type="submit" id="searchsubmit" value="' . esc_attr_x( 'Search', 'submit button', 'woostify' ) . '" />
                </div>
            </form>';
		}
		return $form;
	}
}

if ( ! function_exists( 'woostify_sanitize_abs_number' ) ) {
	/**
	 * Sanitize abs int|float value
	 *
	 * @param integer $value The int|float number.
	 */
	function woostify_sanitize_abs_number( $value ) {
		return abs( $value );
	}
}

if ( ! function_exists( 'woostify_sanitize_json_string' ) ) {
	/**
	 * Sanitize json value
	 *
	 * @param string $value The json string data.
	 */
	function woostify_sanitize_json_string( $value ) {
		$new_value = json_decode( $value );
		return wp_json_encode( $new_value );
	}
}
