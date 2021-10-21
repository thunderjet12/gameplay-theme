<?php
/**
 * Declare any actions and filters here.
 * In most cases you should use a service provider, but in cases where you
 * just need to add an action/filter and forget about it you can add it here.
 *
 * @package GameplayTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:ignore
// add_action( 'some_action', 'some_function' );
// WordPress environment
// require( dirname(__FILE__) . '/../../../wp-load.php' );

// $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05, for multisite works good as well
// $wordpress_upload_dir['url'] the absolute URL to the same folder, actually we do not need it, just to show the link to file

$i = 1; // number of tries when the file with the same name is already exists


//wp_insert_attachment
$cms_upload_dir = wp_upload_dir();
$sbc_images_path = get_theme_file_path() . "/resources/images/";
// $sbc_images_data = file_get_contents( $sbc_images_path );
$sbc_theme_config_data = file_get_contents( get_theme_file_path() . "/sbird-themes-config.json" ); 
$sbc_theme_config_data_decoded =  json_decode( $sbc_theme_config_data , true );
$sbc_theme_config_data_images = $sbc_theme_config_data_decoded["sbcThemeConfig"]["assets"]["media"]["images"];
// var_dump( $sbc_theme_config_data_images );
    // Need to require these files
    if ( !function_exists('media_handle_upload') ) {
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    }

// foreach ($sbc_theme_config_data_images as $sbc_image ) {
	
// 	$sbc_image_path = $sbc_images_path . $sbc_image['name'] ;
// 	$sbc_image_description = $sbc_image['alt'];
// // 	// $sbc_image_data = file_get_contents( $sbc_image_path );
// // 	// file_put_contents(  $sbc_image_path, $sbc_image_data);
	
// // 	//check file type
// // 	// $wp_file_type = wp_check_filetype( $sbc_image_path );
// // 	// $attachment = array(
// // 	// 	'post_mime_type' => $wp_file_type['type'],
// // 	// 	'post_title' => sanitize_file_name( $sbc_image['name'] ),
// // 	// 	'post_content' => '',
// // 	// 	'post_Status' => 'inherit'
// // 	// );

// // 	// $attachment_id = wp_insert_attachment( $attachment, $sbc_image_path );
// // 	// $attachment_data = wp_generate_attachment_metadata( $attachment_id, $sbc_image_path);
// // 	// wp_update_attachment_metadata( $attachment_id, $attachment_data );

// 	$sbc_image_array = array( 
// 		'name' => $sbc_image['name'],
// 		'tmp_name' =>  $sbc_image_path,
// 	);
// 	$sb_image_id = 0;
// 	// If error storing temporarily, return the error.
// 	if( is_wp_error( $sbc_image_array['tmp_name'] ) ) {
// 		return  $sbc_image_array['tmp_name'];
// 	}

// 	// Do the validation and storage stuff.
// 	$sbc_image_id = media_handle_sideload( $sbc_image_array, $sb_image_id, $sbc_image_description );
// 	// If error storing permanently, unlink.
// 	if( is_wp_error( $sbc_image_id ) ){
// 		@unlink( $sbc_image_array['tmp_name'] );
// 		return $sbc_image_id;
// 	}

// }


// var_dump( $sbc_theme_config_data_decoded["sbcThemeConfig"]["assets"]["media"]["images"] );

// $profile_picture = $sbc_images_data;


// wp_insert_attachment(  );







function outlines_data ( \WP_REST_Request $request ) {
	$sbc_blueprints_dir_path = WP_PLUGIN_DIR . "/gameplay-blocks/blueprints/";
	$blueprint_data = json_encode($request['blueprint'] );
	// $blueprint_title = $blueprint_data['currentPostTitle'];
	$blueprint_data_decode = json_decode( $blueprint_data, true );
	$blueprint_title = $blueprint_data_decode['currentPostTitle'];
	$blueprint_content_type = $blueprint_data_decode['currentPostType'];
	
	$sbc_blueprint_full_path = $sbc_blueprints_dir_path . $blueprint_content_type . "/" . $blueprint_title . '.json';
	
	if ( ! file_exists( $sbc_blueprints_dir_path . $blueprint_content_type ) ) {
		$sbc_blueprints_content_type_path = $sbc_blueprints_dir_path . $blueprint_content_type;
		return mkdir( $sbc_blueprints_content_type_path );
	
	} else {
		
	}
	
	$blueprint_open = fopen( $sbc_blueprint_full_path, "w" );
	if (  $blueprint_open  ) {
		return fwrite( $blueprint_open, $blueprint_data );
		fclose( $blueprint_open );
	}
}


function sbc_outlines_permission() {
	return current_user_can( 'edit_others_posts' );	
}

/**
 * rest api
 */
add_action( 'rest_api_init', function() {

	register_rest_route( 'outlines/v1', '/outline', array(
		'methods' => 'any',
		'callback' => 'outlines_data',
		'methods' => WP_REST_Server::ALLMETHODS,
		'permission_callback' => 'sbc_outlines_permission'
	) );


} );


/**
 * @var all required info as variables
 */

$sbc_activated_theme = "classic-white_theme";
$sbc_activated_theme_type = "tournaments_type";
$sbc_been_deactivated_theme = "classic-white_theme";
$sbc_been_deactivated_theme_type = "portal_type";
$sbc_been_deactivated_theme_style = "portal_type";

$sbc_templates_dir = get_theme_file_path() . "/block-templates";
$sbc_ativated_theme_path = get_theme_file_path() . "/outlines" . "/" . $sbc_activated_theme . "/";
$sbc_ativated_theme_type_dir_path =  $sbc_ativated_theme_path . $sbc_activated_theme_type;
$sbc_activated_theme_type_json_path = $sbc_ativated_theme_type_dir_path . "/type.json";
$sbc_ativated_theme_type_templates_dir_path = $sbc_ativated_theme_type_dir_path . "/templates";
$gp_templates_dir_path = get_theme_file_path() . "/block-templates";
// var_dump( file_get_contents($sbc_activated_theme_type_json_path) );
// if( copy( $sbc_ativated_theme_type_dir_path, $sbc_template_path ) ) {
// 	var_dump( "success" ) ;
// } else {
// 	var_dump( "failed" ) ;
// }

// clean previous theme templates fully
$sbc_been_deactivated_theme_type_json_path = "";
// $sbc_been_deactivated_theme_type_data = file_get_contents( $sbc_been_deactivated_theme_type_json_path );
// foreach ()
// $sbc_templates_files = glob( $sbc_templates_dir . '/*' );
// foreach ( $sbc_templates_files as $template_file ) {
// 	if ( is_file( $template_file ) )
// 		// delete template
// 		unlink( $template_file );
// }



// initialize activeted theme fully

$sbc_activated_theme_type_data = file_get_contents( $sbc_activated_theme_type_json_path );
$sbc_activated_theme_type_data_decoded = json_decode( $sbc_activated_theme_type_data, true );
$sbc_activated_theme_type_templates = $sbc_activated_theme_type_data_decoded['templates'];

foreach( $sbc_activated_theme_type_templates as  $template ) {
	copy ($sbc_ativated_theme_type_templates_dir_path . "/" . $template . ".html",
		  $gp_templates_dir_path . "/" . $template . ".html"
	);
}

