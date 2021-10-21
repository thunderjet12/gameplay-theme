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

?>
@if ( get_post_type() === 'post' )
	<div class="article__meta">
		<p>
			{{ get_the_time( 'F jS, Y ' ) }}
			{{-- translators: post author attribution --}}
			{{ sprintf( __( 'by %s', 'gameplay_theme' ), get_the_author() ) }}
		</p>

		<p>
			{{ __( 'Posted in ', 'gameplay_theme' ) }}
			@php the_category( ', ' ) @endphp
			@if (comments_open())
				<span> | </span>
				@php comments_popup_link( __( 'No Comments', 'gameplay_theme' ), __( '1 Comment', 'gameplay_theme' ), __( '% Comments', 'gameplay_theme' ) ) @endphp
			@endif
		</p>

		@php the_tags( '<p>' . __( 'Tags:', 'gameplay_theme' ) . ' ', ', ', '</p>' ) @endphp
	</div>
@endif
