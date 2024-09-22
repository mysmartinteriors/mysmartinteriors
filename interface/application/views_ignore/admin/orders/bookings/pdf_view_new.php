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
	    $bill_no=$order_data['bill_no'];
	    $name=$order_data['name'];
	    $email=$order_data['email'];
	    $phone=$order_data['phone'];
	    $address=$order_data['address'];
	    $createdDate=$order_data['createdDate'];
	    $status=$order_data['status'];
	    $subtotal=$order_data['subtotal'];
	    $taxAmt=$order_data['tax'];
	    $total_amount=$order_data['total_amount'];
	    $createdDate=$order_data['createdDate'];
// 	}
}
// foreach ($settingQ->result() as $row) {
// 	$companyName=$row->companyName;
// 	$caption=$row->caption;
// 	$caddress=$row->address;
// 	$logo=$row->logo;
// 	$cphone=$row->phone;
// 	$CGSTIN=$row->GSTIN;
// 	$cmobile=$row->mobile;
// 	$cemail=$row->email;
// 	$website=$row->website;
// }
?>

<table>
	<tr style="text-align: center;">					
		<td>
			<img src="<?php echo base_url() ?>uploads/site/<?php //echo $logo ?>" style="height:40px;">	
			<?php //if($caption!=''){?><p style="font-size: 15px;font-weight: bold;line-height: 9px;color:#212529"><?php //echo $caption ?></p><?php //} ?>
		</td>
	</tr>
	<tr style="text-align: center;font-family: 'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif !important;">
		<td style="font-size: 9px;line-height: 7px;color:#212529">
			<p style="line-height: 0px;"></p>
			<p><?php //echo $caddress ?></p>
			<p>Ph: <?php //echo $cphone ?> | Email: <?php //echo $cemail ?> | Web: <?php //echo $website ?></p>
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
			<p style="line-height: 12px;"><strong style="font-size: 10px;"><?php //echo ucwords($companyName);?></strong><br><?php //if($caddress!=''){echo ucwords($caddress);}if($pincode!=''){echo '-'.$pincode;}?><br>Ph: <?php //echo $cphone;?></p>
            <p style="line-height: 8px;"><strong>GSTIN:</strong> <?php //echo strtoupper($CGSTIN);?></p>
			<p style="line-height: 0px;"></p>
		</td>
		<td style="width: 20%"></td>
		<td style="color:#212529;width: 30%;text-align: left;font-size: 9px;line-height: 5px;">
			<p style="line-height: 0px;"></p>
			<p style="line-height: 0px;"><strong>GSTIN</strong>: <?php echo $CGSTIN ?></p>
        	<p style="line-height: 10px;"><strong>Order No</strong>: <?php echo $code;?></p>
        	<p style="line-height: 7px;"><strong>Bill No</strong>: <?php echo $bill_no;?></p>
        	<p style="line-height: 9px;"><strong>Date</strong>: <?php echo $createdDate;?></p>
		</td>
	</tr>
	<tr><td style="line-height: 5px;">&nbsp;</td></tr>
</table>

<table style="width:100%;border: solid 0px #000;"  cellpadding="0" cellspacing="0">
	
	<tr style="color:#212529;font-size: 10px;vertical-align: middle;font-weight: bold; ">
		<th style="width:5%;border:solid 1px #000;border-top:0px solid #fff;line-height: 10px;text-align: center;vertical-align: middle;"><span style="line-height: 19px;"></span>Sl</th>
        <th style="width:45%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;vertical-align: middle;"><span style="line-height: 19px;"></span>Description</th>
        <th style="width:10%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Qty</th>
        <th style="width:15%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Rate</th>
        <th style="width:15%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Amount</th>
        <th style="width:10%;border:solid 1px #000;border-top:0px solid #fff;line-height: 5px;text-align: center;"><span style="line-height: 19px;"></span>Tax</th>		
	</tr>
	<?php
		$i=1;
		// if(!empty($product_details)){
			print_R($product_details);echo "<hr>";
		// 	foreach($productsQ->result() as $products){
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
        <td style="width:5%;border-right:solid 1px #000;line-height: 10px;text-align: center;"><?php echo $i?></td>
        <td style="width:45%;border-right:solid 1px #000;line-height: 12px;font-size:10px;word-break: break-all !important;">
        	<?php //echo string_teaser($products->productName,33); ?>
        </td>
        <td style="width:10%;border-right:solid 1px #000;line-height: 10px;text-align: right;"><?php //echo $products->qty ?>&nbsp;&nbsp;&nbsp;</td>
        <td style="width:15%;border-right:solid 1px #000;line-height: 10px;text-align: right;"><?php //echo $products->price ?>&nbsp;&nbsp;&nbsp;</td>

        <td style="width:15%;border-right:solid 1px #000;line-height: 10px;text-align: right;">
        	<?php 
        		// $amount=$products->qty*$products->price;
        		// echo $amount;
        	?>&nbsp;&nbsp;&nbsp;
        
        	</td>
        <td style="width:10%;border-right:solid 1px #000;line-height: 10px;text-align: right;"><?php //echo $products->tax;?>&nbsp;&nbsp;&nbsp;</td>
    </tr>
	<?php
		$i++;
	// 	}
	// }

		// $extraRows=0;
		// if($productsQ->num_rows()<=3){
		// 	$extraRows=7;
		// }else if($productsQ->num_rows()<=5){
		// 	$extraRows=3;
		// }else if($productsQ->num_rows()<=7){
		// 	$extraRows=2;
		// }else if($productsQ->num_rows()<=10){
		// 	$extraRows=0;
		// }else if($productsQ->num_rows()>10){
		// 	$extraRows=4;
		// }
		// if($extraRows!=0){
		// $j=1;
		// do{
	?>
	<tr style="font-family: 'sans-serif' !important;line-height: 30px;">
		<td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	    <td style="border-right:solid 1px #000;"></td>
	</tr>
	<?php
		// $j++;
		// }while ($j<=$extraRows);
		// }
	?>
</table>

<table>
	<tr><td style="line-height: 5px;">&nbsp;</td></tr>
	<tr style="font-family: Arial,sans-serif !important;color:#212529;">
		<td style="width:72%">
			<p style="line-height: 12px;font-size: 10px;">In Words: <span style="font-weight: bold"><?php //echo currencyWords($total_amount);?></span></p>
		</td>
		<td style="width: 28%;font-size: 10px;">
			<table style="line-height: 16px;">
				<tr>				
					<td style="width:50%;text-align: left;">
						<p>Sub Total:</p>
					</td>
					<td style="width:50%;text-align: right;">
						<p><?php //echo number_format((float)$subtotal, 2, '.', ''); ?></p>
					</td>
				</tr>
				<tr>				
					<td style="width:50%;text-align: left;">
						<p>GST Amt(+):</p>
					</td>
					<td style="width:50%;text-align: right;">
						<p><?php //echo number_format((float)$taxAmt, 2, '.', ''); ?></p>
					</td>
				</tr>
				<tr>				
					<td style="width:50%;text-align: left;">
						<p>Round Off:</p>
					</td>
					<td style="width:50%;text-align: right;">
						<p>
							<?php 
                        	// if($taxAmt+$subtotal==$total_amount){
                        	// 	echo "0.00";
                        	// }else{
                        	// 	$roundoff= $total_amount-($subtotal+$taxAmt);
                        	// 	echo number_format((float)$roundoff, 2, '.', '');
                        	// }
                        	?></p>
					</td>
				</tr>
				<tr style="font-weight: bold;">				
					<td style="width:50%;text-align: left;border-top:solid 1px #000;line-height: 22px;">
						<p>Grand Total:</p>
					</td>
					<td style="width:50%;text-align: right;border-top:solid 1px #000;line-height: 22px;">
						<p><?php //echo number_format((float)$total_amount, 2, '.', ''); ?></p>
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
    		<p style="line-height: 8px;">&nbsp;</p>
			<p style="line-height: 12px;">Received the above items in<br>Good condition and Accepted</p>
		</td>
		<td style="width: 30%;text-align: center;line-height: 3px;">
    		<p style="line-height: 3px;">&nbsp;</p>			
    		<p style="line-height: 3px;">For Gurudev Telecom</p>
    		<p style="line-height: 20px;">&nbsp;</p>
    		<p style="margin-bottom:0px;">Auth. Signatory/Manager</p>
		</td>
	</tr>
</table>