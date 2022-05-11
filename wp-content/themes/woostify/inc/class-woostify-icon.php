<?php
/**
 * Woostify Icon Class
 *
 * @package  woostify
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Icon' ) ) {
	/**
	 * Class Woostify_Icon
	 */
	class Woostify_Icon {
		/**
		 * Woostify SVGs.
		 *
		 * @var woostify_svgs
		 */
		private static $woostify_svgs = null;

		/**
		 * Woostify SVGs array.
		 *
		 * @var woostify_svgs_arr
		 */
		private static $woostify_svgs_arr = null;

		/**
		 * Get an SVG Icon
		 *
		 * @param string $icon the icon name.
		 * @param bool   $echo echo icon.
		 */
		public static function fetch_svg_icon( $icon = '', $echo = true ) {
			$svg_output = '';

			if ( ! self::$woostify_svgs ) {
				ob_start();
				include_once WOOSTIFY_THEME_DIR . 'assets/svg/svgs.json'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				self::$woostify_svgs     = json_decode( ob_get_clean(), true );
				self::$woostify_svgs     = apply_filters( 'woostify_svg_icons', self::$woostify_svgs );
				self::$woostify_svgs_arr = self::$woostify_svgs;
			}
			$svg_output .= isset( self::$woostify_svgs[ $icon ] ) ? self::$woostify_svgs[ $icon ] : '';

			$classes = array(
				'woostify-svg-icon',
				'icon-' . $icon,
			);

			$output = sprintf(
				'<span class="%1$s">%2$s</span>',
				implode( ' ', $classes ),
				$svg_output
			);

			if ( $echo ) {
				echo apply_filters( 'woostify_generate_svg_icon', $output, $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				return apply_filters( 'woostify_generate_svg_icon', $output, $icon );
			}
		}

		/**
		 * Get all SVG Icon
		 */
		public static function fetch_all_svg_icon() {
			if ( ! self::$woostify_svgs_arr ) {
				ob_start();
				include_once WOOSTIFY_THEME_DIR . 'assets/svg/svgs.json'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				self::$woostify_svgs_arr = json_decode( ob_get_clean(), true );
				self::$woostify_svgs     = self::$woostify_svgs_arr;
				self::$woostify_svgs_arr = apply_filters( 'woostify_svg_icons_arr', self::$woostify_svgs_arr );
			}

			return self::$woostify_svgs_arr;
		}
	}
}
