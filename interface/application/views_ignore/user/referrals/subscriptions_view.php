<?php?>
<div class="row">
    <div class="col-12">
        <?php //print_R($subscriptions); echo "<hr>"; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Sl.No</td>
                    <td>Subscription Name</td>
                    <td>Subscription Amount</td>
                    <td>Subscription Wallet Points</td>
                    <td>Purchased ON</td>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($subscriptions as $subscription){ ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $subscription['subscription_name'] ?></td>
                        <td><?php echo $subscription['subscription_base_amount'] ?></td>
                        <td><?php echo $subscription['subscription_wallet_points'] ?></td>
                        <td><?php echo $subscription['createdDate'] ?></td>
                    </tr>
                <?php $i++;} ?>
            </tbody>
        </table>
    </div>
</div>