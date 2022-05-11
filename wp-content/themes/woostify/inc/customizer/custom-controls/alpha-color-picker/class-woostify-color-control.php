<?php
/**
 * Woostify_Color_Control
 *
 * @package woostify
 */

/**
 * Customize Alpha Color Control class.
 */
class Woostify_Color_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-color';

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $suffix = '';

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

		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}
		$this->json['value']  = $this->value();
		$this->json['link']   = $this->get_link();
		$this->json['id']     = $this->id;
		$this->json['label']  = esc_html( $this->label );
		$this->json['suffix'] = $this->suffix;
		$this->json['tab']    = $this->tab;

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}
	}

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		$uri = WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/alpha-color-picker/';

		wp_enqueue_style(
			'woostify-alpha-color',
			$uri . 'css/alpha-color-picker.css',
			array(),
			woostify_version()
		);

		wp_enqueue_script(
			'woostify-alpha-color',
			$uri . 'js/alpha-color-picker.js',
			array( 'jquery', 'wp-color-picker' ),
			woostify_version(),
			true
		);

		wp_enqueue_script(
			'woostify-customizer-color-control',
			$uri . 'js/woostify-color-control.js',
			array( 'woostify-alpha-color' ),
			woostify_version(),
			true
		);

		wp_localize_script(
			'woostify-alpha-color',
			'woostify_customizer_color_control',
			array(
				'clear'            => esc_html__( 'Clear', 'woostify' ),
				'clearAriaLabel'   => esc_html__( 'Clear color', 'woostify' ),
				'defaultAriaLabel' => esc_html__( 'Select default color', 'woostify' ),
				'defaultLabel'     => esc_html__( 'Color value', 'woostify' ),
				'defaultString'    => esc_html__( 'Default', 'woostify' ),
				'pick'             => esc_html__( 'Select Color', 'woostify' ),
			)
		);
	}

	/**
	 * Render a JS template for the content of the color picker control.
	 */
	public function content_template() {
		?>

		<#
		var defaultValue = '#RRGGBB', defaultValueAttr = '';
		if ( data.default ) {
		defaultValue     = data.default;
		defaultValueAttr = ' data-default-color=' + data.default; // Quotes added automatically.
		}

		if ( data.label ) {
		#>
		<label> <span class="customize-control-title">{{{ data.label }}}</span> </label>
		<# } #>
		<# if ( data.description ) { #>
		<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<div class="customize-control-content">
			<input class="woostify-color-picker-alpha color-picker-hex" type="text" maxlength="7" data-alpha="true" placeholder="{{ defaultValue }}" {{ defaultValueAttr }} value="{{data.value}}"/>
		</div>

		<?php
	}
}
