<?php
/**
 * Sidebar partial.
 *
 * @link https://codex.wordpress.org/Customizing_Your_Sidebar
 *
 * @package GameplayTheme
 */

?>
<div class="sidebar">
	<ul class="widgets">
		<?php dynamic_sidebar( \GameplayTheme::core()->sidebar()->getCurrentSidebarId() ); ?>
	</ul>
</div>
