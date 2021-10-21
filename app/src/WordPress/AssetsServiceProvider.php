<?php

namespace GameplayTheme\WordPress;

use WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Register and enqueues assets.
 */
class AssetsServiceProvider implements ServiceProviderInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		// Nothing to register.
	}

	public function asset_file( $handle, $key ) {
		$default_asset_file = array(
			'dependencies' => array(),
			'version'      => '1.0',
		);
	
		$asset_filepath = get_stylesheet_directory_uri()  . "/resources/dist/{$handle}.asset.php";
		$asset_file     = file_exists( $asset_filepath ) ? include $asset_filepath : $default_asset_file;
	
		if ( 'version' === $key ) {
			return $asset_file['version'];
		}
	
		if ( 'dependencies' === $key ) {
			return $asset_file['dependencies'];
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		add_action( 'wp_enqueue_scripts', [$this, 'enqueueFrontendAssets'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueueAdminAssets'] );
		add_action( 'login_enqueue_scripts', [$this, 'enqueueLoginAssets'] );
		add_action( 'enqueue_block_editor_assets', [$this, 'enqueueEditorAssets'] );

		add_action( 'wp_head', [$this, 'addFavicon'], 5 );
		add_action( 'login_head', [$this, 'addFavicon'], 5 );
		add_action( 'admin_head', [$this, 'addFavicon'], 5 );

		add_action( 'upload_dir', [$this, 'fixUploadDirUrlSchema'] );

		add_action( 'wp_footer', [$this, 'loadSvgSprite'] );
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * @return void
	 */
	public function enqueueFrontendAssets() {
		// Enqueue the built-in comment-reply script for singular pages.
		if ( is_singular() ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Enqueue scripts.
		\GameplayTheme::core()->assets()->enqueueScript(
			'theme-js-bundle',
			// \GameplayTheme::core()->assets()->getBundleUrl( 'frontend', get_stylesheet_directory_uri() .'/resources/dist/frontend.min.js' ),
			get_stylesheet_directory_uri() .'/resources/dist/frontend.min.js',
			$this->asset_file( 'frontend', 'dependencies' ),
			$this->asset_file( 'frontend', 'version' ),
			true
		);

		// Enqueue styles.
		$style = \GameplayTheme::core()->assets()->getBundleUrl( 'frontend', '.css' );

		if ( $style ) {
			\GameplayTheme::core()->assets()->enqueueStyle(
				'theme-css-bundle',
				$style
			);
		}

		// Enqueue theme's style.css file to allow overrides for the bundled styles.
		\GameplayTheme::core()->assets()->enqueueStyle( 'theme-styles', get_stylesheet_directory_uri() . '/style.css' );
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @return void
	 */
	public function enqueueAdminAssets() {
		// Enqueue scripts.
		\GameplayTheme::core()->assets()->enqueueScript(
			'theme-admin-js-bundle',
			get_stylesheet_directory_uri() .'/resources/dist/admin.min.js',
			$this->asset_file( 'admin', 'dependencies' ),
			$this->asset_file( 'admin', 'version' ),
			true
		);

		// Enqueue styles.
		// $style = \GameplayTheme::core()->assets()->getBundleUrl( 'admin', '.css' );
		$style = get_stylesheet_directory_uri() .'/resources/dist/styles/admin.min.css' ;
		// var_dump( $style );
		if ( $style ) {
			\GameplayTheme::core()->assets()->enqueueStyle(
				'theme-admin-css-bundle',
				$style
			);
		}
	}

	/**
	 * Enqueue login assets.
	 *
	 * @return void
	 */
	public function enqueueLoginAssets() {
		// Enqueue scripts.
		\GameplayTheme::core()->assets()->enqueueScript(
			'theme-login-js-bundle',
			\GameplayTheme::core()->assets()->getBundleUrl( 'login', '.js' ),
			get_stylesheet_directory_uri() .'/resources/dist/login.min.js',
			$this->asset_file( 'login', 'dependencies' ),
			$this->asset_file( 'login', 'version' ),
			true
			
		);

		// Enqueue styles.
		// $style = \GameplayTheme::core()->assets()->getBundleUrl( 'login', '.css' );
		$style = get_stylesheet_directory_uri() .'/resources/dist/styles/login.min.css' ;
		if ( $style ) {
			\GameplayTheme::core()->assets()->enqueueStyle(
				'theme-login-css-bundle',
				$style
			);
		}
	}

	/**
	 * Enqueue editor assets.
	 *
	 * @return void
	 */
	public function enqueueEditorAssets() {
		// Enqueue scripts.
		\GameplayTheme::core()->assets()->enqueueScript(
			'theme-editor-js-bundle',
			// \GameplayTheme::core()->assets()->getBundleUrl( 'editor', '.js' ),
			get_stylesheet_directory_uri() .'/resources/dist/editor.min.js',
			$this->asset_file( 'editor', 'dependencies' ),
			$this->asset_file( 'editor', 'version' ),
			true
		);

		// Enqueue styles.
		// $style = \GameplayTheme::core()->assets()->getBundleUrl( 'login', '.css' );
		$style = get_stylesheet_directory_uri() .'/resources/dist/styles/editor.min.css' ;
		if ( $style ) {
			\GameplayTheme::core()->assets()->enqueueStyle(
				'theme-editor-css-bundle',
				$style
			);
		}
	}

	/**
	 * Add favicon.
	 *
	 * @return void
	 */
	public function addFavicon() {
		\GameplayTheme::core()->assets()->addFavicon();
	}


	/**
	 * Fix upload_dir urls having incorrect url schema.
	 *
	 * The wp_upload_dir() function urls' schema depends on the site_url option which
	 * can cause issues when HTTPS is forced using a plugin, for example.
	 *
	 * @link https://core.trac.wordpress.org/ticket/25449
	 * @param  array $upload_dir Array containing the current upload directory’s path and url.
	 * @return array Filtered array.
	 */
	public function fixUploadDirUrlSchema( $upload_dir ) {
		if ( is_ssl() ) {
			$upload_dir['url']     = set_url_scheme( $upload_dir['url'], 'https' );
			$upload_dir['baseurl'] = set_url_scheme( $upload_dir['baseurl'], 'https' );
		} else {
			$upload_dir['url']     = set_url_scheme( $upload_dir['url'], 'http' );
			$upload_dir['baseurl'] = set_url_scheme( $upload_dir['baseurl'], 'http' );
		}

		return $upload_dir;
	}

	/**
	 * Load SVG sprite.
	 *
	 * @return void
	 */
	public function loadSvgSprite() {
		$file_path = implode(
			DIRECTORY_SEPARATOR,
			array_filter(
				[
					get_stylesheet_directory_uri(),
					'dist',
					'images',
					'sprite.svg'
				]
			)
		);

		if ( ! file_exists( $file_path ) ) {
			return;
		}

		readfile( $file_path );
	}
}
