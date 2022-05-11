<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package woostify
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to woostify_page add_action
	 *
	 * @hooked woostify_page_content - 20
	 */
	do_action( 'woostify_page' );
	?>
</article><!-- #post-## -->
