<?php
/**
 * Woostify Get CSS
 *
 * @package  woostify
 */

/**
 * The Woostify Get CSS class
 */
class Woostify_Get_CSS {
	/**
	 * Base path.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var string
	 */
	protected $base_path;

	/**
	 * Base URL.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var string
	 */
	protected $base_url;

	/**
	 * Subfolder name.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var string
	 */
	protected $subfolder_name;

	/**
	 * The fonts folder.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var string
	 */
	protected $style_folder;

	/**
	 * The local stylesheet's path.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var string
	 */
	protected $stylesheet_path;

	/**
	 * The local stylesheet's URL.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var string
	 */
	protected $local_stylesheet_url;

	/**
	 * The final CSS.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @var string
	 */
	protected $css;

	/**
	 * Cleanup routine frequency.
	 */
	const CLEANUP_FREQUENCY = 'monthly';

	/**
	 * Wp enqueue scripts
	 */
	public function __construct() {
		add_action( 'woostify_enqueue_scripts', array( $this, 'woostify_dynamic_css' ), 10 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'woostify_guten_block_editor_assets' ) );

		// Add a cleanup routine.
		$this->schedule_cleanup();
		add_action( 'delete_dynamic_stylesheet_folder', array( $this, 'delete_dynamic_stylesheet_folder' ) );
	}

	/**
	 * Schedule a cleanup.
	 *
	 * This way dynamic stylesheet files will get updated regularly,
	 * and we avoid edge cases where unused files remain in the server.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function schedule_cleanup() {
		if ( ! is_multisite() || ( is_multisite() && is_main_site() ) ) {
			if ( ! wp_next_scheduled( 'delete_dynamic_stylesheet_folder' ) && ! wp_installing() ) {
				wp_schedule_event( time(), self::CLEANUP_FREQUENCY, 'delete_dynamic_stylesheet_folder' );
			}
		}
	}

	/**
	 * Get the base path.
	 *
	 * @return string
	 */
	public function get_base_path() {
		if ( ! $this->base_path ) {
			$this->base_path = apply_filters( 'woostify_dynamic_style_base_path', $this->get_filesystem()->wp_content_dir() );
		}
		return $this->base_path;
	}

	/**
	 * Get the base URL.
	 *
	 * @return string
	 */
	public function get_base_url() {
		if ( ! $this->base_url ) {
			$this->base_url = apply_filters( 'woostify_dynamic_style_base_url', content_url() );
		}
		return $this->base_url;
	}

	/**
	 * Get the subfolder name.
	 *
	 * @return string
	 */
	public function get_subfolder_name() {
		if ( ! $this->subfolder_name ) {
			$this->subfolder_name = apply_filters( 'woostify_dynamic_style_subfolder_name', 'woostify-stylesheet' );
		}
		return $this->subfolder_name;
	}

	/**
	 * Get the folder for dynamic style.
	 *
	 * @return string
	 */
	public function get_style_folder() {
		if ( ! $this->style_folder ) {
			$this->style_folder = $this->get_base_path();
			if ( $this->get_subfolder_name() ) {
				$this->style_folder .= '/' . $this->get_subfolder_name();
			}
		}
		return $this->style_folder;
	}

	/**
	 * Check if the local stylesheet exists.
	 *
	 * @return bool
	 */
	public function local_file_exists() {
		return ( ! file_exists( $this->get_local_stylesheet_path() ) );
	}

	/**
	 * Get the stylesheet path.
	 *
	 * @access public
	 * @since 1.1.0
	 * @return string
	 */
	public function get_local_stylesheet_path() {
		if ( ! $this->stylesheet_path ) {
			$this->stylesheet_path = $this->get_style_folder() . '/' . $this->get_stylesheet_filename() . '.css';
		}
		return $this->stylesheet_path;
	}

	/**
	 * Get the local stylesheet filename.
	 *
	 * This is a hash, generated from the site-URL, the wp-content path and the URL.
	 * This way we can avoid issues with sites changing their URL, or the wp-content path etc.
	 *
	 * @return string
	 */
	public function get_stylesheet_filename() {
		return apply_filters( 'woostify_dynamic_style_filename', 'woostify-dynamic-css' );
	}

	/**
	 * Get the local stylesheet URL.
	 *
	 * @return string
	 */
	public function get_local_stylesheet_url() {
		if ( ! $this->local_stylesheet_url ) {
			$this->local_stylesheet_url = str_replace(
				$this->get_base_path(),
				$this->get_base_url(),
				$this->get_local_stylesheet_path()
			);
		}
		return $this->local_stylesheet_url;
	}

	/**
	 * Get the local URL which contains the styles.
	 *
	 * Fallback to the remote URL if we were unable to write the file locally.
	 *
	 * @return string
	 */
	public function get_url() {

		// Check if the local stylesheet exists.
		if ( $this->local_file_exists() ) {
			// Attempt to update the stylesheet. Return the local URL on success.
			if ( $this->write_stylesheet() ) {
				return $this->get_local_stylesheet_url();
			}
		}

		// If the local file exists, return its URL, with a fallback to the remote URL.
		return file_exists( $this->get_local_stylesheet_path() )
			? $this->get_local_stylesheet_url()
			: false;
	}

	/**
	 * Write the CSS to the filesystem.
	 *
	 * @return string|false Returns the absolute path of the file on success, or false on fail.
	 */
	protected function write_stylesheet() {
		$file_path  = $this->get_local_stylesheet_path();
		$filesystem = $this->get_filesystem();

		if ( ! defined( 'FS_CHMOD_DIR' ) ) {
			define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );
		}

		// If the folder doesn't exist, create it.
		if ( ! file_exists( $this->get_style_folder() ) ) {
			$this->get_filesystem()->mkdir( $this->get_style_folder(), FS_CHMOD_DIR );
		}

		// If the file doesn't exist, create it. Return false if it can not be created.
		if ( ! $filesystem->exists( $file_path ) && ! $filesystem->touch( $file_path ) ) {
			return false;
		}

		// If we got this far, we need to write the file.
		// Get the CSS.
		if ( ! $this->css ) {
			$this->get_styles();
		}

		// Put the contents in the file. Return false if that fails.
		if ( ! $filesystem->put_contents( $file_path, $this->css ) ) {
			return false;
		}

		return $file_path;
	}

	/**
	 * Delete the style folder.
	 *
	 * @access public
	 *
	 * @return bool
	 */
	public function delete_dynamic_stylesheet_folder() {
		return $this->get_filesystem()->delete( $this->get_style_folder(), true );
	}

	/**
	 * Get Customizer css.
	 *
	 * @see get_woostify_theme_mods()
	 * @return array $styles the css
	 */
	public function get_styles() {

		// Get all theme option value.
		$options = woostify_options( false );

		// GENERATE CSS.
		// Remove outline select on Firefox.
		$styles = '
			select:-moz-focusring{
				text-shadow: 0 0 0 ' . esc_attr( $options['text_color'] ) . ';
			}
		';

		// For mega menu.
		$styles = '
			.main-navigation .mega-menu-inner-wrapper {
				width: 100%;
				max-width: ' . esc_attr( $options['container_width'] ) . 'px;
				margin: 0 auto;
				padding-left: 15px;
				padding-right: 15px;
			}
		';

		// Container.
		$styles .= '
			@media (min-width: 992px) {
				.woostify-container,
				.site-boxed-container #view,
				.site-content-boxed-container .site-content {
					max-width: ' . esc_attr( $options['container_width'] ) . 'px;
				}
			}
		';

		// Logo width.
		$logo_width        = $options['logo_width'];
		$tablet_logo_width = $options['tablet_logo_width'];
		$mobile_logo_width = $options['mobile_logo_width'];
		if ( $logo_width && $logo_width > 0 ) {
			$styles .= '
				@media ( min-width: 769px ) {
					.elementor .site-branding img,
					.site-branding img{
						max-width: ' . esc_attr( $logo_width ) . 'px;
					}
				}
			';
		}

		if ( $tablet_logo_width && $tablet_logo_width > 0 ) {
			$styles .= '
				@media ( min-width: 481px ) and ( max-width: 768px ) {
					.elementor .site-branding img,
					.site-branding img{
						max-width: ' . esc_attr( $tablet_logo_width ) . 'px;
					}
				}
			';
		}

		if ( $mobile_logo_width && $mobile_logo_width > 0 ) {
			$styles .= '
				@media ( max-width: 480px ) {
					.elementor .site-branding img,
					.site-branding img{
						max-width: ' . esc_attr( $mobile_logo_width ) . 'px;
					}
				}
			';
		}

		// Topbar.
		$styles .= '
			.topbar{
				background-color: ' . esc_attr( $options['topbar_background_color'] ) . ';
				padding: ' . esc_attr( $options['topbar_space'] ) . 'px 0;
			}
			.topbar *{
				color: ' . esc_attr( $options['topbar_text_color'] ) . ';
			}
		';

		// Menu Breakpoint.
		$styles .= '
			@media ( max-width: ' . esc_attr( $options['header_menu_breakpoint'] - 1 ) . 'px ) {
				.primary-navigation.primary-mobile-navigation + .primary-navigation{
					display: none;
				}
				.has-header-layout-1 .wrap-toggle-sidebar-menu {
					display: block;
				}
				.site-header-inner .site-navigation, .site-header-inner .site-search {
					display: none;
				}
				.has-header-layout-1 .sidebar-menu {
					display: block;
				}
				.has-header-layout-1 .site-navigation {
					text-align: left;
				}
				.has-header-layout-3 .header-layout-3 .wrap-toggle-sidebar-menu {
					display: block !important;
				}
				.has-header-layout-3 .header-layout-3 .navigation-box, .has-header-layout-3 .header-layout-3 .left-content {
					display: none;
				}
				.has-header-layout-4 .header-layout-4 .wrap-toggle-sidebar-menu {
					display: block !important;
				}
				.has-header-layout-5 .header-layout-5 .wrap-toggle-sidebar-menu {
					display: block !important;
				}
				.has-header-layout-5 .header-layout-5 .navigation-box, .has-header-layout-5 .header-layout-5 .center-content {
					display: none;
				}
				.site-branding {
					text-align: center;
				}
				.header-layout-6 .wrap-toggle-sidebar-menu, .header-layout-6 .header-content-top .shopping-bag-button {
					display: block !important;
				}
				.header-layout-6 .content-top-right, .header-layout-6 .header-content-bottom {
					display: none;
				}
				.header-layout-8 .content-top-right, .header-layout-8 .header-content-bottom {
					display: none !important;
				}
				.header-layout-8 .wrap-toggle-sidebar-menu, .header-layout-8 .header-search-icon {
					display: block !important;
				}
				.header-layout-8 .header-content-top .site-tools {
					display: flex !important;
				}
				.header-layout-1 .site-branding {
				    flex: 0 1 auto;
				}
				.header-layout-1 .wrap-toggle-sidebar-menu, .header-layout-1 .site-tools {
				    flex: 1 1 0px;
				}
				.site-header-inner .site-navigation, .site-header-inner .site-search {
					display: none;
				}
				.header-layout-1 .wrap-toggle-sidebar-menu,
				  .header-layout-1 .site-tools {
				    flex: 1 1 0px;
				}

				.header-layout-1 .site-branding {
				    flex: 0 1 auto;
				}

				.site-header-inner .woostify-container {
				    padding: 15px;
				    justify-content: center;
				}

				.site-header-inner .logo {
				    max-width: 70%;
				    margin: 0 auto;
				}

				.site-tools .header-search-icon,
				  .site-tools .my-account {
				    display: none;
				}

				.site-header .shopping-bag-button {
				    margin-right: 15px;
				}

				.has-custom-mobile-logo a:not(.custom-mobile-logo-url) {
				    display: none;
				}

				.has-header-transparent.header-transparent-for-mobile .site-header {
				    position: absolute;
				}

				.header-layout-1 .wrap-toggle-sidebar-menu,
				.header-layout-1 .site-tools {
					flex: 1 1 0px;
				}

				.header-layout-1 .site-branding {
				    flex: 0 1 auto;
				}

				.site-header-inner .woostify-container {
				    padding: 15px;
				    justify-content: center;
				}

				.site-header-inner .logo {
				    max-width: 70%;
				    margin: 0 auto;
				}

				.site-tools .header-search-icon,
				.site-tools .my-account {
				    display: none;
				}

				.has-header-transparent.header-transparent-for-mobile .site-header {
				    position: absolute;
				}
				.sub-mega-menu {
    				display: none;
  				}
  				.site-branding .custom-mobile-logo-url {
					display: block;
				}

				.has-custom-mobile-logo.logo-transparent .custom-transparent-logo-url {
					display: block;
				}

				.mobile-nav-tab li.active:after {
					background: ' . $options['theme_color'] . ';
				}
			}
		';

		$styles .= '
			@media ( min-width: ' . esc_attr( $options['header_menu_breakpoint'] ) . 'px ) {
				.primary-navigation.primary-mobile-navigation,
				.primary-navigation.categories-mobile-menu,
				.mobile-nav-tab {
					display: none;
				}

				.has-header-layout-1 .wrap-toggle-sidebar-menu {
					display: none;
				}

				.site-branding .custom-mobile-logo-url {
					display: none;
				}

				.sidebar-menu .main-navigation .primary-navigation > .menu-item {
				    display: block;
				}

				.sidebar-menu .main-navigation .primary-navigation > .menu-item > a {
					padding: 0;
				}

				.main-navigation .primary-navigation > .menu-item > a {
				    padding: 20px 0;
				    margin: 0 20px;
				    display: flex;
				    justify-content: space-between;
				    align-items: center;
				}

				.main-navigation .primary-navigation > .menu-item {
				    display: inline-flex;
				    line-height: 1;
				    align-items: center;
				    flex-direction: column;
				}

				.has-header-layout-1 .sidebar-menu {
				    display: none;
				}

				.sidebar-menu .main-navigation .primary-navigation .menu-item-has-mega-menu .mega-menu-wrapper {
				    min-width: auto;
				    max-width: 100%;
				    transform: none;
				    position: static;
				    box-shadow: none;
				    opacity: 1;
				    visibility: visible;
				}

				.sidebar-menu .main-navigation .primary-navigation .sub-menu {
				    margin-left: 20px !important;
				}

				.sidebar-menu .main-navigation .primary-navigation .sub-menu:not(.sub-mega-menu) {
				    transition-duration: 0s;
				}

				.sidebar-menu .main-navigation .primary-navigation > .menu-item ul:not(.sub-mega-menu) {
				    opacity: 1;
				    visibility: visible;
				    transform: none;
				    position: static;
				    box-shadow: none;
				    transition-duration: 0s;
				    min-width: auto;
				}

				.sidebar-menu .main-navigation .primary-navigation > .menu-item ul:not(.sub-mega-menu) a {
				    padding-right: 0;
				    padding-left: 0;
				}

				.sidebar-menu-open .sidebar-menu .site-navigation {
    				left: 60px;
   					right: 60px;
  				}

				.has-header-transparent.header-transparent-for-desktop .site-header {
  					position: absolute;
				}

				.woostify-nav-menu-widget .woostify-toggle-nav-menu-button, .woostify-nav-menu-widget .site-search, .woostify-nav-menu-widget .woostify-nav-menu-account-action {
				    display: none;
				}

				.sidebar-menu-open .sidebar-menu .site-navigation {
				    left: 60px;
				    right: 60px;
				}

				.has-header-transparent.header-transparent-for-desktop .site-header {
				    position: absolute;
				}

				.has-custom-mobile-logo .custom-mobile-logo-url {
				    display: none;
				}

				.main-navigation li {
					list-style: none;
				}

				.site-header-inner .site-navigation:last-child .main-navigation {
				    padding-right: 0;
			  	}

			  	.main-navigation ul {
				    padding-left: 0;
				    margin: 0;
				}

				.main-navigation .primary-navigation {
				    font-size: 0;
				}

				.main-navigation .primary-navigation > .menu-item .sub-menu {
				    opacity: 0;
				    visibility: hidden;
				    position: absolute;
				    top: 110%;
				    left: 0;
				    margin-left: 0;
				    min-width: 180px;
				    text-align: left;
				    z-index: -1;
				}

				.main-navigation .primary-navigation > .menu-item .sub-menu .menu-item-has-children .menu-item-arrow {
				    transform: rotate(-90deg);
				}

				.main-navigation .primary-navigation > .menu-item .sub-menu a {
				    padding: 10px 0 10px 20px;
				    display: flex;
				    justify-content: space-between;
				    align-items: center;
				}
				.main-navigation .primary-navigation > .menu-item .sub-menu a.tinvwl_add_to_wishlist_button, .main-navigation .primary-navigation > .menu-item .sub-menu a.woocommerce-loop-product__link, .main-navigation .primary-navigation > .menu-item .sub-menu a.loop-add-to-cart-btn {
				    padding: 0;
				    justify-content: center;
				    border-radius: 0;
				}

				.main-navigation .primary-navigation > .menu-item .sub-menu a.tinvwl_add_to_wishlist_button:hover, .main-navigation .primary-navigation > .menu-item .sub-menu a.woocommerce-loop-product__link:hover, .main-navigation .primary-navigation > .menu-item .sub-menu a.loop-add-to-cart-btn:hover {
				    background-color: transparent;
				}

				.main-navigation .primary-navigation > .menu-item .sub-menu a:hover {
				    background: rgba(239, 239, 239, 0.28);
				}

				.main-navigation .primary-navigation .menu-item {
				    position: relative;
				}

				.main-navigation .primary-navigation .menu-item:hover > .sub-menu {
				    pointer-events: auto;
				    opacity: 1;
				    visibility: visible;
				    top: 100%;
				    z-index: 999;
				    -webkit-transform: translateY(0px);
				    transform: translateY(0px);
				}

				.main-navigation .primary-navigation .sub-menu {
				    pointer-events: none;
				    background-color: #fff;
				    -webkit-box-shadow: 0 2px 8px 0 rgba(125, 122, 122, 0.2);
				    box-shadow: 0 2px 8px 0 rgba(125, 122, 122, 0.2);
				    border-radius: 4px;
				    -webkit-transition-duration: 0.2s;
				    transition-duration: 0.2s;
				    -webkit-transform: translateY(10px);
				    transform: translateY(10px);
				}

				.main-navigation .primary-navigation .sub-menu > .menu-item > .sub-menu {
				    -webkit-transform: translateY(0px);
				    transform: translateY(0px);
				    top: 0;
				    left: 110%;
				}

				.main-navigation .primary-navigation .sub-menu > .menu-item:hover > .sub-menu {
				    left: 100%;
				}

				.has-header-layout-1 .wrap-toggle-sidebar-menu {
				    display: none;
				}

				.has-header-layout-1 .site-navigation {
				    flex-grow: 1;
				    text-align: right;
				}

				.has-header-layout-1 .site-navigation .site-search:not(.woostify-search-form-widget),
				  .has-header-layout-1 .site-navigation .mobile-my-account {
				    display: none;
				}
			}
		';

		// Body css.
		$styles .= '
			body, select, button, input, textarea{
				font-family: ' . esc_attr( $options['body_font_family'] ) . ';
				font-weight: ' . esc_attr( $options['body_font_weight'] ) . ';
				line-height: ' . esc_attr( $options['body_line_height'] ) . 'px;
				text-transform: ' . esc_attr( $options['body_font_transform'] ) . ';
				font-size: ' . esc_attr( $options['body_font_size'] ) . 'px;
				color: ' . esc_attr( $options['text_color'] ) . ';
			}

			.woostify-svg-icon svg {
				width:  ' . esc_attr( $options['body_font_size'] ) . 'px;
				height:  ' . esc_attr( $options['body_font_size'] ) . 'px;
			}

			.pagination a,
			.pagination a,
			.woocommerce-pagination a,
			.woocommerce-loop-product__category a,
			.woocommerce-loop-product__title,
			.price del,
			.stars a,
			.woocommerce-review-link,
			.woocommerce-tabs .tabs li:not(.active) a,
			.woocommerce-cart-form__contents .product-remove a,
			.comment-body .comment-meta .comment-date,
			.woostify-breadcrumb a,
			.breadcrumb-separator,
			#secondary .widget a,
			.has-woostify-text-color,
			.button.loop-add-to-cart-icon-btn,
			.button.loop-add-to-cart-icon-btn .woostify-svg-icon,
			.loop-wrapper-wishlist a,
			#order_review .shop_table .product-name {
				color: ' . esc_attr( $options['text_color'] ) . ';
			}

			.loop-wrapper-wishlist a:hover,
			.price_slider_wrapper .price_slider,
			.has-woostify-text-background-color{
				background-color: ' . esc_attr( $options['text_color'] ) . ';
			}

			.elementor-add-to-cart .quantity {
				border: 1px solid ' . esc_attr( $options['text_color'] ) . ';
			}

			.product .woocommerce-loop-product__title{
				font-size: ' . esc_attr( $options['body_font_size'] ) . 'px;
			}
		';

		// Primary menu css.
		$styles .= '
			.primary-navigation a{
				font-family: ' . esc_attr( $options['menu_font_family'] ) . ';
				text-transform: ' . esc_attr( $options['menu_font_transform'] ) . ';
			}

			.primary-navigation > li > a,
			.primary-navigation .sub-menu a {
				font-weight: ' . esc_attr( $options['menu_font_weight'] ) . ';
			}

			.primary-navigation > li > a {
				font-size: ' . esc_attr( $options['parent_menu_font_size'] ) . 'px;
				line-height: ' . esc_attr( $options['parent_menu_line_height'] ) . 'px;
				color: ' . esc_attr( $options['primary_menu_color'] ) . ';
			}

			.primary-navigation > li > a .woostify-svg-icon {
				color: ' . esc_attr( $options['primary_menu_color'] ) . ';
			}

			.primary-navigation .sub-menu a{
				line-height: ' . esc_attr( $options['sub_menu_line_height'] ) . 'px;
				font-size: ' . esc_attr( $options['sub_menu_font_size'] ) . 'px;
				color: ' . esc_attr( $options['primary_sub_menu_color'] ) . ';
			}
			.site-tools .tools-icon .woostify-header-total-price {
				font-family: ' . esc_attr( $options['menu_font_family'] ) . ';
				font-size: ' . esc_attr( $options['parent_menu_font_size'] ) . 'px;
				color: ' . esc_attr( $options['primary_menu_color'] ) . ';
			}
		';

		// Heading css.
		$styles .= '
			h1, h2, h3, h4, h5, h6{
				font-family: ' . esc_attr( $options['heading_font_family'] ) . ';
				font-weight: ' . esc_attr( $options['heading_font_weight'] ) . ';
				text-transform: ' . esc_attr( $options['heading_font_transform'] ) . ';
				line-height: ' . esc_attr( $options['heading_line_height'] ) . ';
				color: ' . esc_attr( $options['heading_color'] ) . ';
			}
			h1,
			.has-woostify-heading-1-font-size{
				font-size: ' . esc_attr( $options['heading_h1_font_size'] ) . 'px;
			}
			h2,
			.has-woostify-heading-2-font-size{
				font-size: ' . esc_attr( $options['heading_h2_font_size'] ) . 'px;
			}
			h3,
			.has-woostify-heading-3-font-size{
				font-size: ' . esc_attr( $options['heading_h3_font_size'] ) . 'px;
			}
			h4,
			.has-woostify-heading-4-font-size{
				font-size: ' . esc_attr( $options['heading_h4_font_size'] ) . 'px;
			}
			h5,
			.has-woostify-heading-5-font-size{
				font-size: ' . esc_attr( $options['heading_h5_font_size'] ) . 'px;
			}
			h6,
			.has-woostify-heading-6-font-size{
				font-size: ' . esc_attr( $options['heading_h6_font_size'] ) . 'px;
			}

			.product-loop-meta .price,
			.variations label,
			.woocommerce-review__author,
			.button[name="apply_coupon"],
			.quantity .qty,
			.form-row label,
			.select2-container--default .select2-selection--single .select2-selection__rendered,
			.form-row .input-text:focus,
			.wc_payment_method label,
			.shipping-methods-modified-label,
			.woocommerce-checkout-review-order-table thead th,
			.woocommerce-checkout-review-order-table .product-name,
			.woocommerce-thankyou-order-details strong,
			.woocommerce-table--order-details th,
			.woocommerce-table--order-details .amount,
			.wc-breadcrumb .woostify-breadcrumb,
			.sidebar-menu .primary-navigation .arrow-icon,
			.default-widget a strong:hover,
			.woostify-subscribe-form input,
			.woostify-shop-category .elementor-widget-image .widget-image-caption,
			.shop_table_responsive td:before,
			.dialog-search-title,
			.cart-collaterals th,
			.woocommerce-mini-cart__total strong,
			.woocommerce-form-login-toggle .woocommerce-info a,
			.woocommerce-form-coupon-toggle .woocommerce-info a,
			.has-woostify-heading-color,
			.woocommerce-table--order-details td,
			.woocommerce-table--order-details td.product-name a,
			.has-distraction-free-checkout .site-header .site-branding:after,
			.woocommerce-cart-form__contents thead th,
			#order_review .shop_table th,
			#order_review .shop_table th.product-name,
			#order_review .shop_table .product-quantity {
				color: ' . esc_attr( $options['heading_color'] ) . ';
			}

			.has-woostify-heading-background-color{
				background-color: ' . esc_attr( $options['heading_color'] ) . ';
			}

			.variations label{
				font-weight: ' . esc_attr( $options['heading_font_weight'] ) . ';
			}
		';

		// Link color.
		$styles .= '
			.cart-sidebar-content .woocommerce-mini-cart__buttons a:not(.checkout),
			.product-loop-meta .button,
			.multi-step-checkout-button[data-action="back"],
			.multi-step-checkout-button[data-action="back"] .woostify-svg-icon,
			.review-information-link,
			a{
				color: ' . esc_attr( $options['accent_color'] ) . ';
			}

			.woostify-icon-bar span{
				background-color: ' . esc_attr( $options['accent_color'] ) . ';
			}
		';

		// Link hover color.
		$styles .= '
			.cart-sidebar-content .woocommerce-mini-cart__buttons a:not(.checkout):hover,
			.product-loop-meta .button:hover,
			.multi-step-checkout-button[data-action="back"]:hover,
			.multi-step-checkout-button[data-action="back"] .woostify-svg-icon:hover,
			.review-information-link:hover,
			a:hover {
				color: ' . esc_attr( $options['link_hover_color'] ) . ';
			}

			.woostify-icon-bar span:hover {
				background-color: ' . esc_attr( $options['link_hover_color'] ) . ';
			}
		';

		// Buttons.
		$styles .= '
			.woostify-button-color,
			.loop-add-to-cart-on-image+.added_to_cart, {
				color: ' . esc_attr( $options['button_text_color'] ) . ';
			}

			.woostify-button-bg-color,
			.woocommerce-cart-form__contents:not(.elementor-menu-cart__products) .actions .coupon [name="apply_coupon"],
			.loop-add-to-cart-on-image+.added_to_cart,
			.related .tns-controls button,
			.up-sells .tns-controls button,
			.woostify-product-recently-viewed-section .tns-controls button {
				background-color: ' . esc_attr( $options['button_background_color'] ) . ';
			}

			.woostify-button-hover-color,
			.button[name="apply_coupon"]:hover{
				color: ' . esc_attr( $options['button_hover_text_color'] ) . ';
			}

			.woostify-button-hover-bg-color,
			.loop-add-to-cart-on-image+.added_to_cart:hover,
			.button.loop-add-to-cart-icon-btn:hover,
			.product-loop-action .yith-wcwl-add-to-wishlist:hover,
			.product-loop-action .yith-wcwl-wishlistaddedbrowse.show,
			.product-loop-action .yith-wcwl-wishlistexistsbrowse.show,
			.product-loop-action .added_to_cart,
			.product-loop-image-wrapper .tinv-wraper .tinvwl_add_to_wishlist_button:hover,
			.related .tns-controls button:hover,
			.up-sells .tns-controls button:hover,
			.woostify-product-recently-viewed-section .tns-controls button:hover {
				background-color: ' . esc_attr( $options['button_hover_background_color'] ) . ';
			}

			@media (min-width: 992px) {
				.main-navigation .primary-navigation > .menu-item ul:not(.sub-mega-menu) a.tinvwl_add_to_wishlist_button:hover {
					background-color: ' . esc_attr( $options['button_hover_background_color'] ) . ';
				}
			}

			.button,
			.woocommerce-widget-layered-nav-dropdown__submit,
			.form-submit .submit,
			.elementor-button-wrapper .elementor-button,
			.has-woostify-contact-form input[type="submit"],
			#secondary .widget a.button,
			.product-loop-meta.no-transform .button,
			.product-loop-meta.no-transform .added_to_cart,
			[class*="elementor-kit"] .checkout-button {
				background-color: ' . esc_attr( $options['button_background_color'] ) . ';
				color: ' . esc_attr( $options['button_text_color'] ) . ';
				border-radius: ' . esc_attr( $options['buttons_border_radius'] ) . 'px;
			}

			.button .woostify-svg-icon,
			.product-loop-meta.no-transform .added_to_cart .woostify-svg-icon {
				color: ' . esc_attr( $options['button_text_color'] ) . ';
			}

			.cart:not(.elementor-menu-cart__products) .quantity,
			.loop-add-to-cart-on-image+.added_to_cart,
			.loop-product-qty .quantity,
			.mini-cart-product-infor .mini-cart-quantity {
				border-radius: ' . esc_attr( $options['buttons_border_radius'] ) . 'px;
			}

			.button:hover,
			.single_add_to_cart_button.button:not(.woostify-buy-now):hover,
			.woocommerce-widget-layered-nav-dropdown__submit:hover,
			#commentform input[type="submit"]:hover,
			.form-submit .submit:hover,
			#secondary .widget a.button:hover,
			.woostify-contact-form input[type="submit"]:hover,
			.loop-add-to-cart-on-image+.added_to_cart:hover,
			.product-loop-meta.no-transform .button:hover,
			.product-loop-meta.no-transform .added_to_cart:hover{
				background-color: ' . esc_attr( $options['button_hover_background_color'] ) . ';
				color: ' . esc_attr( $options['button_hover_text_color'] ) . ';
			}

			/*.product-loop-wrapper .button .woostify-svg-icon {
				color: ' . esc_attr( $options['button_hover_text_color'] ) . ';
			}*/

			.loop-add-to-cart-on-image+.added_to_cart:hover .woostify-svg-icon {
				color: ' . esc_attr( $options['button_hover_text_color'] ) . ';
			}

			.select2-container--default .select2-results__option--highlighted[aria-selected],
			.select2-container--default .select2-results__option--highlighted[data-selected]{
				background-color: ' . esc_attr( $options['button_background_color'] ) . ' !important;
			}

			@media ( max-width: 600px ) {
				.woocommerce-cart-form__contents [name="update_cart"] {
					background-color: ' . esc_attr( $options['button_background_color'] ) . ';
					filter: grayscale(100%);
				}
				.woocommerce-cart-form__contents [name="update_cart"],
				.woocommerce-cart-form__contents .coupon button {
					color: ' . esc_attr( $options['button_text_color'] ) . ';
				}
			}
		';

		// Free shipping threshold.
		$message_color         = ( '' === $options['shipping_threshold_message_color'] ) ? 'inherit' : $options['shipping_threshold_message_color'];
		$message_success_color = ( '' === $options['shipping_threshold_message_success_color'] ) ? 'inherit' : $options['shipping_threshold_message_success_color'];

		$styles .= '
		.free-shipping-progress-bar .progress-bar-message {
			color: ' . $message_color . ';
		}
		.free-shipping-progress-bar[data-progress="100"] .progress-bar-message {
			color: ' . $message_success_color . ';
		}
		.free-shipping-progress-bar .progress-bar-indicator {
			background: linear-gradient( 270deg, ' . $options['shipping_threshold_progress_bar_color'] . ' 0, #fff 200%);
			background-color: ' . $options['shipping_threshold_progress_bar_color'] . ';
		}
		.free-shipping-progress-bar .progress-bar-status.success .progress-bar-indicator {
			background: ' . $options['shipping_threshold_progress_bar_success_color'] . ';
		}
		';

		// Theme color.
		$styles .= '
			.woostify-theme-color,
			.primary-navigation li.current-menu-item > a,
			.primary-navigation > li.current-menu-ancestor > a,
			.primary-navigation > li.current-menu-parent > a,
			.primary-navigation > li.current_page_parent > a,
			.primary-navigation > li.current_page_ancestor > a,
			.woocommerce-cart-form__contents tbody .product-subtotal,
			.woocommerce-checkout-review-order-table .order-total,
			.woocommerce-table--order-details .product-name a,
			.primary-navigation a:hover,
			.primary-navigation a:hover > .menu-item-arrow .woostify-svg-icon,
			.primary-navigation .menu-item-has-children:hover > a,
			.primary-navigation .menu-item-has-children:hover > a > .menu-item-arrow .woostify-svg-icon,
			.default-widget a strong,
			.woocommerce-mini-cart__total .amount,
			.woocommerce-form-login-toggle .woocommerce-info a:hover,
			.woocommerce-form-coupon-toggle .woocommerce-info a:hover,
			.has-woostify-primary-color,
			.blog-layout-grid .site-main .post-read-more a,
			.site-footer a:hover,
			.woostify-simple-subsbrice-form input[type="submit"],
			.woocommerce-tabs li.active a,
			#secondary .widget .current-cat > a,
			#secondary .widget .current-cat > span,
			.site-tools .header-search-icon:hover,
			.product-loop-meta .button:hover,
			#secondary .widget a:not(.tag-cloud-link):hover,
			.cart-sidebar-content .woocommerce-mini-cart__buttons a:not(.checkout):hover,
			.product-nav-item:hover > a,
			.product-nav-item .product-nav-item-price,
			.woocommerce-thankyou-order-received,
			.site-tools .tools-icon:hover,
			.site-tools .tools-icon:hover .woostify-svg-icon,
			.tools-icon.my-account:hover > a,
			.multi-step-checkout-button[data-action="back"]:hover,
			.multi-step-checkout-button[data-action="back"]:hover .woostify-svg-icon,
			.review-information-link:hover,
			.has-multi-step-checkout .multi-step-item,
			#secondary .chosen a,
			#secondary .chosen .count,
			.cart_totals .shop_table .woocommerce-Price-amount,
			#order_review .shop_table .woocommerce-Price-amount {
				color: ' . esc_attr( $options['theme_color'] ) . ';
			}

			.onsale,
			.pagination li .page-numbers.current,
			.woocommerce-pagination li .page-numbers.current,
			.tagcloud a:hover,
			.price_slider_wrapper .ui-widget-header,
			.price_slider_wrapper .ui-slider-handle,
			.cart-sidebar-head .shop-cart-count,
			.wishlist-item-count,
			.shop-cart-count,
			.sidebar-menu .primary-navigation a:before,
			.woocommerce-message,
			.woocommerce-info,
			#scroll-to-top,
			.woocommerce-store-notice,
			.has-woostify-primary-background-color,
			.woostify-simple-subsbrice-form input[type="submit"]:hover,
			.has-multi-step-checkout .multi-step-item .item-text:before,
			.has-multi-step-checkout .multi-step-item:before,
			.has-multi-step-checkout .multi-step-item:after,
			.has-multi-step-checkout .multi-step-item.active:before,
			.woostify-single-product-stock .woostify-single-product-stock-progress-bar {
				background-color: ' . esc_attr( $options['theme_color'] ) . ';
			}

			.woocommerce-thankyou-order-received,
			.woostify-lightbox-button:hover,
			.photoswipe-toggle-button:hover {
				border-color: ' . esc_attr( $options['theme_color'] ) . ';
			}

			/* Fix issue not showing on IE - Must use single line css */
			.woostify-simple-subsbrice-form:focus-within input[type="submit"]{
				background-color: ' . esc_attr( $options['theme_color'] ) . ';
			}
		';

		// Header.
		$styles .= '
			.site-header-inner{
				background-color: ' . esc_attr( $options['header_background_color'] ) . ';
			}
		';

		// Header transparent.
		if ( woostify_header_transparent() ) {
			$styles .= '
				.has-header-transparent .site-header-inner{
					border-bottom-width: ' . esc_attr( $options['header_transparent_border_width'] ) . 'px;
					border-bottom-color: ' . esc_attr( $options['header_transparent_border_color'] ) . ';
				}
				.has-header-transparent .primary-navigation > li > a {
					color: ' . esc_attr( $options['header_transparent_menu_color'] ) . ';
				}
				.has-header-transparent .site-tools .tools-icon {
					color: ' . esc_attr( $options['header_transparent_icon_color'] ) . ';
				}
				.has-header-transparent .wishlist-item-count, .has-header-transparent .shop-cart-count {
					background-color: ' . esc_attr( $options['header_transparent_count_background'] ) . ';
				}
			';
		}

		// Page header.
		if ( $options['page_header_display'] ) {
			$page_header_background_image = '';
			if ( $options['page_header_background_image'] ) {
				$page_header_background_image .= 'background-image: url(' . esc_attr( $options['page_header_background_image'] ) . ');';
				$page_header_background_image .= 'background-size: ' . esc_attr( $options['page_header_background_image_size'] ) . ';';
				$page_header_background_image .= 'background-repeat: ' . esc_attr( $options['page_header_background_image_repeat'] ) . ';';
				$page_header_bg_image_position = str_replace( '-', ' ', $options['page_header_background_image_position'] );
				$page_header_background_image .= 'background-position: ' . esc_attr( $page_header_bg_image_position ) . ';';
				$page_header_background_image .= 'background-attachment: ' . esc_attr( $options['page_header_background_image_attachment'] ) . ';';
			}

			$styles .= '
				.page-header{
					padding-top: ' . esc_attr( $options['page_header_padding_top'] ) . 'px;
					padding-bottom: ' . esc_attr( $options['page_header_padding_bottom'] ) . 'px;
					margin-bottom: ' . esc_attr( $options['page_header_margin_bottom'] ) . 'px;
					background-color: ' . esc_attr( $options['page_header_background_color'] ) . ';' . $page_header_background_image . '
				}

				.page-header .entry-title{
					color: ' . esc_attr( $options['page_header_title_color'] ) . ';
				}

				.woostify-breadcrumb,
				.woostify-breadcrumb a{
					color: ' . esc_attr( $options['page_header_breadcrumb_text_color'] ) . ';
				}
			';
		}

		// Sidebar Width.
		$styles .= '
			@media (min-width: 992px) {

				.has-sidebar:not(.offcanvas-sidebar) #secondary {
				width: ' . esc_attr( $options['sidebar_width'] ) . '%;
				}

				.has-sidebar:not(.offcanvas-sidebar) #primary {
					width: calc( 100% - ' . esc_attr( $options['sidebar_width'] ) . '%);
				}
			}
		';

		// Footer.
		$styles .= '
			.site-footer{
				margin-top: ' . esc_attr( $options['footer_space'] ) . 'px;
			}

			.site-footer a{
				color: ' . esc_attr( $options['footer_link_color'] ) . ';
			}

			.site-footer{
				background-color: ' . esc_attr( $options['footer_background_color'] ) . ';
				color: ' . esc_attr( $options['footer_text_color'] ) . ';
			}

			.site-footer .widget-title,
			.site-footer .widgettitle,
			.woostify-footer-social-icon a{
				color: ' . esc_attr( $options['footer_heading_color'] ) . ';
			}

			.woostify-footer-social-icon a:hover{
				background-color: ' . esc_attr( $options['footer_heading_color'] ) . ';
			}

			.woostify-footer-social-icon a {
				border-color: ' . esc_attr( $options['footer_heading_color'] ) . ';
			}

			#scroll-to-top {
				border-radius: ' . esc_attr( $options['scroll_to_top_border_radius'] ) . 'px;
			}
		';
		// Sticky Footer Bar.
		$styles .= '
			.woostify-sticky-footer-bar {
				background: ' . esc_attr( $options['sticky_footer_bar_background'] ) . ';
			}
			.woostify-sticky-footer-bar .woostify-item-list-item__icon .woositfy-sfb-icon svg {
				color: ' . esc_attr( $options['sticky_footer_bar_icon_color'] ) . ';
				fill: ' . esc_attr( $options['sticky_footer_bar_icon_color'] ) . ';
			}
			.woostify-sticky-footer-bar .woostify-item-list__item a:hover .woostify-item-list-item__icon .woositfy-sfb-icon svg {
				color: ' . esc_attr( $options['sticky_footer_bar_icon_hover_color'] ) . ';
				fill: ' . esc_attr( $options['sticky_footer_bar_icon_hover_color'] ) . ';
			}
			.woostify-sticky-footer-bar .woostify-item-list-item__name {
				color: ' . esc_attr( $options['sticky_footer_bar_text_color'] ) . ';
				font-weight: ' . esc_attr( $options['sticky_footer_bar_text_font_weight'] ) . ';
			}
			.woostify-sticky-footer-bar .woostify-item-list__item a:hover .woostify-item-list-item__name {
				color: ' . esc_attr( $options['sticky_footer_bar_text_hover_color'] ) . ';
			}
		';

		// MOBILE MENU.
		$tab_padding                         = $options['mobile_menu_tab_padding'];
		$icon_bar_color                      = $options['mobile_menu_icon_bar_color'];
		$sidebar_tab_color                   = $options['mobile_menu_tab_color'];
		$sidebar_background                  = $options['mobile_menu_background'];
		$sidebar_text_color                  = $options['mobile_menu_text_color'];
		$nav_tab_spacing_bottom              = $options['mobile_menu_nav_tab_spacing_bottom'];
		$sidebar_text_hover_color            = $options['mobile_menu_text_hover_color'];
		$sidebar_tab_active_color            = $options['mobile_menu_tab_active_color'];
		$sidebar_tab_background_color        = $options['mobile_menu_tab_background'];
		$sidebar_tab_active_background_color = $options['mobile_menu_tab_active_background'];

		$styles .= '
		.toggle-sidebar-menu-btn.woostify-icon-bar span {
			background-color: ' . $icon_bar_color . ';
		}
		.sidebar-menu {
			background-color: ' . $sidebar_background . ';
			color: ' . $sidebar_text_color . ';
		}
		.sidebar-menu a, .sidebar-menu .primary-navigation > li > a, .sidebar-menu .primary-navigation .sub-menu a {
			color: ' . $sidebar_text_color . ';
		}
		.sidebar-menu a:hover {
			color: ' . $sidebar_text_hover_color . ';
		}
		.sidebar-menu .mobile-nav-tab, .woostify-nav-menu-inner .mobile-nav-tab {
			margin-bottom: ' . $nav_tab_spacing_bottom . 'px;
		}
		.sidebar-menu .mobile-tab-title, .woostify-nav-menu-inner .mobile-tab-title {
			background: ' . $sidebar_tab_background_color . ';
			' . esc_attr( woostify_render_css_spacing( $tab_padding, 'padding' ) ) . '
		}
		.sidebar-menu .mobile-tab-title.active, .woostify-nav-menu-inner .mobile-tab-title.active {
			background: ' . $sidebar_tab_active_background_color . ';
		}
		.sidebar-menu .mobile-tab-title a, .woostify-nav-menu-inner .mobile-tab-title a {
			color: ' . $sidebar_tab_color . ';
		}
		.sidebar-menu .mobile-tab-title.active a, .woostify-nav-menu-inner .mobile-tab-title.active a {
			color: ' . $sidebar_tab_active_color . ';
		}
		';

		if ( is_customize_preview() ) {
			$styles .= '
			@media ( min-width: 769px ) {
				.woostify-sticky-footer-bar {
					' . esc_attr( woostify_render_css_spacing( $options['sticky_footer_bar_padding'], 'padding' ) ) . ';
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__icon .woositfy-sfb-icon svg {
					width: ' . esc_attr( $options['sticky_footer_bar_icon_font_size'] ) . 'px;
					height: ' . esc_attr( $options['sticky_footer_bar_icon_font_size'] ) . 'px;
				}
				.woostify-sticky-footer-bar ul.woostify-item-list li.woostify-item-list__item a .woostify-item-list-item__icon {
					margin-bottom: ' . esc_attr( $options['sticky_footer_bar_icon_spacing'] ) . 'px;
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__name {
					font-size: ' . esc_attr( $options['sticky_footer_bar_text_font_size'] ) . 'px;
				}
			}
			@media ( min-width: 321px ) and ( max-width: 768px ) {
				.woostify-sticky-footer-bar {
					' . esc_attr( woostify_render_css_spacing( $options['tablet_sticky_footer_bar_padding'], 'padding' ) ) . ';
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__icon .woositfy-sfb-icon svg {
					width: ' . esc_attr( $options['tablet_sticky_footer_bar_icon_font_size'] ) . 'px;
					height: ' . esc_attr( $options['tablet_sticky_footer_bar_icon_font_size'] ) . 'px;
				}
				.woostify-sticky-footer-bar ul.woostify-item-list li.woostify-item-list__item a .woostify-item-list-item__icon {
					margin-bottom: ' . esc_attr( $options['tablet_sticky_footer_bar_icon_spacing'] ) . 'px;
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__name {
					font-size: ' . esc_attr( $options['tablet_sticky_footer_bar_text_font_size'] ) . 'px;
				}
			}
			@media ( max-width: 320px ) {
				.woostify-sticky-footer-bar {
					' . esc_attr( woostify_render_css_spacing( $options['mobile_sticky_footer_bar_padding'], 'padding' ) ) . ';
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__icon .woositfy-sfb-icon svg {
					width: ' . esc_attr( $options['mobile_sticky_footer_bar_icon_font_size'] ) . 'px;
					height: ' . esc_attr( $options['mobile_sticky_footer_bar_icon_font_size'] ) . 'px;
				}
				.woostify-sticky-footer-bar ul.woostify-item-list li.woostify-item-list__item a .woostify-item-list-item__icon {
					margin-bottom: ' . esc_attr( $options['mobile_sticky_footer_bar_icon_spacing'] ) . 'px;
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__name {
					font-size: ' . esc_attr( $options['mobile_sticky_footer_bar_text_font_size'] ) . 'px;
				}
			}
			';
		} else {
			$styles .= '
			@media ( min-width: 992px ) {
				.woostify-sticky-footer-bar {
					' . esc_attr( woostify_render_css_spacing( $options['sticky_footer_bar_padding'], 'padding' ) ) . ';
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__icon .woositfy-sfb-icon svg {
					width: ' . esc_attr( $options['sticky_footer_bar_icon_font_size'] ) . 'px;
					height: ' . esc_attr( $options['sticky_footer_bar_icon_font_size'] ) . 'px;
				}
				.woostify-sticky-footer-bar ul.woostify-item-list li.woostify-item-list__item a .woostify-item-list-item__icon {
					margin-bottom: ' . esc_attr( $options['sticky_footer_bar_icon_spacing'] ) . 'px;
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__name {
					font-size: ' . esc_attr( $options['sticky_footer_bar_text_font_size'] ) . 'px;
				}
			}
			@media ( min-width: 768px ) and ( max-width: 991px ) {
				.woostify-sticky-footer-bar {
					' . esc_attr( woostify_render_css_spacing( $options['tablet_sticky_footer_bar_padding'], 'padding' ) ) . ';
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__icon .woositfy-sfb-icon svg {
					width: ' . esc_attr( $options['tablet_sticky_footer_bar_icon_font_size'] ) . 'px;
					height: ' . esc_attr( $options['tablet_sticky_footer_bar_icon_font_size'] ) . 'px;
				}
				.woostify-sticky-footer-bar ul.woostify-item-list li.woostify-item-list__item a .woostify-item-list-item__icon {
					margin-bottom: ' . esc_attr( $options['tablet_sticky_footer_bar_icon_spacing'] ) . 'px;
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__name {
					font-size: ' . esc_attr( $options['tablet_sticky_footer_bar_text_font_size'] ) . 'px;
				}
			}
			@media ( max-width: 767px ) {
				.woostify-sticky-footer-bar {
					' . esc_attr( woostify_render_css_spacing( $options['mobile_sticky_footer_bar_padding'], 'padding' ) ) . ';
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__icon .woositfy-sfb-icon svg {
					width: ' . esc_attr( $options['mobile_sticky_footer_bar_icon_font_size'] ) . 'px;
					height: ' . esc_attr( $options['mobile_sticky_footer_bar_icon_font_size'] ) . 'px;
				}
				.woostify-sticky-footer-bar ul.woostify-item-list li.woostify-item-list__item a .woostify-item-list-item__icon {
					margin-bottom: ' . esc_attr( $options['mobile_sticky_footer_bar_icon_spacing'] ) . 'px;
				}
				.woostify-sticky-footer-bar .woostify-item-list-item__name {
					font-size: ' . esc_attr( $options['mobile_sticky_footer_bar_text_font_size'] ) . 'px;
				}
			}
			';
		}

		// Scroll to top.
		$styles .= '
			#scroll-to-top {
				bottom: ' . esc_attr( $options['scroll_to_top_offset_bottom'] ) . 'px;
				background-color: ' . esc_attr( $options['scroll_to_top_background'] ) . ';
			}

			#scroll-to-top .woostify-svg-icon {
				color: ' . esc_attr( $options['scroll_to_top_color'] ) . ';
			}

			#scroll-to-top svg {
				width: ' . esc_attr( $options['scroll_to_top_icon_size'] ) . 'px;
				height: ' . esc_attr( $options['scroll_to_top_icon_size'] ) . 'px;
			}

			@media (min-width: 992px) {
				#scroll-to-top.scroll-to-top-show-mobile {
					display: none;
				}
			}
			@media (max-width: 992px) {
				#scroll-to-top.scroll-to-top-show-desktop {
					display: none;
				}
			}
		';

		// Spinner color.
		$styles .= '
			.circle-loading:before,
			.product_list_widget .remove_from_cart_button:focus:before,
			.updating-cart.ajax-single-add-to-cart .single_add_to_cart_button:before,
			.product-loop-meta .loading:before,
			.updating-cart #shop-cart-sidebar:before {
				border-top-color: ' . esc_attr( $options['theme_color'] ) . ';
			}
		';

		// SHOP PAGE.

		$styles .= '
			.product-loop-wrapper .button,.product-loop-meta.no-transform .button {
				background-color: ' . esc_attr( $options['shop_page_button_cart_background'] ) . ';
				color: ' . esc_attr( $options['shop_page_button_cart_color'] ) . ';
				border-radius: ' . esc_attr( $options['shop_page_button_border_radius'] ) . 'px;
			}

			.product-loop-wrapper .button .woostify-svg-icon {
				color: ' . esc_attr( $options['shop_page_button_cart_color'] ) . ';
			}

			.product-loop-wrapper .button:hover, .product-loop-meta.no-transform .button:hover, .product-loop-wrapper .button:hover .woostify-svg-icon {
				background-color: ' . esc_attr( $options['shop_page_button_background_hover'] ) . ';
				color: ' . esc_attr( $options['shop_page_button_color_hover'] ) . ';
			}
		';

		// Product card.
		if ( 'none' !== $options['shop_page_product_card_border_style'] ) {
			$styles .= '
				.products .product:not(.product-category) .product-loop-wrapper {
					border-style: ' . esc_attr( $options['shop_page_product_card_border_style'] ) . ';
					border-width: ' . esc_attr( $options['shop_page_product_card_border_width'] ) . 'px;
					border-color: ' . esc_attr( $options['shop_page_product_card_border_color'] ) . ';
				}
			';
		}

		// Product content.
		if ( $options['shop_page_product_content_equal'] ) {
			$styles .= '
				.product-loop-content {
					min-height: ' . esc_attr( $options['shop_page_product_content_min_height'] ) . 'px;
				}
			';
		}

		// Product image.
		if ( 'none' !== $options['shop_page_product_image_border_style'] ) {
			$styles .= '
				.product-loop-image-wrapper {
					border-style: ' . esc_attr( $options['shop_page_product_image_border_style'] ) . ';
					border-width: ' . esc_attr( $options['shop_page_product_image_border_width'] ) . 'px;
					border-color: ' . esc_attr( $options['shop_page_product_image_border_color'] ) . ';
				}
			';
		}

		// Equal image height.
		if ( $options['shop_page_product_image_equal_height'] ) {
			$styles .= '
				.has-equal-image-height {
					height: ' . $options['shop_page_product_image_height'] . 'px;
				}
			';
		}

		// Sale tag.
		if ( $options['shop_page_sale_square'] ) {
			$styles .= '
				.woostify-tag-on-sale.is-square {
					width: ' . esc_attr( $options['shop_page_sale_size'] ) . 'px;
					height: ' . esc_attr( $options['shop_page_sale_size'] ) . 'px;
				}
			';
		}
		$styles .= '
			.onsale {
				color: ' . esc_attr( $options['shop_page_sale_color'] ) . ';
				border-radius: ' . esc_attr( $options['shop_page_sale_border_radius'] ) . 'px;
			}
		';

		// Out of stock label.
		if ( $options['shop_page_out_of_stock_square'] ) {
			$styles .= '
				.woostify-out-of-stock-label.is-square {
					width: ' . esc_attr( $options['shop_page_out_of_stock_size'] ) . 'px;
					height: ' . esc_attr( $options['shop_page_out_of_stock_size'] ) . 'px;
				}
			';
		}
		$styles .= '
			.woostify-out-of-stock-label {
				color: ' . esc_attr( $options['shop_page_out_of_stock_color'] ) . ';
				background-color: ' . esc_attr( $options['shop_page_out_of_stock_bg_color'] ) . ';
				border-radius: ' . esc_attr( $options['shop_page_out_of_stock_border_radius'] ) . 'px;
			}
		';

		// SHOP SINGLE.
		$styles .= '
			.single-product .content-top,
			.product-page-container{
				background-color:  ' . esc_attr( $options['shop_single_content_background'] ) . ';
			}
		';

		// Single Product Add to cart.
		$styles .= '
			.single_add_to_cart_button.button:not(.woostify-buy-now){
				border-radius: ' . esc_attr( $options['shop_single_button_border_radius'] ) . 'px;
				background-color:  ' . esc_attr( $options['shop_single_button_cart_background'] ) . ';
				color:  ' . esc_attr( $options['shop_single_button_cart_color'] ) . ';
			}
			.single_add_to_cart_button.button:not(.woostify-buy-now):hover{
				color:  ' . esc_attr( $options['shop_single_button_color_hover'] ) . ';
				background-color:  ' . esc_attr( $options['shop_single_button_background_hover'] ) . ';
			}
		';

		// 404.
		$error_404_bg = $options['error_404_image'];
		if ( $error_404_bg ) {
			$styles .= '
				.error404 .site-content{
					background-image: url(' . esc_url( $error_404_bg ) . ');
				}
			';
		}

		// Mini cart.
		$mini_cart_bg = $options['mini_cart_background_color'];
		$styles      .= '#shop-cart-sidebar {
			background-color: ' . $mini_cart_bg . ';
		}';

		// Catalog Mode.
		$catalog_mode_enabled = $options['catalog_mode'];
		$hide_variations      = $options['hide_variations'];
		if ( $catalog_mode_enabled ) {
			$hide_classes = 'form.cart button.single_add_to_cart_button, form.cart .quantity';
			if ( $hide_variations ) {
				$hide_classes .= ', table.variations, form.variations_form, .single_variation_wrap .variations_button';
			}
			$styles .= $hide_classes . '{ display: none !important; }';
		}

		// YITH Woocommerce Wishlist.
		$styles .= '
		.product-loop-action .yith-wcwl-add-to-wishlist a {
			color: ' . esc_attr( $options['text_color'] ) . ';
		}
		.product-loop-action .yith-wcwl-add-to-wishlist a:hover {
			background-color: ' . esc_attr( $options['button_hover_background_color'] ) . ';
		}
		.product-loop-action .yith-wcwl-add-to-wishlist:hover .feedback {
			background-color: ' . esc_attr( $options['button_hover_background_color'] ) . ';
		}
		.loop-wrapper-wishlist .feedback:hover {
			background-color: ' . esc_attr( $options['text_color'] ) . ';
		}
		';

		$this->css = apply_filters( 'woostify_customizer_css', $styles );
		$this->css = $this->minimize_dynamic_css();

		$this->write_stylesheet();

		return $this->css;
	}

	/**
	 * Minimize dynamic css
	 */
	protected function minimize_dynamic_css() {
		if ( ! $this->css ) {
			$this->get_styles();
		}

		$this->css = preg_replace( '/\/\*((?!\*\/).)*\*\//', '', $this->css ); // negative look ahead.
		$this->css = preg_replace( '/\s{2,}/', ' ', $this->css );
		$this->css = preg_replace( '/\s*([:;{}])\s*/', '$1', $this->css );
		$this->css = preg_replace( '/;}/', '}', $this->css );

		return $this->css;
	}

	/**
	 * Get the filesystem.
	 *
	 * @access protected
	 * @since 2.0.0
	 * @return \WP_Filesystem_Base
	 */
	protected function get_filesystem() {
		global $wp_filesystem;

		// If the filesystem has not been instantiated yet, do it here.
		if ( ! $wp_filesystem ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			}
			WP_Filesystem();
		}
		return $wp_filesystem;
	}

	/**
	 * Add Gutenberg css.
	 */
	public function woostify_guten_block_editor_assets() {
		// Get all theme option value.
		$options = woostify_options( false );

		$block_styles = '
			.edit-post-visual-editor, .edit-post-visual-editor p{
				font-family: ' . esc_attr( $options['body_font_family'] ) . ';
			}

			.editor-post-title__block .editor-post-title__input,
			.wp-block-heading, .editor-rich-text__tinymce{
				font-family: ' . esc_attr( $options['heading_font_family'] ) . ';
			}
		';

		wp_register_style( 'woostify-block-editor', false ); // @codingStandardsIgnoreLine
		wp_enqueue_style( 'woostify-block-editor' );
		wp_add_inline_style( 'woostify-block-editor', $block_styles );
	}

	/**
	 * Enqueue dynamic stylesheet file.
	 */
	public function woostify_dynamic_css() {
		$options = woostify_options( false );
		if ( $this->get_url() && $options['enabled_dynamic_css'] ) {
			wp_enqueue_style( 'woostify-dynamic', $this->get_url(), array(), WOOSTIFY_VERSION, 'all' );
		} else {
			wp_add_inline_style( 'woostify-style', $this->get_styles() );
		}
	}
}

return new Woostify_Get_CSS();
