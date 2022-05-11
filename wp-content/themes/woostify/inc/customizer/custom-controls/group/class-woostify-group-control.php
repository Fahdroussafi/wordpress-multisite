<?php
/**
 * Woostify_Group_Control
 *
 * @package woostify
 */

/**
 * Customize Group Control class.
 */
class Woostify_Group_Control extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-group';

	/**
	 * Inputs var
	 *
	 * @var array
	 */
	public $inputs_label = array();

	/**
	 * Tab
	 *
	 * @var string
	 */
	public $tab = '';

	/**
	 * Allow negative value
	 *
	 * @var bool
	 */
	public $negative_value = false;

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
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'woostify-group',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/group/js/group.js',
			array( 'jquery' ),
			woostify_version(),
			true
		);

		wp_enqueue_style(
			'woostify-group',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/group/css/group.css',
			array(),
			woostify_version()
		);
	}

	/**
	 * To json data
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

		$this->json['negative_value'] = $this->negative_value;

		$this->json['desktop_label'] = __( 'Desktop', 'woostify' );
		$this->json['tablet_label']  = __( 'Tablet', 'woostify' );
		$this->json['mobile_label']  = __( 'Mobile', 'woostify' );
		$this->json['reset_label']   = __( 'Reset', 'woostify' );

		$this->json['inputs_label'] = $this->inputs_label;
	}

	/**
	 * Renter the control
	 *
	 * @return void
	 */
	protected function render_content() {
		$reset_label       = __( 'Reset', 'woostify' );
		$data              = $this->settings;
		$link_values_label = __( 'Link Values Together', 'woostify' );
		?>
		<div class="woostify-group-control">
		<div class="woostify-responsive-title-area">
			<?php if ( '' !== $this->label ) { ?>
				<label class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php } ?>
			<?php if ( '' !== $this->description ) { ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>

			<div class="woostify-responsive-devices">
					<span class="woostify-responsive-devices-container">
						<?php
						foreach ( $this->settings as $setting_key => $setting_id ) {
							if ( 'desktop' === $setting_key ) {
								$device_label = __( 'Desktop', 'woostify' );
							} elseif ( 'tablet' === $setting_key ) {
								$device_label = __( 'Tablet', 'woostify' );
							} else {
								$device_label = __( 'Mobile', 'woostify' );
							}
							?>
							<span class="woostify-device-<?php echo esc_attr( $setting_key ); ?> dashicons dashicons-<?php echo 'mobile' === esc_attr( $setting_key ) ? 'smartphone' : esc_attr( $setting_key ); ?>" data-option="<?php echo esc_attr( $setting_key ); ?>" title="<?php echo esc_attr( $device_label ); ?>"></span>
						<?php } ?>
					</span>
				<span title="<?php echo esc_attr( $reset_label ); ?>" class="woostify-reset dashicons dashicons-image-rotate"></span>
			</div>
		</div>

		<div class="woostify-group-fields-area">
			<?php
			foreach ( $data as $setting_k => $setting_data ) {
				$link        = $this->get_link( $setting_k );
				$value       = explode( ' ', $this->value( $setting_k ) );
				$reset_value = isset( $setting_data->default ) ? explode( ' ', $setting_data->default ) : array();
				?>
				<div class="woostify-group-container" data-option="<?php echo esc_attr( $setting_k ); ?>" style="display: none">
					<?php
					if ( ! empty( $this->inputs_label ) ) {
						foreach ( $this->inputs_label as $input_k => $input_v ) {
							$input_id = preg_replace( '/[\[\]]/', '_', $setting_data->id ) . $input_k;
							?>
							<div class="woostify-group-field">
								<input type="number" class="woostify-group-input" data-reset_value="<?php echo esc_attr( $reset_value[ $input_k ] ); ?>" min="<?php echo esc_attr( $this->choices[ $setting_k ]['min'] ); ?>" max="<?php echo esc_attr( $this->choices[ $setting_k ]['max'] ); ?>" step="<?php echo esc_attr( $this->choices[ $setting_k ]['step'] ); ?>" id="<?php echo esc_attr( $input_id ); ?>" value="<?php echo isset( $value[ $input_k ] ) ? esc_attr( $value[ $input_k ] ) : ''; ?>">
								<label class="woostify-group-field-label" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $input_v ); ?></label>
							</div>
							<?php
						}
						?>
						<div class="woostify-link-value-together">
							<span class="woostify-link-value-together-btn dashicons dashicons-admin-links linked" title="<?php echo esc_attr( $link_values_label ); ?>"></span>
						</div>
						<?php
					}
					?>
					<input type="hidden" class="woostify-group-value" <?php echo $link; //phpcs:ignore ?> value="<?php echo esc_attr( $this->value( $setting_k ) ); ?>">
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
