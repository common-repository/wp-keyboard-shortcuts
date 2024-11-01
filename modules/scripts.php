<?php 

class voo_add_styles_and_scripts{
	
	protected $plugin_prefix;
	protected $plugin_version;
	
	public  function __construct( $data ){
		
		$this->plugin_prefix = $data;
		$this->plugin_version = '1.0';
		
		add_action('wp_print_scripts', array( $this, 'add_script_fn') );
	}
	public function add_script_fn(){
		wp_enqueue_style( $this->plugin_prefix.'bootsrap_css', plugins_url('/inc/assets/css/boot-cont.css', __FILE__ ) ) ;	
		wp_enqueue_script( $this->plugin_prefix.'md5.min.js', plugins_url('/js/md5.min.js', __FILE__ ), array('jquery'  ), $this->plugin_version ) ;		
		wp_enqueue_script(	$this->plugin_prefix.'common_js', plugins_url('/js/common.js', __FILE__ ), array('jquery'  ), $this->plugin_version ) ;
		wp_enqueue_style( $this->plugin_prefix.'common_css', plugins_url('/css/common.css', __FILE__ ) ) ;	
		
		
		if(is_admin()){	
			wp_enqueue_media();
			
			wp_enqueue_script( $this->plugin_prefix.'jqujquery.qtip.js', plugins_url('/inc/qtip/jquery.qtip.js', __FILE__ ), array('jquery'), '1.0' ) ;
			wp_enqueue_style( $this->plugin_prefix.'jqujquery.qtip.css', plugins_url('/inc/qtip/jquery.qtip.css', __FILE__ ) ) ;
			
			wp_enqueue_script( $this->plugin_prefix.'admin_js', plugins_url('/js/admin.js', __FILE__ ), array('jquery'  ), $this->plugin_version ) ;
			wp_enqueue_style( $this->plugin_prefix.'admin_css', plugins_url('/css/admin.css', __FILE__ ) ) ;	
		  }else{
			wp_enqueue_script( $this->plugin_prefix.'front_js', plugins_url('/js/front.js', __FILE__ ), array( 'jquery' ), $this->plugin_version ) ;
			wp_enqueue_style( $this->plugin_prefix.'front_css', plugins_url('/css/front.css', __FILE__ ) ) ;			
		  }
	}
}

$insert_script = new voo_add_styles_and_scripts('wks1');

?>