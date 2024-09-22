<?php if (isset($pagination_data)) { ?>
    <div class="row">
        <div class="col-12">
            <p class="text-left">Total Records <?php echo $pagination_data['total_rows'] ?></p>
        </div>
    </div>
<?php } ?>
<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl</th>
                <th>Mobile</th>
                <th>HTTP Status/Text</th>
                <th>GUID</th>
                <th>Response Message</th>
                <th>Reference Module -> Table -> Reference ID -> Customer ID</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($data_list) && !empty($data_list)) {
                $i = $pagination_data['slno'];
                foreach ($data_list as $rowData) { ?>
                    <tr>
                        <td>
                            <?php echo $i ?>
                        </td>
                        <td>
                            <?php echo $rowData['mobile_number'] ?>
                        </td>
                        <td>
                            <?php if($rowData['http_status']==200){ ?>
                                <span class="badge bg-success">
                                <?php }else{ ?>
                                    <span class="badge bg-danger">
                                <?php } ?>
                                <?php echo $rowData['http_status'] . ' - ' . $rowData['status_text'] ?>
                                </span>
                        </td>
                        <td>
                            <?php if ($rowData['guid'] != '') {
                                echo $rowData['guid'];
                            } else {
                                echo 'N/A';
                            } ?>
                        </td>
                        <td>
                            <?php if ($rowData['response_message'] != '') {
                                echo $rowData['response_message'];
                            } else {
                                echo "N/A";
                            } ?>
                        </td>
                        <td>
                            <?php if ($rowData['reference_module'] != '') {
                                echo $rowData['reference_module'];
                            } else {
                                echo 'N/A';
                            } ?>
                            <?php if ($rowData['reference_table'] != '') {
                                echo ' -> ' . $rowData['reference_table'];
                            } else {
                                echo ' -> N/A';
                            } ?>
                            <?php if ($rowData['reference_id'] != '') {
                                echo ' -> ' . $rowData['reference_id'];
                            } else {
                                echo ' -> N/A';
                            } ?>
                            <?php if ($rowData['customer_id'] != '') {
                                echo ' -> ' . $rowData['customer_id'];
                            } else {
                                echo ' -> N/A';
                            } ?>
                        </td>
                        <td>
                            <?php if ($rowData['created_at'] != '') {
                                echo $rowData['created_at'];
                            } else {
                                echo 'N/A';
                            } ?>
                        </td>
                        <td>
                            <div class="tbl-action-holder">
                                <button class="tbl-action-btn btn btn-info btn-circle mb-2 wp_json"
                                    data-responseJson='<?php echo $rowData['response_json'] ?>' title="View Response JSON">
                                    <i class="ti-eye"></i> Response Json
                                </button><br>
                                <button class="tbl-action-btn btn btn-info btn-circle mb-2 wp_text"
                                    data-requestMessage="<?php echo $rowData['message'] ?>" title="View Message">
                                    <i class="ti-eye"></i> Whatsapp Text
                                </button><br>
                            </div>
                        </td>
                    </tr>
                    <?php $i++;
                }
            } else { ?>
                <tr>
                    <td colspan="10" class="text-center">No records found...</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div id="page_result" class="table-pagination-holder">
        <?php if (isset($pagination_data['pagination_links'])) {
            echo $pagination_data['pagination_links'];
        } ?>
    </div>
</div>


<script>
    $(document).ready(function () {
        $(".wp_json").on('click', function (e) {
            e.preventDefault();
            const data = $(this).attr('data-responseJson');
            console.log(data);
            let formattedData;
            try {
                // Parse the JSON string
                const jsonData = JSON.parse(data);
                // Format JSON data for better readability
                formattedData = JSON.stringify(jsonData, null, 2);
            } catch (error) {
                formattedData = 'Error parsing JSON data:';
            }
            var ticketModal = bootbox.dialog({
                title: 'Whatsapp JSON Response',
                message: '<pre>' + formattedData + '</pre>',
                closeButton: true,
                size: 'medium',
                animate: true,
                // className: "small ticketViewMdl",
            });
        });


        $(".wp_text").on('click', function (e) {
            e.preventDefault();
            const data = $(this).attr('data-requestMessage');
            var ticketModal = bootbox.dialog({
                title: 'Whatsapp Request Message',
                message: data,
                closeButton: true,
                size: 'medium',
                animate: true,
            });
        })
    })
</script>