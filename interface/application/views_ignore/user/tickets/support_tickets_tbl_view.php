<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Sl</th>
                <th>Date</th>
                <th>Subject</th>
                <th>Status</th>
                <th>View</th>          
            </tr>
        </thead>
        <tbody>
            <?php 
                if($ticketQ['status']=='success' && !empty($ticketQ['data_list'])){
                    $i=1; 
                    foreach ($ticketQ['data_list'] as $row) {
            ?>
            <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $this->admin->getCustomDate("%d-%M-%Y %H:%i:%s",strtotime($row['submitDate'])); ?></td>
                <td><?php echo $row['subject'] ?></td>
                <td>

					<?php if($row['status']==1){ ?>
					<span class="badge badge-danger badge-pill">Pending</span>
					<?php }else if($row['status']==0){ ?>
					<span class="badge badge-success badge-pill">Resolved</span>
					<?php } ?>
                        
                </td>
                <td>
                    <button type="button" class="btn btn-actions-sm view-action btn-tktView" data-id="<?php echo $row['id'] ?>" data-code="<?php echo $row['code'] ?>"><i class="fa fa-search-plus"></i></button>
                </td>
            </tr>
            <?php $i++;} }else{ ?>
                <tr>
                    <td colspan="5" class="text-center">Seems you do not have any support tickets...</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>