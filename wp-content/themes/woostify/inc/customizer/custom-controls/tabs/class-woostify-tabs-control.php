<?php
/**
 * Woostify_Tabs_Control
 *
 * @package woostify
 */

/**
 * Customize Tabs Control class.
 */
class Woostify_Tabs_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-tabs';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_style(
			'woostify-tabs',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/tabs/css/tabs.css',
			array(),
			woostify_version()
		);

		wp_enqueue_script(
			'woostify-tabs',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/tabs/js/tabs.js',
			array( 'jquery' ),
			woostify_version(),
			true
		);
	}

	/**
	 * Renter the control
	 *
	 * @return void
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}
		$total_tabs = count( $this->choices );

		?>
		<div class="woostify-component-tabs wp-clearfix">
			<ul>
			<?php foreach ( $this->choices as $k => $choice ) { ?>
				<li data-tab="<?php echo esc_attr( $k ); ?>" class="woostify-tab-button"><?php echo esc_html( $choice ); ?></li>
			<?php } ?>
			</ul>
		</div>
		<style>
			.woostify-component-tabs .woostify-tab-button {
				width: calc( 100% / <?php echo (int) $total_tabs; ?>);
			}
		</style>
		<?php
	}
}
