<?php
/**
 * Get Pro Version section.
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'WP_Customize_Section' ) && ! class_exists( 'Woostify_Get_Pro_Section' ) ) {
	/**
	 * Create our get pro version section.
	 */
	class Woostify_Get_Pro_Section extends WP_Customize_Section {
		/**
		 * Woostify pro section name
		 *
		 * @var $type woostify-pro-section
		 */
		public $type = 'woostify-pro-section';

		/**
		 * Woostify get pro version url
		 *
		 * @var $pro_url
		 */
		public $pro_url = '';

		/**
		 * Woostify get pro vertion text
		 *
		 * @var $pro_text
		 */
		public $pro_text = '';

		/**
		 * Woostify get pro vertion id
		 *
		 * @var $id
		 */
		public $id = '';

		/**
		 * Json
		 *
		 * @return array()  json data
		 */
		public function json() {
			$json             = parent::json();
			$json['pro_text'] = $this->pro_text;
			$json['pro_url']  = esc_url( $this->pro_url );
			$json['id']       = $this->id;

			return $json;
		}

		/**
		 * Render template
		 */
		protected function render_template() {
			?>
			<li id="accordion-section-{{ data.id }}" class="woostify-get-pro-version-accordion-section control-section-{{ data.type }} cannot-expand accordion-section">
				<h3 class="wp-ui-highlight"><a class="wp-ui-text-highlight" href="{{{ data.pro_url }}}" target="_blank">{{ data.pro_text }}</a></h3>
			</li>
			<?php
		}
	}
}

if ( ! function_exists( 'woostify_customizer_section_pro_static' ) ) {
	add_action( 'customize_controls_enqueue_scripts', 'woostify_customizer_section_pro_static' );
	/**
	 * Add JS/CSS for our controls
	 */
	function woostify_customizer_section_pro_static() {
		wp_enqueue_style(
			'woostify-get-pro-section',
			WOOSTIFY_THEME_URI . 'inc/customizer/sections/woostify-pro/css/get-pro-section.css',
			array(),
			woostify_version()
		);

		wp_enqueue_script(
			'woostify-get-pro-section',
			WOOSTIFY_THEME_URI . 'inc/customizer/sections/woostify-pro/js/get-pro-section.js',
			array( 'customize-controls' ),
			woostify_version(),
			true
		);
	}
}
