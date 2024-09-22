<div class="row">
    <?php if ($status == 'success' && !empty($data_list)) {
        $i = $pagination_data['slno'];
        foreach ($data_list as $rowData) { ?>
            <div class="col-md-4">
                <div class="ibox" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                    <div class="ibox-head page-head-btns p-2">
                        <div class="ibox-title">
                            <p class="badge bg-primary"><?php echo 'Challan ID : ' . $rowData['unique_id']; ?></p>
                            <p class="badge <?php echo $rowData['l_color_name'] ?> btn-labeled btn-labeled-left btn-icon btn-addData"
                                data-id="">Status : <?php echo $rowData['l_status_name'] ?></p>
                        </div>
                    </div>
                    <div class="ibox-body adv_filter tblbox">
                        <div class="row mb-4">
                            <div class="col-sm-12 col-12">
                                <table class="table">
                                    <tr>
                                        <td>Created</td>
                                        <td>
                                            <?php echo custom_date('d-M-Y h:i A', $rowData['created_at']); ?>
                                            <?php if (!empty($rowData['created_user_firstname'])) {
                                                echo '<br>By - ' . ucwords($rowData['created_user_firstname'] . ' ' . $rowData['created_user_lastname']);
                                            } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Updated At</td>
                                        <td><?php echo !empty($rowData['updated_at']) ? custom_date('d-M-Y h:i A', $rowData['updated_at']) : 'N/A'; ?>
                                        </td>
                                        <?php if (!empty($rowData['updated_user_firstname'])) {
                                            echo '<br>By - ' . ucwords($rowData['updated_user_firstname'] . ' ' . $rowData['updated_user_lastname']);
                                        } ?>
                                    </tr>
                                </table>
                                <div class="col-12 text-right">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <button
                                    onclick="window.open('<?php echo base_url() ?>vendors/dashboard/pdf/<?php echo $rowData['id'] ?>')"
                                    title="Download Challan" class="btn btn-success"><i class="la la-download"></i>Download
                                    Challan</button>
                                <a class="btn btn-danger viewProduct"
                                    href="<?php echo base_url() . 'vendors/dashboard/challanDetails/' . $rowData['id'] ?>"
                                    data-id="<?php echo $rowData['id'] ?>"><i class="la la-eye"></i> View Products</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++;
        }
    } else { ?>
        <div class="col-12">
            <div class="ibox">
                <div class="ibox-head page-head-btns">
                    <div class="btn btn-success btn-labeled btn-labeled-left btn-icon btn-addData text-center d-flex justify-content-center"
                        data-id="">
                        <span class="btn-label"><i class="la la-plus"></i></span>No Challans Found
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>



<script>
    $(".updateProduct").on('click', function (e) {
        e.preventDefault();
        const challanId = $(this).attr('data-id');
        $.post(urljs + 'admin/vendor/add_challan', { id: challanId }, function (response) {
            if (response.status == 'success') {
                swal_alert('Success!!', 'Updated Successfully', 'success', '');
            } else {
                swal_alert('Error!!', 'Update Error', 'error', '');
            }
        }, 'json')
    })
</script>