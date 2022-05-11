<?php
/**
 * Woostify_Product_Data_Tabs_Control
 *
 * @package woostify
 */

/**
 * Customize Product Data Tabs class.
 */
class Woostify_Product_Data_Tabs_Control extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-product-data-tabs';

	/**
	 * Description
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_media();

		wp_enqueue_script(
			'woostify-customize-product-data-tabs',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/product-data-tabs/script.js',
			array( 'jquery' ),
			woostify_version(),
			true
		);

		wp_localize_script(
			'woostify-media-upload',
			'woostify_svg_icons',
			array(
				'file_url' => WOOSTIFY_THEME_URI . 'assets/svg/svgs.json',
			)
		);

		wp_enqueue_style(
			'woostify-customize-product-data-tabs',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/product-data-tabs/style.css',
			array(),
			woostify_version()
		);

		wp_enqueue_editor();
	}

	/**
	 * Renter the control
	 *
	 * @return void
	 */
	public function render_content() {
		$items = json_decode( $this->value() );
		?>
		<div class="woostify-adv-list-container">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<div class="woostify-adv-list-items woostify-sortable-control-list">
				<?php foreach ( $items as $k => $val ) { ?>
					<?php
					$tab_name = __( 'Custom', 'woostify' );
					switch ( $val->type ) {
						case 'description':
							$tab_name = __( 'Description', 'woostify' );
							break;
						case 'additional_information':
							$tab_name = __( 'Additional information', 'woostify' );
							break;
						case 'reviews':
							$tab_name = __( 'Reviews', 'woostify' );
							break;
						default:
							$tab_name = __( 'Custom', 'woostify' );
					}
					?>
					<div class="woostify-sortable-list-item-wrap checked">
						<div class="woostify-sortable-list-item woostify-adv-list-item checked" data-item_id="<?php echo esc_attr( $k ); ?>">
							<span class="sortable-item-icon-del dashicons dashicons-no-alt"></span>
							<span class="sortable-item-name"><?php echo esc_html( $tab_name ); ?></span>
							<span class="sortable-item-icon-expand dashicons dashicons-arrow-down-alt2"></span>
						</div>
						<div class="adv-list-item-content" data-item_id="<?php echo esc_attr( $k ); ?>">
							<div class="type-field woostify-adv-list-control customize-control-select" data-field_name="type">
								<?php
								$type_field_id   = preg_replace( '/[\[\]]/', '_', $this->id ) . $k . '_type';
								$type_field_name = "{$this->id}[{$k}][type]";
								?>
								<label for="<?php echo esc_attr( $type_field_id ); ?>"><?php echo esc_html__( 'Type', 'woostify' ); ?></label>
								<select name="<?php echo esc_attr( $type_field_name ); ?>" id="<?php echo esc_attr( $type_field_id ); ?>" class="woostify-adv-list-input woostify-adv-list-select">
									<option value="custom" <?php selected( $val->type, 'custom' ); ?>><?php esc_html_e( 'Custom', 'woostify' ); ?></option>
									<option value="description" <?php selected( $val->type, 'description' ); ?>><?php esc_html_e( 'Description', 'woostify' ); ?></option>
									<option value="additional_information" <?php selected( $val->type, 'additional_information' ); ?>><?php esc_html_e( 'Additional information', 'woostify' ); ?></option>
									<option value="reviews" <?php selected( $val->type, 'reviews' ); ?>><?php esc_html_e( 'Reviews', 'woostify' ); ?></option>
								</select>
							</div>
							<div class="name-field woostify-adv-list-control customize-control-text" data-field_name="name">
								<?php
								$name_field_id   = preg_replace( '/[\[\]]/', '_', $this->id ) . $k . '_name';
								$name_field_name = "{$this->id}[{$k}][name]";
								?>
								<label for="<?php echo esc_attr( $name_field_id ); ?>"><?php esc_html_e( 'Name', 'woostify' ); ?></label>
								<input type="text" class="woostify-adv-list-input woostify-adv-list-input--name" name="<?php echo esc_attr( $name_field_name ); ?>" id="<?php echo esc_attr( $name_field_id ); ?>" value="<?php echo esc_html( $val->name ); ?>">
							</div>
							<div class="content-field woostify-adv-list-control customize-control-textarea" data-field_name="content">
								<?php
								$content_field_id   = preg_replace( '/[\[\]]/', '_', $this->id ) . $k . '_content';
								$content_field_name = "{$this->id}[{$k}][content]";
								?>
								<label for="<?php echo esc_attr( $content_field_id ); ?>"><?php esc_html_e( 'Content', 'woostify' ); ?></label>
								<textarea class="woostify-adv-list-editor"  id="<?php echo esc_attr( $content_field_id ); ?>" rows="5"><?php echo esc_html( $val->content ); ?></textarea>
								<input type="hidden" class="woostify-adv-list-input woostify-adv-list-input--content" name="<?php echo esc_attr( $content_field_name ); ?>" data-editor-id="<?php echo esc_attr( $content_field_id ); ?>" value="<?php echo esc_attr( $val->content ); ?>">
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="woostify-sortable-list-item-wrap checked example-item-tmpl">
					<div class="woostify-sortable-list-item woostify-adv-list-item checked" data-item_id="{{ITEM_ID}}">
						<span class="sortable-item-icon-del dashicons dashicons-no-alt"></span>
						<span class="sortable-item-name"></span>
						<span class="sortable-item-icon-expand dashicons dashicons-arrow-down-alt2"></span>
					</div>
					<div class="adv-list-item-content" data-item_id="{{ITEM_ID}}">
						<div class="type-field woostify-adv-list-control customize-control-select" data-field_name="type">
							<?php
							$type_field_id   = preg_replace( '/[\[\]]/', '_', $this->id ) . '{{ITEM_ID}}_type';
							$type_field_name = "{$this->id}[{{ITEM_ID}}][type]";
							?>
							<label for="<?php echo esc_attr( $type_field_id ); ?>"><?php echo esc_html__( 'Type', 'woostify' ); ?></label>
							<select name="<?php echo esc_attr( $type_field_name ); ?>" id="<?php echo esc_attr( $type_field_id ); ?>" class="woostify-adv-list-input woostify-adv-list-select">
								<option value="custom"><?php esc_html_e( 'Custom', 'woostify' ); ?></option>
								<option value="description"><?php esc_html_e( 'Description', 'woostify' ); ?></option>
								<option value="additional_information"><?php esc_html_e( 'Additional information', 'woostify' ); ?></option>
								<option value="reviews"><?php esc_html_e( 'Reviews', 'woostify' ); ?></option>
							</select>
						</div>
						<div class="name-field woostify-adv-list-control customize-control-text" data-field_name="name">
							<?php
							$name_field_id   = preg_replace( '/[\[\]]/', '_', $this->id ) . '{{ITEM_ID}}_name';
							$name_field_name = "{$this->id}[{{ITEM_ID}}][name]";
							?>
							<label for="<?php echo esc_attr( $name_field_id ); ?>"><?php esc_html_e( 'Name', 'woostify' ); ?></label>
							<input type="text" class="woostify-adv-list-input woostify-adv-list-input--name" name="<?php echo esc_attr( $name_field_name ); ?>" id="<?php echo esc_attr( $name_field_id ); ?>" value="">
						</div>
						<div class="content-field woostify-adv-list-control customize-control-textarea" data-field_name="content">
							<?php
							$content_field_id   = preg_replace( '/[\[\]]/', '_', $this->id ) . '{{ITEM_ID}}_content';
							$content_field_name = "{$this->id}[{{ITEM_ID}}][content]";
							?>
							<label for="<?php echo esc_attr( $content_field_id ); ?>"><?php esc_html_e( 'Content', 'woostify' ); ?></label>
							<textarea class="woostify-adv-list-editor"  id="<?php echo esc_attr( $content_field_id ); ?>" rows="5"></textarea>
							<input type="hidden" class="woostify-adv-list-input woostify-adv-list-input--content" name="<?php echo esc_attr( $content_field_name ); ?>" data-editor-id="<?php echo esc_attr( $content_field_id ); ?>" value="">
						</div>
					</div>
				</div>
			</div>
			<button class="button button-primary adv-list-add-item-btn"><?php esc_html_e( 'Add Tab', 'woostify' ); ?></button>
			<input type="hidden" class="woostify-adv-list-value" <?php $this->link(); ?> value='<?php echo $this->value(); //phpcs:ignore ?>'/>
		</div>
		<?php
	}
}
