<div class="table-responsive">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>
                        Sl
                </th>      
                <th>
                        Code
                </th>    
                <th>
                        Value
                </th>
                <th>
                        Applicable To
                </th> 
                <th>
                        Valid
                </th>
                <th>
                         Created
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
                if($dataQ->num_rows()>0){
                    if ($page_number == 1) {
                         $i = 1;
                    } else {
                         $i = (($page_number - 1) * $item_per_page) + 1;
                     }
                        foreach ($dataQ->result() as $rowData){
            ?>
            <tr>
                    <td>
                        <?php echo $i?>
                    </td>               
                    <td>
                            <?php echo $rowData->coupon_code ?>
                    </td>              
                    <td>
                            <?php echo $rowData->coupon_value.' '.$rowData->price_type ?>
                    </td>           
                    <td>    
                            <?php 
                                if($rowData->applicable_to==1){
                                    echo 'New customers';
                                }else{
                                    echo 'All';
                                }
                            ?>
                    </td>
                    <td>
                            <?php echo 'From: '.getMyDbDate("%d-%M-%Y %H:%i:%s",$rowData->valid_from); ?>
                            <?php echo '<br>To: '.getMyDbDate("%d-%M-%Y %H:%i:%s",$rowData->valid_to); ?>
                    </td>           
                    <td>
                            <?php echo getMyDbDate("%d-%M-%Y %H:%i:%s",$rowData->createdDate); ?>
                    </td>
                    <td>
                        <?php if($rowData->status==1){ ?>
                        <span class="badge badge-success badge-pill statusData" data-id="<?php echo $rowData->id;?>" data-status="0">Active</span>
                        <?php }else{ ?>
                        <span class="badge badge-danger badge-pill statusData" data-id="<?php echo $rowData->id;?>" data-status="1">Inactive</span>
                        <?php } ?>
                    </td>
                    <td>
                        <div class="tbl-action-holder">
                        <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air btn-addData" data-id="<?php echo $rowData->id;?>" title="Edit">
                        <i class="ti-pencil"></i>
                        </button>
                        <button class="tbl-action-btn btn btn-danger btn-icon-only btn-circle btn-sm btn-air delData" data-id="<?php echo $rowData->id;?>" title="Delete">
                        <i class="ti-trash"></i>
                        </button>
                    </div>
                    </td>

            </tr>
    <?php 
    $i++;
                    }
            }
            else{
    ?>  
    <tr>
        <td colspan="10" class="text-center">No records found...</td>
    </tr>
 <?php 
            }
    ?>  
        </tbody>

    </table>
    <div id="page_result" class="table-pagination-holder">
        <?php
        if(isset($item_per_page)!=""){
            echo $pagination;
            }
        ?>
    </div>
</div>