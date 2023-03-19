<?php 
    $page = 'cashier';
    include('../method/checkIfAccountLoggedIn.php');
    include('../method/query.php');
    $_SESSION['from'] = 'adminOrderList';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <link rel="stylesheet" href="../css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- modal script  -->
    <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>  
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <!-- data table -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3"><a href="admin.php"><?php echo ucwords($_SESSION['accountType']); ?></a></h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="adminPos.php"><i class="bi bi-tag me-2"></i>Point of Sales</a>
                </li>
                <li class="mb-2 active">
                    <a href="adminOrders.php"><i class="bi bi-minecart me-2"></i>Orders</a>
                </li>
                <li class="mb-2">
                    <a href="adminOrdersQueue.php"><i class="bi bi-clock me-2"></i>Orders Queue</a>
                </li>
                <li class="mb-2">
                    <a href="topupRfid.php"><i class="bi bi-credit-card me-2"></i>Top-Up RFID</a>
                </li>
            
            <?php if($_SESSION['accountType'] != 'cashier'){?>
                <li class="mb-2">
                    <a href="adminInventory.php"><i class="bi bi-box-seam me-2"></i>Inventory</a>
                </li>
                <li class="mb-2">
                    <a href="adminSalesReport.php"><i class="bi bi-bar-chart me-2"></i>Sales Report</a>
                </li>
                <li class="mb-2">
                    <a href="accountManagement.php"><i class="bi bi-person-circle me-2"></i>Account Management</a>
                </li>
                <li class="mb-2">
                    <a href="adminFeedbackList.php"><i class="bi bi-chat-square-text me-2"></i>Customer Feedback</a>
                </li>
                <li class="mb-2">
                    <a href="adminTopUp.php"><i class="bi bi-cash-stack me-2"></i>Top-Up</a>
                </li>
                <li class="mb-1">
                    <a href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a>
                </li>
            <?php } ?>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout"><i class="bi bi-power me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">
                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;"><i class="bi bi-list"></i> Toggle</button>
                </div>
            </nav>

            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row g-3 justify-content-center">
            
                        <!-- select sort -->
                        <select id="status" class="form-control form-control-lg col-12 mb-3" >
                            <option value="all">all</option>
                            <option value="prepairing">preparing</option>
                            <option value="serving">serving</option>
                            <option value="order complete">complete</option>
                            <option value="void">void</option>
                        </select>
                        
                        <!-- table container -->
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered table-hover col-lg-12" id="tbl1">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">NO.</th>
                                        <th scope="col">CUSTOMER<br>NAME</th>
                                        <th scope="col">ORDER#</th>
                                        <th scope="col">ORDER<br>STATUS</th>
                                        <th scope="col">DATE & TIME<br>(MM/DD/YYYY)</th>
                                        <th scope="col">STAFF<br>(IN-CHARGE)</th>
                                        <th scope="col">ORDER<br>DETAILS</th>
                                        <th scope="col">CUSTOMER<br>INFO</th>
                                        <th scope="col">OPTIONS</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody1">
                                    <script>
                                            let status = $('#status').find(":selected").text();
                                            var latestId;
                                            //get id of latest order then after 2 seconds get the id of latest order again then compare
                                            $.getJSON({
                                            url: "ajax/orders_getNewestOrder.php",
                                            method: "post",
                                            data: {'status':JSON.stringify(status)},
                                            success: function(res){
                                                latestId = res;
                                            }
                                            });
                                            function checkIfDbChange(){
                                                $.getJSON({
                                                url: "ajax/orders_getNewestOrder.php",
                                                method: "post",
                                                data: {'status':JSON.stringify(status)},
                                                success: function(res){
                                                    let result = parseInt(res) > parseInt(latestId);
                                                    if(result){
                                                        updateTb();
                                                        latestId = res;
                                                    }
                                                },
                                                complete: function(){
                                                    setTimeout(checkIfDbChange, 2000);
                                                }
                                                });
                                            }
                                            checkIfDbChange();
                                     
                                            $.ajax({
                                            url: "ajax/orders_getOrders.php",
                                            method: "post",
                                            data: {'status':JSON.stringify(status)},
                                            success: function(res){
                                                $('#tbody1').append(res);
                                                $('#tbl1').dataTable({
                                                "columnDefs": [
                                                    { "targets": [6,7,8,9], "orderable": false }
                                                ]
                                                });
                                            }
                                            });

                                            $('#status').on('change', function () {
                                                updateTb();
                                            });

                                            function updateTb(){
                                                let status = $('#status').find(":selected").text();
                                                $.ajax({
                                                url: "ajax/orders_getOrders.php",
                                                method: "post",
                                                data: {'status':JSON.stringify(status)},
                                                success: function(res){
                                                    $('#tbl1').DataTable().clear().destroy();
                                                    $('#tbody1').append(res);
                                                    $('#tbl1').dataTable({
                                                    "columnDefs": [
                                                        { "targets": [6,7,8,9], 
                                                            "orderable": false }
                                                    ]
                                                    });
                                                }
                                                });
                                            }

                                    </script>
                                </tbody>
                            </table>
                        </div>

                    <!-- customerProfileModal (Bootstrap MODAL) -->
                    <div class="modal fade" id="customerProfileModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content container">
                                <div class="modal-body">
                                    <!-- table -->
                                    <div class="table-responsive col-lg-12">
                                        <table class="table table-bordered table-hover col-lg-12 text-start">
                                            <tbody>
                                                <?php
                                                    $query = "select a.*,b.* from weboms_user_tb a inner join weboms_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$_GET[viewCustomerInfo]' ";
                                                    $resultSet =  getQuery2($query);
                                                    if($resultSet!= null)
                                                    foreach($resultSet as $row){ 
                                                    // init
                                                    $id = $row['id'];
                                                    $name = $row['name'];
                                                    $picName = $row['picName'];
                                                    $username = $row['username'];
                                                    $g = $row['gender'];
                                                    $phoneNumber = $row['phoneNumber'];
                                                    $address = $row['address'];
                                                    $balance = $row['balance'];
                                                    $email = $row['email'];
                                                    //gender process
                                                    $g = $row['gender'];
                                                    if($g == 'm'){
                                                        $gender = 'male';
                                                        $genderIndex = 0;
                                                    }
                                                    elseif($g == 'f'){
                                                        $gender = 'female';
                                                        $genderIndex = 1;
                                                    }else{
                                                        $gender = 'NA';
                                                        $genderIndex = 2;
                                                    }
                                                    ?>
                                                    <?php if($picName != null){ ?>
                                                        <tr class="text-center">
                                                            <th colspan="2"><img src="../profilePic/<?php echo $picName; ?>" style="width:200px;height:200px;border:1px solid black;"></th>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td><b>NAME</b></td>
                                                        <td><?php echo ucwords($name);?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>USERNAME</b></td>
                                                        <td><?php echo $username;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>EMAIL</b></td>
                                                        <td><?php echo $email;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>GENDER</b></td>
                                                        <td><?php echo ucwords($gender);?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>PHONE NUMBER</b></td>
                                                        <td><?php echo $phoneNumber;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>ADDRESS</b></td>
                                                        <td><?php echo ucwords($address);?></td>
                                                    </tr>
                                                    <tr class="bg-success text-white">
                                                        <td><b>BALANCE</b></td>
                                                        <td><b><?php echo '₱'. number_format($balance,2);?></b></td>
                                                    </tr>
                                                    <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
// sidebar (js)
$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
</script>

<?php 
    //button to serve order
    if(isset($_GET['serve'])){
        $order_id = $_GET['serve'];
        $query = "UPDATE weboms_order_tb SET status='serving' WHERE order_id='$order_id' ";     
        if(Query2($query)){
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('SUCCESS!');</SCRIPT>";
        }
    }

    //button to make order complete
    if(isset($_GET['orderComplete'])){
        $order_id = $_GET['orderComplete'];
        $query = "UPDATE weboms_order_tb SET status='complete' WHERE order_id='$order_id' ";     
        if(Query2($query))
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('SUCCESS!');</SCRIPT>";
    }

    //void button
    if(isset($_GET['void'])){
        $arr = explode(',',$_GET['void']);
        $order_id = $arr[0];
        $user_id = $arr[1];
        $totalOrder = $arr[2];
        $query = "UPDATE weboms_order_tb SET status='void' WHERE order_id='$order_id' ";     
        $query2 = "UPDATE weboms_userInfo_tb SET balance = (balance + '$totalOrder') WHERE user_id= '$user_id' ";    
        if(Query2($query)){
            if(Query2($query2)){
                echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('SUCCESS!');</SCRIPT>";
            }
        }


        $dishesArr = array();
        $dishesQuantity = array();

        $query = "select a.*, b.* from weboms_menu_tb a inner join weboms_ordersDetail_tb b on a.orderType = b.orderType where b.order_id = '$order_id' ";
        $resultSet = getQuery2($query); 

        foreach($resultSet as $row){
            array_push($dishesArr,$row['dish']);
            array_push($dishesQuantity,$row['quantity']);
        }
            
        for($i=0; $i<count($dishesArr); $i++){ 
            $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
            Query2($updateQuery);    
        }
    }

    //view customer info
    if(isset($_GET['viewCustomerInfo'])){
        echo "<script>$('#customerProfileModal').modal('show');</script>";
    }
?>

<?php 
    if(isset($_POST['logout'])){
        $dishesArr = array();
        $dishesQuantity = array();
        if(isset($_SESSION['dishes'])){
            for($i=0; $i<count($_SESSION['dishes']); $i++){
                if(in_array( $_SESSION['dishes'][$i],$dishesArr)){
                    $index = array_search($_SESSION['dishes'][$i], $dishesArr);
                }
                else{
                    array_push($dishesArr,$_SESSION['dishes'][$i]);
                }
            }
            foreach(array_count_values($_SESSION['dishes']) as $count){
                array_push($dishesQuantity,$count);
            }
            for($i=0; $i<count($dishesArr); $i++){ 
                $updateQuery = "UPDATE weboms_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query2($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('../general/login.php');</script>";
    }
?>
<script>
   
</script>