<?php
/*
Plugin Name: WP Keyboard Shortcuts Lite
Plugin URI: https://codecanyon.net/item/wordpress-keyboard-shortcuts/20922588
Description: With this plugin you can bind any keyboard combination to different Wordpres actions and menus. 
Version: 1.5
Author: Evgen "EvgenDob" Dobrzhanskiy
Author URI: http://voodoopress.net
Stable tag: 1.5
*/

error_reporting( 0 );

$custom_actions = array(
	'Submit Post' => '#publish',
	'Post Preview' => '#post-preview',
	'Save Post' => '#save-post',
	'Post List Prev Page' => '.prev-page span',
	'Post List Next Page' => '.next-page span', 
	'Select All Posts' => '#cb-select-all-1',  
	'Add Featured Image' => '#set-post-thumbnail',  
);

include('modules/hooks.php');
include('modules/functions.php');
#include('modules/shortcodes.php');
include('modules/settings.php');
include('modules/meta_box.php');
#include('modules/widgets.php');

include('modules/cpt.php');
include('modules/scripts.php');
#include('modules/ajax.php');


?>