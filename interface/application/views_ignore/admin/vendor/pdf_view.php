<table>
	<tr style="text-align: center;">					
		<td>
			<img src="<?php echo base_url() ?>ui/frontend/images/logo.png" style="height:40px;">	
			<?php //if($caption!=''){?><p style="font-size: 15px;font-weight: bold;line-height: 9px;color:#212529"><?php //echo $caption ?></p><?php //} ?>
		</td>
	</tr>
	<tr style="text-align: center;font-family: 'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif !important;">
		<td style="font-size: 9px;line-height: 7px;color:#212529">
			<p style="line-height: 0px;"></p>
			<p><?php //echo $caddress ?></p>
			<p>Ph: +91-87624 53738 | Email: nalaaorganic@gmail.com </p>
			<!-- | Web: <?php //echo $website ?> -->
			<p style="line-height: 0px;"></p>
		</td>
	</tr>
</table>

<table>
	<tr style="font-family: calibri !important;">
		<td style="color:#212529;width: 50%;text-align: left;font-size: 9px;line-height: 5px;">
			<p style="line-height: 0px;"></p> 
			<p style="line-height: 0px;">DC To,</p>
			<p style="line-height: 9px;"><?php echo $vendor_challan_history['vendor_name'] ?></p>
			<!-- <p style="line-height: 12px;"><strong style="font-size: 10px;"><?php //echo $order_data['deliveryAddress'];?></strong></p> -->
			<p style="line-height: 9px;"><?php //echo $phone ?></p>

            <!-- <p style="line-height: 8px;"><strong>GSTIN:</strong> <?php //echo strtoupper($CGSTIN);?></p> -->
			<p style="line-height: 0px;"></p>
		</td>
		<td style="width: 20%"></td>
		<td style="color:#212529;width: 30%;text-align: left;font-size: 9px;line-height: 5px;">
			<p style="line-height: 0px;"></p>
			<!-- <p style="line-height: 0px;"><strong>GSTIN</strong>: <?php //echo $CGSTIN ?></p> -->
        	<p style="line-height: 10px;"><strong>CHALLAN ID</strong>: <?php echo $vendor_challan_history['unique_id'];?></p>
        	<!-- <p style="line-height: 7px;"><strong>Bill No</strong>: <?php //echo $bill_no;?></p> -->
			
        	<p style="line-height: 9px;"><strong>Date</strong>: <?php echo $vendor_challan_history['created_at'];?></p>
		</td>
	</tr>
	<tr><td style="line-height: 5px;">&nbsp;</td></tr>
</table>

<table style="width:100%;border: solid 0px #000;"  cellpadding="0" cellspacing="0">
	
	<tr style="color:#212529;font-size: 10px;vertical-align: middle;font-weight: bold; ">
		<th style="width:5%;border:solid 1px #000;border-top:0px solid #fff;line-height: 10px;text-align: center;vertical-align: middle;"><span style="line-height: 19px;"></span>Sl. No.</th>
        <th style="width:55%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;vertical-align: middle;"><span style="line-height: 19px;"></span>Item Description</th>
        <th style="width:20%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Quantity</th>
        <th style="width:20%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Unit</th>
	</tr>
	<?php
		$i=1;
		if(!empty($vendor_challan_history['products'])){
			foreach($vendor_challan_history['products'] as $product){ ?>
        <tr>
            <td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
            <td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
            <td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
            <td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
        </tr>
        <tr style="color:#212529;font-size: 10px;font-family: 'sans-serif' !important;">
            <td style="width:5%;border-right:solid 1px #000;line-height: 5px;text-align: center;"><?php echo $i?></td>
            <td style="width:55%;border-right:solid 1px #000;line-height: 10px;font-size:10px;word-break: break-all !important; text-align: center"><?php echo $product['product_name']?></td>
            <td style="width:20%;border-right:solid 1px #000;line-height: 5px;text-align: center;"><?php echo $product['product_quantity'] ?>&nbsp;&nbsp;&nbsp;</td>
            <td style="width:20%;border-right:solid 1px #000;line-height: 5px;text-align: center;"><?php echo $product['product_unit'] ?>&nbsp;&nbsp;&nbsp;</td>
        </tr>
	<?php $i++; } } ?>
        <tr style="font-family: 'sans-serif' !important;line-height: 30px;">
            <td style="border-right:solid 1px #000;"></td>
            <td style="border-right:solid 1px #000;"></td>
            <td style="border-right:solid 1px #000;"></td>
            <td style="border-right:solid 1px #000;"></td>
        </tr>
</table>


<table>
	<tr>
		<td style="border-top: solid 1px #000;line-height: 5px;width: 100%;"></td>
	</tr>
	<tr style="font-size: 10px;color:#212529;line-height: 5px;">
		<td style="width: 70%;text-align: left;line-height: 5px;">
			<p style="line-height: 12px;"></p>
		</td>
		<td style="width: 30%;text-align: center;line-height: 3px;">	
    		<p style="line-height: 3px;">Digitally Signed By</p>
    		<p style="line-height: 3px;">&nbsp;</p>
    		<p>NALAA ORGANIC</p>
    		<p style="line-height: 3px;">&nbsp;</p>
    		<p>Machonayakanahalli, Nelamangala, <br><br><br><br>Bengaluru, Karnataka-562 123 </p>
		</td>
	</tr>
</table>