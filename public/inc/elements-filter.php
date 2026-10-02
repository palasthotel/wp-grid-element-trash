<?php

namespace GridElementTrash;


class ElementsFilter {
	
	
	/**
	 * ElementsFilter constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;
		
		// grid hook
		add_filter( 'grid_boxes_search', array( $this, 'boxes_filter' ) );
		add_filter( 'grid_metaboxes', array( $this, 'boxes_filter' ) );
		add_filter( 'grid_reuse_box_ids', array($this, 'reuse_box_ids_filter'));
		add_filter( 'grid_containers', array( $this, 'containers_filter' ) );
		add_filter( 'grid_reusable_containers', array($this, 'reusable_containers_filter'));
		add_filter( 'grid_reuse_container_ids', array($this, 'reuse_container_ids_filter'));
	}
	
	/**
	 * filter for boxes
	 */
	public function boxes_filter( $boxes ) {
		
		$trash = new Store();
		for ( $i = 0; $i < count( $boxes ); $i ++ ) {
			$trashId = $boxes[ $i ]["type"];
			if ( $boxes[ $i ]["type"] == "reference" && ! empty( $boxes[ $i ]["content"] ) ) {
				$trashId = "reference-" . $boxes[ $i ]["content"]->boxid;
			}
			if ( $trash->is_box_trashed( $trashId ) ) {
				array_splice( $boxes, $i, 1 );
				$i --;
			}
		}
		
		return $boxes;
	}

	public function reuse_box_ids_filter($boxIds){
		$trash = new Store();
		for ( $i = 0; $i < count( $boxIds ); $i ++ ) {
			$trashId = "reference-" .$boxIds[$i];
			if ( $trash->is_box_trashed( $trashId ) ) {
				array_splice( $boxIds, $i, 1 );
				$i --;
			}
		}
		return $boxIds;
	}
	
	/**
	 * filter for containers
	 */
	public function containers_filter( $containers ) {
		$trash = new Store();
		for ( $i = 0; $i < count( $containers ); $i ++ ) {
			if ( $trash->is_container_trashed( $containers[ $i ]["type"] ) ) {
				array_splice( $containers, $i, 1 );
				$i --;
			}
		}
		
		return $containers;
	}

	public function reusable_containers_filter($containers){
		$trash = new Store();
		for ( $i = 0; $i < count( $containers ); $i ++ ) {
			if ( $trash->is_reuse_container_trashed( $containers[ $i ]["id"]) ) {
				array_splice( $containers, $i, 1 );
				$i --;
			}
		}
		return $containers;
	}

	public function reuse_container_ids_filter($ids){
		$trash = new Store();
		for ( $i = 0; $i < count( $ids ); $i ++ ) {
			if ( $trash->is_reuse_container_trashed( $ids[$i]) ) {
				array_splice( $ids, $i, 1 );
				$i --;
			}
		}
		return $ids;
	}
	
}