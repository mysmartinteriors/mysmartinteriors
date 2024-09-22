<div class="table-responsive">
     <table class="table table-bordered mb-0 crm_tables">
		<thead>
		<tr>
				<th>
						Sl
				</th>
				<th>
						Username
				</th>
				 <th>
						Full name
				</th>
				<th>
						Email
				</th>
				<th>
						Role
				</th>
				<!-- <th>
						Status
				</th> -->
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
					// print_R($datas);echo "<hr>";
		?>
		<tr>
			<td>
				<?php echo $i ?>
			</td>
			<td>
				<?php echo ucwords($datas['login_id']) ?>
			</td>
			<td>
				<?php echo ucwords($datas['first_name']." ".$datas['last_name']) ?>
			</td>
			<td>
				<?php if($datas['email']!=""){ echo $datas['email']; }else{echo "-------";} ?>
			</td>
			<td>
				<?php if(!empty($datas['roles_name'])){ echo ucwords($datas['roles_name']); }else{echo "-------";} ?>
			</td>
			<!-- <td>
			<?php //if($datas['status']==1){ ?>
				<span class="badge badge-success">Active</span>
			<?php// }else if($datas['status']==0){ ?>
				<span class="badge badge-danger">Inactive</span>						
			<?php //} ?>
            
        	</td> -->

            <td>
                <div class="tbl-action-holder">
                    <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air" onclick="window.location.href='<?php echo base_url() ?>admin/adminusers/edit/<?php echo $datas['id'];?>'" title="Edit">
                    <i class="ti-pencil"></i>
                    </button>
					<button class="tbl-action-btn btn btn-danger btn-icon-only btn-circle btn-sm btn-air delData"
                                    data-id="<?php echo $datas['id']; ?>" title="Delete">
                                    <i class="ti-trash"></i>
                                </button>
                </div>
            </td>

		</tr>
		<?php 
			$i++; } }else{
    	?>	
		<tr>
			<td colspan="7" class="text-center">No users found...</td>
		</tr>
	 	<?php } ?>
		</tbody>
	</table>
	<div id="page_result" class="table-pagination-holder">
        <?php if (isset($pagination_data['pagination_links'])) { echo $pagination_data['pagination_links']; } ?>
	</div>
</div>