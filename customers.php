<?php
require_once './session.php';
require_once $classes_url . 'Customer.php';

$c = new Customer();
$c -> get();
$customers = $c -> feedback;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    <title>DELI | ADMIN</title>
</head>
<body>
    <header><?php require './header.php';?></header>
    <main>
        <div class="customers">
            <?php
                if(count($customers) > 0){
                    require './customers_table.php';
                }
                else
                    echo '<div class="alert alert-danger">No customers found</div>';
            ?>
        </div>
    </main>
    <footer><?php require './footer.php';?></footer>
    <script src="../static/js/jquery.min.js"></script>
    <script src="../static/js/bootstrap.min.js"></script>
    <script src="../static/js/script.js"></script>
</body>
</html>