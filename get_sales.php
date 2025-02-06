<?php
require_once './functions.php';
require_once $classes_url . 'Order.php';

$o = new Order();
$o -> get();
$sales = $o -> feedback;

if(isset($_POST['year'])){
    foreach($sales as $key => $sale){
        if(date('Y', strtotime($sale['date'])) != $_POST['year']){
            unset($sales[$key]);
        }
    }
}

if(isset($_POST['month'])){
    foreach($sales as $key => $sale){
        if(date('m', strtotime($sale['date'])) != $_POST['month']){
            unset($sales[$key]);
        }
    }
}

$dates = array();
foreach($sales as $sale){
    $dates[] = date('Y-m-d', strtotime($sale['date']));
}
$dates = array_unique($dates);

foreach($dates as $date){
    echo '<div class="sale">';
    echo "<h3>$date</h3>";
    require './sales.php';
    echo '</div>';
}

?>