<?php
require_once './functions.php';
require_once $classes_url . 'Customer.php';
require_once $classes_url . 'Order.php';

$customer = new Customer();

$order_sales = new Order();
$order_sales -> date = $date;
$order_sales -> getByDate();

$sales = $order_sales -> feedback;
?>
<table class="table table-stripped table-hover">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Transaction Code</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($sales as $sale):?>
        <?php
            $customer -> email = $sale['customer'];
            $customer -> getByEmail();
            $sale['customer_name'] = $customer -> feedback['name'];
        ?>
        <tr>
            <td><?php echo $sale['id'];?></td>
            <td><?php echo $sale['customer_name'];?></td>
            <td><?php echo $sale['status'];?></td>
            <td><?php echo $sale['amount'];?></td>
            <td><?php echo $sale['transaction'];?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>