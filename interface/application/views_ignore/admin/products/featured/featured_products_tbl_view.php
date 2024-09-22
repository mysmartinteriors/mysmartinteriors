<div class="table-responsive">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>
                        Sl
                </th>      
                <th>
                        Product
                </th>
                <th>
                         Category
                </th> 
                <th>
                         Status
                </th>
                <th>
                         Actions
                </th>
            </tr>
        </thead>
        <tbody>
             <?php 
                if(is_array($data_list) && !empty($data_list)){
                    $i=$pagination_data['slno'];
                    foreach($data_list as $rowData){ ?>
            <tr>
                <td>
                    <?php echo $i?>
                </td>               
                <td>
                        <?php if($rowData['product_image']!=""){?>
                            <img src="<?php echo base_url().$rowData['product_image'] ?>" class="img-sm">
                        <?php }?>
                        <?php echo $rowData['name'] ?>
                </td>           
                <td>
                        <?php echo $rowData['categoryName'] ?>
                </td>            
                <td>
                    <?php if($rowData['fp_status']==1){ ?>
                    <span class="badge badge-success badge-pill statusData" data-id="<?php echo $rowData['fpId'];?>" data-status="0">Active</span>
                    <?php }else{ ?>
                    <span class="badge badge-danger badge-pill statusData" data-id="<?php echo $rowData['fpId'];?>" data-status="1">Inactive</span>
                    <?php } ?>
                </td>
                <td>
                    <span>
                        <a class="action-icons c-delete delData" data-id="<?php echo $rowData['id'];?>" title="Delete"><i class="fa fa-trash"></i></a>
                    </span>
                </td>
            </tr>
            <?php $i++; } } else{ ?>  
            <tr><td colspan="10" class="text-center">No records found...</td></tr>
        <?php } ?>  
        </tbody>
        <div id="page_result" class="table-pagination-holder">
      <?php if(isset($pagination_data['pagination_links'])){ echo $pagination_data['pagination_links'];} ?>
   </div>
    </table>
</div>