<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl</th>
                <th>Name</th>
                <th>Description</th>
                <th>Preview</th>
                <th>Created</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($data_list) && !empty($data_list)) {
                $i = $pagination_data['slno'];
                foreach ($data_list as $rowData) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $i ?>
                        </td>
                        <td>
                            <?php echo $rowData['name'] ?>
                        </td>
                        <td>
                            <?php echo $rowData['description'] ?>
                        </td>
                        
                        <td>
                            <?php if ($rowData['image'] != '') {
                                echo "<img src=".base_url().'uploads/testimonials/'. $rowData['image']." width='100px'>";
                            } else {
                                echo '---';
                            } ?>
                        </td>
                        
                        <td>
                            <?php echo getMyDbDate("%d-%M-%Y %H:%i:%s", $rowData['createdDate']); ?>
                        </td>
                        <td>
                            <?php //echo '<span class="badge badge-pill statusData '.$rowData['status_color_name'].'" data-id='.$rowData['id'].'>' .$rowData['status_name'].'</span>' ?>
                            <?php if ($rowData['status'] == 46) { ?>
                                <span class="badge badge-success badge-pill statusData" data-id="<?php echo $rowData['id']; ?>"
                                    data-status="47">Active</span>
                            <?php } else { ?>
                                <span class="badge badge-danger badge-pill statusData" data-id="<?php echo $rowData['id']; ?>"
                                    data-status="46">Inactive</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="tbl-action-holder">
                                <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air btn-addData"
                                    data-id="<?php echo $rowData['id']; ?>" title="Edit">
                                    <i class="ti-pencil"></i>
                                </button>
                                <button class="tbl-action-btn btn btn-danger btn-icon-only btn-circle btn-sm btn-air delData"
                                    data-id="<?php echo $rowData['id']; ?>" title="Delete">
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