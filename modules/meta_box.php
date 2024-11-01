<?php 
		

add_action( 'add_meta_boxes', 'wks_add_custom_box' );
function wks_add_custom_box() {
	global $post;
	global $current_user;
		add_meta_box( 
			'wks_system_editor',
			__( 'Query Editor', 'wl' ),
			'wks_query_editor',
			'actions_query' , 'advanced', 'high'
		);

	
	
		
}
function wks_query_editor(){
	global $post;

	$out .= '
<div class="tw-bs">

	<div class="control_line pagination-right table">
		<input type="button" class="btn btn-success add_action" value="'.__( 'Add Action', 'wks' ).'" />
	</div>

	<table class="table action_query_table">
        <thead>
          <tr>
            <th>'.__('#','wks').'</th>
            <th>'.__('Selector','wks').'</th>
			<th>'.__('Action Type','wks').'</th>            
			<th>'.__('Value','wks').'</th>            
            <th>'.__('Actions','wks').'</th>
    
          </tr>
        </thead>
        <tbody class="orderable_table">';
		
		$all_actions = get_post_meta( $post->ID, 'query_action', true );
 
		if( count($all_actions) > 0 && $all_actions['selector'][0] != '' ){
		
			for( $i=0; $i <count($all_actions['selector']); $i++ ){
				$tmp = $i+1;
				$out .= '
				  <tr>
					<td class="numbering_block">
					'.$tmp.'	
					</td>
					<td>
					<input type="text" name="query_action[selector][]" value="'.$all_actions['selector'][$i].'" class="selector_value" />
					
					</td>
					<td>
					<select name="query_action[type][]" class="action_type">
						<option value="">'.__('Select Action','wks').'
						<option value="set_value" '.( $all_actions['type'][$i] == 'set_value' ? ' selected ' : '' ).' >'.__('Set Value','wks').'
						<option value="click" '.( $all_actions['type'][$i] == 'click' ? ' selected ' : '' ).' >'.__('Click','wks').'
					</select>	 
					</td>
					<td>
						<input type="text" name="query_action[value][]" value="'.$all_actions['value'][$i].'"   />
					</td>
					<td>
						<input type="button" class="btn btn-danger delete_row"  value="'.__('Remove', 'wks').'" />
					</td>
					
				  </tr>';
			}
		
			
		}else{
			$out .= '
			  <tr>
				<td class="numbering_block">
				1	
				</td>
				<td>
				<input type="text" name="query_action[selector][]" class="selector_value" />
				
				</td>
				<td>
				<select name="query_action[type][]" class="action_type">
					<option value="">'.__('Select Action','wks').'
					<option value="set_value">'.__('Set Value','wks').'
					<option value="click">'.__('Click','wks').'
				</select>	 
				</td>
				<td>
					<input type="text" name="query_action[value][]" value=""   />
				</td>
				<td>
					<input type="button" class="btn btn-danger delete_row"  value="'.__('Remove', 'wks').'" />
				</td>
				
			  </tr>';
		}
		
		$out .= '
        </tbody>
    </table>
</div>';	
	echo $out;
}


add_action( 'save_post', 'wks_save_postdata' );
function wks_save_postdata( $post_id ) {
global $current_user; 
 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }
  /// User editotions
 
	if( get_post_type($post_id) == 'actions_query' ){
		
		 
		$fin_arr = array();
		foreach( $_POST['query_action']  as $key=>$value ){
			
			foreach( $value as $single_value ){
				$fin_arr[$key][] = sanitize_text_field( $single_value  );
			}
		}
	 
 
		update_post_meta( $post_id, 'query_action',  $fin_arr  );
	}
	
}

?>