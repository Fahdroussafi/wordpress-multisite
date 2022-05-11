<?php
/**
 * Heading control class
 *
 * @package  woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The heading control class
 */
class Woostify_Heading_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-heading';

	/**
	 * The description var
	 *
	 * @var string $description the control description.
	 */
	public $description = '';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_style(
			'woostify-heading-control',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/heading/style.css',
			array(),
			woostify_version()
		);
	}

	/**
	 * Renter the control
	 */
	public function content_template() {
		?>
		<div class="woostify-heading-control">
			<# if ( data.label ) { #>
			<span class="woostify-section-control-label">{{ data.label }}</span>
			<# } #>
		</div>
		<?php
	}
}
