<?php
/**
 * Woostify_Color_Group_Control
 *
 * @package woostify
 */

/**
 * Customize Color Group Control class.
 */
class Woostify_Color_Group_Control extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-color-group';

	/**
	 * Tab
	 *
	 * @access public
	 * @var string
	 */
	public $tab = '';

	/**
	 * Tooltips
	 *
	 * @access public
	 * @var array
	 */
	public $tooltips = array();

	/**
	 * Color format
	 *
	 * @access public
	 * @var string
	 */
	public $color_format = 'rgba';

	/**
	 * Enable opacity
	 *
	 * @access public
	 * @var boolean
	 */
	public $enable_opacity = true;

	/**
	 * Enable Swatches
	 *
	 * @access public
	 * @var boolean
	 */
	public $enable_swatches = true;

	/**
	 * Is global color
	 *
	 * @access public
	 * @var boolean
	 */
	public $is_global_color = false;

	/**
	 * Setting prefix
	 *
	 * @access public
	 * @var string
	 */
	public $prefix = 'woostify_setting';

	/**
	 * Renders the control wrapper and calls $this->render_content() for the internals.
	 *
	 * @since 3.4.0
	 */
	protected function render() {
		$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class = 'customize-control customize-control-' . $this->type;

		if ( $this->is_global_color ) {
			$class .= ' woostify-global-color';
		}

		printf( '<li id="%s" class="%s" data-tab="%s">', esc_attr( $id ), esc_attr( $class ), esc_attr( $this->tab ) );
		$this->render_content();
		echo '</li>';
	}

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'woostify-color-picker-group',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/color-group/js/pickr.min.js',
			array(),
			woostify_version(),
			true
		);

		wp_enqueue_script(
			'woostify-color-group',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/color-group/js/color-group.js',
			array(),
			woostify_version(),
			true
		);

		wp_enqueue_style(
			'woostify-color-picker-group-monolith',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/color-group/css/monolith.min.css',
			array(),
			woostify_version()
		);

		wp_enqueue_style(
			'woostify-color-group',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/color-group/css/color-group.css',
			array(),
			woostify_version()
		);
	}

	/**
	 * To json data
	 */
	public function to_json() {
		parent::to_json();
		$options = woostify_options( false );

		$swatches                    = array();
		$global_color_settings       = $options['global_color_settings'];
		$global_color_settings_count = count( $global_color_settings );
		for ( $i = 0; $i < $global_color_settings_count; $i++ ) {
			$swatches[ $i ] = $options[ $global_color_settings[ $i ] ];
		}
		$this->json['swatches'] = $swatches;

		$this->json['swatchLabels']    = $options['global_color_labels'];
		$this->json['tab']             = $this->tab;
		$this->json['tooltips']        = $this->tooltips;
		$this->json['color_format']    = $this->color_format;
		$this->json['enable_opacity']  = $this->enable_opacity;
		$this->json['enable_swatches'] = $this->enable_swatches;
	}

	/**
	 * Renter the control
	 *
	 * @return void
	 */
	protected function render_content() {
		$control_id_arr = explode( '[', $this->id );
		$control_id     = isset( $control_id_arr[1] ) ? explode( ']', $control_id_arr[1] )[0] : $control_id_arr[0];
		$settings       = $this->settings;
		?>
		<div class="woostify-control-wrap woostify-color-group-control woostify-color-group-control-<?php echo esc_attr( $control_id ); ?>" data-control_id="<?php echo esc_attr( $control_id ); ?>" data-prefix="<?php echo esc_attr( $this->prefix ); ?>">
			<div class="color-group-wrap">
				<?php if ( '' !== $this->label ) { ?>
					<label class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
				<?php } ?>
				<div class="woostify-color-buttons">
					<span title="Reset" class="woostify-reset dashicons dashicons-image-rotate"></span>

					<?php foreach ( $settings as $setting_k => $setting ) { ?>
						<?php
						$btn_id_arr = explode( '[', $setting->id );
						$btn_id     = isset( $btn_id_arr[1] ) ? explode( ']', $btn_id_arr[1] )[0] : $btn_id_arr[0];
						?>
						<div class="woostify-color-picker-btn">
							<span class="woostify-color-group-btn btn-<?php echo esc_attr( $btn_id ); ?>"></span>
							<?php if ( ! empty( $this->tooltips ) ) { ?>
								<span class="btn-tooltip"><?php echo esc_html( $this->tooltips[ $setting_k ] ); ?></span>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
			foreach ( $settings as $k => $setting ) {
				$link        = $this->get_link( $k );
				$btn_id_arr  = explode( '[', $setting->id );
				$btn_id      = isset( $btn_id_arr[1] ) ? explode( ']', $btn_id_arr[1] )[0] : $btn_id_arr[0];
				$reset_value = $setting->default;
				?>
				<input type="hidden" class="color-group-value color-group-value-<?php echo esc_attr( $btn_id ); ?>" data-reset_value="<?php echo esc_attr( $this->value( $k ) ); ?>" <?php echo $link; //phpcs:ignore?> value="<?php echo esc_attr( $this->value( $k ) ); ?>">
			<?php } ?>
		</div>
		<?php
	}
}
