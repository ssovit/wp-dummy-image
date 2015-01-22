<?php

/*
Plugin Name: Dummy Image Uploader
Description: Upload Dummy Images for your choice.
Plugin URI: http://wppress.net/
Author: WPPress.net
Version: 1.0
Author URI: http://codecanyon.net/user/wppress
*/

class WPP_Dummy_Imager
{
	private static $instance = null;
	private $imageTypes = array(
		"jpg",
		"png",
		"gif",
		"jpeg"
	);
	private $defaultBG = "333333";
	private $defaultColor = "FFFFFF";

	function __construct($file = false) {
		
		/*add_filter('media_upload_tabs', array(&$this,
			'media_upload_tabs'
		) , 1000, 2);*/
		add_action('wp_enqueue_media', array(&$this,
			'wp_enqueue_media'
		));
		
		add_action('admin_enqueue_scripts', array(&$this,
			'admin_enqueue_scripts'
		));
		
		add_filter('media_view_strings', array(&$this,
			'media_view_strings'
		) , 10, 2);
		add_action('wp_ajax_upload_dummy_image', array(&$this,
			'upload_image'
		));
	}
	public static function output_handle($output){
		header('Content-Length: ' . strlen($output));
		return $output;

	}
	function clearn_colors($code){

	}
	function upload_image() {
		$dump_url = add_query_arg(array(
			'action' => 'dummy_image',
			'type' => "png",
			'width' => $_GET['width'],
			'height' => $_GET['height']
		) , get_admin_url(null, 'admin-ajax.php'));

		$dump_url = "http://placehold.it/".$_GET['width']."x".$_GET['height'].".png"."/".$_GET['bg']."/".$_GET['color']."/";
		$temp_file = download_url($dump_url);
		if (!is_wp_error($temp_file)) {
		
			// array based on $_FILE as seen in PHP file uploads
			$file = array(
				'name' =>$_GET['width'] . "x" . $_GET['height']. "-dummy-image". ".png",
				'type' => 'image/png',
				'tmp_name' => $temp_file,
				'error' => 0,
				//'size' => filesize($temp_file) ,
			);
			$overrides = array(
				'test_form' => false,
				'test_size' => true,
				'test_upload' => true,
			);
			
			// move the temporary file into the uploads directory
			$id = media_handle_sideload($file, false);
			if ( is_wp_error($id) ) {
				echo json_encode(array(
					'result' => "error"
				));
			} else {
				echo json_encode(array(
					'result' => "success"
				));
			}
			die();
		}
		echo json_encode(array(
			'result' => "error"
		));
		die();
	}
	function admin_enqueue_scripts() {
		wp_enqueue_script('custom', plugins_url('script.js', __FILE__) , array(
			'media-views'
		) , false, true);
	}
	function media_view_strings($strings, $post) {
		$strings['wppressInsertDummyImageTitle'] = __('Dummy Image', 'wppress');
		return $strings;
	}
	function wp_enqueue_media() {
		include "template.php";
	}
	
	public static function get_instance() {
		if (self::$instance == null) {
			self::$instance = new self(__FILE__);
		}
		return self::$instance;
	}
}

$WPP_Dummy_Imager = WPP_Dummy_Imager::get_instance();
