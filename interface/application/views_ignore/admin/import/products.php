<div class="uploadModal">
	<form class="" id="import_prd_form">
		<div class="row">
		  	<div class="col-12">
		        <div class="fallback mb-2">
		            <input name="file" type="file" class="dropify" data-max-file-size="5M" accept=".xlsx, .xls"/>
		        </div>
		        <a href="javascript:void(0);" onclick="download_sample(this);" class="sample_data" data-type="products" >Download sample data</a>
		  	</div>
		</div>
		<div class="row">
		  	<div class="col-12 mb-3 mt-3">
                <label class="radio radio-inline">
                    <input type="radio" value="yes" name="update" checked="">
                    <span class="input-span"></span>Overwrite if exists
                </label>
                <label class="radio radio-inline">
                    <input type="radio" value="no" name="update">
                    <span class="input-span"></span>Don't do anything if exists
                </label>
	        </div>
        </div>
		<div class="row">
			<div class="col-12">
				<div class="text-center mb-3 mt-3">
					<button type="submit" class="btn btn-success btn-sm" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Importing data...">Import Now</button>
				</div>
			</div>
		</div>
	</form>
	<div class="row">	  
	    <div class="col-12">
	      	<div class="excel_progress"></div>         
	    </div>
	</div>
</div>
