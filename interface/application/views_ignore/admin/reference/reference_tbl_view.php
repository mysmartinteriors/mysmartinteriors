<?php //print_R($data_list);echo "<hr>"; ?>
<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="thead-default">
            <tr>
                <th>Sl</th>
                <th>Name</th>
                <th>Percentage</th>
                <th>Created At</th>
                <th>Updated At</th>
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
                            <?php echo ucwords($rowData['percentage']) . " " . "<i class='fa fa-percentage'></i>" ?>
                        </td>
                        <td>
                            <?php if(!empty($rowData['createdDate'])){ echo getMyDbDate("%d-%M-%Y %H:%i:%s", $rowData['createdDate']);}else{echo "-----";} ?>
                        </td>
                        <td>
                            <?php if(!empty($rowData['updatedDate'])){ echo getMyDbDate("%d-%M-%Y %H:%i:%s", $rowData['updatedDate']);}else{echo "-----";} ?>
                        </td>
                        
                        <td>
                            <?php echo '<span class="badge badge-pill '.$rowData['status_color_name'].'">'.$rowData['status_name'].'</span>' ?>
                        </td>
                        <td>
                            <div class="tbl-action-holder">
                                <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air btn-editData"
                                    data-id="<?php echo $rowData['id']; ?>" title="Edit">
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



<script>
    $(".btn-editData").on('click', function(e){
        e.preventDefault();
        const id = $(this).attr('data-id');
        // $.post(urljs+'admin/subscription/add_new', {id}, )
        var ticketModal = bootbox.dialog({
        title: 'Edit Reference Percentage',
        message: '<p class="loaderBlock"><i class="fa fa-spinner fa-spin"></i> Loading, Please wait...</p>',
        closeButton: true,
        size: 'extra-large',
        animate: true,
        className: "ticketViewMdl",
        });
        $.post(urljs + "admin/reference/add_new", { 'id': id }, function (data) {
            if (data.status == 'success') {
                save_add_data();
                ticketModal.find('.bootbox-body').html(data.message);
            } else {
                swal("Error!", data.message, "warning");
            }
        }, "json");
    })
</script>