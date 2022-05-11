<?php
/**
 * Woostify Walker Menu Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Walker_Menu' ) ) {
	/**
	 * Woostify Walker Menu Class
	 */
	class Woostify_Walker_Menu extends Walker_Nav_Menu {
		/**
		 * Walker menu
		 *
		 * @see Walker::start_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int    $depth Depth of menu item. Used for padding.
		 * @param object $args The array.
		 * @param int    $id The id.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent      = $depth ? str_repeat( "\t", $depth ) : '';
			$class_names = '';
			$value       = '';

			// Classes name.
			$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			if ( 'mega_menu' === $item->object ) {
				$this->megamenu_width    = get_post_meta( $item->ID, 'woostify_mega_menu_item_width', true );
				$this->megamenu_width    = '' !== $this->megamenu_width ? $this->megamenu_width : 'content';
				$this->megamenu_position = get_post_meta( $item->ID, 'woostify_mega_menu_item_position', true );
				$this->megamenu_url      = get_post_meta( $item->ID, 'woostify_mega_menu_item_url', true );
				$this->megamenu_icon     = get_post_meta( $item->ID, 'woostify_mega_menu_item_icon', true );
				$this->megamenu_icon     = str_replace( 'ti-', '', $this->megamenu_icon );
				$href                    = $this->megamenu_url;

				if ( ! $href ) {
					$href = '#';
				}

				$classes[] = 'menu-item-has-children';
				$classes[] = 'menu-item-has-mega-menu';
				$classes[] = 'has-mega-menu-' . $this->megamenu_width . '-width';
				$classes[] = woostify_is_elementor_page( $item->object_id ) ? 'mega-menu-elementor' : '';

				if ( 'parent' === $this->megamenu_position ) {
					$classes[] = 'menu-item-has-children-same-position';
				}
			}
			$classes = array_filter( $classes );

			// Check this item has children.
			$has_child = in_array( 'menu-item-has-children', $classes, true ) ? true : false;

			// Join classes name.
			$class_names = join( ' ', apply_filters( 'woostify_mega_menu_css_class', $classes, $item, $args ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			// Ids.
			$id = apply_filters( 'woostify_mega_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			// Start output.
			$output .= $indent . '<li' . $id . $value . $class_names . '>';

			// Attributes.
			$atts           = array();
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';
			$atts           = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
			$attributes     = '';

			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
					$value       = 'mega_menu' === $item->object ? $href : $value;
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			if ( ! empty( $item->attr_title ) ) {
				$item_output .= '<a' . $attributes . ' title="' . esc_attr( $item->attr_title ) . '">';
			} else {
				$item_output .= '<a' . $attributes . '>';
			}

			// Menu icon.
			if ( 'mega_menu' === $item->object && $this->megamenu_icon ) {
				$item_output .= '<span class="menu-item-icon">';
				$item_output .= Woostify_Icon::fetch_svg_icon( $this->megamenu_icon, false );
				$item_output .= '</span>';
			}

			$title = apply_filters( 'the_title', $item->title, $item->ID );
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			// Menu item text.
			$item_output .= $args->link_before . '<span class="menu-item-text">' . $title . '</span>' . $args->link_after;

			// Add arrow icon.
			if ( $has_child ) {
				$item_output .= '<span class="menu-item-arrow arrow-icon">' . Woostify_Icon::fetch_svg_icon( 'angle-down', false ) . '</span>';
			}

			$item_output .= '</a>';

			// Start Mega menu content.
			if ( 'mega_menu' === $item->object && 0 === $depth && ! woostify_is_elementor_editor() ) {
				$mega_menu = '';

				if ( class_exists( 'Woostify_Header_Footer_Builder' ) ) {
					$frontend   = new \Elementor\Frontend();
					$mega_menu .= $frontend->get_builder_content_for_display( $item->object_id, true );
				} else {
					$mega_args = array(
						'p'                   => $item->object_id,
						'post_type'           => 'mega_menu',
						'post_status'         => 'publish',
						'posts_per_page'      => 1,
						'ignore_sticky_posts' => 1,
						'fields'              => 'ids',
					);

					$query = new WP_Query( $mega_args );

					if ( $query->have_posts() ) {
						ob_start();
						echo '<div class="mega-menu-inner-wrapper">';
						while ( $query->have_posts() ) {
							$query->the_post();

							the_content();
						}
						echo '</div>';
						$mega_menu .= ob_get_clean();

						wp_reset_postdata();
					}
				}

				if ( ! empty( $mega_menu ) ) {
					$item_output .= '<ul class="sub-mega-menu">';
					$item_output .= '<div class="mega-menu-wrapper">';
					$item_output .= $mega_menu;
					$item_output .= '</div>';
					$item_output .= '</ul>';
				}
			} // End Mega menu content.

			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
}
