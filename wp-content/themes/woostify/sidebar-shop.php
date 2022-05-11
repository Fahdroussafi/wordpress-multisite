<?php
/**
 * The sidebar containing the shop page widget area.
 *
 * @package woostify
 */

?>
<div id="secondary" class="widget-area shop-widget" role="complementary">
	<?php
	if ( is_active_sidebar( 'sidebar-shop' ) ) {
		dynamic_sidebar( 'sidebar-shop' );
	} elseif ( is_user_logged_in() ) {
		?>
		<div class="widget widget_text default-widget">
			<h6 class="widgettitle"><?php esc_html_e( 'Sidebar Shop Widget', 'woostify' ); ?></h6>
			<div class="textwidget">
				<p>
					<?php
					printf(
						/* translators: 1: admin URL */
						__( 'Replace this widget content by going to <a href="%1$s"><strong>Appearance / Widgets / Woocommerce Sidebar</strong></a> and dragging widgets into this widget area.', 'woostify' ),
						esc_url( admin_url( 'widgets.php' ) )
					);  // WPCS: XSS ok.
					?>
				</p>
			</div>
		</div>

		<div class="widget widget_text default-widget">
			<h6 class="widgettitle"><?php esc_html_e( 'Sidebar Shop Widget', 'woostify' ); ?></h6>
			<div class="textwidget">
				<p>
					<?php
					printf(
						/* translators: 1: admin URL */
						__( 'Replace this widget content by going to <a href="%1$s"><strong>Appearance / Widgets / Woocommerce Sidebar</strong></a> and dragging widgets into this widget area.', 'woostify' ),
						esc_url( admin_url( 'widgets.php' ) )
					);  // WPCS: XSS ok.
					?>
				</p>
			</div>
		</div>

		<div class="widget widget_text default-widget">
			<h6 class="widgettitle"><?php esc_html_e( 'Sidebar Shop Widget', 'woostify' ); ?></h6>
			<div class="textwidget">
				<p>
					<?php
					printf(
						/* translators: 1: admin URL */
						__( 'Replace this widget content by going to <a href="%1$s"><strong>Appearance / Widgets / Woocommerce Sidebar</strong></a> and dragging widgets into this widget area.', 'woostify' ),
						esc_url( admin_url( 'widgets.php' ) )
					);  // WPCS: XSS ok.
					?>
				</p>
			</div>
		</div>
	<?php } ?>
</div>
