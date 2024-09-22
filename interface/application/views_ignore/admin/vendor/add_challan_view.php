
<form id="addedit_vendor_challan_form">
    <input type="hidden" name="id" value="<?php echo isset($id)?$id:''; ?>">
    <?php if(!empty($dataQ)){ 
        // print_r($dataQ); 
        // echo "<hr>";
    } ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-control-label">Select Vendor<span class="text-danger">*</span></label>
                <select name="vendor_id" class="form-control" id="">
                    <option value="">--Select Vendor--</option>
                    <?php if ($vendors['status'] == 'success' && !empty($vendors['data_list'])) { ?>
                        <?php foreach ($vendors['data_list'] as $vendor) { ?>
                            <option value="<?php echo $vendor['id'] ?>" <?php echo (!empty($dataQ) && $dataQ['vendor_id']==$vendor['id'])?'selected':'' ?>><?php echo $vendor['name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-sm-12 mt-2">
            <div class="mt-3" id="custom_field_sec">
                <div class="card">
                    <div class="card-box mb-0 p-2">
                        <div class="appendRows" data-count="1"> 
                            <?php if(!empty($dataQ) && !empty($dataQ['products'])){ ?>
                                <?php foreach($dataQ['products'] as $product){ ?>
                                    <div class="row">
                                        <div class="form-group col-sm-4 col-12">
                                            <label class="form-control-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name[]" id="name_1" class="c_name form-control"
                                                value="<?php echo !empty($product['product_name'])?$product['product_name']:'' ?>">
                                        </div>
                                        <div class="form-group col-sm-2 col-12">
                                            <label class="form-control-label">Quantity<span class="text-danger">*</span></label>
                                            <input type="text" name="quantity[]" id="qty_1" class="c_email form-control"
                                            value="<?php echo !empty($product['product_quantity'])?$product['product_quantity']:'' ?>">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label>Unit<span class="text-danger">*</span></label>
                                            <select class="form-control select2_unit" name="unit[]" id="unit_1">
                                                <option value="">--Select Unit--</option>
                                                <option value="Kg" <?php echo (!empty($product['product_unit']) && $product['product_unit']=='Kg')?'selected':'' ?>>Kg</option>
                                                <option value="Gram" <?php echo (!empty($product['product_unit']) && $product['product_unit']=='Gram')?'selected':'' ?>>Gram</option>
                                                <option value="Litre" <?php echo (!empty($product['product_unit']) && $product['product_unit']=='Litre')?'selected':'' ?>>Litre</option>
                                                <option value="Piece" <?php echo (!empty($product['product_unit']) && $product['product_unit']=='Piece')?'selected':'' ?>>Piece</option>
                                                <option value="Bunch" <?php echo (!empty($product['product_unit']) && $product['product_unit']=='Bunch')?'selected':'' ?>>Bunch</option>
                                                <option value="Pack" <?php echo (!empty($product['product_unit']) && $product['product_unit']=='Pack')?'selected':'' ?>>Pack</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-2 col-4 action-block text-center">
                                                <label class="form-control-label">Remove</label><br>
                                                <span class="text-danger btn-delField" onclick="delCustomField(this);" title="Remove"><i class="fa fa-times"></i></span>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php }else{ ?>
                                <div class="row">
                                    <div class="form-group col-sm-4 col-12">
                                        <label class="form-control-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name[]" id="name_1" class="c_name form-control"
                                            value="">
                                    </div>
                                    <div class="form-group col-sm-2 col-12">
                                        <label class="form-control-label">Quantity<span class="text-danger">*</span></label>
                                        <input type="text" name="quantity[]" id="qty_1" class="c_email form-control"
                                            value="">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label>Unit<span class="text-danger">*</span></label>
                                        <select class="form-control select2_unit" name="unit[]" id="unit_1">
                                            <option value="">--Select Unit--</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Gram">Gram</option>
                                            <option value="Litre">Litre</option>
                                            <option value="Piece">Piece</option>
                                            <option value="Bunch">Bunch</option>
                                            <option value="Pack">Pack</option>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 text-end" style="text-align:end">
                        <span><label class="action-icons btn btn-success btn-sm btn-addCustomField"
                                onclick="addCustomField();"><i class="fa fa-plus"></i> Add Field</label></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-group text-center mt-3">
            <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving"
                class="btn btn-primary">Create Challan</button>
        </div>
    </div>
    </div>
</form>

<script>
    
function addCustomField() {
    var count = $(".appendRows:last").attr('data-count');
    $.post(urljs + "admin/vendors/add_custom_field", { 'count': count }, function (data) {
        if (data.result == 'success') {
            var del_html = '<label class="form-control-label">Remove</label><br>';
            del_html += '<span class="text-danger btn-delField" onclick="delCustomField(this);" title="Remove"><i class="fa fa-times"></i></span>';
            $(".appendRows:last").find(".action-block").html(del_html);
            $(".appendRows:last").after(data.msg);
        } else {
            show_toast('warning', data.msg);
        }
    }, "json");
}



function delCustomField(param) {
    $(param).parent().parent().parent().remove();
    var card_length = $('#custom_field_sec').find('.appendRows').length;
    console.log(card_length);
    if (card_length <= 0 || card_length == undefined) {
        $.post(urljs + "admin/vendors/add_custom_field", { 'count': card_length }, function (data) {
            if (data.result == 'success') {
                $("#custom_field_sec .card-box").html(data.msg);
            } else {
                show_toast('warning', data.msg);
            }
        }, "json");
    }
}
</script>