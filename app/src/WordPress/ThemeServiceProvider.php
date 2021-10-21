<?php

namespace GameplayTheme\WordPress;

use WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Register theme support options.
 */
class ThemeServiceProvider implements ServiceProviderInterface
{

	protected $sbc_theme_config_data_decoded;
	protected $sbc_theme_config_data_images;
	protected $sbc_images_path;

	public function __construct() {
		$cms_upload_dir = wp_upload_dir();
		$this->sbc_images_path = get_theme_file_path() . "/resources/images/";
		$sbc_theme_config_data = file_get_contents( get_theme_file_path() . "/sbird-themes-config.json" ); 
		$this->sbc_theme_config_data_decoded =  json_decode( $sbc_theme_config_data , true );
		$this->sbc_theme_config_data_images = $this->sbc_theme_config_data_decoded["sbcThemeConfig"]["assets"]["media"]["images"];
			// Need to require these files
		if ( !function_exists('media_handle_upload') ) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				require_once(ABSPATH . "wp-admin" . '/includes/file.php');
				require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		// Nothing to register.
		foreach ($this -> sbc_theme_config_data_images as $sbc_image ) {
	
			$sbc_image_path = $this->sbc_images_path . $sbc_image['name'] ;
			$sbc_image_description = $sbc_image['alt'];
			$sbc_image_array = array( 
				'name' => $sbc_image['name'],
				'tmp_name' =>  $sbc_image_path,
			);
			$sb_image_id = 0;
			// If error storing temporarily, return the error.
			if( is_wp_error( $sbc_image_array['tmp_name'] ) ) {
				return  $sbc_image_array['tmp_name'];
			}
		
			// Do the validation and storage stuff.
			$sbc_image_id = media_handle_sideload( $sbc_image_array, $sb_image_id, $sbc_image_description );
			// If error storing permanently, unlink.
			if( is_wp_error( $sbc_image_id ) ){
				@unlink( $sbc_image_array['tmp_name'] );
				return $sbc_image_id;
			}
		
		}

	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		add_action( 'after_setup_theme', [$this, 'loadTextdomain'] );
		add_action( 'after_setup_theme', [$this, 'addThemeSupport'] );
		/**
		 * rest api
		 */
		add_action( 'rest_api_init', [$this, 'sbThemesRestapiRegister'] );
		
	}

	public function  sbthemeData () {
		return $this->sbc_theme_config_data_decoded;
	}
	
	// sb permission
	public function sbThemesPermission() {
		return current_user_can( 'edit_others_posts' );	
	}
	/**
	 * 
	 */

	public function sbThemesRestapiRegister() {

		register_rest_route( 'sbthemes/v1', '/sbtheme', array(
			'methods' => 'any',
			'callback' => [$this, 'sbthemeData'],
			'methods' => \WP_REST_Server::ALLMETHODS,
			'permission_callback' => [$this, 'sbThemesPermission']
		) );


	} 

	/**
	 * Load textdomain.
	 *
	 * @return void
	 */
	public function loadTextdomain() {
		load_theme_textdomain( 'gameplay_theme', get_template_directory() . DIRECTORY_SEPARATOR . 'languages' );
	}

	/**
	 * Add theme support.
	 *
	 * @return void
	 */
	public function addThemeSupport() {
		/**
		 * Support custom logo.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		/**
		 * Support automatic feed links.
		 *
		 * @link https://codex.wordpress.org/Automatic_Feed_Links
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Support post thumbnails.
		 *
		 * @link https://codex.wordpress.org/Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Support document title tag.
		 *
		 * @link https://codex.wordpress.org/Title_Tag
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Support HTML5 markup.
		 *
		 * @link https://codex.wordpress.org/Theme_Markup
		 */
		add_theme_support( 'html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption'] );

		/**
		 * Manually select Post Formats to be supported.
		 *
		 * @link http://codex.wordpress.org/Post_Formats
		 */
		// phpcs:ignore
		// add_theme_support( 'post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'] );

		/**
		 * Support default editor block styles.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
		 */
		add_theme_support( 'wp-block-styles' );

		/**
		 * Support wide alignment for editor blocks.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
		 */
		add_theme_support( 'align-wide' );

		/**
		 * Support block editor styles.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
		 */
		add_theme_support( 'editor-styles' );
		add_editor_style( 'dist/styles/editor.css' );

		/**
		 * Support custom editor block color palette.
		 * Don't forget to edit resources/styles/shared/variables.scss when you update these.
		 * Uses Material Design colors.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
		 */
		add_theme_support(
			'editor-color-palette',
			[
				[
					'name'  => __( 'Red', 'gameplay_theme' ),
					'slug'  => 'material-red',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-red', '#000000' ),
				],
				[
					'name'  => __( 'Pink', 'gameplay_theme' ),
					'slug'  => 'material-pink',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-pink', '#000000' ),
				],
				[
					'name'  => __( 'Purple', 'gameplay_theme' ),
					'slug'  => 'material-purple',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-purple', '#000000' ),
				],
				[
					'name'  => __( 'Deep Purple', 'gameplay_theme' ),
					'slug'  => 'material-deep-purple',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-deep-purple', '#000000' ),
				],
				[
					'name'  => __( 'Indigo', 'gameplay_theme' ),
					'slug'  => 'material-indigo',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-indigo', '#000000' ),
				],
				[
					'name'  => __( 'Blue', 'gameplay_theme' ),
					'slug'  => 'material-blue',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-blue', '#000000' ),
				],
				[
					'name'  => __( 'Light Blue', 'gameplay_theme' ),
					'slug'  => 'material-light-blue',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-light-blue', '#000000' ),
				],
				[
					'name'  => __( 'Cyan', 'gameplay_theme' ),
					'slug'  => 'material-cyan',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-cyan', '#000000' ),
				],
				[
					'name'  => __( 'Teal', 'gameplay_theme' ),
					'slug'  => 'material-teal',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-teal', '#000000' ),
				],
				[
					'name'  => __( 'Green', 'gameplay_theme' ),
					'slug'  => 'material-green',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-green', '#000000' ),
				],
				[
					'name'  => __( 'Light Green', 'gameplay_theme' ),
					'slug'  => 'material-light-green',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-light-green', '#000000' ),
				],
				[
					'name'  => __( 'Lime', 'gameplay_theme' ),
					'slug'  => 'material-lime',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-lime', '#000000' ),
				],
				[
					'name'  => __( 'Yellow', 'gameplay_theme' ),
					'slug'  => 'material-yellow',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-yellow', '#000000' ),
				],
				[
					'name'  => __( 'Amber', 'gameplay_theme' ),
					'slug'  => 'material-amber',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-amber', '#000000' ),
				],
				[
					'name'  => __( 'Orange', 'gameplay_theme' ),
					'slug'  => 'material-orange',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-orange', '#000000' ),
				],
				[
					'name'  => __( 'Deep Orange', 'gameplay_theme' ),
					'slug'  => 'material-deep-orange',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-deep-orange', '#000000' ),
				],
				[
					'name'  => __( 'Brown', 'gameplay_theme' ),
					'slug'  => 'material-brown',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-brown', '#000000' ),
				],
				[
					'name'  => __( 'Grey', 'gameplay_theme' ),
					'slug'  => 'material-grey',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-grey', '#000000' ),
				],
				[
					'name'  => __( 'Blue Grey', 'gameplay_theme' ),
					'slug'  => 'material-blue-grey',
					'color' => \GameplayTheme::core()->config()->get( 'variables.color.material-blue-grey', '#000000' ),
				],
			]
		);

		/**
		 * Support color palette enforcement.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
		 */
		// phpcs:ignore
		// add_theme_support( 'disable-custom-colors' );

		/**
		 * Support custom editor block font sizes.
		 * Don't forget to edit resources/styles/shared/variables.scss when you update these.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
		 */
		add_theme_support(
			'editor-font-sizes',
			[
				[
					'name'      => __( 'extra small', 'gameplay_theme' ),
					'shortName' => __( 'XS', 'gameplay_theme' ),
					'size'      => (int) \GameplayTheme::core()->config()->get( 'variables.font-size.xs', 12 ),
					'slug'      => 'xs',
				],
				[
					'name'      => __( 'small', 'gameplay_theme' ),
					'shortName' => __( 'S', 'gameplay_theme' ),
					'size'      => (int) \GameplayTheme::core()->config()->get( 'variables.font-size.s', 16 ),
					'slug'      => 's',
				],
				[
					'name'      => __( 'regular', 'gameplay_theme' ),
					'shortName' => __( 'M', 'gameplay_theme' ),
					'size'      => (int) \GameplayTheme::core()->config()->get( 'variables.font-size.m', 20 ),
					'slug'      => 'm',
				],
				[
					'name'      => __( 'large', 'gameplay_theme' ),
					'shortName' => __( 'L', 'gameplay_theme' ),
					'size'      => (int) \GameplayTheme::core()->config()->get( 'variables.font-size.l', 28 ),
					'slug'      => 'l',
				],
				[
					'name'      => __( 'extra large', 'gameplay_theme' ),
					'shortName' => __( 'XL', 'gameplay_theme' ),
					'size'      => (int) \GameplayTheme::core()->config()->get( 'variables.font-size.xl', 36 ),
					'slug'      => 'xl',
				],
			]
		);

		/**
		 * Support WooCommerce.
		 */
		add_theme_support( 'woocommerce' );
	}
}
