<?php
/**
 * Template used to display post content.
 *
 * @package woostify
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-loop' ); ?>>

	<?php
		/**
		 * Functions hooked in to woostify_loop_post action.
		 *
		 * @hooked woostify_post_header_open    - 10
		 * @hooked woostify_post_structure      - 20
		 * @hooked woostify_post_header_close   - 30
		 * @hooked woostify_post_content        - 40
		 */
		do_action( 'woostify_loop_post' );
	?>

</article>
