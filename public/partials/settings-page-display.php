<?php
/**
 * Settings page
 * @var grid_db $storage
 * @var Store $trash
 * @var array $containers
 * @var array $meta_boxes
 * @var grid_container[] $reuseContainers
 */

use GridElementTrash\Store;

?>

<div class="wrap grid-element-trash-wrapper">
	<h2>Grid › Trash</h2>
	<p>Uncheck to disable Element in Grid</p>
	<dl>
		<dt>Containers</dt>
		<dd>
			<ul class="container-trash-list">
			<?php 
			foreach ($containers as $container) {
				$type = $container["type"];
				if(strpos($type, "c-") !== 0) continue;
				$checked = '';
				if( !$trash->is_container_trashed($type) ){
					$checked = 'checked="checked"';
				}
				echo "<li><input class='trash-check check-container' type='checkbox' data-element='container' $checked name='$type' /> ".$type."</li>";
			}

			?>
			</ul>
		</dd>
        <dt>Reuse Containers</dt>
        <dd>
            <ul class="container-trash-list">
				<?php
				foreach ($reuseContainers as $container) {
				    $title = $container->reusetitle;
					$type = $container->type;
					$id = $container->containerid;
					$checked = 'checked="checked"';
					if( $trash->is_reuse_container_trashed($id) ){
						$checked = '';
					}
					echo "<li><input class='trash-check check-container' type='checkbox' data-element='reuse-container' $checked name='$id' /> ".$title."</li>";
				}

				?>
            </ul>
        </dd>
		<dt>Boxes</dt>
		<dd><?php 
		foreach ($meta_boxes as $meta_box) {
			$class = get_class($meta_box);
			if($class == "grid_post_box") continue;
			/**
			 * @var grid_box $obj
			 */
			$obj=new $class();
			$obj->storage = $storage;
			$searchresult=$obj->metaSearch($meta_box->metaSearchCriteria(),"");
			?>
			<dl>
				
				<dt><?php echo $class; ?></dt>
				
				<?php
				foreach ($searchresult as $key => $box) 
				{
					/**
					 * @var grid_box $box
					 */
					$type = $box->type();
					$display = $box->render(true);
					$checked = '';
					$trashid = $type;
					if($type == "reference")
					{
						$trashid = "reference-".$box->content->boxid;
					}
					if( !$trash->is_box_trashed($trashid) )
					{
						$checked = 'checked="checked"';
					}
					?>
						<dd><?php echo "<input class='trash-check check-box' type='checkbox' data-element='box' $checked name='$trashid' />".$display; ?></dd>

					<?php
				}
				?>
			</dl>
			<?php
		}
		?>
		</dd>
	</dl>

</div>