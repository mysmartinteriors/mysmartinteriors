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
                         Price
                </th>  
                <th width="8%">
                        Details
                </th>
                <th>
                         In_Stock?
                </th>
            </tr>
        </thead>
        <tbody>
             <?php 
                if(!empty($dataQ)){
                    $i = 1;
                    foreach ($dataQ as $rowData){
                        // print_R($rowData);echo "<hr>";
            ?>
            <tr>
                <td>
                    <?php echo $i?>
                </td>
                <td>
                    <?php echo $rowData['code'] ?>
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
                    <?php echo '<i class="fa fa-rupee-sign"></i>'.$rowData['price'] ?>
                </td>  
                <td>
                    <button class="tbl-more-btn btn btn-info btn-fix btn-air btn-circle" onclick="window.location.href='<?php echo base_url() ?>admin/products/details/<?php echo $rowData['id'];?>'" title="Manage product features">
                        <span class="btn-icon"><i class="ti-pencil"></i>Manage</span>
                    </button>
                </td>         
                <td>
                        <?php if($rowData['in_stock']==1){echo 'Yes';}else{echo 'No';} ?>
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