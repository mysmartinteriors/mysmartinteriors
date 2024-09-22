	<script type="text/javascript">	
	var urljs="<?php echo base_url()?>";
	var url="<?php echo site_url()?>";

	</script>
    <!-- CORE PLUGINS-->
    <script src="<?php echo base_url(); ?>ui/frontend/js/main.min.js"></script> 
    
    <script src="<?php echo base_url(); ?>ui/frontend/js/plugins.min.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>ui/frontend/js/toastr.min.js"></script>
    <script src="<?php echo base_url(); ?>ui/assets/plugins/bootstrap-sweetalert/dist/sweetalert.min.js"></script>

    <script src="<?php echo base_url(); ?>ui/assets/plugins/bootbox/bootbox.min.js"></script>
    
    <script src="<?php echo base_url()?>ui/assets/plugins/rateit/jquery.rateit.min.js"></script>
      <!-- Main JS File -->
    <script src="<?php echo base_url(); ?>ui/frontend/js/init_common.js"></script>
    <script src="<?php echo base_url(); ?>ui/frontend/js/cart.js"></script>
    <script src="<?php echo base_url(); ?>ui/frontend/js/products.js"></script> 
    <script src="<?php echo base_url(); ?>ui/frontend/js/user.js"></script>
    <!-- PAGE LEVEL SCRIPTS-->
<script type="text/javascript">
jQuery(function($) {
   var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
   $('.header-bottom.sticky-header ul.menu li a').each(function() { 
      if (this.href === path) {
          $(this).parent().closest("li").addClass('active');
      }
   });
});

// $(".card").on("click", function(){
//   var mappingId = $(this).attr('data-id');
//     $.post(urljs + "subscription/payment", { 'id': mappingId }, function (data) {
//       if(data.status == 'success'){
//         $(".show").removeClass();
//         swal_alert(data.status, data.message, 'success', '');
//       }else{
//         swal_alert(data.status, data.message, 'fail', '');
//       }
//     }, "json");
// })
</script>