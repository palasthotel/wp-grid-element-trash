<?php

namespace GridElementTrash;

/**
 * Class Settings
 * @package GridElementTrash
 */
class Settings {
	
	const PAGE = "settings-grid-element-trash";

	const AJAX_ACTION = "grid_element_trash_change_option";

	/**
	 * @var Plugin
	 */
	public $plugin;
	
	/**
	 * Settings constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct($plugin) {
		$this->plugin = $plugin;
		
		add_action( 'admin_menu', array( $this, 'menu_page' ), 15 );
		add_action( 'wp_ajax_' . self::AJAX_ACTION, array( $this, 'change_option') );
		
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
			
			wp_enqueue_script(
				'grid-element-trash-settings-page',
				$this->plugin->url . 'js/settings-page.js',
				array(),
				filemtime( $this->plugin->path . 'js/settings-page.js' ),
				true
			);
			wp_localize_script(
				'grid-element-trash-settings-page',
				'GridElementTrash',
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'action'  => self::AJAX_ACTION,
					'nonce'   => wp_create_nonce( self::AJAX_ACTION ),
				)
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

		check_ajax_referer( self::AJAX_ACTION );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(
				(object) array(
					"error"     => true,
					"error_msg" => "You are not allowed to change the Grid trash.",
				),
				403
			);
		}

		$element  = isset( $_POST["element"] ) ? sanitize_text_field( wp_unslash( $_POST["element"] ) ) : "";
		$type     = isset( $_POST["type"] ) ? sanitize_text_field( wp_unslash( $_POST["type"] ) ) : "";
		$disabled = isset( $_POST["value"] ) ? intval( $_POST["value"] ) : 0;

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
		wp_send_json( $return );
	}
	
}