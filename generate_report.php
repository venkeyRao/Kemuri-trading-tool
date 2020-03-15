<?php

if(empty($_SESSION))
    session_start();

$referer = $_SERVER['HTTP_REFERER'];

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(empty($_POST["stock"]) || empty($_POST["start_date"]) || empty($_POST["end_date"])){
        $_SESSION['errors'] = 'Kindly Select Stock Name, Range Start and End Date.';
        header("Location: $referer");
        exit();
    } 

    if(empty(($_SESSION['stock_data']))){
        $_SESSION['errors'] = 'Kindly Upload a CSV file and click on Analyze before genrating any reports';
        header("Location: $referer");
        exit();
    }

    if(!empty($_SESSION['errors'])){
        $_SESSION['errors'] = null;
    }

    if(!empty(($_SESSION['success']) && !empty(($_SESSION['reports']))) ){
        $_SESSION['success'] = null;
        $_SESSION['reports'] = null;
    }

    $stock_prices = $_SESSION['stock_data'][$_POST['stock']];

    $reports = [];
    $reports['selected_stock'] = $_POST['stock'];
    
    asort($stock_prices);

    $reports['min_price_unfiltered'] = current($stock_prices);
    $reports['min_date_unfiltered'] = key($stock_prices);
    $reports['max_price_unfiltered'] = end($stock_prices);
    $reports['max_date_unfiltered'] = key($stock_prices);

    ksort($stock_prices); //sorting the price list by date 

    $closest_start_date = closest($_POST['start_date'], array_keys($stock_prices));
    $closest_end_date = closest($_POST['end_date'], array_keys($stock_prices));

    $filtered_data = remove_data_outside_rage($closest_start_date, $closest_end_date, $stock_prices);
    asort($filtered_data);
    if(!empty($filtered_data)){
        $reports['min_price_filtered'] = current($filtered_data);
        $reports['min_date_filtered'] = key($filtered_data);
        $reports['max_price_filtered'] = end($filtered_data);
        $reports['max_date_filtered'] = key($filtered_data);
    }else{
        $reports['min_price_filtered'] = null;
        $reports['max_price_filtered'] = null;  
    }
    
    $_SESSION['reports'] = $reports;
    header("Location: $referer");
    exit();
}


function closest($needle, $haystack) {
    return array_reduce($haystack, function($a, $b) use ($needle) {
        return abs($needle-$a) < abs($needle-$b) ? $a : $b;
    });
}

function remove_data_outside_rage($min, $max, $data){
    foreach($data as $key=>$value){
        if($key < $min || $key > $max){
            unset($data[$key]);
        }
    }
    return $data;
}

//array_key_first function available in PHP 7.3 
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

//array_key_last function available in PHP 7.3 
if (! function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
       
        return array_keys($array)[count($array)-1];
    }
}

?>