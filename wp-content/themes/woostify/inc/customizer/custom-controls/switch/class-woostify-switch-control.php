<?php
/**
 * Switch for Customizer.
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Create a range slider control.
 * This control allows you to add responsive settings.
 */
class Woostify_Switch_Control extends WP_Customize_Control {

	/**
	 * Declare the control type.
	 *
	 * @var string
	 */
	public $type = 'switch';

	/**
	 * Declare the control type.
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
	 * To json data
	 */
	public function to_json() {
		parent::to_json();

		$this->json['tab'] = $this->tab;
	}

	/**
	 * Enqueue scripts and styles for the custom control.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'woostify-switch-control',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/switch/css/switch.css',
			array(),
			woostify_version()
		);
	}

	/**
	 * Render the control to be displayed in the Customizer.
	 */
	public function render_content() {
		$name  = '_customize-switch-' . $this->id;
		$id    = $this->id;
		$label = $this->label;
		$value = false === $this->value() ? 0 : 1;
		$desc  = $this->description;
		?>

		<div class="woostify-switch-customize-control">
			<?php if ( ! empty( $label ) ) { ?>
				<span class="customize-control-title">
					<?php echo esc_html( $label ); ?>
				</span>
			<?php } ?>

			<div class="woostify-switch-toggle">
				<input
					id="<?php echo esc_attr( $id ); ?>"
					type="checkbox"
					name="<?php echo esc_attr( $name ); ?>"
					class="woostify-switch-control switch-control"
					value="<?php echo esc_attr( $value ); ?>"
					<?php
						$this->link();
						checked( $value );
					?>
					/>

				<label for="<?php echo esc_attr( $id ); ?>" class="switch-control-label">
					<span class="on-off-label"></span>
				</label>
			</div>

			<?php if ( ! empty( $desc ) ) { ?>
				<span class="description customize-control-description"><?php echo esc_html( $desc ); ?></span>
			<?php } ?>
		</div>

		<?php
	}
}

