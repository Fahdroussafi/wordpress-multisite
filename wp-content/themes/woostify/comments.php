<?php
/**
 * The template for displaying comments.
 *
 * @package woostify
 */

if ( post_password_required() ) {
	return;
}

?>

<section id="comments" class="comments-area" aria-label="<?php esc_attr_e( 'Post Comments', 'woostify' ); ?>">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf(
					/* translators: 1: number of comments, 2: post title */
					esc_html( _nx( '%1$s comment', '%1$s comments', get_comments_number(), 'comments title', 'woostify' ) ),
					esc_html( number_format_i18n( get_comments_number() ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav class="comment-navigation" aria-label="<?php esc_attr_e( 'Comment Navigation Above', 'woostify' ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'woostify' ); ?></span>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'woostify' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'woostify' ) ); ?></div>
		</nav>
		<?php endif; ?>

		<ol class="comment-list">
			<?php
				wp_list_comments(
					array(
						'style'      => 'ol',
						'short_ping' => true,
						'callback'   => 'woostify_comment',
					)
				);
			?>
		</ol>

	<?php endif; ?>

	<?php if ( ! comments_open() && '0' !== get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'woostify' ); ?></p>
		<?php
	endif;

	$fields = array(
		'author' => '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_attr__( 'Name', 'woostify' ) . '" />',
		'email'  => '<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="' . esc_attr__( 'Email', 'woostify' ) . '" required />',
	);

	$args = apply_filters(
		'woostify_comment_form_args',
		array(
			'title_reply_before'   => '<span id="reply-title" class="gamma comment-reply-title">',
			'title_reply_after'    => '</span>',
			'comment_notes_before' => '',
			'comment_field'        => '<textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Message', 'woostify' ) . '" required ></textarea>',
			'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
			'label_submit'         => esc_html__( 'Submit', 'woostify' ),
		)
	);

	comment_form( $args );
	?>
</section>
