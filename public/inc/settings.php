<?php

namespace GridElementTrash;

/**
 * Class Settings
 * @package GridElementTrash
 */
class Settings {
	
	const PAGE = "settings-grid-element-trash";
	
	/**
	 * Settings constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct($plugin) {
		$this->plugin = $plugin;
		
		add_action( 'admin_menu', array( $this, 'menu_page' ), 15 );
		add_action( 'wp_ajax_grid_element_trash_change_option', array( $this, 'change_option') );
		
	}
	
	/**
	 * Register the octavius menu page
	 */
	public function menu_page()
	{
		add_submenu_page(
			'grid_settings',
			'Trash ‹ Grid',
			'Trash',
			'manage_options',
			self::PAGE,
			array($this, "render_settings")
		);
	}
	
	/**
	 * renders the settings page
	 */
	public function render_settings(){
		
		if(class_exists('\grid_grid')){
			
			/**
			 * style for settingspage
			 */
			wp_enqueue_style(
				'style-settings-page',
				$this->plugin->url.'/css/settings-page.css'
			);
			
			/**
			 * add js for settingspage
			 */
			wp_enqueue_script(
				'script-settings-page',
				$this->plugin->url.'/js/settings-page.js' ,
				array( 'jquery' )
			);
			
			/**
			 * get the grid storage
			 */
			$storage = grid_wp_get_storage();
			$containers = $storage->fetchContainerTypes();
			$meta_boxes = grid_plugin()->gridAPI->getMetaTypes();
			$reuseContainerIds = $storage->getReuseContainerIds();
			$reuseContainers=array();
			foreach($reuseContainerIds as $id)
			{
				$reuseContainers[]=$storage->loadReuseContainer($id);
			}

			$trash = new Store();
			
			require $this->plugin->path."/partials/settings-page-display.php";
		} else {
			print "<p>You have to install and activate Grid. https://wordpress.org/plugins/grid/</p>";
		}
		
	}
	
	/**
	 * change option ajax endpoint
	 */
	public function change_option(){
		
		$element = sanitize_text_field($_GET["element"]);
		$type = sanitize_text_field( $_GET["type"] );
		$disabled = intval( $_GET["value"] );
		
		$trash = new Store();
		
		$return = (object) array(
			"error" => false,
			"error_msg" => "",
			"element" => $element,
			"type" => $type,
			"value" => $disabled,
		);
		if($element == "box"){
			$trash->set_box($type, $disabled);
		} else if($element == "container"){
			$trash->set_container($type, $disabled);
		} else if($element == "reuse-container"){
			$trash->set_reuse_container($type, $disabled);
		} else {
			$return->error = true;
			$return->error_msg = "Could not find matching element";
		}
		echo json_encode($return);
		exit;
	}
	
}