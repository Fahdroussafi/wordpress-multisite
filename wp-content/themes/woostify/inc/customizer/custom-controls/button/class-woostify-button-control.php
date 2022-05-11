<?php
/**
 * The Button Customizer control.
 *
 * @package wosstify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Woostify_Button_Control' ) ) {
	/**
	 * Create the button element control.
	 */
	class Woostify_Button_Control extends WP_Customize_Control {
		/**
		 * Create the button element control.
		 *
		 * @var $type
		 */
		public $type = 'woostify-button-control';

		/**
		 * Button label
		 *
		 * @var $button_label
		 */
		public $button_label = 'Button';

		/**
		 * Button classes
		 *
		 * @var $button_class
		 */
		public $button_class = '';

		/**
		 * Button ajax action
		 *
		 * @var $ajax_action
		 */
		public $ajax_action = '';

		/**
		 * Button link
		 *
		 * @var $button_link
		 */
		public $button_link = '#';

		/**
		 * Enqueue javascript and css file
		 */
		public function enqueue() {
			wp_enqueue_script(
				'woostify-button-customizer',
				WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/button/js/button-customizer.js',
				array( 'customize-controls' ),
				woostify_version(),
				true
			);
		}
		/**
		 * To json data
		 */
		public function to_json() {
			parent::to_json();
			$this->json['button_label'] = $this->button_label;
			$this->json['ajax_action']  = $this->ajax_action;
		}

		/**
		 * Renter the control
		 *
		 * @return void
		 */
		protected function render_content() {
			$settings = $this->settings;
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<a href="<?php echo esc_attr( $this->button_link ); ?>" class="button <?php echo esc_attr( $this->button_class ); ?>"><?php echo esc_html( $this->button_label ); ?></a>
			<?php
		}
	}
}
