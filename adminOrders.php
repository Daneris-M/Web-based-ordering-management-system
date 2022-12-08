<?php 
    $page = 'admin';
    include('method/checkIfAccountLoggedIn.php');
    include('method/query.php');
    $_SESSION['from'] = 'adminOrderList';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <link rel="stylesheet" href="css/bootstrap 5/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <!-- online css bootsrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <!-- modal script  -->
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>  
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header bg-dark">
                <h3 class="mt-3">
                    <a href="admin.php">Admin</a>
                </h3>
            </div>
            <ul class="list-unstyled components ms-3">
                <li class="mb-2">
                    <a href="#" id="pos">
                        <i class="bi bi-tag me-2"></i>
                        Point of Sales
                    </a>
                </li>
                <li class="mb-2 active">
                    <a href="#">
                        <i class="bi bi-minecart me-2"></i>
                        Orders
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="ordersQueue">
                        <i class="bi bi-clock me-2"></i>
                        Orders Queue
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="inventory">
                        <i class="bi bi-box-seam me-2"></i>
                        Inventory
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="salesReport">
                        <i class="bi bi-bar-chart me-2"></i>
                        Sales Report
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="accountManagement">
                        <i class="bi bi-person-circle me-2"></i>
                        Account Management
                    </a>
                </li>
                <li class="mb-2">
                    <a href="#" id="customerFeedback">
                        <i class="bi bi-chat-square-text me-2"></i>
                        Customer Feedback
                    </a>
                </li>
                <li class="mb-1">
                    <a href="#" id="adminTopUp">
                        <i class="bi bi-cash-stack me-2"></i>
                        Top-Up
                    </a>
                </li>
                <li>
                    <form method="post">
                        <button class="btn btnLogout btn-dark text-danger" id="Logout" name="logout">
                            <i class="bi bi-power me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg bg-light">
                <div class="container-fluid bg-transparent">

                    <button type="button" id="sidebarCollapse" class="btn" style="font-size:20px;">
                        <i class="bi bi-list"></i>
                        <span>Dashboard</span>
                    </button>
                </div>
            </nav>
            <!-- content here -->
            <div class="container-fluid text-center">
                <div class="row justify-content-center">
                    <form method="get" class="col-lg-12">
                        <!-- select sort -->
                        <select name="sort" class="form-control form-control-lg col-11 mb-3" method="get">
                            <?php 
                                if(isset($_GET['sort'])){ ?>
                            <option value="<?php echo $_GET['sort'];?>" selected>
                                SORT
                                <?php echo strtoupper($_GET['sort']);?>
                            </option>
                            <?php
                                }else{ ?>
                            <option value="all" selected>
                                SELECT OPTION
                            </option>
                            <?php } ?>
                            </option>
                            <option value="all">ALL</option>
                            <option value="prepairing">PREPARING</option>
                            <option value="serving">SERVING</option>
                            <option value="order complete">ORDER COMPLETE</option>
                        </select>
                        <!-- button sort -->
                        <input type="submit" value="SORT" class="btn btn-lg btn-success col-12 mb-4">
                    </form>
                        <!-- sorted table -->
                        <?php
                        // sort query
                        if(isset($_GET['sort'])){
                            $_SESSION['query'] = $_GET['sort'];
                        }
                        if($_SESSION['query'] == 'all')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id order by b.id asc " ;
                        elseif($_SESSION['query'] == 'prepairing')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id where b.status = 'prepairing' order by b.id asc " ;
                        elseif($_SESSION['query'] == 'serving')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id where b.status = 'serving' order by b.id asc " ;
                        elseif($_SESSION['query'] == 'order complete')
                            $query = "select a.*, b.*, c.* from WEBOMS_userInfo_tb a inner join WEBOMS_order_tb b on a.user_id = b.user_id inner join WEBOMS_user_tb c on a.user_id = c.user_id where b.status = 'complete' order by b.id asc " ;

                        $resultSet =  getQuery($query);
                        if($resultSet != null){ ?>
                        <!-- table container -->
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered col-lg-12">
                                <thead>
                                    <tr>
                                        <th scope="col">CUSTOMER NAME</th>
                                        <th scope="col">ORDERS ID</th>
                                        <th scope="col">ORDER STATUS</th>
                                        <th scope="col">DATE & TIME</th>
                                        <th scope="col">STAFF (IN-CHARGE)</th>
                                        <th scope="col">ORDER DETAILS</th>
                                        <th scope="col" colspan="3">OPTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($resultSet as $row){?>
                                    <tr>
                                        <!-- name -->
                                        <td><?php echo $row['accountType'] == 'customer'  ? $row['name']:'POS'; ?></td>
                                        <!-- orders link id -->
                                        <td><?php echo $row['order_id'];?></td>
                                        <!-- order status -->
                                        <?php 
                                            if($row['status'] == 'approved'){
                                            ?>
                                        <td>
                                            <i class="bi bi-check"></i>
                                            <span class="ms-1">APPROVED</span>
                                        </td>
                                        <?php
                                            }
                                            elseif($row['status'] == 'prepairing'){
                                            ?>
                                        <td>
                                            <i class="bi bi-clock"></i>
                                            <span class="ms-1">PREPARING</span>
                                        </td>
                                        <?php
                                            }
                                            elseif($row['status'] == 'serving'){
                                            ?>
                                        <td>
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span class="ms-1">SERVING</span>
                                        </td>
                                        <?php
                                            }
                                            elseif($row['status'] == 'complete'){
                                            ?>
                                        <td>
                                            <i class="bi bi-check"></i>
                                            <span class="ms-1">ORDER COMPLETE</span>
                                        </td>
                                        <?php
                                            }
                                        ?>
                                        <!-- date and time -->
                                        <td><?php echo date('m/d/Y h:i a ', strtotime($row['date'])); ?></td>
                                        <!-- staff in charge -->
                                        <td><?php echo strtoupper($row['staffInCharge'] == 'null' ? ' ' :$row['staffInCharge'])?>
                                        </td>
                                        <!-- order details -->
                                        <td>
                                            <a class="btn btn-light border-secondary"
                                                href="adminOrder_details.php?idAndPic=<?php echo $row['order_id']?>">
                                                <i class="bi bi-list"></i>
                                                <span class="ms-1">VIEW</span>
                                            </a>
                                        </td>
                                        <!-- options -->
                                        <?php if($row['staffInCharge'] == 'online order') {?>
                                            <td><a class="btn btn-success" href="?viewCustomerInfo=<?php echo $row['user_id'] ?>"><span class="ms-1">View Customer Info</span></a></td>
                                            <!-- status -->
                                            <?php  if($row['status'] == 'prepairing'){ ?>
                                                    <td><a class="btn btn-success" href="?serve=<?php echo $row['order_id'] ?>"><i class="bi bi-box-arrow-right"></i><span class="ms-1">SERVE</span></a></td>
                                            <?php }elseif($row['status'] == 'serving'){ ?>
                                                    <td><a class="btn btn-success" href="?orderComplete=<?php echo $row['order_id'] ?>"><i class="bi bi-check"></i><span class="ms-1">ORDER COMPLETE</span></a></td><?php }
                                                elseif($row['status'] == 'complete'){?>
                                                    <td>NONE</td><?php } ?>
                                        <?php } else{ ?>
                                            <!-- status -->
                                            <?php  if($row['status'] == 'prepairing'){ ?>
                                                    <td colspan="2"><a class="btn btn-success" href="?serve=<?php echo $row['order_id'] ?>"><i class="bi bi-box-arrow-right"></i><span class="ms-1">SERVE</span></a></td>
                                            <?php }elseif($row['status'] == 'serving'){ ?>
                                                    <td><a class="btn btn-success" href="?orderComplete=<?php echo $row['order_id'] ?>"><i class="bi bi-check"></i><span class="ms-1">ORDER COMPLETE</span></a></td><?php }
                                                elseif($row['status'] == 'complete'){?>
                                                    <td>NONE</td><?php } ?>
                                        <?php } ?>
                                
                                        <!-- delete -->
                                        <td>
                                            <a class="btn btn-danger"
                                                href="?delete=<?php echo $row['ID'].','.$row['order_id'] ?>">
                                                <i class="bi bi-trash"></i>
                                                <span class="ms-1">DELETE</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <!-- customerProfileModal (Bootstrap MODAL) -->
                    <div class="modal fade" id="customerProfileModal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content container">
                                <div class="modal-body">
                                    <!-- table -->
                                    <div class="table-responsive col-lg-12">
                                        <table class="col-lg-12">
                                            <tbody>
                                                <?php
                                                    $query = "select a.*,b.* from WEBOMS_user_tb a inner join WEBOMS_userInfo_tb b on a.user_id = b.user_id where a.user_id = '$_GET[viewCustomerInfo]' ";
                                                    $resultSet =  getQuery($query);
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
                                                    <tr>
                                                        <th scope="col">Profile Info</th>
                                                        <img src="profilePic/<?php echo $picName; ?>" style=width:200px;height:200px>
                                                    </tr>
                                                    <tr><td><?php echo $name;?></td></tr>
                                                    <tr><td><strong>Username: </strong> <?php echo $username;?></td></tr>
                                                    <tr><td><strong>Email: </strong> <?php echo $email;?></td></tr>
                                                    <tr><td><strong>Gender: </strong> <?php echo $gender;?></td></tr>
                                                    <tr><td><strong>Phone Number: </strong> <?php echo $phoneNumber;?></td></tr>
                                                    <tr><td><strong>Address: </strong> <?php echo $address;?></td></tr>
                                                    <tr><td><strong>Balance: </strong> <?php echo '₱'.$balance;?></td></tr>
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
        $query = "UPDATE WEBOMS_order_tb SET status='serving' WHERE order_id='$order_id' ";     
        if(Query($query)){
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('success!');</SCRIPT>";
        }
    }

    //button to make order complete
    if(isset($_GET['orderComplete'])){
        $order_id = $_GET['orderComplete'];
        $query = "UPDATE WEBOMS_order_tb SET status='complete' WHERE order_id='$order_id' ";     
        if(Query($query))
            echo "<SCRIPT>  window.location.replace('adminOrders.php'); alert('success!');</SCRIPT>";
    }

    //delete button
    if(isset($_GET['delete'])){
        $arr = explode(',',$_GET['delete']);
        $id = $arr[0];
        $linkId = $arr[1];
        $query1 = "DELETE FROM WEBOMS_order_tb WHERE id='$id'";
        $query2 = "DELETE FROM WEBOMS_ordersDetail_tb WHERE order_id='$linkId'";
            if(Query($query1) && Query($query2)){
                echo "<script> window.location.replace('adminOrders.php'); alert('Delete data successfully'); </script>";  
            }
    }

    //view customer info
    if(isset($_GET['viewCustomerInfo'])){
        echo "<script>$('#customerProfileModal').modal('show');</script>";
    }
?>

<script>
    // for navbar click locations
    document.getElementById("pos").onclick = function() {
        window.location.replace('adminPos.php');
    };
    document.getElementById("ordersQueue").onclick = function() {
        window.location.replace('adminOrdersQueue.php');
    };
    document.getElementById("inventory").onclick = function() {
        window.location.replace('adminInventory.php');
    };
    document.getElementById("salesReport").onclick = function() {
        window.location.replace('adminSalesReport.php');
    };
    document.getElementById("accountManagement").onclick = function() {
        window.location.replace('accountManagement.php');
    };
    document.getElementById("customerFeedback").onclick = function() {
        window.location.replace('adminFeedbackList.php');
    };
    document.getElementById("adminTopUp").onclick = function() {
        window.location.replace('adminTopUp.php');
    };
</script>

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
                $updateQuery = "UPDATE WEBOMS_menu_tb SET stock = (stock + '$dishesQuantity[$i]') WHERE dish= '$dishesArr[$i]' ";    
                Query($updateQuery);    
            }
        }
        session_destroy();
        echo "<script>window.location.replace('Login.php');</script>";
    }
?>