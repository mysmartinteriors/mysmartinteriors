<div class="table-responsive">
     <table class="table table-bordered mb-0 crm_tables">
		<thead>
		<tr>
				<th>
						Sl
				</th>
				<th>
						Role name
				</th>
				<th>
						Description
				</th>
				<th>
						 Actions
				</th>
		</tr>
		</thead>
		<tbody>
		<?php 
			if (is_array($data_list) && !empty($data_list)) {
                $i = $pagination_data['slno'];
                foreach ($data_list as $datas) {
		?>
		<tr>
			<td>
				<?php echo $i ?>
			</td>
			<td>
				<?php echo $datas['name'] ?>
			</td>
			<td>
				<?php if(!empty($datas['description'])){ echo $datas['name']; }else{
					echo "-------";
				} ?>
			</td>
            <td>
                <div class="tbl-action-holder">
                    <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air" onclick="window.location.href='<?php echo base_url() ?>admin/roles/edit/<?php echo $datas['id'];?>'" title="Edit">
                    <i class="ti-pencil"></i>
                    </button>
                    <button class="tbl-action-btn btn btn-danger btn-icon-only btn-circle btn-sm btn-air delrole" data-id="<?php echo $datas['id'];?>" title="Delete">
                    <i class="ti-trash"></i>
                    </button>
                </div>
            </td>

		</tr>
		<?php 
					$i++;
					}
				}else{
    ?>	
	<tr>
		<td colspan="3" class="text-center">No roles found...</td>
	</tr>
 <?php 
			}
    ?>

		</tbody>

	</table>
	<div id="page_result" class="table-pagination-holder">
        <?php if (isset($pagination_data['pagination_links'])) { echo $pagination_data['pagination_links']; } ?>
	</div>
</div>