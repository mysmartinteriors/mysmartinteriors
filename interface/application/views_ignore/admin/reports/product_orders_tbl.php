<div class="table-responsive mt-3">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>
                        Sl
                </th> 
                <th>
                        ID
                </th>      
                <th>
                        Product
                </th> 
                <th>
                         Category
                </th> 
                <th>
                        Total Sale
                </th>  
                <th>
                        Qty Sold
                </th>
            </tr>
        </thead>
        <tbody>
             <?php 
                if($dataQ->num_rows()>0){
                    $i = 1;
                    foreach ($dataQ->result() as $rowData){
            ?>
            <tr>
                <td>
                    <?php echo $i?>
                </td>
                <td>
                    <?php echo $rowData->productCode ?>
                </td>               
                <td>
                    <?php if($rowData->productImage!=""){?>
                        <img src="<?php echo base_url().$rowData->productImage ?>" class="img-sm">
                    <?php }?>
                    <?php echo $rowData->productName ?>
                </td>            
                <td>
                    <?php echo $rowData->categoryName ?>
                </td>           
                <td>
                    <?php echo '<i class="fa fa-rupee-sign"></i>'.$rowData->totalAmt ?>
                </td>  
                <td>
                    <?php if($rowData->totalQty!=''){echo $rowData->totalQty;}else{echo '0';} ?>
                </td>
            </tr>
    <?php 
        $i++;
        }
        } else{
    ?>  
    <tr>
        <td colspan="10" class="text-center">No records found...</td>
    </tr>
    <?php 
        }
    ?>  
        </tbody>
    </table>
</div>