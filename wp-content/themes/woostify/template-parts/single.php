<?php
/**
 * Single template
 *
 * @package woostify
 */

?>

<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			do_action( 'woostify_single_post_before' );

			get_template_part( 'template-parts/content', 'single' );

			do_action( 'woostify_single_post_after' );

		endwhile;
		?>

		</main>
	</div>

<?php
do_action( 'woostify_sidebar' );
