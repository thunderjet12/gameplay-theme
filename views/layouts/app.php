<?php
/**
 * Base layout.
 *
 * @link https://docs.wpemerge.com/#/framework/views/layouts
 *
 * @package GameplayTheme
 */

\GameplayTheme::render( 'header' );

if ( ! is_singular() ) {
	gameplay_theme_the_title( '<h2 class="post-title">', '</h2>' );
}

\GameplayTheme::layoutContent();

\GameplayTheme::render( 'sidebar' );

\GameplayTheme::render( 'footer' );
