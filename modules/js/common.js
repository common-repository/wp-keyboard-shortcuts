jQuery(document).ready(function($){
	$('.show_info_block').click(function(){
		$('.shortcode_helper .info_data').slideToggle();
		 
	})
})
function stripSlashes(str)
	{
		return str.replace(/\\/g, '');
	}

function get_conbination_from_obj(e){
	var special_keys = {
				'esc':27,
				'escape':27,
				'tab':9,
				'space':32,
				'return':13,
				'enter':13,
				'backspace':8,
	
				'scrolllock':145,
				'scroll_lock':145,
				'scroll':145,
				'capslock':20,
				'caps_lock':20,
				'caps':20,
				'numlock':144,
				'num_lock':144,
				'num':144,
				
				'pause':19,
				'break':19,
				
				'insert':45,
				'home':36,
				'delete':46,
				'end':35,
				
				'pageup':33,
				'page_up':33,
				'pu':33,
	
				'pagedown':34,
				'page_down':34,
				'pd':34,
	
				'left':37,
				'up':38,
				'right':39,
				'down':40,
	
				'f1':112,
				'f2':113,
				'f3':114,
				'f4':115,
				'f5':116,
				'f6':117,
				'f7':118,
				'f8':119,
				'f9':120,
				'f10':121,
				'f11':122,
				'f12':123,
				
				'shift':16,
				'control':17,
				'alt':18,
				
		}
		if (e.keyCode){
			button_code = e.keyCode;
		}else if ( e.which ){
			button_code = e.which;
		} 
	
		var is_spec_char = 0;
		var custom_fn = '';
		
		jQuery.each(special_keys, function( index, value ){			 
			if( value == button_code ){
				custom_fn = index;
				is_spec_char = 1;
			}
		})
	
	 
	
		if( is_spec_char == 0 ){
			var character = String.fromCharCode(button_code).toUpperCase();
		}else{
			var character = custom_fn.toUpperCase();
		}
		
		
	
		var reportStr   =	
			( e.ctrlKey  ? "Control " : "" ) +
			( e.shiftKey ? "Shift "   : "" ) +
			( e.altKey   ? "Alt "     : "" ) +
			( e.metaKey  ? "Meta "    : "" ) +
			character	;
			
		return reportStr;
}