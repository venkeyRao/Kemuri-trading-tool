<?php

if(empty($_SESSION))
    session_start();

$referer = $_SERVER['HTTP_REFERER'];
$has_title_row = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(!empty($_SESSION['errors'])){
        $_SESSION['errors'] = null;
    }
    
    if(!empty(($_SESSION['stock_data'])) && !empty(($_SESSION['success'])) ){
        $_SESSION['stock_data'] = [];
        $_SESSION['success'] = null;
    }

    if(is_uploaded_file($_FILES['csvfile']['tmp_name'])){
        $filename = basename($_FILES['csvfile']['name']);
        if(substr($filename, -3) == 'csv'){
            $tmpfile = $_FILES['csvfile']['tmp_name'];
            if (($fh = fopen($tmpfile, "r")) !== FALSE) {
                $i=0;
                $stock_data = [];
                //Validate the CSV data and convert it into an array in desired format.

                while (($items = fgetcsv($fh, 100000, ",")) !== FALSE) {
                    if($has_title_row === true && $i == 0){ //skiping the first row since there is a tile row in CSV file
                        $i++; continue;
                    }
                    $i++;
                    if(validate($items)){
                        array_push($stock_data, $items);
                    }else{
                        $_SESSION['errors'] = 'Invalid row detected in line number - '.$i .'<br>All the 4 fields are required.<br>The following values are allowed:<br>Cloumn 1 - Sl No (Number)<br>Column 2 - Date (d-m-Y format)<br>Cloumn 3 - Stock Name (String)<br>Column 4 - Price (Number). ';
                        header("Location: $referer");
                        exit(); 
                    }
                }
                $_SESSION['success'] = 'CSV file successfully processed, Kindly choose Stock Name & Dates for best buying & selling price.';
                $_SESSION['stock_data'] =  group_by(2, $stock_data, $columns_to_remove = [0, 2]);
                header("Location: $referer");
                exit();
            }
        }
        else{
            $_SESSION['errors'] = 'Invalid file format uploaded. Please upload only CSV files.';
            header("Location: $referer");
            exit();
        }
    }
    else{
        $_SESSION['errors'] = 'Please upload a CSV file.';
        header("Location: $referer");
        exit();
    }
}

function validate($data) {
    if(count($data) != 4){
        return false;
    }
    if(!is_numeric($data[0]) && !is_numeric($data[3])){
        return false;
    }elseif(!validate_date($data[1])){
        return false;
    }elseif(!is_string($data[2])){
        return false;
    }
    return true;
}

function validate_date($date, $format = 'd-m-Y'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function group_by($key, $data, $columns_to_remove) {
    $result = array();

    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $data_lite = format_data($columns_to_remove, $val);
            $stock_name = $val[$key];
            if(empty($result[$stock_name])){
                $stock_prices = [];
            }else{
                $stock_prices = $result[$stock_name];
            }
            //if there are multiple entries in CSV file for a stock with same date, entry with higgest selling price will be retained 
            if(array_key_exists($stock_prices, $data_lite['date'])){ 
                if($stock_prices[$data_lite['date']] < $data_lite['price']){
                    $stock_prices[$data_lite['date']] = $data_lite['price'];
                }
            }else{
                $stock_prices[$data_lite['date']] = $data_lite['price'];
            }
            //asort($stock_prices);
            $result[$stock_name] = $stock_prices; 
        }
    }
    return $result;
}

function format_data($columns, $data){

    foreach($columns as $column){
        unset($data[$column]);
    }

    return ['date' => $data[1], 'price' => $data[3]];
}

?>