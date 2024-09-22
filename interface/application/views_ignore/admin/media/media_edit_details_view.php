<?php
$fileId=$fileSize=$fileType=$folder=$fileName=$fileTitle=$createdDate=$fileUrl='';
if($fileQ['status']=='success' && !empty($fileQ['data_list'])){
  $fileId=$fileQ['data_list']['id'];
  $fileName=$fileQ['data_list']['fileName'];
  $fileTitle=$fileQ['data_list']['fileTitle'];
  $folder=$fileQ['data_list']['folder'];
  $fileSize=$fileQ['data_list']['fileSize'];
  $fileType=$fileQ['data_list']['fileType'];
  $createdDate=getMyDbDate("%d-%M-%Y %H:%i:%s",$fileQ['data_list']['createdDate']);
}else{
    echo '<div class="mediaEditModal">
            <div class="row">
                <div class="col-md-12 col-12"> 
                </div>
            </div>
        </div>';
        exit();
}
$fileUrl=$folder.$fileName;
?>
<div class="mediaEditModal">
      <div class="row">
          <div class="col-md-12 col-12">
              <form id="edit_media_form" role="form">
                <input type="hidden" name="fileId" value="<?php echo $fileId?>">
                <input type="hidden" name="oldName" value="<?php echo $fileName?>">
                <div class="form-group row">
                    <label class="col-3 col-form-label">File Name</label>
                    <div class="col-9">
                        <input class="form-control" type="text" name="fileName" value="<?php echo $fileName?>" data-edit="<?php echo $fileName?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Title <br>(Alt tag)</label>
                    <div class="col-9">
                        <input class="form-control" type="text" name="fileTitle" value="<?php echo $fileTitle?>">
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-3 col-form-label">Size</label>
                    <div class="col-9">
                        <p><?php echo $fileSize?> KB</p>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-3 col-form-label">Type</label>
                    <div class="col-9">
                        <p><?php echo $fileType?></p>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <label class="col-3 col-form-label">Uploaded Time</label>
                    <div class="col-9">
                        <p><?php echo $createdDate?></p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-3 col-form-label">Embed URL</label>
                    <div class="col-9 copyTextHolder">
                        <p id="copyText"><?php echo $fileUrl ?></p>
                        <span class="copyTextIcon" data-clipboard="" data-clipboard-target="#copyText"><i class="fa fa-clipboard"></i>Copy URL</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary waves-effect waves-light float-right">Update</button>
              </form>
          </div>
        </div>
</div>