<!DOCTYPE html>
<?php if(empty($_SESSION))
        session_start();
?>
<html lang="">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Stock Market Application</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css">

        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <body>

        <!-- Top content -->
        <div class="top-content">
            <div class="inner-bg">
                <div class="container"> 
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-box">
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3>Stock Trading Tool</h3>
                                        <p>Upload an CSV file with list of companies and their closing stock prices for each day (in INR)</p>
                                    </div>
                                    <div class="form-top-right">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <form role="form" enctype="multipart/form-data" action="analyze_file.php" method="post" class="login-form">
                                        <div class="form-group">
                                            <label class="sr-only" for="csvfile">Stock Prices</label>
                                            <input type="file" name="csvfile" id="csvfile">
                                        </div>
                                        <button type="submit" class="btn">Analyze</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <?php if($_SESSION['errors']){ ?>
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2 text">
                                    <div class="alert alert-danger">
                                        <?php echo $_SESSION['errors']; $_SESSION['errors'] = null; ?>
                                    </div>
                            </div>
                        </div>
                    <?php }elseif($_SESSION['success']){ ?>
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2 text">
                                    <div class="alert alert-success">
                                        <?php echo $_SESSION['success']; $_SESSION['success'] = null; ?>
                                    </div>
                            </div>
                        </div>
                    <?php } ?>            
                </div>

                <div class="container">     
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-box">
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3>Stock Details</h3>
                                        <p>Choose an Stock and Date range to show best buying and selling dates and rates</p>
                                    </div>
                                    <div class="form-top-right">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <form role="form" action="generate_report.php" method="post" class="login-form">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="stock">Stock: </label>
                                                    <br>
                                                    <input id="stock" name="stock" list="stocksList">
                                                    <datalist id="stocksList">
                                                    <?php foreach($_SESSION['stock_data'] as $key => $value){ ?>
                                                        <option value="<?php echo $key;?>"></option>
                                                    <?php } ?>
                                                    </datalist>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="start_date">Date Range Start: </label>
                                                <div class="input-group date" data-date-format="dd.mm.yyyy">
                                                    <input  type="text" name="start_date"  class="form-control" placeholder="dd.mm.yyyy">
                                                    <div class="input-group-addon" >
                                                    <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="end_date">Date Range End: </label>
                                                <div class="input-group date" data-date-format="dd.mm.yyyy">
                                                    <input  type="text" name="end_date"  class="form-control" placeholder="dd.mm.yyyy">
                                                    <div class="input-group-addon" >
                                                    <span class="glyphicon glyphicon-th"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn">Generate Report</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">    
                        <div class="form-box">
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3>Reports & Statistics</h3>
                                    </div>
                                    <div class="form-top-right">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <dl class = "dl-horizontal">
                                        <?php if(!empty($_SESSION['reports'])){ ?>
                                            <h3> Report without date filter</h3>
                                            <h4>Lowest buying price for <?php echo $_SESSION['reports']['selected_stock'];?> was <?php echo $_SESSION['reports']['min_price_unfiltered'];?> on <?php echo $_SESSION['reports']['min_date_unfiltered'];?> </h4>
                                            <h4>Highest buying price for <?php echo $_SESSION['reports']['selected_stock'];?> was <?php echo $_SESSION['reports']['max_price_unfiltered'];?> on <?php echo $_SESSION['reports']['max_date_unfiltered'];?> </h4>
                                            <br>
                                            <?php if($_SESSION['reports']['min_date_filtered'] != null){ ?>
                                                <h3> Report with date filter</h3>
                                                <h4>Lowest buying price for <?php echo $_SESSION['reports']['selected_stock'];?> was <?php echo $_SESSION['reports']['min_price_filtered'];?> on <?php echo $_SESSION['reports']['min_date_filtered'];?></h4>
                                                <h4>Highest buying price for <?php echo $_SESSION['reports']['selected_stock'];?> was <?php echo $_SESSION['reports']['max_price_filtered'];?> on <?php echo $_SESSION['reports']['max_date_filtered'];?> </h4>
                                                <br>
                                            <?php }else{ ?>
                                                <h2>Data not available in CSV for selected range</h2>
                                        <?php }}else{ ?>
                                            <p> Click Generate Reports to get reports </p> 
                                        <?php } ?>
                                    </dl>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="footer-border"></div>
                       
                    </div>
                    
                </div>
            </div>
        </footer>

        <!-- Javascript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/scripts.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.8/angular.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js" charset="utf-8"></script>
        <script>
            $('.input-group.date').datepicker({format: "dd.mm.yyyy", autoclose:true});
        </script>
    </body>

</html>