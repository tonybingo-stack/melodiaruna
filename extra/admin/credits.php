<?php
include 'inc/admin.php';
$user = check_user();

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (intval($_GET['action'])==0) {
        $status = 0;
    } else {
        $status = 1;
    }
    $id = $_GET['id'];
    query("UPDATE `gr_credits` SET status = $status WHERE id= $id");

}


if (isset($_POST['action']) && isset($_POST['package_id']) && isset($_POST['package_name']) && isset($_POST['credit']) && isset($_POST['price'])) {


    $name = $_POST['package_name'];
    $app_in_id = $_POST['app_in_id'];
    $credit = $_POST['credit'];
    $price = $_POST['price'];

    if ($_POST['package_id']=='') {

        $sql = "INSERT INTO `gr_credits`(`name`, `app_in_id`, `credits`, `price`) VALUES ('$name','$app_in_id',$credit,$price)";
        query($sql);
    } else {
        $id = $_POST['package_id'];
        $sql = "UPDATE `gr_credits` SET `name`='$name',`app_in_id`='$app_in_id',`credits`=$credit,`price`='$price' WHERE id = $id";
        query($sql);
    }


}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand ps-3" href="index.php" data='dashboard'>Grupo Admin Extra</a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php" data='dashboard'>
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link" href="users.php" data='users'>
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="sub_list.php" data='subs_list'>
                            <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                            Subs List
                        </a>
                        <a class="nav-link" href="orders.php" data='orders'>
                            <div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div>
                            Orders
                        </a>
                        <a class="nav-link active" href="credits.php" data='credits'>
                            <div class="sb-nav-link-icon"><i class="fas fa-coins"></i></div>
                            Credit packages 
                        </a>
                        <a class="nav-link" href="subs.php" data='subs'>
                            <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                            Subs packages
                        </a>
						<a class="nav-link" href="gifts.php" data='gifts'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gift"></i></div>
                            Gifts
                        </a>
                        <div class="sb-sidenav-menu-heading">Paid Channels</div>
                        <a class="nav-link" href="paidChannelLogs.php" data='withdrawal'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                            Paid Channel Process
                        </a>
                        <div class="sb-sidenav-menu-heading">Agencies</div>
                        <a class="nav-link" href="agencies.php" data='agencies'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                            Agencies List
                        </a>
                        <a class="nav-link" href="withdrawal.php" data='withdrawal'>
                            <div class="sb-nav-link-icon"><i class="fas fa-gear"></i></div>
                            Withdrawal requests
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo $user['display_name']; ?>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Credits</h1>

                    <div class="row">
                       
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info me-1"></i>
                                   Credit packages  <a href="#" class="btn btn-success btn-sm" onclick='add_credits_package();' style="float: right;">Add Package</a>
                                </div>
                                <div class="card-body">
                                	
                                	 <table class="table">
                                		<thead>
                                			<tr><th>Packages name</th><th>Credit</th><th>Price</th><th>App-In</th><th>Status</th><th>Action</th></tr>
                                		</thead>
                                		<tbody>
                                			 <?php 
                                			 $credits = get_credits_list();
                                			 foreach ($credits as $key => $value): ?>
                                			 	<tr>
                                			 		<td><?php echo $value['name']; ?></td>
                                			 		<td><?php echo $value['credits']; ?></td>
                                			 		<td><?php echo $value['price']; ?>$</td>
                                			 		<td><?php echo $value['app_in_id']; ?></td>
                                			 		<td><?php echo $value['status']; ?></td>
                                			 		<td>
                                                         <a item_id="<?php echo $value['id']; ?>" href='#' class="btn btn-primary btn-sm edit_credits">Edit this product</a>
                                                         <a href="credits.php?id=<?php echo $value['id']; ?>&action=0" class="btn btn-warning btn-sm">Suspend this product</a>
                                                         <a href="credits.php?id=<?php echo $value['id']; ?>&action=1" class="btn btn-success btn-sm">Active this product</a>
                                                    </td>
                                			 	</tr>
                                			 <?php endforeach ?>
                                			<tr></tr>
                                		</tbody>
                                	</table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <script type="text/javascript">
                var data1 = <?php echo json_encode($credits); ?>;
            </script>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2022</div>
                    </div>
                </div>
            </footer>


            <div class="modal fade" id="add_package" tabindex="-1" aria-labelledby="add_package" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="package Title">Add new credits package</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="credits.php" method="POST">
                                <input type="hidden" name="action" value='credit'>
                                <input type="hidden" name="package_id">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name='package_name' >
                                </div>
                                <div class="form-group">
                                    <label>Credit</label>
                                    <input type="text" class="form-control" name='credit' >
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" class="form-control" name='price' >
                                </div>
                                <div class="form-group">
                                    <label>App-In-Purc ID</label>
                                    <input type="text" class="form-control" name='app_in_id' >
                                </div>
                                
                                <button type="submit" class="btn btn-success"  style="margin: 20px auto;">Save</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script type="text/javascript">
                var add_form = new bootstrap.Modal(document.getElementById('add_package'));
    </script>
    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
</body>

</html>