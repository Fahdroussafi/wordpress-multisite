<?php
/**
 * Woostify Class
 *
 * @package  woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify' ) ) {
	/**
	 * The main Woostify class
	 */
	class Woostify {

		/**
		 * Setup class.
		 */
		public function __construct() {
			// Set the content width based on the theme's design and stylesheet.
			$this->woostify_content_width();
			$this->woostify_includes();

			// Add theme version into html tag.
			add_filter( 'language_attributes', 'woostify_info' );

			add_action( 'after_setup_theme', array( $this, 'woostify_setup' ) );
			add_action( 'wp', array( $this, 'woostify_wp_action' ) );
			add_action( 'widgets_init', array( $this, 'woostify_widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'woostify_scripts' ), 10 );
			add_filter( 'wpcf7_load_css', '__return_false' );
			add_filter( 'excerpt_length', array( $this, 'woostify_limit_excerpt_character' ), 99 );

			// Search form.
			add_filter( 'get_search_form', 'woostify_custom_search_form', 10, 2 );

			// ELEMENTOR.
			add_action( 'elementor/theme/register_locations', array( $this, 'woostify_register_elementor_locations' ) );
			add_action( 'elementor/preview/enqueue_scripts', array( $this, 'woostify_elementor_preview_scripts' ) );
			add_action( 'init', array( $this, 'woostify_elementor_global_colors' ) );

			// Add Image column on blog list in admin screen.
			add_filter( 'manage_post_posts_columns', array( $this, 'woostify_columns_head' ), 10 );
			add_action( 'manage_post_posts_custom_column', array( $this, 'woostify_columns_content' ), 10, 2 );

			add_filter( 'body_class', array( $this, 'woostify_body_classes' ) );
			add_filter( 'wp_page_menu_args', array( $this, 'woostify_page_menu_args' ) );
			add_filter( 'navigation_markup_template', array( $this, 'woostify_navigation_markup_template' ) );
			add_action( 'customize_preview_init', array( $this, 'woostify_customize_live_preview' ) );
			add_filter( 'wp_tag_cloud', array( $this, 'woostify_remove_tag_inline_style' ) );
			add_filter( 'excerpt_more', array( $this, 'woostify_modify_excerpt_more' ) );

			// Compatibility.
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'woostify_add_elementor_widget' ) );
			add_filter( 'the_content', array( $this, 'woostify_modify_the_content' ) );
			add_action( 'init', array( $this, 'woostify_override_divi_color_pciker' ), 12 );

			add_action( 'wp_head', array( $this, 'sticky_footer_bar' ), 15 );

			// CONTENT.
			add_filter( 'wp_kses_allowed_html', 'woostify_modify_wp_kses_allowed_html' );
		}

		/**
		 * Ahihi
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 */
		public function woostify_nav_menu_start_el( $item_output, $item, $depth, $args ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}

			if ( 'mega_menu' === $item->object ) {
				$this->megamenu_width = get_post_meta( $item->ID, 'woostify_mega_menu_item_width', true );
				$this->megamenu_width = '' !== $this->megamenu_width ? $this->megamenu_width : 'content';
				$this->megamenu_url   = get_post_meta( $item->ID, 'woostify_mega_menu_item_url', true );
				$this->megamenu_icon  = get_post_meta( $item->ID, 'woostify_mega_menu_item_icon', true );
				$this->megamenu_icon  = str_replace( 'ti-', '', $this->megamenu_icon );

				$classes[] = 'menu-item-has-children';
				$classes[] = 'menu-item-has-mega-menu';
				$classes[] = 'has-mega-menu-' . $this->megamenu_width . '-width';
				$classes[] = woostify_is_elementor_page( $item->object_id ) ? 'mega-menu-elementor' : '';
				$classes   = array_filter( $classes );
			} else {
				$classes = array_filter( $item->classes );
			}

			$indent      = ( $depth ) ? str_repeat( $t, $depth ) : '';
			$classes     = array_filter( $item->classes );
			$has_child   = in_array( 'menu-item-has-children', $classes, true ) ? true : false;
			$class_names = implode( ' ', $classes );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			// Ids.
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			// Start output.
			$item_output = $indent . '<li' . $id . $class_names . '>';

			// Attributes.
			$atts           = array();
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';
			$atts           = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
			$attributes     = '';

			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
					$value       = 'mega_menu' === $item->object ? $href : $value;
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output .= $args->before;

			if ( ! empty( $item->attr_title ) ) {
				$item_output .= '<a' . $attributes . ' title="' . esc_attr( $item->attr_title ) . '">';
			} else {
				$item_output .= '<a' . $attributes . '>';
			}

			// Menu icon.
			if ( 'mega_menu' === $item->object && $this->megamenu_icon ) {
				$item_output .= '<span class="menu-item-icon">';
				$item_output .= Woostify_Icon::fetch_svg_icon( $this->megamenu_icon, false );
				$item_output .= '</span>';
			}

			$title = apply_filters( 'the_title', $item->title, $item->ID );
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			// Menu item text.
			$item_output .= $args->link_before . '<span class="menu-item-text">' . $title . '</span>' . $args->link_after;

			// Add arrow icon.
			if ( $has_child ) {
				$item_output .= '<span class="menu-item-arrow arrow-icon">' . Woostify_Icon::fetch_svg_icon( 'angle-down', false ) . '</span>';
			}

			$item_output .= '</a>';

			// Start Mega menu content.
			if ( 'mega_menu' === $item->object && 0 === $depth && ! woostify_is_elementor_editor() ) {
				$item_output .= '<ul class="sub-mega-menu">';
				$item_output .= '<div class="mega-menu-wrapper">';

				if ( woostify_is_elementor_page( $item->object_id ) ) {
					$frontend     = new \Elementor\Frontend();
					$item_output .= $frontend->get_builder_content_for_display( $item->object_id, true );
					wp_enqueue_style( 'elementor-frontend' );
					wp_reset_postdata();
				} else {
					$mega_args = array(
						'p'                   => $item->object_id,
						'post_type'           => 'mega_menu',
						'post_status'         => 'publish',
						'posts_per_page'      => 1,
						'ignore_sticky_posts' => 1,
					);

					$query = new WP_Query( $mega_args );

					if ( $query->have_posts() ) {
						ob_start();
						echo '<div class="mega-menu-inner-wrapper">';
						while ( $query->have_posts() ) {
							$query->the_post();

							the_content();
						}
						echo '</div>';
						$item_output .= ob_get_clean();

						// Reset post data.
						wp_reset_postdata();
					}
				}

				$item_output .= '</div>';
				$item_output .= '</ul>';
			} // End Mega menu content.

			$item_output .= $args->after;

			return $item_output;
		}

		/**
		 * Add elementor widget
		 */
		public function woostify_add_elementor_widget() {
			if ( ! woostify_is_elementor_activated() ) {
				return;
			}

			require_once WOOSTIFY_THEME_DIR . 'inc/compatibility/elementor/class-woostify-elementor-single-product-images.php';
		}

		/**
		 * Modify content
		 *
		 * @param object $content The content.
		 */
		public function woostify_modify_the_content( $content ) {
			if ( ! defined( 'ET_BUILDER_PLUGIN_VERSION' ) ) {
				return $content;
			}

			return et_builder_get_layout_opening_wrapper() . $content . et_builder_get_layout_closing_wrapper();
		}

		/**
		 * Modify again for Divi, lol
		 */
		public function woostify_override_divi_color_pciker() {
			if ( ! defined( 'ET_BUILDER_PLUGIN_VERSION' ) || ! is_customize_preview() ) {
				return;
			}

			wp_localize_script(
				'wp-color-picker',
				'wpColorPickerL10n',
				array(
					'clear'            => __( 'Clear', 'woostify' ),
					'clearAriaLabel'   => __( 'Clear color', 'woostify' ),
					'defaultString'    => __( 'Default', 'woostify' ),
					'defaultAriaLabel' => __( 'Select default color', 'woostify' ),
					'pick'             => __( 'Select Color', 'woostify' ),
					'defaultLabel'     => __( 'Color value', 'woostify' ),
				)
			);
		}

		/**
		 * Sticky footer bar
		 */
		public function sticky_footer_bar() {
			$options       = woostify_options( false );
			$header_layout = $options['header_layout'];
			if ( 'layout-7' !== $header_layout ) {
				remove_action( 'woostify_after_footer', 'woostify_sticky_footer_bar', 5 );
			} else {
				remove_action( 'woostify_before_footer', 'woostify_sticky_footer_bar', 15 );
			}
		}

		/**
		 * Includes
		 */
		public function woostify_includes() {
			// Nav menu walker.
			require_once WOOSTIFY_THEME_DIR . 'inc/class-woostify-walker-menu.php';
		}

		/**
		 * Set the content width based on the theme's design and stylesheet.
		 */
		public function woostify_content_width() {
			if ( ! isset( $content_width ) ) {
				// Pixel.
				$content_width = 1170;
			}
		}

		/**
		 * Get featured image
		 *
		 * @param int $post_ID The post id.
		 *
		 * @return     string Image src.
		 */
		public function woostify_get_featured_image_src( $post_ID ) {
			$img_id  = get_post_thumbnail_id( $post_ID );
			$img_src = WOOSTIFY_THEME_URI . 'assets/images/thumbnail-default.jpg';

			if ( $img_id ) {
				$src = wp_get_attachment_image_src( $img_id, 'thumbnail' );
				if ( $src ) {
					$img_src = $src[0];
				}
			}

			return $img_src;
		}

		/**
		 * Column head
		 *
		 * @param array $defaults The defaults.
		 */
		public function woostify_columns_head( $defaults ) {
			// See: https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns.
			$order    = array();
			$checkbox = 'cb';
			foreach ( $defaults as $key => $value ) {
				$order[ $key ] = $value;
				if ( $key === $checkbox ) {
					$order['thumbnail_image'] = __( 'Image', 'woostify' );
				}
			}

			return $order;
		}

		/**
		 * Column content
		 *
		 * @param string $column_name The column name.
		 * @param int    $post_ID The post id.
		 */
		public function woostify_columns_content( $column_name, $post_ID ) {
			if ( 'thumbnail_image' === $column_name ) {
				$_img_src = $this->woostify_get_featured_image_src( $post_ID );
				?>
				<a href="<?php echo esc_url( get_edit_post_link( $post_ID ) ); ?>">
					<img src="<?php echo esc_url( $_img_src ); ?>"/> </a>
				<?php
			}
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function woostify_is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function woostify_setup() {
			/*
			 * Load Localisation files.
			 *
			 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
			 */

			// Loads wp-content/languages/themes/woostify-it_IT.mo.
			load_theme_textdomain( 'woostify', WP_LANG_DIR . '/themes/' );

			// Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
			load_theme_textdomain( 'woostify', get_stylesheet_directory() . '/languages' );

			// Loads wp-content/themes/woostify/languages/it_IT.mo.
			load_theme_textdomain( 'woostify', WOOSTIFY_THEME_DIR . 'languages' );

			/**
			 * Add default posts and comments RSS feed links to head.
			 */
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );

			// Post formats.
			add_theme_support(
				'post-formats',
				array(
					'gallery',
					'image',
					'link',
					'quote',
					'video',
					'audio',
					'status',
					'aside',
				)
			);

			/**
			 * Enable support for site logo.
			 */
			add_theme_support(
				'custom-logo',
				apply_filters(
					'woostify_custom_logo_args',
					array(
						'height'      => 110,
						'width'       => 470,
						'flex-width'  => true,
						'flex-height' => true,
					)
				)
			);

			/**
			 * Register menu locations.
			 */
			register_nav_menus(
				apply_filters(
					'woostify_register_nav_menus',
					array(
						'primary'           => __( 'Primary Menu', 'woostify' ),
						'footer'            => __( 'Footer Menu', 'woostify' ),
						'mobile'            => __( 'Mobile Menu', 'woostify' ),
						'mobile_categories' => __( 'Mobile Categories Menu', 'woostify' ),
					)
				)
			);

			/*
			 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
			 * to output valid HTML5.
			 */
			add_theme_support(
				'html5',
				apply_filters(
					'woostify_html5_args',
					array(
						'search-form',
						'comment-form',
						'comment-list',
						'gallery',
						'caption',
						'widgets',
					)
				)
			);

			/**
			 * Setup the WordPress core custom background feature.
			 */
			add_theme_support(
				'custom-background',
				apply_filters(
					'woostify_custom_background_args',
					array(
						'default-color' => apply_filters( 'woostify_default_background_color', 'ffffff' ),
						'default-image' => '',
					)
				)
			);

			/**
			 * Declare support for title theme feature.
			 */
			add_theme_support( 'title-tag' );

			/**
			 * Declare support for selective refreshing of widgets.
			 */
			add_theme_support( 'customize-selective-refresh-widgets' );

			/**
			 * Gutenberg.
			 */
			$options = woostify_options( false );

			// Default block styles.
			add_theme_support( 'wp-block-styles' );

			// Responsive embedded content.
			add_theme_support( 'responsive-embeds' );

			// Editor styles.
			add_theme_support( 'editor-styles' );

			// Wide Alignment.
			add_theme_support( 'align-wide' );

			// Editor Color Palette.
			add_theme_support(
				'editor-color-palette',
				array(
					array(
						'name'  => __( 'Primary Color', 'woostify' ),
						'slug'  => 'woostify-primary',
						'color' => $options['theme_color'],
					),
					array(
						'name'  => __( 'Heading Color', 'woostify' ),
						'slug'  => 'woostify-heading',
						'color' => $options['heading_color'],
					),
					array(
						'name'  => __( 'Text Color', 'woostify' ),
						'slug'  => 'woostify-text',
						'color' => $options['text_color'],
					),
				)
			);

			// Block Font Sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name' => __( 'H6', 'woostify' ),
						'size' => $options['heading_h6_font_size'],
						'slug' => 'woostify-heading-6',
					),
					array(
						'name' => __( 'H5', 'woostify' ),
						'size' => $options['heading_h5_font_size'],
						'slug' => 'woostify-heading-5',
					),
					array(
						'name' => __( 'H4', 'woostify' ),
						'size' => $options['heading_h4_font_size'],
						'slug' => 'woostify-heading-4',
					),
					array(
						'name' => __( 'H3', 'woostify' ),
						'size' => $options['heading_h3_font_size'],
						'slug' => 'woostify-heading-3',
					),
					array(
						'name' => __( 'H2', 'woostify' ),
						'size' => $options['heading_h2_font_size'],
						'slug' => 'woostify-heading-2',
					),
					array(
						'name' => __( 'H1', 'woostify' ),
						'size' => $options['heading_h1_font_size'],
						'slug' => 'woostify-heading-1',
					),
				)
			);

			// Boostify Header Footer plugin support.
			add_theme_support( 'boostify-header-footer' );
		}

		/**
		 * WP Action
		 */
		public function woostify_wp_action() {
			// Support Elementor Pro - Theme Builder.
			if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
				return;
			}

			if ( woostify_elementor_has_location( 'header' ) && woostify_elementor_has_location( 'footer' ) ) {
				add_action( 'woostify_theme_header', 'woostify_view_open', 0 );
				add_action( 'woostify_after_footer', 'woostify_view_close', 0 );
			} elseif ( woostify_elementor_has_location( 'header' ) && ! woostify_elementor_has_location( 'footer' ) ) {
				add_action( 'woostify_theme_header', 'woostify_view_open', 0 );
			} elseif ( ! woostify_elementor_has_location( 'header' ) && woostify_elementor_has_location( 'footer' ) ) {
				add_action( 'woostify_after_footer', 'woostify_view_close', 0 );
			}
		}

		/**
		 * Register widget area.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		public function woostify_widgets_init() {
			// Woostify widgets.
			require_once WOOSTIFY_THEME_DIR . 'inc/widget/class-woostify-recent-post-thumbnail.php';

			// Setup.
			$sidebar_args['sidebar'] = array(
				'name'          => __( 'Main Sidebar', 'woostify' ),
				'id'            => 'sidebar',
				'description'   => __( 'Appears in the sidebar of the site.', 'woostify' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
			);

			if ( class_exists( 'woocommerce' ) ) {
				$sidebar_args['shop_sidebar'] = array(
					'name'          => __( 'Woocommerce Sidebar', 'woostify' ),
					'id'            => 'sidebar-shop',
					'description'   => __( ' Appears in the sidebar of shop/product page.', 'woostify' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
				);
			}

			$sidebar_args['footer'] = array(
				'name'          => __( 'Footer Widget', 'woostify' ),
				'id'            => 'footer',
				'description'   => __( 'Appears in the footer section of the site.', 'woostify' ),
				'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
				'after_widget'  => '</div>',
			);

			foreach ( $sidebar_args as $sidebar => $args ) {
				$widget_tags = array(
					'before_title' => '<h6 class="widget-title">',
					'after_title'  => '</h6>',
				);

				/**
				 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
				 */
				$filter_hook = sprintf( 'woostify_%s_widget_tags', $sidebar );
				$widget_tags = apply_filters( $filter_hook, $widget_tags );

				if ( is_array( $widget_tags ) ) {
					register_sidebar( $args + $widget_tags );
				}
			}

			// Register.
			register_widget( 'Woostify_Recent_Post_Thumbnail' );
		}

		/**
		 * Enqueue scripts and styles.
		 */
		public function woostify_scripts() {
			$options = woostify_options( false );

			// Import parent theme if using child-theme.
			if ( is_child_theme() ) {
				wp_enqueue_style(
					'woostify-parent-style',
					get_template_directory_uri() . '/style.css',
					array(),
					woostify_version()
				);
			}

			/**
			 * Styles
			 */
			wp_enqueue_style(
				'woostify-style',
				get_stylesheet_uri(),
				array(),
				woostify_version()
			);

			if ( is_rtl() ) {
				wp_enqueue_style(
					'woostify-rtl',
					WOOSTIFY_THEME_URI . 'rtl.css',
					array(),
					woostify_version()
				);
			}

			/**
			 * Scripts
			 */
			// For IE.
			if ( 'ie' === woostify_browser_detection() ) {
				// Fetch API polyfill.
				wp_enqueue_script(
					'woostify-fetch-api-polyfill',
					WOOSTIFY_THEME_URI . 'assets/js/fetch-api-polyfill' . woostify_suffix() . '.js',
					array(),
					woostify_version(),
					true
				);

				// Foreach polyfill.
				wp_enqueue_script(
					'woostify-for-each-polyfill',
					WOOSTIFY_THEME_URI . 'assets/js/for-each-polyfill' . woostify_suffix() . '.js',
					array(),
					woostify_version(),
					true
				);
			}

			// General script.
			wp_enqueue_script(
				'woostify-general',
				WOOSTIFY_THEME_URI . 'assets/js/general' . woostify_suffix() . '.js',
				array( 'jquery' ),
				woostify_version(),
				true
			);

			wp_localize_script(
				'woostify-general',
				'woostify_svg_icons',
				array(
					'file_url' => WOOSTIFY_THEME_URI . 'assets/svg/svgs.json',
					'list'     => wp_json_encode( Woostify_Icon::fetch_all_svg_icon() ),
				)
			);

			// Fallback add wc_add_to_cart_params.
			if ( woostify_is_woocommerce_activated() && 'yes' !== get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
				wp_localize_script(
					'woostify-general',
					'wc_add_to_cart_params',
					array(
						'ajax_url'                => WC()->ajax_url(),
						'wc_ajax_url'             => WC_AJAX::get_endpoint( '%%endpoint%%' ),
						'i18n_view_cart'          => esc_attr__( 'View cart', 'woostify' ),
						'cart_url'                => apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null ),
						'is_cart'                 => is_cart(),
						'cart_redirect_after_add' => get_option( 'woocommerce_cart_redirect_after_add' ),
					)
				);
			}

			// Mobile menu.
			wp_enqueue_script(
				'woostify-navigation',
				WOOSTIFY_THEME_URI . 'assets/js/navigation' . woostify_suffix() . '.js',
				array( 'jquery' ),
				woostify_version(),
				true
			);

			// Arrive jquery plugin.
			wp_register_script(
				'woostify-arrive',
				WOOSTIFY_THEME_URI . 'assets/js/arrive.min.js',
				array(),
				woostify_version(),
				true
			);

			// Quantity button.
			wp_register_script(
				'woostify-quantity-button',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/quantity-button' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);

			// Multi step checkout.
			wp_register_script(
				'woostify-multi-step-checkout',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/multi-step-checkout' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);

			if ( class_exists( 'woocommerce' ) && is_checkout() ) {
				$wc_total = WC()->cart->get_totals();
				$price    = 'yes' === get_option( 'woocommerce_calc_taxes' ) ? ( (float) $wc_total['cart_contents_total'] + (float) $wc_total['total_tax'] ) : $wc_total['cart_contents_total'];

				wp_localize_script(
					'woostify-multi-step-checkout',
					'woostify_multi_step_checkout',
					array(
						'ajax_none'     => wp_create_nonce( 'woostify_update_checkout_nonce' ),
						'content_total' => wp_kses( $price, array() ),
						'cart_total'    => wp_kses( wc_price( $wc_total['total'] ), array() ),
					)
				);
			}

			// Woocommerce sidebar for mobile.
			wp_register_script(
				'woostify-woocommerce-sidebar',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/woocommerce-sidebar' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);

			// Congrats confetti effect.
			wp_register_script(
				'woostify-congrats-confetti-effect',
				WOOSTIFY_THEME_URI . 'assets/js/confetti' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);

			// Woocommerce.
			wp_register_script(
				'woostify-woocommerce',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/woocommerce' . woostify_suffix() . '.js',
				array( 'jquery', 'woostify-arrive', 'woostify-quantity-button' ),
				woostify_version(),
				true
			);

			if ( $options['shop_single_image_zoom'] ) {
				// Product gallery zoom.
				wp_register_script(
					'easyzoom',
					WOOSTIFY_THEME_URI . 'assets/js/easyzoom' . woostify_suffix() . '.js',
					array( 'jquery' ),
					woostify_version(),
					true
				);

				// Product gallery zoom handle.
				wp_register_script(
					'easyzoom-handle',
					WOOSTIFY_THEME_URI . 'assets/js/woocommerce/easyzoom-handle' . woostify_suffix() . '.js',
					array( 'easyzoom' ),
					woostify_version(),
					true
				);
			}

			// Product varitions.
			wp_register_script(
				'woostify-product-variation',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/product-variation' . woostify_suffix() . '.js',
				array( 'jquery' ),
				woostify_version(),
				true
			);

			// Lightbox js.
			wp_register_script(
				'lity',
				WOOSTIFY_THEME_URI . 'assets/js/lity' . woostify_suffix() . '.js',
				array( 'jquery' ),
				woostify_version(),
				true
			);

			// Sticky sidebar js.
			wp_register_script(
				'sticky-sidebar',
				WOOSTIFY_THEME_URI . 'assets/js/sticky-sidebar' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);

			// Tiny slider js.
			wp_register_script(
				'tiny-slider',
				WOOSTIFY_THEME_URI . 'assets/js/tiny-slider' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);

			// Product images ( Flickity ).
			wp_register_script(
				'woostify-flickity',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/flickity.pkgd' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);

			$ios_script = '
			( function () {
				var touchingCarousel = false,
				touchStartCoords;

				document.body.addEventListener( "touchstart", function( e ) {
					if ( e.target.closest( ".flickity-slider" ) ) {
						touchingCarousel = true;
					} else {
						touchingCarousel = false;
						return;
					}

					touchStartCoords = {
						x: e.touches[0].pageX,
						y: e.touches[0].pageY
					}
				});

				document.body.addEventListener( "touchmove" , function(e) {
					if ( ! ( touchingCarousel && e.cancelable ) ) {
						return;
					}

					var moveVector = {
						x: e.touches[0].pageX - touchStartCoords.x,
						y: e.touches[0].pageY - touchStartCoords.y
					};

					if ( Math.abs( moveVector.x ) > 7 )
						e.preventDefault()

				}, { passive: false } );
			} ) ();
			';
			wp_add_inline_script( 'woostify-flickity', $ios_script );

			// Product images ( Tiny slider ).
			wp_register_script(
				'woostify-product-images',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/product-images' . woostify_suffix() . '.js',
				array( 'jquery', 'tiny-slider', 'woostify-flickity' ),
				woostify_version(),
				true
			);

			if ( $options['shop_single_image_lightbox'] ) {
				// Photoswipe init js.
				wp_register_script(
					'photoswipe-init',
					WOOSTIFY_THEME_URI . 'assets/js/photoswipe-init' . woostify_suffix() . '.js',
					array( 'photoswipe', 'photoswipe-ui-default' ),
					woostify_version(),
					true
				);
			}

			// Ajax single add to cart.
			if ( $options['shop_single_ajax_add_to_cart'] ) {
				wp_register_script(
					'woostify-single-add-to-cart',
					WOOSTIFY_THEME_URI . 'assets/js/woocommerce/ajax-single-add-to-cart' . woostify_suffix() . '.js',
					array(),
					woostify_version(),
					true
				);
			}

			// Sticky footer bar.
			if ( $options['sticky_footer_bar_enable'] && $options['sticky_footer_bar_hide_when_scroll'] ) {
				wp_enqueue_script(
					'woostify-sticky-footer-bar',
					WOOSTIFY_THEME_URI . 'assets/js/sticky-footer-bar' . woostify_suffix() . '.js',
					array(),
					woostify_version(),
					true
				);
			}

			// Infinite scroll.
			wp_register_script(
				'woostify-infinite-scroll-plugin',
				WOOSTIFY_THEME_URI . 'assets/js/woocommerce/infinite-scroll.pkgd.min.js',
				array(),
				woostify_version(),
				true
			);

			// Comment reply.
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			do_action( 'woostify_enqueue_scripts' );
		}

		/**
		 * Support Elementor Location
		 *
		 * @param array|object $elementor_theme_manager The elementor theme manager.
		 */
		public function woostify_register_elementor_locations( $elementor_theme_manager ) {
			$elementor_theme_manager->register_location(
				'header',
				array(
					'hook'         => 'woostify_theme_header',
					'remove_hooks' => array( 'woostify_template_header' ),
				)
			);

			$elementor_theme_manager->register_location(
				'footer',
				array(
					'hook'         => 'woostify_theme_footer',
					'remove_hooks' => array( 'woostify_template_footer' ),
				)
			);

			$elementor_theme_manager->register_all_core_location();
		}

		/**
		 * Elementor pewview scripts
		 */
		public function woostify_elementor_preview_scripts() {
			// Elementor widgets js.
			wp_enqueue_script(
				'woostify-elementor-live-preview',
				WOOSTIFY_THEME_URI . 'assets/js/elementor-preview' . woostify_suffix() . '.js',
				array(),
				woostify_version(),
				true
			);
		}

		/**
		 * Limit the character length in exerpt
		 *
		 * @param int $length The length.
		 */
		public function woostify_limit_excerpt_character( $length ) {
			// Don't change anything inside /wp-admin/.
			if ( is_admin() ) {
				return $length;
			}

			$options = woostify_options( false );
			$length  = $options['blog_list_limit_exerpt'];

			return $length;
		}

		/**
		 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
		 *
		 * @param array $args Configuration arguments.
		 *
		 * @return array
		 */
		public function woostify_page_menu_args( $args ) {
			$args['show_home'] = true;

			return $args;
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		public function woostify_body_classes( $classes ) {
			// Get theme options.
			$options = woostify_options( false );

			// Infinite scroll.
			if ( $options['shop_page_infinite_scroll_enable'] ) {
				$classes[] = 'infinite-scroll-active';
			}

			// Broser detection.
			if ( woostify_browser_detection() ) {
				$classes[] = woostify_browser_detection() . '-detected';
			}

			// Detect site using child theme.
			if ( is_child_theme() ) {
				$classes[] = 'child-theme-detected';
			}

			// Site container layout.
			$classes[] = woostify_get_site_container_class();

			// Header layout.
			$classes[] = apply_filters( 'woostify_has_header_layout_classes', 'has-header-layout-1' );

			// Header transparent.
			if ( woostify_header_transparent() ) {
				$classes[] = 'has-header-transparent header-transparent-for-' . $options['header_transparent_enable_on'];
			}

			// Sidebar class detected.
			$classes[] = woostify_sidebar_class();

			// Blog page layout.
			$classes[] = ( ( ! is_singular( 'post' ) && woostify_is_blog() ) || ( is_search() && 'any' === get_query_var( 'post_type' ) ) ) ? 'blog-layout-' . $options['blog_list_layout'] : '';

			// Detect page created by Divi builder.
			if ( woostify_is_divi_page() ) {
				$classes[] = 'edited-by-divi-builder';
			}

			// Disable cart sidebar.
			if ( ( defined( 'ELEMENTOR_PRO_VERSION' ) && 'yes' === get_option( 'elementor_use_mini_cart_template' ) ) || defined( 'XOO_WSC_PLUGIN_FILE' ) ) {
				$classes[] = 'no-cart-sidebar';
			}

			return array_filter( $classes );
		}

		/**
		 * Custom navigation markup template hooked into `navigation_markup_template` filter hook.
		 */
		public function woostify_navigation_markup_template() {
			$template  = '<nav class="post-navigation navigation %1$s" aria-label="' . esc_attr__( 'Post Pagination', 'woostify' ) . '">';
			$template .= '<h2 class="screen-reader-text">%2$s</h2>';
			$template .= '<div class="nav-links">%3$s</div>';
			$template .= '</nav>';

			return apply_filters( 'woostify_navigation_markup_template', $template );
		}

		/**
		 * Customizer live preview
		 */
		public function woostify_customize_live_preview() {
			wp_enqueue_script(
				'woostify-customizer-preview',
				WOOSTIFY_THEME_URI . 'assets/js/customizer-preview' . woostify_suffix() . '.js',
				array( 'jquery' ),
				woostify_version(),
				true
			);
		}

		/**
		 * Remove inline css on tag cloud
		 *
		 * @param string $string tagCloud.
		 */
		public function woostify_remove_tag_inline_style( $string ) {
			return preg_replace( '/ style=("|\')(.*?)("|\')/', '', $string );
		}


		/**
		 * Modify excerpt more to `...`
		 *
		 * @param string $more More exerpt.
		 */
		public function woostify_modify_excerpt_more( $more ) {
			// Don't change anything inside /wp-admin/.
			if ( is_admin() ) {
				return $more;
			}

			$more = apply_filters( 'woostify_excerpt_more', '...' );

			return $more;
		}

		/**
		 * Add color to Elementor Global Color
		 */
		public function woostify_elementor_global_colors() {
			if ( '__DEFAULT__' === get_option( 'elementor_disable_color_schemes', '__DEFAULT__' ) ) {
				update_option( 'elementor_disable_color_schemes', 'yes' );
			}

			add_filter(
				'elementor/schemes/enabled_schemes',
				function ( $s ) {
					return $s;
				}
			);

			add_filter(
				'rest_request_after_callbacks',
				function ( $response, $handler, $request ) {
					$options = woostify_options( false );
					$route   = $request->get_route();
					$rest_id = substr( $route, strrpos( $route, '/' ) + 1 );

					$palettes = array(
						'woostify_color_1' => array(
							'id'    => 'woostify_color_1',
							'title' => __( 'Theme Primary Color', 'woostify' ),
							'value' => $options['theme_color'],
						),

						'woostify_color_2' => array(
							'id'    => 'woostify_color_2',
							'title' => __( 'Theme Text Color', 'woostify' ),
							'value' => $options['text_color'],
						),

						'woostify_color_3' => array(
							'id'    => 'woostify_color_3',
							'title' => __( 'Theme Accent Color', 'woostify' ),
							'value' => $options['accent_color'],
						),

						'woostify_color_6' => array(
							'id'    => 'woostify_color_6',
							'title' => __( 'Theme Link Hover Color', 'woostify' ),
							'value' => $options['link_hover_color'],
						),

						'woostify_color_4' => array(
							'id'    => 'woostify_color_4',
							'title' => __( 'Theme Extra Color 1', 'woostify' ),
							'value' => $options['extra_color_1'],
						),

						'woostify_color_5' => array(
							'id'    => 'woostify_color_5',
							'title' => __( 'Theme Extra Color 2', 'woostify' ),
							'value' => $options['extra_color_2'],
						),
					);

					if ( isset( $palettes[ $rest_id ] ) ) {
						return new \WP_REST_Response( $palettes[ $rest_id ] );
					}

					if ( '/elementor/v1/globals' === $route ) {
						$data   = $response->get_data();
						$colors = array(
							'color1' => $options['theme_color'],
							'color2' => $options['text_color'],
							'color3' => $options['accent_color'],
							'color6' => $options['link_hover_color'],
							'color4' => $options['extra_color_1'],
							'color5' => $options['extra_color_2'],
						);

						$colors_for_palette = array(
							'woostify_color_1' => 'color1',
							'woostify_color_2' => 'color2',
							'woostify_color_3' => 'color3',
							'woostify_color_6' => 'color6',
							'woostify_color_4' => 'color4',
							'woostify_color_5' => 'color5',
						);

						foreach ( $palettes as $key => $value ) {
							$value['value'] = $colors[ $colors_for_palette[ $key ] ];

							$data['colors'][ $key ] = $value;
						}

						$response->set_data( $data );
					}

					return $response;
				},
				1000,
				3
			);
		}
	}

	$woostify = new Woostify();
}
