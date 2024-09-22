<style>
    /* .table-borderless td,
    .table-borderless th {
        border: none;
    } */
     .bootbox-body{
        padding: 0 10px 0 10px;
     }
</style>

<!-- <div class="row">
    <div class="col-md-12"> -->
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th rowspan="2">Sl.No</th>
                    <th rowspan="2">Level</th>
                    <th colspan="2" style="text-align: center">Customers</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($referralData)) { ?>
                    <tr>
                        <td colspan="4" class="text-center">No Referral Data Found</td>
                    </tr>
                <?php } else { ?>
                    <?php $i = 1;
                    foreach ($referralData as $key => $val) {
                        if (!empty($val)) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $key; ?></td>
                                <td colspan="2">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <?php $j = 1;
                                            foreach ($val as $k => $v) { ?>
                                                <tr style="padding: 0">
                                                    <td style="padding: 0; width: 100%">
                                                        <table class="table-borderless" style="width: 100%">
                                                            <tr>
                                                                <td style="width: 40%">
                                                                    <?php echo '<strong>' . $v['firstName'] . ' ' . $v['lastName'] . '</strong>'; ?>
                                                                </td>
                                                                <td style="width: 30%">
                                                                    <span style="cursor: pointer" data-id="<?php echo $v['id'] ?>"
                                                                        class="subscriptions text-white badge <?php echo ($v['is_subscribed'] > 0 ? 'bg-success' : 'bg-danger') ?>">
                                                                        <?php echo $v['is_subscribed'] ?>
                                                                        Subscriptions </span>
                                                                </td>
                                                                <td style="width: 30%">
                                                                    <span> At <?php echo $v['createdDate'] ?> </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <?php $j++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php $i++;
                        }
                    } ?>
                <?php } ?>
            </tbody>
        </table>
    <!-- </div>
</div> -->

<script>
    $(document).ready(function () {
        $(".subscriptions").on('click', function (e) {
            e.preventDefault();
            const customerId = $(this).attr('data-id');
            var dataModal = bootbox.dialog({
                title: "",
                message: '<i class="fa fa-spinner fa-spin"></i> Loading, Please wait...',
                closeButton: true,
                size: 'small',
                animate: true,
                centerVertical: true,
                className: "userModalView",
            });
            $.post(urljs + 'account/check_subscriptions', { customerId }, function (response) {
                if (response.status == 'success') {
                    dataModal.find('.bootbox-body').html(response.message);
                } else {
                    bootbox.closeAll();
                    swal('No detail found', '', 'warning');
                }
            }, 'json')
        })
    })
</script>
</body>

</html>