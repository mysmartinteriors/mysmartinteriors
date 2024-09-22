<?php //print_R($data_list);echo "<hr>"; ?>
<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl</th>
                <th>Ticket Code</th>
                <th>Customer</th>
                <th>Contact Info</th>
                <th>Message</th>
                <th>Comments</th>
                <th>Status</th>
                <th>Date</th>
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
                            <?php echo $rowData['code'] ?>
                        </td>
                        <td>
                            <?php echo ucwords($rowData['name']) ?>
                        </td>
                        <td>
                            <?php if ($rowData['email'] != '') {
                                echo $rowData['email'];
                            } ?>
                            <?php if ($rowData['phone'] != '') {
                                echo '<br>' . $rowData['phone'];
                            } ?>
                        </td>
                        <td>
                            <?php if ($rowData['subject'] != '') {
                                echo '<b>Subject : </b> ' . ucfirst($rowData['subject']);
                            } ?>
                            <?php if ($rowData['message'] != '') {
                                echo '<br>' . '<p class="read-more-text"><b>Message :</b> ' . ucfirst($rowData['message']).'</p>';
                            } ?>
                        </td>
                        <td>
                            <?php if ($rowData['comments'] != '') {
                                echo ucfirst($rowData['comments']);
                            }else{
                                echo "-----";
                            } ?>
                        </td>
                        <td>
                            <?php echo '<span class="badge badge-pill '.$rowData['status_color_name'].'">'.$rowData['status_name'].'</span>' ?>
                        </td>
                        <td>
                            <?php echo '<span class="">'.$rowData['submitDate'].'</span>' ?>
                        </td>
                        <td>
                            <div class="tbl-action-holder">
                                <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air btn-tktView"
                                    data-id="<?php echo $rowData['id'] ?>" data-code="<?php echo $rowData['code'] ?>"
                                    title="View details">
                                    <i class="ti-pencil"></i>
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
                    <td colspan="7" class="text-center">No records found...</td>
                </tr>
            <?php
            }
            ?>
        </tbody>

    </table>
    <div id="page_result" class="table-pagination-holder">
        <?php if (isset($pagination_data['pagination_links'])) {
            echo $pagination_data['pagination_links'];
        } ?>
    </div>
</div>