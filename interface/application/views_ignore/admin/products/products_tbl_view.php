<style>
   td{
      font-size: 11px;
   }
   th{
      font-size: 11px;
   }
</style>

<div class="table-responsive">
   <table class="table table-centered table-bordered table-striped dt-responsive w-100" id="organization_brnch_table">
      <thead>
         <tr>
            <th>S.No.</th>
            <th>Product</th>
            <th>Category</th>
            <th>Quantity/Price</th>
            <th>Created</th>
            <th>Repurchase</th>
            <th>Status</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         <?php
         if (is_array($data_list) && !empty($data_list)) {
            $i = $pagination_data['slno'];
            foreach ($data_list as $datas) {
               ?>
               <tr>
                  <td>
                     <?php echo $i ?>
                  </td>
                  <td class="">
                     <?php
                     if ($datas['product_image'] != "") {
                        $img_prev = base_url() . $datas['product_image'];
                     } else {
                        $img_prev = base_url() . 'uploads/default_product.jpg';
                     }
                     ?>
                     <img src="<?php echo $img_prev ?>" style="width: 50px; margin-right: 5px;" class="img-sm"><a
                        onclick="window.open('<?php echo base_url() . 'product/' . $datas['code'] ?>')">
                        <br>
                        <?php echo $datas['name'] ?>
                     </a>
                  </td>
                  <td>
                     <?php echo $datas['categoryName']; ?>
                  </td>
                  <td>
                     <table>
                        <thead>
                           <th>Qty</th>
                           <th>Unit</th>
                           <th>Price</th>
                           <th>MRP</th>
                           <th>Action</th>
                        </thead>
                     <?php if (!empty($datas['product_metrics'])) {
                        foreach ($datas['product_metrics'] as $details) { ?>
                              <tr>
                                 <td><input class="<?php echo 'quantity_'.$details['id'] ?>" style="max-width: 35px;" type="text" value="<?php echo $details['quantity'] ?>" /></td>
                                 <td><input class="<?php echo 'unit_'.$details['id'] ?>" style="max-width: 40px;" type="text" value="<?php echo $details['unit'] ?>" /></td>
                                 <td><input class="<?php echo 'price_'.$details['id'] ?>" style="max-width: 40px;" type="text" value="<?php echo $details['price'] ?>" /></td>
                                 <td><input class="<?php echo 'mrp_'.$details['id'] ?>" style="max-width: 40px;" type="text" value="<?php echo $details['mrp'] ?>" /></td>
                                 <td style="text-align: right;"><button class="tbl-action-btn btn btn-primary btn-icon-only btn-circle btn-sm btn-air updatePrice" data-id="<?php echo $details['id']; ?>" title="Update"><i class="ti-pencil"></i></button></td>
                              </tr>
                              <?php }
                     } ?>
                     </table>
                  </td>
                  <td>
                     <?php echo custom_date('d-M-Y h:i A', $datas['createdDate']); ?>
                     <?php if (!empty($datas['created_username'])) {
                        echo '<br>By - ' . ucwords($datas['created_username']);
                     } ?>
                  </td>
                  <td>
                     <span data-toggle="toggle" title="Change Comission applicable" class="badge-pill p-2 btn-<?php echo $datas['comission_applicable'] == 'yes' ? 'success' : 'danger' ?> btn-fix btn-air btn-circle mb-2 comissionApplicable"
                        data-applicable="<?php echo $datas['comission_applicable'] ?>" data-id="<?php echo $datas['id'] ?>">
                        <?php echo !empty($datas['comission_applicable']) ? $datas['comission_applicable'] : 'no' ?>
                     </span>
                  </td>
                  <td>
                     <?php echo '<span data-toggle="toggle" title="Change Status" class="statusUpdate badge-pill p-2' . $datas['status_color_name'] . '" data-status="' . $datas['status'] . '" data-id="' . $datas['id'] . '">' . $datas['status_name'] . '</span>' ?>
                  </td>
                  <td>
                     <div class="tbl-action-holder">
                        <!-- <button class="tbl-more-btn btn btn-info btn-fix btn-air btn-circle mb-2"
                           onclick="window.location.href='<?php //echo base_url() ?>admin/products/details/<?php //echo $datas['id']; ?>'"
                           title="Manage product features">
                           <span class="btn-icon"><i class="ti-pencil"></i>Manage</span>
                        </button><br> -->
                        <div class="text-center">
                           <button class="tbl-action-btn btn btn-info btn-icon-only btn-circle btn-sm btn-air"
                              onclick="window.location.href='<?php echo site_url('admin/products/edit?id=' . $datas['id'] . '&page=' . 0) ?>'"
                              title="Edit"><i class="ti-pencil"></i></button>
                           <button class="tbl-action-btn btn btn-danger btn-icon-only btn-circle btn-sm btn-air delData"
                              data-id="<?php echo $datas['id']; ?>" title="Delete"><i class="ti-trash"></i></button>
                        </div>
                     </div>
                  </td>
               </tr>
               <?php $i++;
            }
         } else { ?>
            <tr>
               <td colspan="15" class="text-center">No records found...</td>
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
   report_template();
   function report_template() {
      $(".report-template").on('click', function (e) {
         const id = $(this).attr('data-id');
         e.preventDefault();
         var dataModal = bootbox.dialog({
            title: "Export Cases",
            message: '<i class="fa fa-spinner fa-spin"></i> Loading, Please wait...',
            closeButton: true,
            size: 'medium',
            animate: true,
            centerVertical: true,
            className: "reportsModalView",
         });
         $(".bootbox.reportsModalView").removeAttr('tabindex');
         $.post(urljs + "report_template/view", { id: id }, function (data) {
            dataModal.find('.bootbox-body').html(data.message);
            dataModal.find('input,select').attr('autocomplete', 'off');
         }, "json");
      })
   }

   $(document).ready(function () {
      $(".comissionApplicable").on('click', function (e) {
         e.preventDefault();
         const thisBtn = $(this)
         const id = $(this).attr('data-id');
         const applicable = $(this).attr('data-applicable');
         $.post(urljs + 'admin/products/changeRepurchase', { id, applicable }, function (response) {
            if (response.status == 'success') {
               show_toast('success', 'Updated Successfully')
               thisBtn.html(response.applicable);
               thisBtn.attr('data-applicable', response.applicable)
               if (response.applicable == 'yes') {
                  thisBtn.removeClass('bg-danger');
                  thisBtn.addClass('bg-success')
               } else {
                  thisBtn.removeClass('bg-success')
                  thisBtn.addClass('bg-danger')
               }
            } else {
               show_toast('warning', 'Couldn\'t Update');
            }
         }, 'json')
      })


      $(".statusUpdate").on('click', function (e) {
         e.preventDefault();
         const thisBtn = $(this);
         const id = $(this).attr('data-id');
         const status = $(this).attr('data-status');
         $.post(urljs + 'admin/products/updateStatus', { id, status }, function (response) {
            if (response.status == 'success' && response.data_list) {
               show_toast('success', 'Status Updated Successfully')
               thisBtn.html(response.data_list.status_name);
               thisBtn.attr('data-status', response.data_list.status)
               console.log(response.data_list.status);
               if (response.data_list.status == 32) {
                  thisBtn.removeClass('bg-danger');
                  thisBtn.addClass('bg-success')
               } else {
                  thisBtn.removeClass('bg-success')
                  thisBtn.addClass('bg-danger')
               }
            } else {
               show_toast('warning', 'Couldn\'t Update');
            }
         }, 'json')
      })

      $(".updatePrice").on('click', function(e){
         e.preventDefault();
          id = $(this).attr('data-id');
          const quantity = $(".quantity_"+id).val();
          const unit = $(".unit_"+id).val();
          const price = $(".price_"+id).val();  
          const mrp = $(".mrp_"+id).val(); 
         $.post(urljs + 'admin/products/updateMetrics', { id, quantity, unit, price, mrp }, function (response) {
            if (response.status == 'success') {
               show_toast('success', 'Metrics Updated Successfully')
            } else {
               show_toast('warning', 'Couldn\'t Update');
            }
         }, 'json')
      })
   })

</script>