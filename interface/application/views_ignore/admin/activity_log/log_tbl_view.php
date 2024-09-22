<div class="table-responsive">
   <table class="table table-centered table-bordered table-striped dt-responsive nowrap w-100" id="organization_brnch_table">
      <thead>
         <tr>
            <th>S.No.</th>
            <th>IP Address</th>    
            <th>Type</th>
            <th>Username</th>
            <th>Action</th>
            <th>Module</th>
            <th>Description</th>      
            <th>Time</th>
         </tr>
      </thead>
      <tbody>
         <?php 
            if(is_array($data_list) && !empty($data_list)){
               $i=$pagination_data['slno'];
               foreach($data_list as $datas){
         ?>
            <tr>
               <td><?php echo $i ?></td>
               <td><?php echo $datas['ip_address'] ?></td>
               <td><?php echo $datas['refer_type'] ?></td>
               <td><?php echo $datas['reference_name'] ?></td>
               <td><?php echo $datas['action'] ?></td>
               <td><?php echo $datas['module'] ?></td>
               <td><?php echo $datas['description'] ?></td>
               <td><?php echo custom_date('d-M-Y h:i A',$datas['created_at']); ?></td>
            </tr>
         <?php $i++;} }else{ ?>
            <tr>
               <td colspan="8" class="text-center">No records found...</td>
            </tr>
         <?php } ?>
      </tbody>
   </table>
</div>
<div id="result">
   <?php if(isset($pagination_data['pagination_links'])){ echo $pagination_data['pagination_links'];} ?>
</div>