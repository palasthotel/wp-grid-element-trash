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

defined( 'ABSPATH' ) || exit;

// This template is included from Settings::render_settings(), so its variables are local to that method.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

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
				?>
				<li><input class='trash-check check-container' type='checkbox' data-element='container' <?php checked( ! $trash->is_container_trashed( $type ) ); ?> name='<?php echo esc_attr( $type ); ?>' /> <?php echo esc_html( $type ); ?></li>
				<?php
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
					?>
					<li><input class='trash-check check-container' type='checkbox' data-element='reuse-container' <?php checked( ! $trash->is_reuse_container_trashed( $id ) ); ?> name='<?php echo esc_attr( $id ); ?>' /> <?php echo esc_html( $title ); ?></li>
					<?php
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
				
				<dt><?php echo esc_html( $class ); ?></dt>
				
				<?php
				foreach ($searchresult as $key => $box) 
				{
					/**
					 * @var grid_box $box
					 */
					$type = $box->type();
					$display = $box->render(true);
					$trashid = $type;
					if($type == "reference")
					{
						$trashid = "reference-".$box->content->boxid;
					}
					?>
						<dd><input class='trash-check check-box' type='checkbox' data-element='box' <?php checked( ! $trash->is_box_trashed( $trashid ) ); ?> name='<?php echo esc_attr( $trashid ); ?>' /><?php echo wp_kses_post( $display ); ?></dd>

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