<?php
/**
 * Base layout.
 *
 * @package GameplayTheme
 */

?>
@include('views.partials.header')

@if (!is_singular())
	@php gameplay_theme_the_title( '<h2 class="post-title">', '</h2>' ) @endphp
@endif

@yield('content')

@include('views.partials.sidebar')

@include('views.partials.footer')
