<?php $counter=$count+1; ?>
<div class="appendRows" data-count="<?php echo $counter ?>">
	<div class="row">
		<div class="form-group col-sm-4 col-12">
			<label class="form-control-label">MRP<span class="text-danger"></span></label>
			<input type="text" name="mrp[]" id="mrp_<?php echo $counter ?>" class="c_name form-control" value="">
		</div>
		<div class="form-group col-sm-4 col-12">
			<label class="form-control-label">Price<span class="text-danger">*</span></label>
			<input type="text" name="price[]" id="price_<?php echo $counter ?>" class="c_email form-control" value="">
		</div>		
		<div class="form-group col-sm-4 col-12">
			<label class="form-control-label">Quantity<span class="text-danger">*</span></label>
			<input type="text" name="quantity[]" id="quantity_<?php echo $counter ?>" class="c_name form-control" value="">
		</div>
		<div class="form-group col-sm-3 col-12">
			<label class="form-control-label">Unit<span class="text-danger">*</span></label>
			
			<select class="form-control select2_unit" name="unit[]" id="unit_<?php echo $counter ?>">
				<option value="">--Select Unit--</option>
				<option value="Kg">Kg</option>
				<option value="Gram">Gram</option>
				<option value="Litre">Litre</option>
				<option value="Piece">Piece</option>
				<option value="Bunch">Bunch</option>
				<option value="Pack">Pack</option>
			</select>
			<script>$('[name=unit]').val(<?php echo $unit ?>);</script>
		</div>
		<div class="col-sm-3 form-group mb-4">
			<label>CGST</label>
			<input class="form-control form-control-solid" name="CGST[]" id="CGST_<?php echo $counter ?>" type="text" placeholder="In %">
		</div>
		<div class="col-sm-3 form-group mb-4">
			<label>SGST</label>
			<input class="form-control form-control-solid" name="SGST[]" id="SGST_<?php echo $counter ?>" type="text" placeholder="In %">
		</div>

		<div class="form-group col-lg-2 col-4 action-block text-center">
			<?php if($count>0){ ?>
				<label class="form-control-label">Remove</label><br>
				<span class="text-danger btn-delField" onclick="delCustomField(this);" title="Remove"><i class="fa fa-times"></i></span>
			<?php } ?>
		</div>
	</div>
</div>