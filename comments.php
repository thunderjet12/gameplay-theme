<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package GameplayTheme
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<section class="section-comments" id="comments">
	<?php if ( have_comments() ) : ?>
		<h3><?php comments_number( __( 'No Responses', 'gameplay_theme' ), __( 'One Response', 'gameplay_theme' ), __( '% Responses', 'gameplay_theme' ) ); ?></h3>
		<ol class="comments">
			<?php
			wp_list_comments(
				[
					'callback' => function( $comment, $args, $depth ) {
						\GameplayTheme::render(
							'views/partials/comment-single',
							[
								'comment' => $comment,
								'args'    => $args,
								'depth'   => $depth,
							]
						);
					},
				]
			);
			?>
		</ol>

		<?php \GameplayTheme::render( 'views/partials/pagination', [ 'for_comments' => true ] ); ?>
	<?php else : ?>
		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'gameplay_theme' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		[
			'title_reply'         => __( 'Leave a Reply', 'gameplay_theme' ),
			'comment_notes_after' => '',
		]
	);
	?>
</section>
