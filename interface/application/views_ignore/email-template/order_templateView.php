
 <table width="560" bgcolor="#eee" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="fullimage">
      <tbody>
         <tr>
            <td>
               <table bgcolor="#fff" width="580" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" modulebg="edit">
                  <tbody>
                     <tr>
                        <td width="100%" height="20"></td>
                     </tr>
                     <tr>
                        <td>
                           <table width="560" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
                              <tbody>
                                
                                <tr>
                                    <td width="100%" height="28"></td>
                                </tr>
								
				
								
								<tr>
                                    <td width="100%" height="10"></td>
                                </tr>
								
								<tr>
                                 <td width="100%" height="10"><p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; color: #0a1524; text-align:justify;line-height: 20px;">Thanks for using our 'Build To Order' rapid customisation tool. Our engineers will review your request and you will shortly receive a price quotation and a delivery schedule by email. This service is provided free and you need only respond if you have questions or wish to make a purchase. Here is a summary of your request:</p></td>
								</tr>
								
								<tr>
                                    <td width="100%" height="10"></td>
                                </tr>
                              
								
								
							 
                                
                                 <!-- Spacing -->
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
      </tbody>
   </table>
	
	<!-- Start of main-banner -->
 <table width="50%" bgcolor="#eee" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="fullimage">
      <tbody>
         <tr>
            <td>
<table  bgcolor="#eee" width="100%" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
   <tbody>
  
      <tr>
         <td>
            <table bgcolor="#fff" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table  align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 <!-- start of image -->
                                 <td align="center" st-image="banner-image">
                                
                                    <div class="imgpop">
                                    <p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; font-weight: 600;text-transform: capitalize;     color: #ffffff;
    background: #00487f;
    padding: 5px 10px; text-align:justify;line-height: 20px; margin-top: 10px;margin-bottom: 10px;">Request Information </p>
                                 
                                 <table class="table" style=" width: 560px; font-family: 'Open Sans', sans-serif;padding:0px 0px; font-size: 13px; color: #616363; text-align:left;line-height: 20px;text-transform: capitalize;">
<!--     <thead>
      <tr style=" text-align: left">
       
        <th>Lastname</th>
        <th>Email</th>
      </tr>
    </thead> -->
    <tbody>
	<?php if($order_query->num_rows()>0) {
	$row = $order_query->row();
	?>
      <tr>
    
        <td> Request Number:</td>
        <td><?php echo $row->ORDER_ID ?></td>
      </tr>
      <tr>
      
        <td>Request Date:</td>
        <td><?php echo date("l M j, Y", strtotime($row->CREATED_DATE) ); ?></td>
      </tr>

      <tr>
        <td>Request Status:</td>
        <td><?php if($row->STATUS==0) { echo "Request Submitted"; } ?></td>
      </tr>

	<?php } ?>
           

    </tbody>
  </table>

<p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; font-weight: 600;text-transform: capitalize;     color: #ffffff;
    background: #00487f;
    padding: 5px 10px; text-align:justify;line-height: 20px; margin-top: 10px;margin-bottom: 10px;">Customer Information </p>
       <table class="table" style=" width: 560px; font-family: 'Open Sans', sans-serif;padding:0px 0px; font-size: 13px; color: #616363; text-align:left;line-height: 20px;text-transform: capitalize;">

    <tbody>
    <?php  $get_userdata = $this->usermodel->get_user_details($userId);	?>
      <tr>
      
        <td>Email:</td>
        <td><?php echo $get_userdata['EMAIL']; ?></td>
      </tr>

      <tr>
        <td>Company: </td>
        <td><?php echo $get_userdata['COMPANY_NAME']; ?></td>
      </tr>

       <tr>
        <td>First Name: </td>
        <td><?php echo $get_userdata['FIRST_NAME']; ?></td>
      </tr>
         <tr>
        <td>Last Name: </td>
        <td><?php echo $get_userdata['LAST_NAME']; ?></td>
      </tr>


        <tr>
        <td>Address: </td>
        <td> <?php echo $get_userdata['ADDRESS']; ?> </td>
      </tr>

        <tr>
        <td>City: </td>
        <td><?php echo $get_userdata['CITY']; ?> </td>
      </tr>

        <tr>
        <td>Zip / Postal Code: </td>
        <td> <?php echo $get_userdata['ZIP_CODE']; ?> </td>
      </tr>


        <tr>
        <td>Country: </td>
        <td><?php

	$where = array('countryId'=>$get_userdata['COUNTRY_ID']);
			$get_data = $this->Mydb->get_table_data('countries',$where);
			$get_data = array_shift($get_data);
			echo $emaildata['country'] = $get_data['name'];		
		?> </td>
      </tr>

       <tr>
        <td>Phone: </td>
        <td><?php echo $get_userdata['PHONE']; ?> </td>
      </tr>

    </tbody>
  </table>


<?php if($order_query->num_rows()>0) {
	$row = $order_query->row();
	?>
<p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; font-weight: 600;text-transform: capitalize;     color: #ffffff;
    background: #00487f;
    padding: 5px 10px; text-align:justify;line-height: 20px; margin-top: 10px;margin-bottom: 10px;">Requested Items </p>
       <table class="table" style="width: 560px; font-family: 'Open Sans', sans-serif;padding:0px 0px; font-size: 13px; color: #616363; text-align:left;line-height: 20px;text-transform: capitalize;">

    <tbody>
      <tr>
    
        <td> MCU Manufacturer:</td>
        <td><?php echo $row->MANU_NAME ?></td>
      </tr>
      <tr>
      
        <td>MCU Family:</td>
        <td><?php echo $row->MCU_FN ?></td>
      </tr>

      <tr>
        <td>MCU: </td>
        <td><?php echo $row->MCU_NAME ?></td>
      </tr>

       <tr>
        <td>Development Board: </td>
        <td><?php  if($row->DB_NAME == null){ echo '-Not Listed-' ; }else { echo $row->DB_NAME; } ?></td>
      </tr>
         <tr>
        <td>Development Tools: </td>
        <td><?php  if($row->DT_NAME == null){ echo '-Not Listed-' ; }else { echo $row->DT_NAME; } ?></td>
      </tr>

        <tr>
        <td>RTOS: </td>
        <td><?php  if($row->RTOS_NAME == null){ echo '-Not Listed-' ; }else { echo $row->RTOS_NAME; } ?></td>
      </tr>





    </tbody>
  </table>
<?php } ?>
<br>

<?php if($order_prd_query->num_rows()>0) { ?>
       <table class="table" style="width: 560px; font-family: 'Open Sans', sans-serif;padding:0px 0px; font-size: 13px; color: #616363; text-align:left;line-height: 20px;text-transform: capitalize;    border: 1px solid #ddd;">

    <tbody>
       <thead>
      <tr style=" text-align: left;  border: 1px solid #ddd;  ">
          <th style="border: 1px solid #ddd; ">Category</th>
        <th style="border: 1px solid #ddd; ">Item</th>
        <th style="border: 1px solid #ddd; ">SKU</th>
      </tr>
    </thead> 
	  <?php 
		foreach($order_prd_query->result() as $pq){
	  ?>  <tr style=" text-align: left;  border: 1px solid #ddd;  ">
	   <td style="border: 1px solid #ddd; "><?php echo $pq->PR_FAMILY ?></td>
		 <td  style="border: 1px solid #ddd; "><?php echo $pq->PR_SUB_FAMILY ?></td>
		<td  style="border: 1px solid #ddd; "><?php echo $pq->PR_CODE ?></td>
	  </tr>
		<?php } ?>
     
      </tbody>
  </table>
 <?php } ?>

	<!-- Start of main-banner -->
  <table class="table" style="width: 560px; font-family: 'Open Sans', sans-serif;padding:0px 0px; font-size: 13px; color: #616363; text-align:left;line-height: 20px;"	>
      <tbody>
         <tr>
            <td>
               <table bgcolor="#fff" width="580" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" modulebg="edit">
                  <tbody>
                     <tr>
                        <td width="100%" height="20"></td>
                     </tr>
                     <tr>
                        <td>
                           <table width="560" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
                              <tbody>
                                 <!-- title -->
                         <tr>
                                 <td width="100%" height="10"><p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; color: #0a1524; text-align:justify;line-height: 20px;">Part number and interface type:</p></td>
								<td><?php echo $row->PART_NO ?></td>
								</tr>
						 
			
								
							
							  
								<tr>
                                 <td width="100%" height="10"><p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; color: #0a1524; text-align:justify;line-height: 20px;">Customer's note:</p></td>
								<td><?php echo $row->TECH_NOTES ?></td>
								</tr>
  <tr>
                                 <td width="100%" height="10"><p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; color: #0a1524; text-align:justify;line-height: 20px;">  BTO Purpose:</p></td>
								 <td></td>
                </tr>



                                 <!-- end of content -->
                                <tr>
                                    <td width="100%" height="28"></td>
                                 </tr>

                                <tr>
                                 <td>
                                  <p style="font-family: Helvetica, arial, sans-serif; padding:0px 0px;font-size: 15px; color: #616363; text-align:justify;line-height: 20px;">Thank you,<br>
                                     <span style="color:#616363">Team</span> hcc-embedded</p>
                                 </td>
                              </tr>

                                 <!-- Spacing -->
                                 <tr>
                                    <td width="100%" height="30"></td>
                                 </tr>
                     
                                 <!-- Spacing -->
                                
                                 <!-- Spacing -->
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
      </tbody>
   </table>


                                    </div>
                                 </td>
                              </tr>
                               

                              <tr>
                                             <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                    </tr> 
                              

                           </tbody>
                        </table>
                        <!-- end of image -->
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
</td>
</tr>
   </tbody>
</table>
 <!-- end of banner -->




