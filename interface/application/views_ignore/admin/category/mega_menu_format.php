  <?php
  foreach($mega_menu_rows as $rows){
	  $datas = $this->categorymodel->get_mega_menu_column($rows['mm_row']);
  ?>
  <div class="mega-row ui-sortable" data-available-cols="12" data-used-cols="3">
   <div class="mega-row-header">
      <div class="mega-row-actions">  
         <span class="dashicons dashicons-sort"></span> 
         <!-- <span class="dashicons dashicons-admin-generic"></span> -->
         <span class="dashicons dashicons-trash"></span>
      </div>
      <div class="mega-row-settings">
         <input name="mega-hide-on-mobile" type="hidden" value="false">            
         <input name="mega-hide-on-desktop" type="hidden" value="false">            
         <div class="mega-settings-row">                
            <label>Row class</label>                
            <input class="mega-row-class" type="text" value="">            
         </div>
         <div class="mega-settings-row">
            <label>Row columns</label>                
            <select class="mega-row-columns">
               <option value="1" >1 column</option>
               <option value="2">2 columns</option>
               <option value="3" >3 columns</option>
               <option value="4" >4 columns</option>
               <option value="5" >5 columns</option>
               <option value="6">6 columns</option>
               <option value="7" >7 columns</option>
               <option value="8">8 columns</option>
               <option value="9" >9 columns</option>
               <option value="10">10 columns</option>
               <option value="11" >11 columns</option>
               <option value="12" selected>12 columns</option>
            </select>
         </div>
         <button class="button button-primary mega-save-row-settings" type="submit">Save</button>        
      </div>
      <button class="btn btn-success btn-sm mega-add-column">
         <span class="dashicons dashicons-plus"></span>Column
      </button>    
   </div>
   <div class="error notice is-dismissible mega-too-many-cols">
      <p>You should rearrange the content of this row so that all columns fit onto a single line.</p>
   </div>
   <div class="error notice is-dismissible mega-row-is-full" style="display: none;">
      <p>There is not enough space on this row to add a new column.</p>
   </div>   
   
   <?php   
   foreach($datas as $r){
   ?> 
   
   <div class="mega-col" data-span="<?php echo $r['span'] ?>" data-total-blocks="<?=count($r['items'])?>">
      <div class="mega-col-wrap">
         <div class="mega-col-header">
            <div class="mega-col-description">                
               <span class="dashicons dashicons-move ui-sortable-handle"></span>           
               <span class="dashicons dashicons-trash"></span>            
            </div>
            <div class="mega-col-actions">                
               <a class="mega-col-option mega-col-contract" title="Contract">
                  <span class="dashicons dashicons-arrow-left-alt2"></span>
               </a>                
               <span class="mega-col-cols">
                  <span class="mega-num-cols"><?php echo $r['span'] ?></span>
                  <span class="mega-of">/</span>
                  <span class="mega-num-total-cols"><?php echo $r['mm_column'] ?></span>
               </span>                
               <a class="mega-col-options mega-col-expand" title="Expand">
                  <span class="dashicons dashicons-arrow-right-alt2"></span>
               </a>            
            </div>
         </div>
         <div class="mega-col-settings">            
            <input name="mega-hide-on-mobile" type="hidden" value="false">            
            <input name="mega-hide-on-desktop" type="hidden" value="false">            
            <label>Column class</label>            
            <input class="mega-column-class" type="text" value="">            
            <button class="button button-primary mega-save-column-settings" type="submit">Save</button>        
         </div>
         <div class="mega-col-widgets ui-sortable">
			<?php foreach($r['items'] as $s) { ?>
            <div class="widget" title="<?=$s['text'] ?>" id="<?=$s['id'] ?>" data-type="item" data-id="<?=$s['id'] ?>">
               <div class="widget-top ui-sortable-handle">
                  <div class="widget-title">
                     <h4><?=$s['text'] ?></h4>                          
                  </div>
                  <div class="widget-title-action">            
                     <a class="widget-option widget-action" title="Edit"></a>        
                  </div>
               </div>
               <div class="widget-inner widget-inside"></div>
            </div>            
			<?php } ?>
         </div>
      </div>
   </div>
   
   <?php } ?>
   </div>
  <?php } ?>
<button class="btn btn-sm btn-success mega-add-row"><span class="dashicons dashicons-plus"></span>Row</button>