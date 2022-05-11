<?php
/**
 * Sortable for Customizer.
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Create a Sortable control.
 */
class Woostify_Sortable_Control extends WP_Customize_Control {
	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'woostify-sortable';

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
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script(
			'woostify-sortable',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/sortable/js/sortable.js',
			array(),
			woostify_version(),
			true
		);

		wp_enqueue_script(
			'woostify-sortable-handle',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/sortable/js/sortable-handle.js',
			array( 'woostify-sortable' ),
			woostify_version(),
			true
		);

		wp_enqueue_style(
			'woostify-sortable',
			WOOSTIFY_THEME_URI . 'inc/customizer/custom-controls/sortable/css/sortable.css',
			array(),
			woostify_version()
		);
	}

	/**
	 * To Json
	 */
	public function to_json() {
		parent::to_json();

		$this->json['id']          = $this->id;
		$this->json['label']       = $this->label;
		$this->json['description'] = $this->description;
		$this->json['choices']     = $this->choices;
		$this->json['value']       = maybe_unserialize( $this->value() );
		$this->json['link']        = $this->get_link();
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
		<#
			if ( ! data.choices ) {
				return;
			}
		#>
		<div class="woostify-sortable-control">
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>

			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{ data.description }}</span>
			<# } #>

			<div class="woostify-sortable-control-list">
				<#
					_.each( data.value, function( choiceID ) {
						var _choiceId = data.id + '_' + choiceID;
					#>
					<div class="woostify-sortable-list-item checked" data-value="{{{ choiceID }}}">
						<label class="sortable-item-icon-visibility dashicons dashicons-visibility" for="{{{ _choiceId }}}">
							<input class="sortable-item-input" type="checkbox" name="{{{ _choiceId }}}" id="{{{ _choiceId }}}" checked="checked">
						</label>
						<span class="sortable-item-name">{{{ data.choices[ choiceID ] }}}</span>
						<span class="sortable-item-icon-drag dashicons dashicons-menu"></span>
					</div>
				<#
					} );

					_.each( data.choices, function( key, value ) {
						var _id = data.id + '_' + value;

						if ( ! Array.isArray( data.value ) || -1 !== data.value.indexOf( value ) ) {
							return;
						}
					#>
					<div class="woostify-sortable-list-item" data-value="{{{ value }}}">
						<label class="sortable-item-icon-visibility dashicons dashicons-hidden" for="{{{ _id }}}">
							<input class="sortable-item-input" type="checkbox" name="{{{ _id }}}" id="{{{ _id }}}">
						</label>
						<span class="sortable-item-name">{{ key }}</span>
						<span class="sortable-item-icon-drag dashicons dashicons-menu"></span>
					</div>
				<# } ); #>
			</div>
			<#
				var _value = ( Array.isArray( data.value ) && data.value.length ) ? data.value.join( ':' ) : '';
			#>
			<input type="hidden" value="{{{ _value }}}" class="woostify-sortable-control-value">
		</div>
		<?php
	}

	/**
	 * Render the control's content.
	 *
	 * @see WP_Customize_Control::render_content()
	 */
	protected function render_content() {}
}

