<?php
/**
 * This file is required by WordPress. Delegates the actual rendering to sidebar.blade.php.
 *
 * @package GameplayTheme
 * phpcs:disable
 */
add_filter( 'wpemerge.partials.sidebar.hook', '__return_false' );
\GameplayTheme::render( 'views.partials.sidebar' );
remove_filter( 'wpemerge.partials.sidebar.hook', '__return_false' );
