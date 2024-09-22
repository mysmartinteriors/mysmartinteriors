<?php 
$bill_no="";
$createdDate="";
$orderId="";
$customerId="";
$code="";
$name="";
$email="";
$phone="";
$address="";
$status="";
$gtotal="";
$sub_total='';
$taxAmt='';
$total_amount='';
$companyName='';
$caddress='';
$pincode='';
$cphone='';
$CGSTIN='';

if(!empty($order_data)){
	// foreach ($detailsQ->result() as $userRow){
	    $orderId=$order_data['id'];
	    $customerId=$order_data['customerId'];
	    $code=$order_data['orderId'];
	    $bill_no=!empty($order_data['bill_no'])?$order_data['bill_no']:'';
	    $name=!empty($order_data['name'])?$order_data['name']:'';
	    $email=$order_data['email'];
	    $phone=$order_data['phone'];
	    $address=$order_data['address'];
	    $createdDate=$order_data['createdDate'];
	    $status=$order_data['status'];
	    $subtotal=!empty($order_data['subtotal'])?$order_data['subtotal']:'';
	    $taxAmt=!empty($order_data['tax'])?$order_data['tax']:'';
	    $total_amount=!empty($order_data['total_amount'])?$order_data['total_amount']:'';
	    $createdDate=$order_data['createdDate'];
		$newDateTime = date('d-m-yy h:i A', strtotime($createdDate));
}
?>

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
			<p>Ph: +91-87624 53738 | Email: nalaaorganics@gmail.com </p>
			<!-- | Web: <?php //echo $website ?> -->
			<p style="line-height: 0px;"></p>
		</td>
	</tr>
</table>

<table>
	<tr style="background-color: #000000;">		
		<td style="width:100%;text-align: center;">
			<p style="line-height: 20px;font-size: 13px;font-weight: bold;color: #fff;">TAX INVOICE</p>
		</td>
	</tr>
</table>

<table>
	<tr style="font-family: calibri !important;">
		<td style="color:#212529;width: 50%;text-align: left;font-size: 9px;line-height: 5px;">
			<p style="line-height: 0px;"></p>
			<p style="line-height: 0px;">Biiled To,</p>
			<p style="line-height: 12px;"><strong style="font-size: 10px;"><?php echo $order_data['deliveryAddress'];?></strong><br><?php //if($caddress!=''){echo ucwords($caddress);}if($pincode!=''){echo '-'.$pincode;}?><br>Ph: <?php echo $phone;?></p>
			<p style="line-height: 0px;"></p>
		</td>
		<td style="width: 20%"></td>
		<td style="color:#212529;width: 30%;text-align: left;font-size: 9px;line-height: 5px;">
			<p style="line-height: 0px;"></p>
        	<p style="line-height: 10px;"><strong>Order ID</strong>: <?php echo $code;?></p>
			
        	<p style="line-height: 9px;"><strong>Date</strong>: <?php echo $newDateTime;?></p>
		</td>
	</tr>
	<tr><td style="line-height: 5px;">&nbsp;</td></tr>
</table>

<table style="width:100%;border: solid 0px #000;"  cellpadding="0" cellspacing="0">
	
	<tr style="color:#212529;font-size: 10px;vertical-align: middle;font-weight: bold; ">
		<th style="width:5%;border:solid 1px #000;border-top:0px solid #fff;line-height: 10px;text-align: center;vertical-align: middle;"><span style="line-height: 19px;"></span>Sl. No.</th>
        <th style="width:45%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;vertical-align: middle;"><span style="line-height: 19px;"></span>Item Description</th>
        <th style="width:10%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Quantity</th>
        <th style="width:15%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Product Rate</th>
        <th style="width:15%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Tax Amount</th>
        <th style="width:10%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Total Amount</th>		
	</tr>
	<?php
		$i=1;
		if(!empty($order_data['order_details'])){
			foreach($order_data['order_details'] as $order_detail){
	?>
	<tr>
		<td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
		<td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
		<td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
		<td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
		<td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
		<td style="line-height: 15px;border-right:solid 1px #000;">&nbsp;</td>
	</tr>
	<tr style="color:#212529;font-size: 10px;font-family: 'sans-serif' !important;">
        <td style="width:5%;border-right:solid 1px #000;line-height: 5px;text-align: center;"><?php echo $i?></td>
        <td style="width:45%;border-right:solid 1px #000;line-height: 10px;font-size:10px;word-break: break-all !important;">
        	<?php echo $order_detail['product_name'] . " - " . $order_detail['product_quantity'] . " " . $order_detail['product_unit'] ?>
        </td>
        <td style="width:10%;border-right:solid 1px #000;line-height: 5px;text-align: center;"><?php echo $order_detail['count'] ?>&nbsp;&nbsp;&nbsp;</td>
        <td style="width:15%;border-right:solid 1px #000;line-height: 5px;text-align: right;"><?php echo "Rs. " . $order_detail['product_price'] ?>&nbsp;&nbsp;&nbsp;</td>
        <td style="width:15%;border-right:solid 1px #000;line-height: 5px;text-align: right;"><?php echo "Rs. " . number_format($order_detail['gst_amount'], 2) ?>&nbsp;&nbsp;&nbsp;</td>

        <td style="width:10%;border-right:solid 1px #000;line-height: 10px;text-align: right;"><?php echo "Rs. ". $order_detail['totalAmount'];?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
	<?php
		$i++;
		}
	}
	?>
	<tr style="font-family: 'sans-serif' !important;line-height: 30px;">
		<td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	</tr>
</table>

<table>
	<tr><td style="line-height: 5px;">&nbsp;</td></tr>
	<tr style="font-family: Arial,sans-serif !important;color:#212529;">
		<td style="width:72%">
			<p style="line-height: 12px;font-size: 10px;">In Words: <span style="font-weight: bold"><?php echo currencyWords($order_data['actualAmountToPay']);?></span></p>
		</td>
		<td style="width: 28%;font-size: 10px;">
			<table style="line-height: 16px;">
				<tr>				
					<td style="width:50%;text-align: left;">
						<p>Sub Total:</p>
					</td>
					<td style="width:50%;text-align: right;">
						<!-- <p><?php //echo "Rs. ". $order_data['amountWithoutTaxAndDelivery'] ?></p> -->
						<p><?php echo "Rs. ". $order_data['totalAmount'] ?></p>

					</td>
				</tr>
				<tr>				
					<td style="width:50%;text-align: left;">
						<p>Tax Amount(+):</p>
					</td>
					<td style="width:50%;text-align: right;">
						<p><?php echo "Rs. ". $order_data['taxAmount'] ?></p>
					</td>
				</tr>
				<tr>				
					<td style="width:50%;text-align: left;">
						<p>Delivery Charge(+):</p>
					</td>
					<td style="width:50%;text-align: right;">
						<p><?php echo "Rs. ". $order_data['deliveryCharge'] ?></p>
					</td>
				</tr>
				<tr>				
					<td style="width:50%;text-align: left;">
						<p>Discounts(-):</p>
					</td>
					<td style="width:50%;text-align: right;">
						<p><?php echo "Rs. ". $order_data['deductedSubscriptionWalletPointsAmount'] ?></p>
					</td>
				</tr>
				<tr style="font-weight: bold;">				
					<td style="width:50%;text-align: left;border-top:solid 1px #000;line-height: 22px;">
						<p>Grand Total:</p>
					</td>
					<td style="width:50%;text-align: right;border-top:solid 1px #000;line-height: 22px;">
						<p><?php echo "Rs. ". $order_data['actualAmountToPay'] ?></p>
					</td>
				</tr>
			</table>
		</td>
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