<?php
class Cart_model extends CI_Model
{


    function __construct()
    {
        // Set table name 
        $this->table = 'shopping_cart';
        $this->customer_table = 'customers';
        $this->product_table = 'products';
        $this->product_metrics_table = 'product_metrics';
        $this->lookups_table = 'lookups';
    }

    protected $in_field = ['name', 'email', 'phone', 'address'];

    function filter($filter_data, $item = 1, $page = 1, $sortby = '', $orderby = '', $all = true)
    {
        // print_R(['filter_data'=>$filter_data, 'item'=>$item, 'page'=>$page, 'sortby'=>$sortby, 'orderby'=>$orderby]);
        $search_field = array("$this->table.name", "$this->table.email", "$this->table.phone", "$this->table.address");
        if (($item == 0) && ($page == 0)) {
            $sql = "select count($this->table.id)  as countt "
                . "FROM $this->table 
					left join $this->lookups_table as status_table on $this->table.status = status_table.id 
					left join $this->customer_table as customer_table on $this->table.customerId = customer_table.id 
					left join $this->product_table as product_table on $this->table.productId = product_table.id 
                    left join $this->product_metrics_table as product_metrics_table on $this->table.metricsId = product_metrics_table.id 
					where ($this->table.id>0";
        } else {
            $sql = "select $this->table.id as cartId, $this->table.quantity as net_quantity, $this->table.quantity, $this->table.customerId as customerId, $this->table.productId as productId,
			status_table.l_value as status_name, status_table.color_name as status_color_name,
			product_table.*, product_metrics_table.id as metricsID,  product_metrics_table.productId as product_id,
            product_metrics_table.mrp as mrp, product_metrics_table.unit as unit, product_metrics_table.price as totalPrice, product_metrics_table.price as price,
            product_metrics_table.quantity as qty, product_metrics_table.CGST as CGST,
            product_metrics_table.SGST as SGST
			from $this->table
			left join $this->lookups_table as status_table on $this->table.status = status_table.id 
			left join $this->customer_table as customer_table on $this->table.customerId = customer_table.id 
            left join $this->product_metrics_table as product_metrics_table on $this->table.metricsId = product_metrics_table.id 
			INNER JOIN $this->product_table as product_table on $this->table.productId = product_table.id
			where ($this->table.id>0";
        }
        // GROUP_CONCAT(CONCAT(products_table.productCode)) as product_name
        if (!empty ($filter_data)) {
            foreach ($filter_data as $k => $v) {
                if (($v['type'] == 'search') && ($v['value'] != "")) {
                    $values = $v['value'];
                    array_walk($search_field, function (&$value, $key) use ($values) {
                        $value .= " like '%" . $values . "%'";
                    });

                    $sql .= ") AND (" . implode(" || ", $search_field);
                } else {

                    if ($v['value'] != "") {

                        if (in_array($v['type'], $this->in_field)) {
                            $v['type'] = $this->table . "." . $v['type'];
                        }

                        $sql .= ") AND ( " . $v['type'] . " ='" . $v['value'] . "'";
                    }

                }

            }
        }
        if (($item == 0) && ($page == 0)) {

            $sql .= ") group by $this->table.id , $this->table.metricsId order by $sortby  $orderby  ";
            $query = $this->db->query($sql);
            
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $count = $row['countt'];
            } else {
                $count = 0;
            }
            return $count;
        } else {

            $sql .= ")";
            $sql .= " group by $this->table.productId, $this->table.metricsId";

            if (!empty ($orderby)) {
                $sql .= " ORDER BY $sortby  $orderby ";
            }

            if (!$all) {
                $sql .= "limit $page,$item";
            }
        }
        $query = $this->db->query($sql);
        // log_message('debug', 'Get Total Cart');
        // log_message('debug', print_r($this->db->last_query(), true));
        return $query;
    }




    function get_cart_total($customerId)
    {
        $sql = "SELECT c.productId, p.comission_applicable, c.metricsId, pm.price AS price, c.quantity AS totalQuantity, IF(pm.CGST != '', pm.CGST, 0) + IF(pm.SGST != '', pm.SGST, 0) AS productTax, 
        (c.quantity * pm.price) AS totalPrice, 
        SUM(c.quantity * (pm.price + (pm.price * (IF(pm.CGST != '', pm.CGST, 0) + IF(pm.SGST != '', pm.SGST, 0)) / 100))) AS totalPriceWithTax 
        FROM shopping_cart c INNER JOIN product_metrics pm ON pm.id = c.metricsId INNER JOIN products p ON p.id = c.productId WHERE c.customerId = '$customerId' AND c.status = 1 GROUP BY c.productId, c.metricsId;";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            $productTotal = 0;
            $productTotalWithTax = 0;
            $repurchaseAbleAmount = 0;
            foreach ($res->result() as $netprice) {
                $productTotal += $netprice->totalPrice;
                $productTotalWithTax += $netprice->totalPriceWithTax;
                if($netprice->comission_applicable=='yes'){
                    $repurchaseAbleAmount += ($netprice->totalQuantity * $netprice->price);
                }
            }
            // actual Product Tax
            $productTax = $productTotalWithTax - $productTotal;
            $deliveryCharge = $productTotal>=375?0:40;

            // check if the customer has the wallet AMount and the wallet points
            $customerData = $this->db->get_where("customers", array('id'=>$customerId))->row_array();
            $deductedWalletPoints = 0;
            $deductedWalletAmount = 0;
            $productAmountAfterWalletPointsDeduction = $deliveryCharge;
            $productAmountAfterWalletAmountDeduction = 0;
            $walletAmount = 0;
            $walletPoints = 0;
            if(!empty($customerData)){
                $walletAmount = $customerData['subscriptionAmount'];
                $walletPoints = $customerData['subscriptionPoints'];
                $tenpercentOfTheProduct = ($productTotal*10)/100;
                $productAmountWPDeduction = 0;
                if($walletPoints>$tenpercentOfTheProduct){
                    $productAmountWPDeduction = $tenpercentOfTheProduct;
                }
                //discounted amount in the totalProductAmount
                $deductedWalletPoints = $productAmountWPDeduction; 
                // total amount of the product after the 10% discount if applicable
                $productAmountBeforeWalletPointsDeduction = ($productTotal+$deliveryCharge+$productTax); 
                $productAmountAfterWalletAmountDeductionWithoutWalletPoints = 0;
                $availableDeductWalletAmountWithoutWalletPointsDeduction = 0;
                if($walletAmount>=$productAmountBeforeWalletPointsDeduction){
                    $availableDeductWalletAmountWithoutWalletPointsDeduction = $productAmountBeforeWalletPointsDeduction;
                    $productAmountAfterWalletAmountDeductionWithoutWalletPoints = 0;
                }else{
                    $availableDeductWalletAmountWithoutWalletPointsDeduction = $walletAmount;
                    $productAmountAfterWalletAmountDeductionWithoutWalletPoints = $productAmountBeforeWalletPointsDeduction - $walletAmount;
                }

                $productAmountAfterWalletPointsDeduction = ($productTotal+$deliveryCharge+$productTax)-$productAmountWPDeduction;
                if($walletAmount>$productAmountAfterWalletPointsDeduction){
                    $productAmountWADeduction = $productAmountAfterWalletPointsDeduction;
                    $productAmountAfterWalletAmountDeduction = 0;
                }else{
                    $productAmountWADeduction = $walletAmount;
                    $productAmountAfterWalletAmountDeduction = $productAmountAfterWalletPointsDeduction - $walletAmount;
                }
                $deductedWalletAmount = $productAmountWADeduction;
            }
            $remainingWalletAmount = $customerData['subscriptionAmount']-$deductedWalletAmount;
            $remainingWalletPoints = $customerData['subscriptionPoints']-$deductedWalletPoints;
            $productTotalWithTaxAndDelivery = $productTotalWithTax+$deliveryCharge; 
            $totalWalletDeduction = $deductedWalletAmount + $deductedWalletPoints;
            $repurchaseAbleAmountAfterWalletPointsUsage = 0;
            if($deductedWalletPoints>0 && $repurchaseAbleAmount>0){
                $repurchaseAbleAmountAfterWalletPointsUsage -= (($repurchaseAbleAmount * 10)/100);
                // $repurchaseAbleAmountWithoutWalletPointDeduction = $repurchaseAbleAmount
            }
            return [
                'productTotalWithoutTaxAndDeliveryAndDiscounts' => $productTotal,
                'productTotalWithTaxAndDelivery' => $productTotalWithTax+$deliveryCharge,
                'productTax'=>$productTax,
                'deliveryCharge'=>$deliveryCharge,
                'walletPointsDiscounts'=> $deductedWalletPoints,
                'totalWithTaxAndDeliveryAndDiscount'=> ($productTotalWithTax+$deliveryCharge)-$deductedWalletPoints,
                'availableWalletAmountDeduction'=>$deductedWalletAmount,
                'remainingWalletAmount'=>$remainingWalletAmount,
                'remainingWalletPoints'=>$remainingWalletPoints,
                'totalWithoutWalletAmountDeduction'=>$productAmountAfterWalletPointsDeduction,
                'totalWithWalletAmountDeduction'=>$productAmountAfterWalletAmountDeduction,
                'repurchaseComission'=>$repurchaseAbleAmount,
                'repurchaseAbleAmountAfterWalletPointsUsage'=>$repurchaseAbleAmountAfterWalletPointsUsage,
                'availableDeductWalletAmountWithoutWalletPointsDeduction' => $availableDeductWalletAmountWithoutWalletPointsDeduction,
                'productAmountAfterWalletAmountDeductionWithoutWalletPoints' => $productAmountAfterWalletAmountDeductionWithoutWalletPoints
            ];
        } 
    } 

    function find_product_tax($productId, $price, $metricsId)
    {
        $sql = "SELECT CGST+SGST as taxpercent FROM product_metrics WHERE id='$metricsId'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            foreach ($res->result() as $tax) {
                $taxprice = $tax->taxpercent;

            }
            $totalprice = (($taxprice * $price) / 100) + $price;
        } else {
            $totalprice = 0;
        }
        return $totalprice;

    }

    function showcartquantity($uid)
    {
        $sql = "SELECT COUNT(*) AS quantity
                FROM `shopping_cart` 
                WHERE `customerId`='$uid' AND status=1";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $result = $row->quantity;
        } else {
            $result = 0;
        }
        return $result;
    }
}