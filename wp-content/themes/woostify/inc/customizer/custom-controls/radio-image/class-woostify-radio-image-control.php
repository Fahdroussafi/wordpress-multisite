<?php // @codingStandardsIgnoreLine.
/**
 * Create a Radio-Image control
 *
 * This class incorporates code from the Kirki Customizer Framework and from a tutorial
 * written by Otto Wood.
 *
 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
 * is licensed under the terms of the GNU GPL, Version 2 (or later).
 *
 * @link https://github.com/reduxframework/kirki/
 * @link http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
 * @package  woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The radio image class.
 */
class Woostify_Radio_Image_Control extends WP_Customize_Control {

	/**
	 * Declare the control type.
	 *
	 * @var string
	 */
	public $type = 'radio-image';

	/**
	 * Tab
	 *
	 * @var string
	 */
	public $tab = '';

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 *
	 * @since 3.4.0
	 */
	protected function render() {
		$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class = 'customize-control customize-control-' . $this->type;

		printf( '<li id="%s" class="%s" data-tab="%s">', esc_attr( $id ), esc_attr( $class ), esc_attr( $this->tab ) );
		$this->render_content();
		echo '</li>';
	}

	/**
	 * Enqueue scripts and styles for the custom control.
	 *
	 * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
	 *
	 * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
	 * at 'customize_controls_print_styles'.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'woostify-radio-image-control',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/radio-image/css/radio-image.css',
			array(),
			woostify_version()
		);

		wp_enqueue_script(
			'woostify-radio-image',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/radio-image/js/radio-image.js',
			array(),
			woostify_version(),
			true
		);
	}

	/**
	 * Render the control to be displayed in the Customizer.
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}

		$name = '_customize-radio-' . $this->id;
		?>

		<?php if ( ! empty( $this->label ) ) { ?>
			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>
		<?php } ?>

		<?php if ( ! empty( $this->description ) ) { ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php } ?>

		<div id="input_<?php echo esc_attr( $this->id ); ?>" class="image">
			<?php foreach ( $this->choices as $value => $label ) { ?>
				<label for="<?php echo esc_attr( $this->id ) . esc_attr( $value ); ?>" class="radio-image-item <?php echo ( $this->value() === $value ) ? 'active' : ''; ?>">
					<img src="<?php echo esc_url( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" title="<?php echo esc_attr( ucwords( str_replace( '-', ' ', $value ) ) ); ?>">
					<input
						class="image-select"
						type="radio"
						value="<?php echo esc_attr( $value ); ?>"
						id="<?php echo esc_attr( $this->id . $value ); ?>"
						name="<?php echo esc_attr( $name ); ?>"
						<?php
							$this->link();
							checked( $this->value(), $value );
						?>
						>
				</label>
			<?php } ?>
		</div>
		<?php
	}
}
