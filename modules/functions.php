<?php 

// get helprer menu name from action url
function wks_get_menu_name( $url2trace ){
	$full_menu = wks_return_manu_array( );
	foreach( $full_menu as $single_menu ){		
		if( $url2trace == get_option('home').'/wp-admin/'.$single_menu['url'] ){
			$out_name = $single_menu['parent_name'].' - '.$single_menu['name'];
		}
	}
	return $out_name;
}

// get right menu data as array
function wks_return_manu_array( ){
	global $submenu;
	$big_url = array();

 
	foreach( $GLOBALS['menu'] as $key => $value ){		
		$file_path = $value[2];
		if( $value[0] == '' ) continue;	
		
		$big_url[] = array( 'prefix' => '', 'parent_name' => '', 'name' =>wks_strip_tags_content( $value[0] ), 'url' => $value[2]  );
		$parent_name = wks_strip_tags_content( $value[0] );
	
		if( isset( $submenu[$file_path] ) ){
			if( count($submenu[$file_path]) > 0 ) {
				foreach( $submenu[$file_path] as $single_menu ){
					//var_dump( $single_menu );
					if( @substr_count( $single_menu[2], '.php' ) == 0 ){
						$single_menu[2] = 'admin.php?page='.$single_menu[2];
					}
					
					$big_url[] = array( 'prefix' => '--', 'parent_name' => $parent_name, 'name' =>wks_strip_tags_content(  $single_menu[0] ), 'url' => $single_menu[2]  );
				}
			}
		}
	}
	//var_Dump( $big_url );
	return $big_url;
}

// remove tags with content from line
function wks_strip_tags_content($text, $tags = '', $invert = FALSE) { 

  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags); 
  $tags = array_unique($tags[1]); 
    
  if(is_array($tags) AND count($tags) > 0) { 
    if($invert == FALSE) { 
      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text); 
    } 
    else { 
      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text); 
    } 
  } 
  elseif($invert == FALSE) { 
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text); 
  } 
  return $text; 
} 
?>