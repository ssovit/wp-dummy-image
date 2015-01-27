<?php

/*
Plugin Name: Dummy Image Uploader
Description: Upload Dummy Images for your choice.
Plugin URI: http://wppress.net/
Author: WPPress.net
Version: 2.5
Author URI: http://wppress.net
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
	private $typeKeywords = array(
		"abstract"=>"Abstract",
		"animals"=>"Animals",
		"business"=>"Business",
		"cats"=>"Cats",
		"city"=>"City",
		"food"=>"Food",
		"nightlife"=>"Night Life",
		"fashion"=>"Fashion",
		"people"=>"People",
		"nature"=>"Nature",
		"sports"=>"Sports",
		"technics"=>"Technology",
		"transport"=>"Transports",
	);
	
	private $defaultBG = "333333";
	private $defaultColor = "FFFFFF";
	
	function __construct($file = false) {
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
	function upload_image() {
		$dump_url = "http://placehold.it/" . $_GET['width'] . "x" . $_GET['height'] . ".jpg" . "/" . $_GET['bg'] . "/" . $_GET['color'] . "/";
		if ($_GET['image_keyword'] != "use_color") {
			$dump_url = "http://lorempixel.com/" . $_GET['width'] . "/" . $_GET['height'] . "/" . $_GET['image_keyword'];
		}
		$temp_file = download_url($dump_url,30);
		if (!is_wp_error($temp_file)) {
			
			// array based on $_FILE as seen in PHP file uploads
			$name="dummy-image-".time();
			$file = array(
				'name' => $name. ".jpg",
				'type' => 'image/jpg',
				'tmp_name' => $temp_file,
				'error' => 0,
			);
			$overrides = array(
				'test_form' => false,
				'test_size' => true,
				'test_upload' => true,
			);
			// move the temporary file into the uploads directory
			$id = media_handle_sideload($file, false);
			if (is_wp_error($id)) {
				echo json_encode(array(
					'result' => "error"
				));
			} else {
				echo json_encode(array(
					'result' => "success",
					'id' => $id
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
	function get_image_sizes() {
		global $_wp_additional_image_sizes;
		
		$sizes = array(
			'thumbnail' => array() ,
			'medium' => array() ,
			'large' => array() ,
			'full' => array()
		);
		$all_sizes = get_intermediate_image_sizes();
		foreach ($all_sizes as $size) {
			if (!isset($sizes[$size])) {
				$sizes[$size] = array();
			}
			$sizes[$size]['label'] = $size;
			$sizes[$size]['width'] = get_option($size . "_size_w", $_wp_additional_image_sizes[$size]['width']);
			$sizes[$size]['height'] = get_option($size . "_size_h", $_wp_additional_image_sizes[$size]['height']);
		}
		unset($sizes['full']);
		return $sizes;
	}
}

$WPP_Dummy_Imager = WPP_Dummy_Imager::get_instance();
