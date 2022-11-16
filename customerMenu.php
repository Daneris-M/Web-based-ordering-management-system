<?php 
    session_start();
    if(!isset($_SESSION["dishes"]) || !isset($_SESSION["price"]) || !isset($_SESSION["orderType"])){
    $_SESSION["dishes"] = array();
    $_SESSION["price"] = array(); 
    $_SESSION["orderType"] = array(); 
    }
?>
<!DOCTYPE html>
<html>
    <head>
    <title></title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script> 
    <script type="text/javascript" src="js/bootstrap.js"></script>

</head>
<body>
<div class="container text-center">
    <button type="button" class="btn btn-success col-sm-3" id="back">Back</button>
    <button type="button" class="btn btn-success col-sm-3" id="viewCart">View Cart</button>
    <button class="btn btn-success col-sm-3" id="customersFeedback">Customers FeedBack</button>

    <div class="col-lg-12">
    <table class="table" border="10px">
        <tr>	
            <th scope="col">Dishes</th>
            <th scope="col">Price</th>
            <th scope="col">Stock</th>
            <th scope="col">picture</th>
        </tr>
        <?php 
            include_once('class/dishClass.php');
            include_once('method/query.php');
            $dish = new dish();
            $resultSet =  $dish -> getAllDishes(); 
            $dish -> generateDishTableBodyMenu($resultSet);
        ?>
        </table>
    </div>
</div>
</body>
</html>
<?php 
	if(isset($_GET['order'])){
        $order = explode(',',$_GET['order']);  
        $dish = $order[0];
        $price = $order[1];
		$orderType = $order[2];
        array_push($_SESSION['dishes'], $dish);
        array_push($_SESSION['price'], $price);
        array_push($_SESSION['orderType'], $orderType);
    }				
?>

<script>
	document.getElementById("back").onclick = function () {window.location.replace('customer.php'); };
	document.getElementById("viewCart").onclick = function () {window.location.replace('customerCart.php'); };
    document.getElementById("customersFeedback").onclick = function () {window.location.replace('customerFeedbackList.php'); };    

</script>
<style>
  	body{
    background-image: url(settings/bg.jpg);
    background-size: cover;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
    color: white;
    font-family: 'Josefin Sans',sans-serif;
    }
	.container{
     padding: 1%;
     margin-top: 2%;
     background: gray;
   }
</style>