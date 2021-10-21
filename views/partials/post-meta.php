<?php
/**
 * Displays the post date/time, author, tags, categories and comments link.
 *
 * Should be called only within The Loop.
 *
 * It will be displayed only for post type "post".
 *
 * @package GameplayTheme
 */

if ( get_post_type() !== 'post' ) {
	return;
}
?>
<div class="article__meta">
	<p>
		<?php
		the_time( 'F jS, Y ' );
		/* translators: post author attribution */
		echo esc_html( sprintf( __( 'by %s', 'gameplay_theme' ), get_the_author() ) );
		?>
	</p>

	<p>
		<?php
		esc_html_e( 'Posted in ', 'gameplay_theme' );
		the_category( ', ' );
		if ( comments_open() ) {
			echo '<span> | </span>';
			comments_popup_link( __( 'No Comments', 'gameplay_theme' ), __( '1 Comment', 'gameplay_theme' ), __( '% Comments', 'gameplay_theme' ) );
		}
		?>
	</p>

	<?php the_tags( '<p>' . __( 'Tags:', 'gameplay_theme' ) . ' ', ', ', '</p>' ); ?>
</div>
