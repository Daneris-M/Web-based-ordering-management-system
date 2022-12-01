<?php 
    $page = 'admin';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin SR - View Graph</title>
        
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.js"></script>
        
</head>
<body class="bg-light">
    <?php
        $sold = 0;
        $countOfSold = 0;
        $stockLeft = 0;
        $query = "select * from WEBOMS_menu_tb;";
        $resultSet = getQuery($query);
        if($resultSet!=null){
            foreach($resultSet as $row){
                $stockLeft += $row['stock'];
            }
        }
        $query = "select WEBOMS_menu_tb.*,WEBOMS_ordersDetail_tb.*,WEBOMS_order_tb.status from WEBOMS_menu_tb inner join WEBOMS_ordersDetail_tb on WEBOMS_menu_tb.orderType = WEBOMS_ordersDetail_tb.orderType inner join WEBOMS_order_tb on WEBOMS_order_tb.order_id = WEBOMS_ordersDetail_tb.order_id where status = 'complete';";
        $resultSet = getQuery($query);
        if($resultSet!=null){
            foreach($resultSet as $row){
                $countOfSold += $row['quantity'];
            }
        }
    ?>
<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <button class="btn btn-lg btn-dark col-12 mb-3" id="viewSalesReport">Sales Report</button>
            <div class="col-lg-12">
                <h5 class="font-weight-normal">TOTAL COUNT OF STOCK: <?php echo $countOfSold+$stockLeft?></h5>
                <h5 class="font-weight-normal">TOTAL COUNT OF SOLD: <?php echo $countOfSold?></h5>
                <h5 class="font-weight-normal">TOTAL COUNT OF STOCK LEFT: <?php echo $stockLeft?></h5>
                <div class="col-lg-12" id="piechart" style="width: 900px; height: 500px;"></div> <!-- di pa responsive to -->
            </div>
    </div>
</div>
    
</body>
</html>

<script>
        document.getElementById("viewSalesReport").onclick = function () {window.location.replace('adminSalesReport.php'); };

        //graphs
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChartPie);
    
        //pie
        function drawChartPie() {
        var data = google.visualization.arrayToDataTable([
            ['name', 'cost'],
            ['Sold',<?php echo $countOfSold?>],
            ['Stock Left',<?php echo $stockLeft?>]
        ]);

        var options = {
          title: '',
          backgroundColor: 'transparent',
          is3D: false,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    
        }
</script>