<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Emails</title>
      <style type="text/css">
         /* Client-specific Styles */
         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
         body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing. */
         #backgroundTable {margin:0; padding:0; width:600px !important; line-height: 100% !important;}
         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
         a img {border:none;}
         .image_fix {display:block;}
         p {margin: 0px 0px !important;}
         table td {border-collapse: collapse;}
         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
         a {color: #33b9ff;text-decoration: none;text-decoration:none!important;}
         /*STYLES*/
         table[class=full] { width: 100%; clear: both; }
         /*IPAD STYLES*/
         @media only screen and (max-width: 640px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #0a8cce; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #0a8cce !important;
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 440px!important;text-align:center!important;}
         table[class=devicewidthmob] {width: 420px!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
         img[class=banner] {width: 440px!important;height:157px!important;}
         img[class=col2img] {width: 440px!important;height:330px!important;}
         table[class="cols3inner"] {width: 100px!important;}
         table[class="col3img"] {width: 131px!important;}
         img[class="col3img"] {width: 131px!important;height: 82px!important;}
         table[class='removeMobile']{width:10px!important;}
         img[class="blog"] {width: 420px!important;height: 162px!important;}
         }

         /*IPHONE STYLES*/
         @media only screen and (max-width: 480px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #0a8cce; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #0a8cce !important; 
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 280px!important;text-align:center!important;}
         table[class=devicewidthmob] {width: 260px!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
         img[class=banner] {width: 280px!important;height:100px!important;}
         img[class=col2img] {width: 280px!important;height:210px!important;}
         table[class="cols3inner"] {width: 260px!important;}
         img[class="col3img"] {width: 280px!important;height: 175px!important;}
         table[class="col3img"] {width: 280px!important;}
         img[class="blog"] {width: 260px!important;height: 100px!important;}
         td[class="padding-top-right15"]{padding:15px 15px 0 0 !important;}
         td[class="padding-right15"]{padding-right:15px !important;}
         }
      </style>
     <script type="colorScheme" class="swatch active">
         {
           "name":"Default",
           "bgBody":"d1d2d4",
           "link":"382F2E",
           "color":"967B76",
           "bgItem":"ffffff",
           "title":"382F2E"
         }
      </script>
   </head>
   <body>
  <!-- Start of preheader -->
   
<!-- Start of header -->
<table width="600" bgcolor="#eee" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
   <tbody>
    <tr>
      <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
    </tr>
      <tr>
         <td>
            <table width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table bgcolor="#020202" width="560" cellpadding="0" cellspacing="0" border="0" align="center" style="border-top-left-radius:0px;border-top-right-radius:0px;" class="devicewidth">
                           <tbody>
                       
                              <tr>
                                 <td>
                                    <!-- end of logo -->
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
<!-- Start of main-banner -->

<?php 
  $sett=$this->db->get_where('organization_branches')->row_array();
  $org=$this->db->get_where('organization')->row_array();
  $logo=$this->db->get_where('uploads',array('id'=>$org['logo']))->row_array();
  $logo_url='';
  
  if(!empty($logo)){
	 $logo_url=$logo['base_path'].$logo['file_path'].$logo['file_name'];
  }
?>

<table width="100%" bgcolor="#eee" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
   <tbody>
      <tr>
         <td>
            <table width="580" bgcolor="#fff" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="580" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 <td height="10"></td>
                              </tr> 
							  <?php if(!empty($logo_url)){ ?>
                              <tr>
                                 <!-- start of image -->
                                 <td align="center" st-image="banner-image">
                                
                                    <div class="imgpop">
                                     <p>
                                     <img width="30%" border="0" height="auto" alt="logo" style="display:block; border:none; outline:none; text-decoration:none;width: 120px;" src="<?php echo $logo_url ?>" class="bigimage"></p>
                                    </div>
                                 </td>
                              </tr>
							  <?php } ?>
                              <tr>
                                <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;border-bottom: solid 1px #ddd">&nbsp;</td>
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

<table width="100%" bgcolor="#eee" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
   <tbody>

      <tr>
         <td>
            <table width="580" bgcolor="#fff" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="560" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                           <tbody>
                                  <!-- Spacing -->

                              <tr>
                                 <td height="30"></td>
                              </tr>
                        <tr>
                           <td width="100%" >                           
                              <p style="font-weight:600;color: #271906; font-size: 15px; font-family: 'Open Sans', sans-serif;"> Dear&nbsp;<span><?php echo $to_name ?></span>,</p>
                           </td>
                        </tr>
                        <tr>
                           <td height="20"></td>
                        </tr>
                        <tr>
                           <td width="100%" >
                             <p style="padding: 0px 0px;color: #271906; font-size: 15px;line-height: 23px; font-family: 'Open Sans', sans-serif;font-weight: 400;"> 
                              <?php if(isset($description)){ echo $description;} ?>
                             </p>
                           </td> 
                        </tr>
                        
                        <tr>
                           <td height="30"></td>
                        </tr>
                      <!-- /Spacing --> 
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

<!-- Start of postfooter -->
<table width="100%" bgcolor="#eee" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader" >
   <tbody>
      <tr>
         <td>
            <table width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table bgcolor="#ffd700" width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td width="100%" height="10"></td>
                              </tr>
                              <!-- Spacing -->
								<tr>
                                <td align="center" valign="middle" style=" text-align:center;line-height: 18px" st-content="viewonline">
                                  <p style="font-size: 11px; margin-bottom: 5px; color: #000;  font-family: 'Open Sans', sans-serif;">
                                      <span style="color:#000;font-weight:bolder;">Phone:</span> <?php echo $sett['phone'] ?> |
                                      <span style="color:#000;font-weight:bolder;">Email:</span><a style="color: #000;text-decoration: none;" href="mailto:<?php echo $sett['email'] ?>"><?php echo $sett['email'] ?></a>
                                  </p> 
                                  <p style="font-size: 11px; margin-bottom: 5px; color: #000;  font-family: 'Open Sans', sans-serif;">
                                      <span style="color:#000;font-weight:bolder;">Address:</span> 
                                      <?php echo $sett['address'] ?>
                                  </p> 
                                </td>
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
    <tr>
   <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
    </tr>
</table>
<!-- End of postfooter -->
</body>
</html>