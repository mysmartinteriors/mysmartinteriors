<div class="col-lg-12 file-media-manager">
  <div class="">                     
    <div class="ibox">
      <div class="ibox-head media-filters">
          <div class="row">
            <!-- <div class="col-lg-4 col-12">
              <div class="input-group">
                  <input class="form-control refine_filter" type="text" name="product" id="product" data-type="search" data-id="product" placeholder="Search for media...">
                  <span class="input-group-btn">
                      <button type="button" class="btn btn-outline-secondary filter">Go!</button>
                  </span>
              </div>
              <input type="hidden" name="page" id="pagenumber" data-type="page" data-id="page"  class="refine_filter" />
            </div>            
            <div class="col-lg-2 col-12">
                <select class="form-control refine_filter" data-type="sortBy" id="sortBy">
                    <option data-type="sortBy" value="">Sort: Newest</option>
                    <option data-type="sortBy" value="createdAsc">Sort: Last First</option>
                    <option data-type="sortBy" value="updatedAsc">Sort: Latest Updated</option>
                    <option data-type="sortBy" value="ztoa">Sort: A-Z</option>
                    <option data-type="sortBy" value="atoz">Sort: Z-A</option>
                </select>
            </div>
            <div class="col-lg-1 col-12">
                <select class="form-control refine_filter" data-type="perpage" id="perpage">
                    <option data-type="perpage" value="30">30</option>
                    <option data-type="perpage" value="40">40</option>
                    <option data-type="perpage" value="50">50</option>
                </select>
            </div> -->
            <div class="col-lg-7"></div>
            <div class="col-lg-5  col-12 text-right">
                <button type="button" class="btn btn-info" onclick="uploadMedia(this);"><i class="fa fa-upload"></i> Upload New</button>              
            </div>
          </div>
      </div>
      <div class="ibox-body p-0 media-list-box">
        <div id="ajax_Library"></div>
        <div class="" id="mediaTbl">
          
        </div>
      </div>
      <div class="ibox-head file-media-footer">
        <div class="row">
            <div class="col-lg-6">
              <div id="page_result"></div>
            </div>
            <div class="col-lg-6">
              <form id="media_select_form" role="form">
                <input type="hidden" name="boxId" value="<?php echo $boxId ?>">
                <input type="hidden" name="filePath" value="">
                <input type="hidden" name="fileId" value="">
                <input type="hidden" name="fileType" value="">
                <div class="btn btn-success btn-labeled btn-labeled-left btn-icon btn-fix pickFile">
                    <span class="btn-label"><i class="la la-check"></i></span>Pick Image
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>