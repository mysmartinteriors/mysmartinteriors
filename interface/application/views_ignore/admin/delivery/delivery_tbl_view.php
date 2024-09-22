<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>S.No.</th>
                <th>Code</th>    
                <!-- <th>Name</th> -->
                <th>Contact Info</th>
                <th>Address</th>
                <th>Profile Picture</th>
                <th>ID Proof</th>
                <th>Created</th>                 
                <th>Updated</th>
                <th>Status</th>
                <th>Action</th>
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
                        <td><?php echo $datas['code'] ?></td>       
               <!-- <td>
                    <?php 
                        // if($datas['name']!=''){
                        //     echo $datas['name'];
                        // }else{
                        //     echo '---';
                        // }
                    ?>
                </td> -->
               <td>
                  <?php 
                        if($datas['name']!=''){
                            echo $datas['name'];
                        }
                        if($datas['email']!=''){
                            echo "<br> Email : ". $datas['email'];
                        }
                        if($datas['phone']!=''){
                            echo "<br> Phone : ". $datas['phone'];
                        }
                        if($datas['availability']!=''){
                            echo "<br> Availability : ". ucwords($datas['availability']);
                        }
                    ?>
               </td>
               <td>
                    <?php 
                        if($datas['address']!=''){
                            echo $datas['address'];
                        }
                        if($datas['pincode']!=''){
                            echo "<br> Pincode : ". $datas['pincode'];
                        }
                    ?>
                </td>
                <td>
                    <?php if ($datas['profile_picture'] != '') {
                        echo "<img src=".base_url().'uploads/delivery/'. $datas['profile_picture']." width='100px'>";
                    } else {
                        echo '---';
                    } ?>
                </td>
                <td>
                    <?php if ($datas['aadhaar_card'] != '') {
                        echo "<img src=".base_url().'uploads/delivery/'. $datas['aadhaar_card']." width='100px'>";
                    } else {
                        echo '---';
                    } ?>
                </td>  
               <td>
                  <?php echo custom_date('d-M-Y h:i A',$datas['createdDate']); ?>
                  <?php if(!empty($datas['created_username'])){echo '<br>By - '.ucwords($datas['created_username']);} ?>
               </td>
               <td>
                  <?php if(!empty($datas['updatedDate'])){echo custom_date('d-M-Y h:i A',$datas['updatedDate']);}else{ echo '---';} ?>
               </td>
               <td><?php echo '<span class="badge-pill '.$datas['status_color_name'].'">'.$datas['status_name'].'</span>' ?></td>
                        <td>
                            <div class="tbl-action-holder">
                                <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air btn-addData"
                                    data-id="<?php echo $datas['id']; ?>" title="Edit">
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
                    $i++;
                }
            } else {
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
        <?php if (isset($pagination_data['pagination_links'])) { echo $pagination_data['pagination_links']; } ?>
    </div>
</div>