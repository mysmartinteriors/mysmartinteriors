<div class="table-responsive">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl</th>      
                <th>Type</th>    
                <th>Customer</th>
                <th>Contact Info</th> 
                <th>Subscription Info</th> 
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($data_list) && !empty($data_list)){
            $i=$pagination_data['slno'];
            foreach($data_list as $rowData){ ?>
            <tr>
                    <td>
                        <?php echo $i?>
                    </td>               
                    <td>
                            <?php echo $rowData['type'] ?>
                    </td>              
                    <td>
                            <?php echo $rowData['firstName'].' '.$rowData['lastName'] ?>
                    </td>           
                    <td>    
                            <?php if($rowData['email']!=''){echo $rowData['email'];} ?>
                            <?php if($rowData['phone']!=''){echo '<br>'.$rowData['phone'];} ?>
                    </td>            
                    <td>
                        <span class="badge <?php echo $rowData['subscriptionAmount']>0?'badge-success':'badge-danger' ?>">Subscription Amount : <?php echo $rowData['subscriptionAmount']; ?> </span>
                        <hr>
                        <span class="badge <?php echo $rowData['subscriptionPoints']>0?'badge-success':'badge-danger' ?>">Subscription Wallet Points : <?php echo $rowData['subscriptionPoints']; ?> </span>
                    </td>
                    <td>
                        <?php if($rowData['status']==1){ ?>
                        <span class="badge badge-success badge-pill">Active</span>
                        <?php }else if($rowData['status']==0){ ?>
                        <span class="badge badge-danger badge-pill">In Active</span>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="tbl-action-holder">
                            <button class="tbl-action-btn btn btn-info btn-circle mb-2" onclick="window.location.href='<?php echo base_url() ?>admin/customers/view/<?php echo $rowData['id'];?>'" title="Edit">
                            <i class="ti-eye"></i> View
                            </button><br>
                            <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air" onclick="window.location.href='<?php echo base_url() ?>admin/customers/edit/<?php echo $rowData['id'];?>'" title="Edit">
                            <i class="ti-pencil"></i>
                            </button>
                            <button class="tbl-action-btn btn btn-danger btn-icon-only btn-circle btn-sm btn-air delData" data-id="<?php echo $rowData['id'];?>" title="Delete">
                            <i class="ti-trash"></i>
                            </button>
                        </div>
                    </td>
            </tr>
            <?php $i++; } } else{ ?>   
            <tr>
                <td colspan="10" class="text-center">No records found...</td>
            </tr>
            <?php } ?>  
        </tbody>
    </table>
    <div id="page_result" class="table-pagination-holder">
      <?php if(isset($pagination_data['pagination_links'])){ echo $pagination_data['pagination_links'];} ?>
   </div>
</div>