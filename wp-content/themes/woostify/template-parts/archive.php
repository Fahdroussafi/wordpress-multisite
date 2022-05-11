<?php
/**
 * Archive template
 *
 * @package woostify
 */

?>

<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :
			get_template_part( 'template-parts/loop' );
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>

		</main>
	</div>

<?php
do_action( 'woostify_sidebar' );
