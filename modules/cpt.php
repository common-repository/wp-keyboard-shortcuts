<?php
function wks_add_post_type() {
  $labels = array(
    'name' => __('Action Queries', 'fs'),
    'singular_name' => __('Action Query', 'fs'),
    'add_new' => __('Add Query', 'fs'),
    'add_new_item' => __('Add New Action Query', 'fs'),
    'edit_item' => __('Edit Action Query', 'fs'),
    'new_item' => __('New Action Query', 'fs'),
    'all_items' => __('Action Queries', 'fs'),
    'view_item' => __('View Actions Query', 'fs'),
    'search_items' => __('Search Action Query', 'fs'),
    'not_found' =>  __('No Action Queries found', 'fs'),
    'not_found_in_trash' => __('No Action Queries found in Trash', 'fs'), 
    'parent_item_colon' => '',
    'menu_name' => __('Shortcuts', 'fs')

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
	'menu_icon' => 'dashicons-forms',
    'supports' => array( 'title',/* 'custom-fields' 'editor' , 'thumbnail', 'excerpt', 'custom-fields'   'custom-fields' 'custom-fields'  'editor', 'thumbnail', 'custom-fields'  'author', , 'custom-fields', 'editor'  */)
  ); 
  register_post_type('actions_query', $args);
  // Add new taxonomy, NOT hierarchical (like tags)
	 
  
  
}
add_action( 'init', 'wks_add_post_type', 1 );
?>