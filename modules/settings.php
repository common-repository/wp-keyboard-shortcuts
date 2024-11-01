<?php 
// process settings save process
add_Action('init', 'wks_init');
function wks_init(){
	global $current_user; 
		
	if( isset($_POST['wks_editor_field']) ){
		if( wp_verify_nonce( $_POST['wks_editor_field'], 'wks_editor_action' )  ){

				$options = array();
			 
				foreach( $_POST as $key=>$value ){
					if( is_array( $value  ) ){
						$value = array_map( 'sanitize_text_field', $value  );
					}else{
						$value = sanitize_text_field( $value  );
					}
					$options[$key] =    $value  ;
				}
			
				update_user_meta($current_user->ID, 'wks_editor', $options );
			   wp_redirect( get_option('home').'/wp-admin/admin.php?page=wks_editor&status=save' , 301 );
		}
	}
	
	if( isset($_POST['wks_settings_field']) ){
		if( wp_verify_nonce( $_POST['wks_settings_field'], 'wks_settings_action' )  ){
			
				$wks_settings = array();
				foreach( $_POST as $key=>$value ){
					$wks_settings[$key] = sanitize_text_field( $value );
				}
				update_user_meta($current_user->ID, 'wks_settings', $wks_settings );
				wp_redirect( get_option('home').'/wp-admin/admin.php?page=wks_settings&status=save', 301 );
		}
	}
	
}


//addmin menues 
add_action('admin_menu', 'wks_item_menu');

function wks_item_menu() {
	add_menu_page(   __('Shortcuts', 'sc'), __('Shortcuts', 'sc'), 'edit_published_posts', 'wks_editor', 'wks_editor', 'dashicons-forms');
	add_submenu_page( 'wks_editor',  __('Add Query', 'sc'), __('Add Query', 'sc'), 'edit_published_posts', 'post-new.php?post_type=actions_query', 'post-new.php?post_type=actions_query');	
	add_submenu_page( 'wks_editor',  __('Shortcuts', 'sc'), __('Shortcuts', 'sc'), 'edit_published_posts', 'wks_editor', 'wks_editor');	
	add_submenu_page( 'wks_editor',  __('Settings', 'sc'), __('Settings', 'sc'), 'edit_published_posts', 'wks_settings', 'wks_settings');	

	add_submenu_page( 'edit.php?post_type=actions_query',  __('Shortcuts', 'sc'), __('Shortcuts', 'sc'), 'edit_published_posts', 'wks_editor', 'wks_editor');	
	add_submenu_page( 'edit.php?post_type=actions_query',  __('Settings', 'sc'), __('Settings', 'sc'), 'edit_published_posts', 'wks_settings', 'wks_settings');	
}
 
// main editor
function wks_editor(){
	global $custom_actions;
	global $current_user;
?>
<div class="wrap tw-bs">
<h2><?php _e('Shortcuts Setup', 'wcc'); ?></h2>
<hr/>
	<?php if( isset($_GET['status']) ){
		if( $_GET['status'] == 'save' ){
		?>
		<div id="message" class="updated" >
			<p><?php _e('Combinations saved successfully', 'sc'); ?></p>
		</div>    
		<?php
		}
	} ?>
<form class="form-horizontal" method="post" action="" id="submit_shortcodes" enctype="multipart/form-data" >
<?php 
wp_nonce_field( 'wks_editor_action', 'wks_editor_field'  );  

$config = get_user_meta( $current_user->ID, 'wks_editor', true); 
 

$menu_array = wks_return_manu_array();
 //var_dump( $menu_array );
?>  
<fieldset>
	 
	<table class="table editor_table">  
        <thead> 
			<tr>  
				<th> </th>  
				<th> </th>  
				<th> </th>  
				<th> </th>  
				<th> </th>  
				<th> </th>  
				<th><input type="button" class="btn btn-success add_row" value="Add Row" /></th>  
			</tr>
		
          <tr>  
            <th>Combination <span class="helper_block">
			<span class="dashicons dashicons-editor-help info_cont" title="Please, focus this input and press combination you want to use. "></span></span></th>  
            <th>&nbsp;</th> 
			<th>Action Type <span class="helper_block">
				<span class="dashicons dashicons-editor-help info_cont" title="We have three types of actions: <br/>Menu Action - this option will open menu on shortcut click. <br/>Custom Action - on click script will perform predefined action like publish post etc. <br/>Click Emulation - its action for more experienced users. It will emulate click on picked element. So you can bind shortcut to any element you will like."></span>			
			</span></th>  
            <th>Details <span class="helper_block">

				<span class="dashicons dashicons-editor-help info_cont" title="Here you can define action details for each type of action. Like select menu item or predefined action, or CSS selector."></span>
				
			</span></th>  
            <th>Name <span class="helper_block">

				<span class="dashicons dashicons-editor-help info_cont" title="Here you can add Name for shortcut. It will be used in helper and Admin Bar block."></span>
				
			</span></th>
            <th>Admin Bar <span class="helper_block">
				
				<span class="dashicons dashicons-editor-help info_cont" title="Here you can check if you want to show shortcut in admin bar."></span>
				
			</span></th>
            <th>Actions <span class="helper_block">

				<span class="dashicons dashicons-editor-help info_cont" title="Here you can clone row or remove it."></span>
				
			</span></th>  
          </tr>  
		  
		 
		  
        </thead>  
        <tbody class="editor_content">  
		
		<?php if( is_array($config['combination'])&& count( $config['combination'] ) > 0 ): ?>
		
		<?php 

			for( $i=0; $i < count($config['combination'] ); $i++ ){
				?>
				
				 <tr>  
					<td><input name="combination[]" id="keyPrssInp" class="shortcut_input_field input-medium" type="text" value="<?php echo $config['combination'][$i];  ?>" /></td>  
					<td><input class="enter_combination btn btn-warning" type="button"  value="Enter Combination" /></td>  
					<td>
						<select name="action[]" class="action_picker input-medium">
							<option  <?php if( $config['action'][$i] == 'menu_action' ){ echo ' selected ';} ?> value="menu_action">Menu Action
							<option <?php if( $config['action'][$i] == 'custom_action' ){ echo ' selected ';} ?> value="custom_action">Custom Action
							<option <?php if( $config['action'][$i] == 'click_emulation' ){ echo ' selected ';} ?> value="click_emulation">Click Emulation
							<option <?php if( $config['action'][$i] == 'visit_url' ){ echo ' selected ';} ?> value="visit_url">Visit URL
							<option <?php if( $config['action'][$i] == 'actions_query' ){ echo ' selected ';} ?> value="actions_query">Actions Query
						</select>
																	
						
					</td>  
					<td class="pickers_block">
						<?php 
					 
						if( $config['action'][$i] == 'menu_action' ){ 
							$out_style = ' style="display:block;" '; 
						}else{
							$out_style = ' style="display:none;" '; 
						} 
						?>
						<select name="menu_element[]" class="menu_action second_stage_picker  input-medium" <?php echo $out_style; ?> >
							<option value="">Select Menu
						<?php 
							foreach( $menu_array as $single_menu ){
							 								
								echo '<option '.( $single_menu['prefix'] == '' ? ' style="background:#ccc;" ' : '' ).' '.( $config['menu_element'][$i] == get_option('home').'/wp-admin/'.$single_menu['url'] ? ' selected ' : '' ).' value="' ;
								echo get_option('home').'/wp-admin/'.$single_menu['url'];
								echo '">'.$single_menu['prefix'].$single_menu['name'];
							}
						?>
						</select>
						
						<?php 
						if( $config['action'][$i] == 'custom_action' ){ 
							$out_style = ' style="display:block;" '; 
						}else{
							$out_style = ' style="display:none;" '; 
						} 
						?>
						<select name="custom_action[]" class=" input-medium custom_action second_stage_picker" <?php echo $out_style; ?> >
							<option value="">Select Action
							
							<?php 
							foreach( $custom_actions as $key => $value ){
								echo '<option value="'.$value.'" '.( $config['custom_action'][$i] == $value  ? ' selected ' : '').' >'.$key;
							}
							?>

						</select>
						
						<?php 
						if( $config['action'][$i] == 'click_emulation' ){ 
							$out_style = ' style="display:block;" '; 
						}else{
							$out_style = ' style="display:none;" '; 
						} 
						?>
						<input name="click_emulation_selector[]"  class="click_emulation second_stage_picker input-medium" <?php echo $out_style; ?> value="<?php echo htmlentities( stripslashes($config['click_emulation_selector'][$i] ) ); ?>" />
						
						
						<?php 
						if( $config['action'][$i] == 'visit_url' ){ 
							$out_style = ' style="display:block;" '; 
						}else{
							$out_style = ' style="display:none;" '; 
						} 
						?>
						<input name="visit_url_link[]"  class="visit_url second_stage_picker input-medium" <?php echo $out_style; ?> value="<?php echo htmlentities( stripslashes($config['visit_url_link'][$i] ) ); ?>" />
						
						
						
						<?php 
						if( $config['action'][$i] == 'actions_query' ){ 
							$out_style = ' style="display:block;" '; 
						}else{
							$out_style = ' style="display:none;" '; 
						} 
						?>
						<select name="actions_query[]" class=" input-medium actions_query second_stage_picker" <?php echo $out_style; ?> >
							<option value="">Select Query
							
							<?php 
							
							$args = array(
								'showposts' => -1,
								'post_type' => 'actions_query',
								'orderby' => 'title',
								'order' => 'ASC'
							);
							$all_queries = get_posts( $args );
							
							if( count($all_queries) > 0 ){
								foreach( $all_queries as $single_query ){
									echo '<option value="'.$single_query->ID.'" '.( $config['actions_query'][$i] == $single_query->ID  ? ' selected ' : '').' >'.$single_query->post_title;
								}
							}
							
							?>

						</select>
						
					</td>  
			
					<td>
						<input name="small_descr[]"   class="combination_descr  input-medium" type="text" value="<?php echo htmlentities( stripslashes( $config['small_descr'][$i] ) );  ?>" />
					</td> 
			
					<td>
						<input type="checkbox" class="admin_bar_checkbox" name="admin_bar" value="on" 
						<?php 
						if( isset($config['admin_bar'][$i]) ){
							if( $config['admin_bar'][$i] == "on" ){ 
								echo ' checked '; 
							}
						}
						?>  
						/>
					</td>
			
					<td>
						<button type="button" class="btn btn-success clone_row" ><span class="dashicons dashicons-screenoptions" title="<?php _e('Clone Element', 'wks') ?>"></span></button>
						<button type="button" class="btn btn-danger delete_row" ><span class="dashicons dashicons-trash" title="<?php _e('Delete Element', 'wks') ?>"></span></button>
						
					</td>  
				  </tr> 
				
			<?php
			}
		?>
		
		<?php else: ?>
			 <tr>  
					<td><input name="combination[]" id="keyPrssInp" class="shortcut_input_field input-medium" type="text" value="<?php echo $config['combination'][$i];  ?>" /></td>  
					<td><input class="enter_combination btn btn-warning" type="button"  value="Enter Combination" /></td>  
					<td>
						<select name="action[]" class="action_picker input-medium">
							<option  <?php if( $config['action'][$i] == 'menu_action' ){ echo ' selected ';} ?> value="menu_action">Menu Action
							<option <?php if( $config['action'][$i] == 'custom_action' ){ echo ' selected ';} ?> value="custom_action">Custom Action
							<option <?php if( $config['action'][$i] == 'click_emulation' ){ echo ' selected ';} ?> value="click_emulation">Click Emulation
						</select>
	
					</td>  
					<td class="pickers_block">
		
						<select name="menu_element[]" class="menu_action second_stage_picker"  >
							<option value="">Select Menu
						<?php 
							foreach( $menu_array as $single_menu ){
								 
								echo '<option '.( $single_menu['prefix'] == '' ? ' style="background:#ccc;" ' : '' ).' '.( $config['menu_element'][$i] == get_option('home').'/wp-admin/'.$single_menu['url'] ? ' selected ' : '' ).' value="'.get_option('home').'/wp-admin/'.$single_menu['url'].'">'.$single_menu['prefix'].$single_menu['name'];
							}
						?>
						</select>

						<select name="custom_action[]" class="custom_action second_stage_picker  input-medium" >
							<option value="">Select Action
							
							<?php 
							foreach( $custom_actions as $key => $value ){
								echo '<option value="'.$value.'" '.( $config['custom_action'][$i] == $value  ? ' selected ' : '').' >'.$key;
							}
							?>

						</select>

						<input name="click_emulation_selector[]"  class="click_emulation second_stage_picker  input-medium"  value="<?php echo $config['click_emulation_selector'][$i]; ?>" />
					</td>  
					
					<td>
						<input name="small_descr[]"   class=" input-medium" type="text" value="" />
					</td>
					
					<td>
						<input type="checkbox" class="admin_bar_checkbox" name="admin_bar" value="on"   />
					</td>
					
					<td>
						<button type="button" class="btn btn-success clone_row" ><span class="dashicons dashicons-screenoptions" title="<?php _e('Clone Element', 'wks') ?>"></span></button>
						<button type="button" class="btn btn-danger delete_row" ><span class="dashicons dashicons-trash" title="<?php _e('Delete Element', 'wks') ?>"></span></button>
						
					</td>  
				  </tr>
		
		<?php endif;?>
		
		
           
 
        </tbody>  
      </table>  	
	 
	  
	 
		
		
          <div class="form-actions">  
            <button type="submit" class="btn btn-primary">Save Settings</button>  

          </div>  
        </fieldset>   

</form>

</div>


<?php 
}

// settings page
function wks_settings(){
	global $current_user;
	
	$config_big = array(
		array(
			'name' => 'show_helper',
			'type' => 'checkbox',
			'title' => 'Show Helper Block',
			'text' => 'Turn on if you would like to show helper icon top right, that will list you all your combinations',
			'sub_text' => '',
			'style' => ''
		),
		array(
			'name' => 'show_menu_help',
			'type' => 'checkbox',
			'title' => 'Show Combinations In Menu',
			'text' => 'Turn on if you would like to show combinations text in admin menu',
			'sub_text' => '',
			'style' => ''
		),
		array(
			'name' => 'trace_input',
			'type' => 'checkbox',
			'title' => 'Trace shortcuts on input fields',
			'text' => 'Turn on if you would like to trace shortcuts while input in input field, or textarea.',
			'sub_text' => '',
			'style' => ''
		),
		array(
			'name' => 'show_in_admin_bar',
			'type' => 'checkbox',
			'title' => 'Show Shortcuts in Admin Bar',
			'text' => 'Turn on if you would like to show menu with shortcuts in your Wordpress admin bar.',
			'sub_text' => '',
			'style' => ''
		),
	
		
	);

?>
<div class="wrap tw-bs">
<h2><?php _e('Settings', 'sc'); ?></h2>
<hr/>
	<?php if( isset($_GET['status']) ){
		if( $_GET['status'] == 'save' ){
		?>
		<div id="message" class="updated" >
			<p><?php _e('Settings saved successfully', 'sc'); ?></p>
		</div>    
		<?php
		}
	} ?>
		
	
<form class="form-horizontal" method="post" action="">
<?php 

wp_nonce_field( 'wks_settings_action', 'wks_settings_field'  ); 
  
$config = get_user_meta( $current_user->ID, 'wks_settings', true); 
 
?>  
<fieldset>

	<?php 
	foreach( $config_big as $key=>$value ){
		switch( $value['type'] ){
			case "text":
				$out .= '
				<div class="control-group">  
					<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
					<div class="controls">  
					  <input type="text"  class="'.$value['class'].'"  name="'.$value['name'].'" id="'.$value['id'].'" placeholder="'.$value['placeholder'].'" value="'.esc_html( stripslashes( $config[$value['name']] ) ).'">  
					  <p class="help-block">'.$value['sub_text'].'</p>  
					</div>  
				  </div> 
				';
			break;
			case "select":
				$out .= '
				<div class="control-group">  
					<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
					<div class="controls">  
					  <select  style="'.$value['style'].'" class="'.$value['class'].'" name="'.$value['name'].'" id="'.$value['id'].'">' ; 
					  foreach( $value['value'] as $k => $v ){
						  $out .= '<option value="'.$k.'" '.( $config[$value['name']]  == $k ? ' selected ' : ' ' ).' >'.$v.'</option> ';
					  }
				$out .= '		
					  </select>  
					  <p class="help-block">'.$value['sub_text'].'</p> 
					</div>  
				  </div>  
				';
			break;
			case "checkbox":
				$out .= '
				<div class="control-group">  
					<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
					<div class="controls">  
					  <label class="checkbox">  
						<input  class="'.$value['class'].'" type="checkbox" name="'.$value['name'].'" id="'.$value['id'].'" value="on" '.( $config[$value['name']] == 'on' ? ' checked ' : '' ).' > &nbsp; 
						'.$value['text'].'  
						<p class="help-block">'.$value['sub_text'].'</p> 
					  </label>  
					</div>  
				  </div>  
				';
			break;
			case "radio":
				$out .= '
				<div class="control-group">  
					<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
					<div class="controls">';
						foreach( $value['value'] as $k => $v ){
							$out .= '
							<label class="radio">  
								<input  class="'.$value['class'].'" type="radio" name="'.$value['name'].'" id="'.$value['id'].'" value="'.$k.'" '.( $config[$value['name']] == $k ? ' checked ' : '' ).' >&nbsp;  
								'.$v.'  
								<p class="help-block">'.$value['sub_text'].'</p> 
							  </label> ';
						}
					$out .= '
					   
					</div>  
				  </div>  
				';
			break;
			case "textarea":
				$out .= '
				<div class="control-group">  
					<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
					<div class="controls">  
					  <textarea style="'.$value['style'].'" class="'.$value['class'].'" name="'.$value['name'].'" id="'.$value['id'].'" rows="'.$value['rows'].'">'.esc_html( stripslashes( $config[$value['name']] ) ).'</textarea>  
					  <p class="help-block">'.$value['sub_text'].'</p> 
					</div>  
				  </div> 
				';
			break;
			case "multiselect":
				$out .= '
				<div class="control-group">  
					<label class="control-label" for="'.$value['id'].'">'.$value['title'].'</label>  
					<div class="controls">  
					  <select  multiple="multiple" style="'.$value['style'].'" class="'.$value['class'].'" name="'.$value['name'].'[]" id="'.$value['id'].'">' ; 
					  foreach( $value['value'] as $k => $v ){
						  $out .= '<option value="'.$k.'" '.( @in_array( $k, $config[$value['name']] )   ? ' selected ' : ' ' ).' >'.$v.'</option> ';
					  }
				$out .= '		
					  </select>  
					  <p class="help-block">'.$value['sub_text'].'</p> 
					</div>  
				  </div>  
				';
			break;
		}
	}
	echo $out;
	?>

		
          <div class="form-actions">  
            <button type="submit" class="btn btn-primary">Save Settings</button>  
          </div>  
        </fieldset>  

</form>

</div>


<?php 
}




?>