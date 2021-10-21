<?php
/**
 * This file is required by WordPress. Delegates the actual rendering to header.blade.php.
 *
 * @package GameplayTheme
 * phpcs:disable
 */
add_filter( 'wpemerge.partials.header.hook', '__return_false' );
\GameplayTheme::render( 'views.partials.header' );
remove_filter( 'wpemerge.partials.header.hook', '__return_false' );
