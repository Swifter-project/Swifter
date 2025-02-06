<?php
require_once './session.php';
require_once $classes_url . 'Order.php';

$o = new Order();
$o->getAllDate();
$dates = $o->feedback;

$dates = array_unique($dates);

$years = [];

foreach ($dates as $date) {
    $years[] = date('Y', strtotime($date));
}
$years = array_unique($years);
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
    <style>
        .action {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            justify-content: center;
            flex-direction: row;
            width: 100%;
            /* border: 2px solid #ccc; */
            font-size: 20px;
        }
    </style>
</head>

<body>
    <header><?php require './header.php'; ?></header>
    <main>
        <div class="action">
            <div class="mb-3">
                <select name="year" id="year" class="form-control">
                    <option value="<?= date('Y') ?>">Select Year</option>
                    <?php foreach ($years as $year) : ?>
                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <select name="month" id="month" class="form-control" style="margin-left: 20px">
                    <option value="">Select Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
        </div>
        <div class="daily-sales">
            <?php
            if (count($dates) > 0) {
                foreach ($dates as $date) {
                    echo '<div class="sale">';
                    echo "<h3>$date</h3>";
                    require './sales.php';
                    echo '</div>';
                }
            } else
                echo '<div class="alert alert-danger">No sales found</div>';
            ?>
        </div>
    </main>
    <footer><?php require './footer.php'; ?></footer>
    <script src="../static/js/jquery.min.js"></script>
    <script src="../static/js/bootstrap.min.js"></script>
    <script src="../static/js/script.js"></script>
    <script>
        $(document).ready(function() {
            $('#year').on('input', () => {
                let year = $('#year').val();
                $.ajax({
                    url: './get_sales.php',
                    type: 'POST',
                    data: {
                        year: year
                    },
                    success: (data) => {
                        $('.daily-sales').html(data);
                    }
                });
            });
            $('#month').change(function() {
                var year = $('#year').val();
                var month = $(this).val();
                $.ajax({
                    url: './get_sales.php',
                    type: 'POST',
                    data: {
                        year: year,
                        month: month
                    },
                    success: (data) => {
                        $('.daily-sales').html(data);
                    }
                });
            });
        });
    </script>
</body>

</html>