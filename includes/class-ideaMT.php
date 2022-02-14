<?php
/**
 * The core plugin class.
 */
class ideaMT_core {


	/**
	 *
	 */
	public function __construct() {
		$this->version = IMTP_VERSION;
		$this->plugin_name = 'ideaMT';

		 $this->load_dependencies();
		// $this->set_locale();
		// $this->define_admin_hooks();
		// $this->define_public_hooks();
		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Ideas/ideas.php';

	}
    function run_ideaMT_ideas_class() {

        $plugin = new ideaMT_ideas_class();
        $plugin->run_ideas();
    
    }
    

    function run(){
      
    }

}
