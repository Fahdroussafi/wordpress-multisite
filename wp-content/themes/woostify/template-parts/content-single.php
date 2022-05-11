<?php
/**
 * Template used to display post content on single pages.
 *
 * @package woostify
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-loop' ); ?>>

	<?php
	/**
	 * Functions hooked into woostify_single_post add_action
	 *
	 * @hooked woostify_post_single_structure   - 10
	 * @hooked woostify_post_content            - 20
	 * @hooked woostify_post_tags               - 30
	 */
	do_action( 'woostify_single_post' );
	?>

</article><!-- #post-## -->
