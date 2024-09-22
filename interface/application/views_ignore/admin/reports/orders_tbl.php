<div class="table-responsive mt-3">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>
                        Sl
                </th>      
                <th>
                        Date
                </th>      
                <th>
                        Description
                </th>   
                <th>
                        Customer
                </th>
                <th>
                         Address
                </th> 
                <th>
                         Status
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
                        <?php echo getMyDbDate("%d-%M-%Y %H:%i:%s",$rowData->createdDate); ?>
                    </td>
                    <td>
                        <?php echo '<i class="fa fa-rupee-sign"></i>'.$rowData->total_amount.' for '.$rowData->prd_count.' item(s)' ?>                            
                    </td>     
                    <td>    
                        <?php echo $rowData->name ?>
                        <?php if($rowData->email!=''){echo '<br>'.$rowData->email;} ?>
                        <?php if($rowData->phone!=''){echo '<br>'.$rowData->phone;} ?>
                    </td>           
                    <td>
                        <?php echo $rowData->address; ?>
                    </td>
                    <td>
                        <?php if($rowData->status<0){ ?>
                            <span class="badge badge-danger badge-pill">Cancelled</span>
                        <?php } if($rowData->status==0){ ?>
                            <span class="badge badge-danger badge-pill">Pending</span>
                        <?php }else if($rowData->status==1){ ?>
                            <span class="badge badge-info badge-pill">Payment Due</span>
                        <?php }else if($rowData->status==2){ ?>
                            <span class="badge badge-info badge-pill">Dispatched</span>
                        <?php }else if($rowData->status==3){ ?>
                            <span class="badge badge-success badge-pill">Served</span>
                        <?php } ?>
                    </td>

            </tr>
    <?php 
        $i++;
        }
        }else{
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