<?php
// require_once dirname(__DIR__) . '/static/classes/Customer.php';
// require_once dirname(__DIR__) . '/static/classes/Order.php';
?>

<table class="table table-stripped" id="customers">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($customers as $customer) : ?>
            <tr>
                <td><?php echo $customer['name']; ?></td>
                <td><?php echo $customer['phone']; ?></td>
                <td><?php echo $customer['email']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>