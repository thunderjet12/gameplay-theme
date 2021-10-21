<?php

namespace GameplayTheme\WordPress;

use WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Register widgets and sidebars.
 */
class ContentTypesServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		// Nothing to register.
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		add_action( 'init', [$this, 'registerPostTypes'] );
		add_action( 'init', [$this, 'registerTaxonomies'] );
	}

	/**
	 * Register post types.
	 *
	 * @return void
	 */
	public function registerPostTypes() {
		// phpcs:disable
		/*
		register_post_type(
			'gameplay_theme_custom_post_type',
			array(
				'labels'              => array(
					'name'               => __( 'Custom Types', 'gameplay_theme' ),
					'singular_name'      => __( 'Custom Type', 'gameplay_theme' ),
					'add_new'            => __( 'Add New', 'gameplay_theme' ),
					'add_new_item'       => __( 'Add new Custom Type', 'gameplay_theme' ),
					'view_item'          => __( 'View Custom Type', 'gameplay_theme' ),
					'edit_item'          => __( 'Edit Custom Type', 'gameplay_theme' ),
					'new_item'           => __( 'New Custom Type', 'gameplay_theme' ),
					'search_items'       => __( 'Search Custom Types', 'gameplay_theme' ),
					'not_found'          => __( 'No custom types found', 'gameplay_theme' ),
					'not_found_in_trash' => __( 'No custom types found in trash', 'gameplay_theme' ),
				),
				'public'              => true,
				'exclude_from_search' => false,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'hierarchical'        => false,
				'query_var'           => true,
				'menu_icon'           => 'dashicons-admin-post',
				'supports'            => array( 'title', 'editor', 'page-attributes' ),
				'rewrite'             => array(
					'slug'       => 'custom-post-type',
					'with_front' => false,
				),
			)
		);
		*/
		// phpcs:enable
	}

	/**
	 * Register taxonomies.
	 *
	 * @return void
	 */
	public function registerTaxonomies() {
		// phpcs:disable
		/*
		register_taxonomy(
			'gameplay_theme_custom_taxonomy',
			array( 'post_type' ),
			array(
				'labels'            => array(
					'name'              => __( 'Custom Taxonomies', 'gameplay_theme' ),
					'singular_name'     => __( 'Custom Taxonomy', 'gameplay_theme' ),
					'search_items'      => __( 'Search Custom Taxonomies', 'gameplay_theme' ),
					'all_items'         => __( 'All Custom Taxonomies', 'gameplay_theme' ),
					'parent_item'       => __( 'Parent Custom Taxonomy', 'gameplay_theme' ),
					'parent_item_colon' => __( 'Parent Custom Taxonomy:', 'gameplay_theme' ),
					'view_item'         => __( 'View Custom Taxonomy', 'gameplay_theme' ),
					'edit_item'         => __( 'Edit Custom Taxonomy', 'gameplay_theme' ),
					'update_item'       => __( 'Update Custom Taxonomy', 'gameplay_theme' ),
					'add_new_item'      => __( 'Add New Custom Taxonomy', 'gameplay_theme' ),
					'new_item_name'     => __( 'New Custom Taxonomy Name', 'gameplay_theme' ),
					'menu_name'         => __( 'Custom Taxonomies', 'gameplay_theme' ),
				),
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'custom-taxonomy' ),
			)
		);
		*/
		// phpcs:enable
	}
}
