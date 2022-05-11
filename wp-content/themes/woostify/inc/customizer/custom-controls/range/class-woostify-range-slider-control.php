<?php
/**
 * Range Slider for Customizer.
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
class Woostify_Range_Slider_Control extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-range-slider';

	/**
	 * Description
	 *
	 * @var string
	 */
	public $description = '';

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
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		$devices = array( 'desktop', 'tablet', 'mobile' );
		foreach ( $devices as $device ) {
			$this->json['choices'][ $device ]['min']  = ( isset( $this->choices[ $device ]['min'] ) ) ? $this->choices[ $device ]['min'] : '0';
			$this->json['choices'][ $device ]['max']  = ( isset( $this->choices[ $device ]['max'] ) ) ? $this->choices[ $device ]['max'] : '100';
			$this->json['choices'][ $device ]['step'] = ( isset( $this->choices[ $device ]['step'] ) ) ? $this->choices[ $device ]['step'] : '1';
			$this->json['choices'][ $device ]['edit'] = ( isset( $this->choices[ $device ]['edit'] ) ) ? $this->choices[ $device ]['edit'] : false;
			$this->json['choices'][ $device ]['unit'] = ( isset( $this->choices[ $device ]['unit'] ) ) ? $this->choices[ $device ]['unit'] : false;
		}

		foreach ( $this->settings as $setting_key => $setting_id ) {
			$this->json[ $setting_key ] = array(
				'link'    => $this->get_link( $setting_key ),
				'value'   => $this->value( $setting_key ),
				'default' => isset( $setting_id->default ) ? $setting_id->default : '',
			);
		}

		$this->json['tab'] = $this->tab;

		$this->json['desktop_label'] = __( 'Desktop', 'woostify' );
		$this->json['tablet_label']  = __( 'Tablet', 'woostify' );
		$this->json['mobile_label']  = __( 'Mobile', 'woostify' );
		$this->json['reset_label']   = __( 'Reset', 'woostify' );
		$this->json['description']   = $this->description;
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'woostify-range-slider',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/range/js/slider-control.js',
			array( 'jquery', 'customize-base', 'jquery-ui-slider' ),
			woostify_version(),
			true
		);

		wp_enqueue_style(
			'woostify-range-slider',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/range/css/slider-customizer.css',
			array(),
			woostify_version()
		);
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<div class="woostify-range-slider-control">
			<div class="woostify-range-title-area">
				<# if ( data.label || data.description ) { #>
				<div class="woostify-range-title-info">
					<# if ( data.label ) { #>
					<span class="customize-control-title">{{{ data.label }}}</span>
					<# } #>

					<# if ( data.description ) { #>
					<p class="description">{{{ data.description }}}</p>
					<# } #>
				</div>
				<# } #>

				<div class="woostify-range-slider-controls">
					<span class="woostify-device-controls">
						<# if ( 'undefined' !== typeof( data.desktop ) ) { #>
							<span class="woostify-device-desktop dashicons dashicons-desktop" data-option="desktop" title="{{ data.desktop_label }}"></span>
						<# } #>

						<# if ( 'undefined' !== typeof( data.tablet ) ) { #>
							<span class="woostify-device-tablet dashicons dashicons-tablet" data-option="tablet" title="{{ data.tablet_label }}"></span>
						<# } #>

						<# if ( 'undefined' !== typeof( data.mobile ) ) { #>
							<span class="woostify-device-mobile dashicons dashicons-smartphone" data-option="mobile" title="{{ data.mobile_label }}"></span>
						<# } #>
					</span>

					<span title="{{ data.reset_label }}" class="woostify-reset dashicons dashicons-image-rotate"></span>
				</div>
			</div>

			<div class="woostify-range-slider-areas">
				<# if
					( 'undefined' !== typeof( data.desktop ) ) {
					var attrDesktop = ! data.choices['desktop']['edit'] ? 'disabled' : '';
				#>
				<label class="range-option-area" data-option="desktop" style="display: none;">
					<div class="wrapper <# if ( '' !== data.choices['desktop']['unit'] ) { #>has-unit<# } #>">
						<div class="woostify_range_value">
							<input {{{ attrDesktop }}} type="number" step="{{ data.choices['desktop']['step'] }}" class="desktop-range value" value="{{ data.desktop.value }}" min="{{ data.choices['desktop']['min'] }}" max="{{ data.choices['desktop']['max'] }}" {{{ data.desktop.link }}} data-reset_value="{{ data.desktop.default }}" />

							<# if ( data.choices['desktop']['unit'] ) { #>
								<span class="unit">{{ data.choices['desktop']['unit'] }}</span>
							<# } #>
						</div>

						<div class="woostify-slider" data-step="{{ data.choices['desktop']['step'] }}" data-min="{{ data.choices['desktop']['min'] }}" data-max="{{ data.choices['desktop']['max'] }}"></div>
					</div>
				</label>
				<# } #>

				<# if
					( 'undefined' !== typeof( data.tablet ) ) {
					var attrTablet = ! data.choices['tablet']['edit'] ? 'disabled' : '';
				#>
				<label class="range-option-area" data-option="tablet" style="display:none">
					<div class="wrapper <# if ( '' !== data.choices['tablet']['unit'] ) { #>has-unit<# } #>">
						<div class="woostify_range_value">
							<input {{{ attrTablet }}} type="number" step="{{ data.choices['tablet']['step'] }}" class="tablet-range value" value="{{ data.tablet.value }}" min="{{ data.choices['tablet']['min'] }}" max="{{ data.choices['tablet']['max'] }}" {{{ data.tablet.link }}} data-reset_value="{{ data.tablet.default }}" />

							<# if ( data.choices['tablet']['unit'] ) { #>
							<span class="unit">{{ data.choices['tablet']['unit'] }}</span>
							<# } #>
						</div>

						<div class="woostify-slider" data-step="{{ data.choices['tablet']['step'] }}" data-min="{{ data.choices['tablet']['min'] }}" data-max="{{ data.choices['tablet']['max'] }}"></div>
					</div>
				</label>
				<# } #>

				<# if
					( 'undefined' !== typeof( data.mobile ) ) {
					var attrMobile = ! data.choices['mobile']['edit'] ? 'disabled' : '';
				#>
				<label class="range-option-area" data-option="mobile" style="display:none;">
					<div class="wrapper <# if ( '' !== data.choices['mobile']['unit'] ) { #>has-unit<# } #>">
						<div class="woostify_range_value">
							<input {{{ attrMobile }}} type="number" step="{{ data.choices['mobile']['step'] }}" class="mobile-range value" value="{{ data.mobile.value }}" min="{{ data.choices['mobile']['min'] }}" max="{{ data.choices['mobile']['max'] }}" {{{ data.mobile.link }}} data-reset_value="{{ data.mobile.default }}" />

							<# if ( data.choices['mobile']['unit'] ) { #>
							<span class="unit">{{ data.choices['mobile']['unit'] }}</span>
							<# } #>
						</div>

						<div class="woostify-slider" data-step="{{ data.choices['mobile']['step'] }}" data-min="{{ data.choices['mobile']['min'] }}" data-max="{{ data.choices['mobile']['max'] }}"></div>
					</div>
				</label>
				<# } #>
			</div>
		</div>
		<?php
	}
}

