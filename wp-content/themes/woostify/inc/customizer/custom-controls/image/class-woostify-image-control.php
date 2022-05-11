<?php
/**
 * Woositfy Custom Image Control Params
 *
 * @package woostify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Create a extend control with custom params.
 * This control allows you to add responsive settings.
 */
class Woostify_Image_Control extends WP_Customize_Image_Control {

	/**
	 * Declare the custom param: tab.
	 *
	 * @var string
	 */
	public $tab = '';

	/**
	 * To json data
	 */
	public function to_json() {
		parent::to_json();

		$this->json['tab'] = $this->tab;
	}
}
