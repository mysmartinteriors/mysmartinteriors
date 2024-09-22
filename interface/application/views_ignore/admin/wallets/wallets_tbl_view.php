<?php //print_R($data_list);echo "<hr>"; ?>
<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl</th>
                <th>Customer Name</th>
                <th>Plan Name</th>
                <th>Wallet Balance</th>
                <th>Status</th>
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
                            <?php echo $rowData['customerName'] ?>
                        </td>
                        <td>
                            <?php echo ucwords($rowData['plan_name']) ?>
                        </td>
                        <td>
                            <?php echo ucwords($rowData['balance']) ?>
                        </td>
                        
                        <td>
                            <?php echo '<span class="badge badge-pill '.$rowData['status_color_name'].'">'.$rowData['status_name'].'</span>' ?>
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