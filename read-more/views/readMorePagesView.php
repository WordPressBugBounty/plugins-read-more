<?php
$results = ReadMoreData::getAllData();
?>
<div class="ycf-bootstrap-wrapper">
<div class="wrap">
	<h2 class="add-new-buttons">Read Mores <a href="<?php echo admin_url();?>admin.php?page=addNew" class="add-new-h2">Add New</a></h2>
</div>
<div class="expm-wrapper">
	<?php if(YRM_PKG == 1): ?>
		<div class="main-view-upgrade">
			<input type="button" class="expm-update-to-pro" value="Upgrade to PRO version" onclick="window.open('<?php echo YRM_PRO_URL; ?>');">
		</div>
	<?php endif;?>
	<table class="table table-bordered expm-table">
		<tr>
			<td>Id</td>
			<td>Title</td> 
			<td>Type</td>
			<td>Options</td>
		</tr>

		<?php if(empty($results)) { ?>
			<tr>
				<td colspan="4">No Read More</td>
			</tr>
		<?php }
		else {
			foreach ($results as $result) {
				$type = esc_attr($result['type']);
				$id = esc_attr($result['id']);
				?>
				<tr>
					<td><?php echo esc_attr($id); ?></td>
					<td><?php echo esc_attr($result['expm-title']); ?></td>
					<td><?php echo esc_attr($type); ?></td>
					<td>
						<a href="<?php echo admin_url()."admin.php?page=button&type=".esc_attr($type)."&readMoreId=".esc_attr($id).""?>">Edit</a>
						<a class="yrm-delete-link" data-id="<?php echo esc_attr($id);?>" href="<?php echo admin_url()."admin-post.php?action=delete_readmore&readMoreId=".esc_attr($id).""?>">Delete</a>
					</td>
				</tr>
		<?php } ?>

		<?php } ?>

		<tr>
			<td>Id</td>
			<td>Title</td> 
			<td>Type</td>
			<td>Options</td>
		</tr>
	</table>
</div>
</div>