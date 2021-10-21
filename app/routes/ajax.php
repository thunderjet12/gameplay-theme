<?php
/**
 * WordPress AJAX Routes.
 * WARNING: Do not use \GameplayTheme::route()->all() here, otherwise you will override
 * ALL AJAX requests which you most likely do not want to do.
 *
 * @link https://docs.wpemerge.com/#/framework/routing/methods
 *
 * @package GameplayTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Using our ExampleController to handle a custom ajax action, for example.
// phpcs:ignore
// \GameplayTheme::route()->get()->where( 'ajax', 'my-custom-ajax-action' )->handle( 'ExampleController@ajax' );
