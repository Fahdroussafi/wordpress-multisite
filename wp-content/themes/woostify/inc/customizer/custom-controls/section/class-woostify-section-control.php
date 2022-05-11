<?php
/**
 * Section control class
 *
 * @package  woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The section control class
 */
class Woostify_Section_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-section';

	/**
	 * The description var
	 *
	 * @var string $description the control description.
	 */
	public $description = '';

	/**
	 * The dependency var
	 *
	 * @var array $description the array dependency.
	 */
	public $dependency = array();

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'woostify-section',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/section/js/section.js',
			array(),
			woostify_version(),
			true
		);

		wp_enqueue_style(
			'woostify-section',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/section/css/section.css',
			array(),
			woostify_version()
		);
	}

	/**
	 * To Json
	 */
	public function to_json() {
		parent::to_json();
		$this->json['dependency'] = maybe_unserialize( $this->dependency );
	}

	/**
	 * Renter the control
	 */
	public function content_template() {
		?>
		<div class="woostify-section-control">
			<# if ( data.label ) { #>
			<span class="woostify-section-control-label">{{ data.label }}</span>
			<# } #>
			<span class="woostify-section-control-arrow dashicons dashicons-arrow-right-alt2"></span>
		</div>
		<?php
	}
}
