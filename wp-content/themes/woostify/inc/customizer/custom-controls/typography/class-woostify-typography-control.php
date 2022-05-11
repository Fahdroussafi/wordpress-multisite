<?php
/**
 * The typography Customizer control.
 *
 * @package wosstify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Woostify_Typography_Control' ) ) {
	/**
	 * Create the typography elements control.
	 */
	class Woostify_Typography_Control extends WP_Customize_Control {

		/**
		 * Create the typography elements control.
		 *
		 * @var $type
		 */
		public $type = 'woostify-customizer-typography';


		/**
		 * Enqueue javascript and css file
		 */
		public function enqueue() {
			wp_enqueue_script(
				'woostify-typography-selectWoo',
				WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/typography/js/selectWoo.js',
				array( 'customize-controls', 'jquery' ),
				woostify_version(),
				true
			);

			wp_enqueue_style(
				'woostify-typography-selectWoo',
				WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/typography/css/selectWoo.css',
				array(),
				woostify_version()
			);

			wp_enqueue_script(
				'woostify-typography-customizer',
				WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/typography/js/typography-customizer.js',
				array( 'customize-controls', 'woostify-typography-selectWoo' ),
				woostify_version(),
				true
			);

			wp_enqueue_style(
				'woostify-typography-customizer',
				WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/typography/css/typography-customizer.css',
				array(),
				woostify_version()
			);
		}


		/**
		 * Convert to json
		 */
		public function to_json() {
			parent::to_json();

			$number_of_fonts                   = apply_filters( 'woostify_number_of_fonts', 200 );
			$this->to_json['label']            = $this->label;
			$this->json['default_fonts_title'] = __( 'System fonts', 'woostify' );
			$this->json['google_fonts_title']  = __( 'Google fonts', 'woostify' );
			$this->json['google_fonts']        = apply_filters( 'woostify_typography_customize_list', Woostify_Fonts_Helpers::woostify_get_all_google_fonts( $number_of_fonts ) );
			$this->json['default_fonts']       = Woostify_Fonts_Helpers::woostify_typography_default_fonts();
			$this->json['family_title']        = esc_html__( 'Font family', 'woostify' );
			$this->json['weight_title']        = esc_html__( 'Font weight', 'woostify' );
			$this->json['transform_title']     = esc_html__( 'Text transform', 'woostify' );
			$this->json['category_title']      = '';
			$this->json['variant_title']       = esc_html__( 'Variants', 'woostify' );

			foreach ( $this->settings as $setting_key => $setting_id ) {
				$this->json[ $setting_key ] = array(
					'link'    => $this->get_link( $setting_key ),
					'value'   => $this->value( $setting_key ),
					'default' => isset( $setting_id->default ) ? $setting_id->default : '',
					'id'      => isset( $setting_id->id ) ? $setting_id->id : '',
				);

				if ( 'weight' === $setting_key ) {
					$this->json[ $setting_key ]['choices'] = $this->get_font_weight_choices();
				}

				if ( 'transform' === $setting_key ) {
					$this->json[ $setting_key ]['choices'] = $this->get_font_transform_choices();
				}
			}
		}


		/**
		 * Content template
		 */
		public function content_template() {
			?>
			<# if ( '' !== data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>
			<# if ( 'undefined' !== typeof( data.family ) ) { #>
				<div class="woostify-font-family">
					<label>
						<select {{{ data.family.link }}} data-category="{{{ data.category.id }}}" data-variants="{{{ data.variant.id }}}" style="width:100%;">
							<optgroup label="{{ data.default_fonts_title }}">
								<# for ( var key in data.default_fonts ) { #>
									<# var name = data.default_fonts[ key ].split(',')[0]; #>
									<option value="{{ data.default_fonts[ key ] }}"  <# if ( data.default_fonts[ key ] === data.family.value ) { #>selected="selected"<# } #>>{{ name }}</option>
								<# } #>
							</optgroup>
							<optgroup label="{{ data.google_fonts_title }}">
								<# for ( var key in data.google_fonts ) { #>
									<option value="{{ data.google_fonts[ key ].name }}"  <# if ( data.google_fonts[ key ].name === data.family.value ) { #>selected="selected"<# } #>>{{ data.google_fonts[ key ].name }}</option>
								<# } #>
							</optgroup>
						</select>
						<# if ( '' !== data.family_title ) { #>
							<p class="description">{{ data.family_title }}</p>
						<# } #>
					</label>
				</div>
			<# } #>

			<# if ( 'undefined' !== typeof( data.variant ) ) { #>
				<#
				var id = data.family.value.split(' ').join('_').toLowerCase();
				var font_data = data.google_fonts[id];
				var variants = '';
				if ( typeof font_data !== 'undefined' ) {
					variants = font_data.variants;
				}

				if ( null === data.variant.value ) {
					data.variant.value = data.variant.default;
				}
				#>
				<div id={{{ data.variant.id }}}" class="woostify-font-variant" data-saved-value="{{ data.variant.value }}">
					<label>
						<select name="{{{ data.variant.id }}}" multiple class="typography-multi-select" style="width:100%;" {{{ data.variant.link }}}>
							<# _.each( variants, function( label, choice ) { #>
								<option value="{{ label }}">{{ label }}</option>
							<# } ) #>
						</select>

						<# if ( '' !== data.variant_title ) { #>
							<p class="description">{{ data.variant_title }}</p>
						<# } #>
					</label>
				</div>
			<# } #>

			<# if ( 'undefined' !== typeof( data.category ) ) { #>
				<div class="woostify-font-category">
					<label>
							<input name="{{{ data.category.id }}}" type="hidden" {{{ data.category.link }}} value="{{{ data.category.value }}}" class="woostify-hidden-input" />
						<# if ( '' !== data.category_title ) { #>
							<p class="description">{{ data.category_title }}</p>
						<# } #>
					</label>
				</div>
			<# } #>

			<# if ( 'undefined' !== typeof( data.weight ) ) { #>
				<div class="woostify-font-weight">
					<label>
						<select {{{ data.weight.link }}}>

							<# _.each( data.weight.choices, function( label, choice ) { #>

								<option value="{{ choice }}" <# if ( choice === data.weight.value ) { #> selected="selected" <# } #>>{{ label }}</option>

							<# } ) #>

						</select>
						<# if ( '' !== data.weight_title ) { #>
							<p class="description">{{ data.weight_title }}</p>
						<# } #>
					</label>
				</div>
			<# } #>

			<# if ( 'undefined' !== typeof( data.transform ) ) { #>
				<div class="woostify-font-transform">
					<label>
						<select {{{ data.transform.link }}}>

							<# _.each( data.transform.choices, function( label, choice ) { #>

								<option value="{{ choice }}" <# if ( choice === data.transform.value ) { #> selected="selected" <# } #>>{{ label }}</option>

							<# } ) #>

						</select>
						<# if ( '' !== data.transform_title ) { #>
							<p class="description">{{ data.transform_title }}</p>
						<# } #>
					</label>
				</div>
			<# } #>
			<?php
		}


		/**
		 * Gets the font weight choices.
		 *
		 * @return     array  The font weight choices.
		 */
		public function get_font_weight_choices() {
			return array(
				'normal' => 'normal',
				'bold'   => 'bold',
				'100'    => '100',
				'200'    => '200',
				'300'    => '300',
				'400'    => '400',
				'500'    => '500',
				'600'    => '600',
				'700'    => '700',
				'800'    => '800',
				'900'    => '900',
			);
		}


		/**
		 * Gets the font transform choices.
		 *
		 * @return     array  The font transform choices.
		 */
		public function get_font_transform_choices() {
			return array(
				'none'       => 'none',
				'capitalize' => 'capitalize',
				'uppercase'  => 'uppercase',
				'lowercase'  => 'lowercase',
			);
		}

	}
}
