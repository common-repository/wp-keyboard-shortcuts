<?php 
// adding main actions in footer to use front back
add_Action('admin_head', 'wks_wp_head');
add_Action('wp_head', 'wks_wp_head');
function wks_wp_head(){
	global $custom_actions;
	global $current_user;
	
	
	// processing of codes
	$wks_editor = get_user_meta($current_user->ID, 'wks_editor', true); 
	$wks_settings =  get_user_meta($current_user->ID, 'wks_settings', true); 
	

	
	// main js processing
	echo '
	<script>
	jQuery(document).ready(function($){
		';
		
		//settings to show menu helpers
		if( $wks_settings['show_menu_help'] == 'on' ){
		echo '
		// adding subtext to menu
			var all_actions = $("#actions_list").val();
			var obj = jQuery.parseJSON(all_actions);
					
			$.each(obj, function(key,value) {
				if( value.action == "menu_action" ){
	
					var menu_href = value.small_url;
					var combination = value.combination;
					$("#adminmenu .wp-submenu a").each(function(){
	
						if( $(this).attr("href") == menu_href ){
							$(this).parents("li").css("position", "relative");
							$(this).after("<span class=\"link_conbination_helper\">"+combination+"</span>");
						}
					})
							
				}	
			});	';
		}
		
		echo '			
		// keypress tracing
		$("body").on("keydown", function( e ){	

			// filter settings pages
	 
			var cur_element = $( document.activeElement );
			
			if( cur_element.hasClass("combination_descr") || cur_element.hasClass("click_emulation") ){				
			}else{				
				proces_button_click(e);			
			}
			
		
			
		})
		
		// main processing function
		function proces_button_click(e){
			console.log( "clicked" );
			
			// check if input inside input field
			if( $( document.activeElement ).filter("input,textarea").length > 0) {			
				console.log( "in focus" );';
				// trace input check
				if( $wks_settings['trace_input'] != 'on' ){
					echo ' return false;';
				}
				echo '
			}else{
				console.log( "not focused" );
			}

			var reportStr =  get_conbination_from_obj(e);
			console.log( reportStr );
			var combination_hash =  md5 ( reportStr ) ;
			console.log( combination_hash );
			var all_actions = $("#actions_list").val();
			var obj = jQuery.parseJSON(all_actions);
					
			// check all combinations to find correct one
			$.each(obj, function(key,value) {
				
				//console.log( value.hash );
				
				
					if( combination_hash == value.hash){

						if( value.action == "menu_action" ){	
							var this_action = value.menu_element;
							window.location.href = this_action;
							
						}
						if( value.action == "custom_action" ){							
							var target = value.custom_action;	
							$(target).click();
						}
						if( value.action == "click_emulation" ){
							var target = value.click_emulation_selector;
							target = stripSlashes( target ); 
							console.log( target );
							
							$(target).click();
							console.log( "click emulated" );
						}
						if( value.action == "visit_url" ){	
							var this_action = value.visit_url;
							window.location.href = this_action;
							
						}
						 
						if( value.action == "actions_query" ){	
							var all_actions = value.actions_query ;
							$.each( all_actions, function( index, value){
								console.log( value );
								if( value.action == "click" ){
									$(value.selector).click();
								}
								if( value.action == "set_value" ){
									$(value.selector).val( value.value );
								}
							})
							
						}
							//console.log("prevent");				
						e.preventDefault ? e.preventDefault() : (e.returnValue = false);
						e.stopPropagation ();
						e.preventDefault ()
					}
				  
				});	
		}
		';
		
		// trace input in textareas and text fields
		if( $wks_settings['trace_input'] == 'on'   ){
		echo '
		// init checking of tinymce
		setTimeout(function(){			
			if(  typeof(tinymce) != "undefined" ){
				if( tinymce.editors.length > 0 ){
					var b = tinymce.editors[0].on("keydown", function(e){
						proces_button_click(e);				
					});
				}
			}			
		}, 300);
		
		// on tab change tiny check
		$("#content-tmce").click(function(){
			setTimeout(function(){
				if(  typeof(tinymce) != "undefined" ){
					if( tinymce.editors.length > 0 ){
						var b = tinymce.editors[0].on("keydown", function(e){
							proces_button_click(e);				
						});
					}
				}
				
			}, 300);
		})';
		}
		echo '
	
	})
	</script>
	';
	
}



// adding main actions in footer to use front back
add_Action('admin_footer', 'wks_wp_footer');
add_Action('wp_footer', 'wks_wp_footer');
function wks_wp_footer(){
	global $custom_actions;
	global $current_user;
	
	
	// processing of codes
	$wks_editor = get_user_meta($current_user->ID, 'wks_editor', true); 
	$wks_settings =  get_user_meta($current_user->ID, 'wks_settings', true); 
	 
	// helper row data container
	$help_row = array();
	
	// top menu functions generation
	$out_functions_generation = array();
	
	for( $i=0; $i < count($wks_editor['combination'] ); $i++ ){
 
		if( @substr_count( '.php', $wks_editor['menu_element'][$i] ) > 0 ){
			$menu_element = $wks_editor['menu_element'][$i];
		}else{
			$menu_element = $wks_editor['menu_element'][$i];
		}
		
		
		// actions query reprocesing
		$action_array = array();
		if( $wks_editor['actions_query'][$i] ){
			$all_actions = get_post_meta( $wks_editor['actions_query'][$i], 'query_action', true );
			
			
			
			if( count($all_actions) > 0 ){
				for( $j=0; $j<count($all_actions); $j++ ){
					$action_array[] = array( 'selector' => $all_actions['selector'][$j], 'action' =>  $all_actions['type'][$j], 'value' =>  $all_actions['value'][$j]);
				}
			}
		}
 
		// init arrach for action trace
		$comb_array[] = array( 
			'hash' => md5($wks_editor['combination'][$i]), 
			'combination' => $wks_editor['combination'][$i], 
			'small_url' => str_replace( get_option('home').'/wp-admin/', '', $wks_editor['menu_element'][$i] ), 
			'action' => $wks_editor['action'][$i], 
			'menu_element' => $wks_editor['menu_element'][$i], 
			'actions_query' => $action_array, 
			'custom_action' => $wks_editor['custom_action'][$i], 
			'visit_url' => $wks_editor['visit_url_link'][$i], 
			'click_emulation_selector' => $wks_editor['click_emulation_selector'][$i] 
		);
		
		
		
		switch( $wks_editor['action'][$i] ){
			
			//get titles and menus for helper
			case "custom_action":
				$title = "Custom Action";
				foreach( $custom_actions as $k => $v ){
					if( $v == $wks_editor['custom_action'][$i] ){
						$value = $k;
					}
				}	

				$out_functions_generation[] =  '
					function emu_fn_'.md5($wks_editor['combination'][$i]).'(){
						jQuery("'.stripslashes( str_replace( '"', "'",  $wks_editor['custom_action'][$i] ) ).'").click();
					}
				';
				
			break;
			case "click_emulation":
				$title = "Click Emulation";
				$value = $wks_editor['click_emulation_selector'][$i];
				
				$out_functions_generation[] =  '
					function emu_fn_'.md5($wks_editor['combination'][$i]).'(){
						jQuery("'.stripslashes( str_replace( '"', "'",  $wks_editor['click_emulation_selector'][$i] ) ).'").click();
					}
				';
				
			break;
			case "menu_action":
				$title = "Menu Action";			
				$value = wks_get_menu_name( $wks_editor['menu_element'][$i] );
			break;
			case "visit_url":
				$title = "Go To URL";			
				$value = $wks_editor['visit_url_link'][$i] ;
			break;
		}
		
		// helper init
		$help_row[] = '
		 <tr>  
            <td>'.$wks_editor['small_descr'][$i].'</td>  
			<td>'.$wks_editor['combination'][$i].'</td>  
            <td>'.$title.'</td>  
            <td>'.$value.'</td>  
 
          </tr>  
		';
	}
	
	//var_dump( $comb_array );
	
	
	// show helper if  seeting is on
	if( isset($wks_settings['show_helper']) ){
		if( $wks_settings['show_helper'] == 'on' ){
			// reminder functionality
			$helper_code .= '
			<div class="shortcode_helper">
				<div class="info_icon">
					<span class="dashicons dashicons-editor-help  show_info_block" aria-hidden="true"></span>
				</div>
				<div class="info_data">
					<table class="help_table">  
						<thead>  
						  <tr>  
							<th>Name</th> 
							<th>Combination</th>  
							<th>Action Type</th>  
							<th>Info</th>  
							
						  </tr>  
						</thead>  
						'.implode('', $help_row).'
						<tbody> 
						</tbody>  
					</table>  
				</div>
				<div class="clearfix"></div>
			</div>';				
			echo $helper_code;
		}
	}
 
 
 
	// generate functiuons for admin bar
	if( $wks_settings['show_in_admin_bar'] == 'on' ){
		echo '
		<script>
		'.implode("\n", $out_functions_generation ).'
		</script>';
	}
	
	
	
	// main js processing
	echo '
	<input type="hidden" id="actions_list" value="'.htmlentities( json_encode( $comb_array ) ).'" />

	';
	
}

// apply shortcuts in admin bar
add_action( 'admin_bar_menu', 'add_nodes_and_groups_to_toolbar', 999 );
function add_nodes_and_groups_to_toolbar( $wp_admin_bar ) {
	global $current_user;
	 $config =  get_user_meta($current_user->ID, 'wks_settings', true); 

	 

	// getting settings
	$wks_editor = get_user_meta($current_user->ID, 'wks_editor', true); 
	$wks_settings = get_user_meta($current_user->ID, 'wks_settings', true); 
	 
	if( $config['show_in_admin_bar'] != 'on' ){ return false; }
	
	// add a parent item
	$args = array(
		'id'    => 'shortcut_node',
		'title' => 'Shortcuts',
		
	);
	$wp_admin_bar->add_node( $args );
	
	
	for( $i=0; $i < count($wks_editor['combination'] ); $i++ ){

		// actions query reprocesing
		$action_array = array();
		if( $wks_editor['actions_query'][$i] ){
			$all_actions = get_post_meta( $wks_editor['actions_query'][$i], 'query_action', true );
			
			
			
			if( count($all_actions) > 0 ){
				for( $j=0; $j<count($all_actions); $j++ ){
					$action_array[] = array( 'selector' => $all_actions['selector'][$j], 'action' =>  $all_actions['type'][$j], 'value' =>  $all_actions['value'][$j]);
				}
			}
		}
	
	
	
		$comb_array = array( 
			'hash' => md5($wks_editor['combination'][$i]), 
			'combination' => $wks_editor['combination'][$i], 
			'small_url' => str_replace( get_option('home').'/wp-admin/', '', $wks_editor['menu_element'][$i] ), 
			'action' => ( isset( $wks_editor['action'][$i] ) ? $wks_editor['action'][$i]  : '' ), 
			'admin_bar' => ( isset( $wks_editor['admin_bar'][$i] ) ? $wks_editor['admin_bar'][$i]  : '' ), 
			'menu_element' => $wks_editor['menu_element'][$i], 
			'menu_name' => $wks_editor['small_descr'][$i], 
			'actions_query' => $action_array, 
			'visit_url' => $wks_editor['visit_url_link'][$i],
			'custom_action' => $wks_editor['custom_action'][$i], 
			'click_emulation_selector' => $wks_editor['click_emulation_selector'][$i] 
		);
		
		if( $comb_array['admin_bar'] != 'on' ){ continue; }

		switch( $wks_editor['action'][$i] ){
			case "custom_action":
				// add a child item to our parent item
				$args = array(
					'id'     => 'node_'.$comb_array['hash'],
					'title'  => $comb_array['menu_name'],
					'parent' => 'shortcut_node',
					'href' => '#',
					'meta' => array(
						'onclick' => htmlentities(  "emu_fn_".$comb_array['hash']."(); return false;" )
					)
				);
				$wp_admin_bar->add_node( $args );
				
			break;
			case "click_emulation":

				// add a child item to our parent item
				$args = array(
					'id'     => 'node_'.$comb_array['hash'],
					'title'  => $comb_array['menu_name'],
					'parent' => 'shortcut_node',
					'href' => '#',
					'meta' => array(
						'onclick' => htmlentities(  "emu_fn_".$comb_array['hash']."(); return false;" )
					)
				);
				$wp_admin_bar->add_node( $args );
				
				
			break;
			case "menu_action":
				// add a child item to our parent item
				$args = array(
					'id'     => 'node_'.$comb_array['hash'],
					'title'  => $comb_array['menu_name'],
					'parent' => 'shortcut_node',
					'href' => $comb_array['menu_element'],
				);
				$wp_admin_bar->add_node( $args );
				
			break;
			case "visit_url":
				// add a child item to our parent item
				$args = array(
					'id'     => 'node_'.$comb_array['hash'],
					'title'  => $comb_array['menu_name'],
					'parent' => 'shortcut_node',
					'href' => $comb_array['visit_url'],
				);
				$wp_admin_bar->add_node( $args );
				
			break;
		}
 
	}
 


}




?>