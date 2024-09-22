
<div class="row m-0">
  <div class="col-md-16 col-lg-12  p-2">
      <div class="list-unstyled member-list row">
        <?php                                  
          if($status=='success' && !empty($data_list)){
            foreach ($data_list as $media) {
        ?>
        <div class="col-lg-2">
          <a href="javascript:void(0);" class="file-select" onclick="selectMediaFile(this);" data-id="<?php echo $media['id'] ?>"  data-path="<?php echo $media['folder'] ?>" data-name="<?php echo $media['fileName'] ?>" data-type="<?php echo $media['fileType'] ?>">
            <div class="media flex-column">                          
              <div class="media-body">
                <div class="media-img" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="<?php echo $media['fileTitle'] ?>">
                  <img src="<?php echo base_url().$media['folder'].$media['fileName']; ?>" class="img-fluid">
                </div>
                  <div class="file-actions">

                    <button type="button" class="btn btn-primary editFile" data-id="<?php echo $media['id'] ?>"><i class="fa fa-info-circle"></i> Details</button>
                    <button type="button" class="btn btn-danger deleteFile" data-id="<?php echo $media['id'] ?>"><i class="fa fa-trash"></i> Delete</button>
                  </div>
              </div>
            </div>
          </a>
        </div>
        <?php
            }
          }else{
        ?>

        <div class="col-lg-12 p-5">
          <div class="alert alert-info"><strong>Ohh No!</strong><br>Media files not found...</div>
        </div>
        <?php
            }
        ?>
      </div>
  </div>
</div>