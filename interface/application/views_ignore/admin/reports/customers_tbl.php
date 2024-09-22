<div class="table-responsive mt-3">
     <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl</th>      
                <th>Type</th>    
                <th>Customer</th>
                <th>Contact Info</th> 
                <th>Address</th> 
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
             <?php 
                if(!empty($dataQ)){
                    $i = 1;
                    foreach ($dataQ as $rowData){
            ?>
            <tr>
                    <td>
                        <?php echo $i ?>
                    </td>               
                    <td>
                            <?php echo $rowData['type'] ?>
                    </td>              
                    <td>
                            <?php if($rowData['firstName']!=''){
                                echo $rowData['firstName'].' '.$rowData['lastName'];
                        }else{
                                echo "---";
                            }// echo $rowData['firstName'].' '.$rowData['lastName'] ?>
                    </td>           
                    <td>    
                            <?php if($rowData['email']!=''){echo $rowData['email'];}else{echo "---";} ?>
                            <?php if($rowData['phone']!=''){echo '<br>'.$rowData['phone'];} ?>
                    </td>            
                    <td>
                            <?php 
                            if($rowData['city']!=''){
                                echo $rowData['address'];
                        }else{
                                echo "---";
                            }
                            if($rowData['city']!=''){echo ', '.$rowData['city'];}
                            if($rowData['state']!=''){echo '<br>'.$rowData['state'];}
                            if($rowData['country']!=''){echo ', '.$rowData['country'];}
                            if($rowData['postalCode']!=''){echo '-'.$rowData['postalCode'];}
                            ?>
                    </td>
                    <td>
                        <?php echo '<span class="badge badge-pill '.$rowData['status_color_name'].'">'.$rowData['status_name'].'</span>' ?>
                        
                    </td>

            </tr>
    <?php 
        $i++;
        } }else{
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