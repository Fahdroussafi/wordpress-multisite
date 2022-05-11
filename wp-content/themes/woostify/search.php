<?php
/**
 * The template for displaying search results pages.
 *
 * @package woostify
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title entry-title">
					<?php
						/* translators: %s: search term */
						printf( esc_html__( 'Search Results for: %s', 'woostify' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<?php
			get_template_part( 'template-parts/loop' );

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'woostify_sidebar' );
get_footer();
