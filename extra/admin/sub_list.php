<?php
include 'inc/admin.php';
$user = check_user();

if (isset($_POST['force_end']) && isset($_POST['user_id'])) {
    force_end_subs($_POST['user_id']);
}

$subs = query('SELECT id,name FROM `gr_subs`');

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
                        <a class="nav-link active" href="sub_list.php" data='subs_list'>
                            <div class="sb-nav-link-icon"><i class="fas fa-user-check"></i></div>
                            Subs List
                        </a>
                        <a class="nav-link" href="orders.php" data='orders'>
                            <div class="sb-nav-link-icon"><i class="fas fa-cart-arrow-down"></i></div>
                            Orders
                        </a>
                        <a class="nav-link" href="credits.php" data='credits'>
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
                    <h1 class="mt-4">Subs List</h1>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-info me-1"></i>
                                    Subs
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <th>Credit</th>
                                                <th>Subs</th>
                                                <th>Subs End</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                             $last_orders = subs_users();
                                             foreach ($last_orders as $key => $value): ?>
                                            <tr>
                                                <td>
                                                    <?php echo $value['display_name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['role']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['credits']; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $data ='Unknown';

                                                    foreach ($subs as $key2 => $value2) {
                                                       if ($value2['id']==$value['subs']) {
                                                        $data = $value2['name'];
                                                        break;
                                                       }
                                                    }

                                                    echo $data; ?>
                                                </td>
                                                <td>
                                                    <?php if ($value['subs_end']>0) echo date("Y-m-d H:i:s", $value['subs_end']); else echo 'Not set'; ?>
                                                </td>
                                                <td>
                                                    <?php if ($value['real_role']!=="5"): ?>
                                                    <a onclick="return confirm('Are you sure?')" href='sub_list.php?user_id<?php echo $value['user_id']; ?>&force_end=1' class="add_credit btn btn-danger btn-sm">Force End This subs</a>
                                                    <?php endif ?>
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
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2022</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <div class="modal fade" id="add_subs" tabindex="-1" aria-labelledby="add_subs" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_subs">Subs Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <input type="hidden" name="user_id">
                        <div class="form-group">
                            <label>Nick</label>
                            <input type="text" class="form-control" name='nick' disabled="true">
                        </div>
                        <div class="form-group">
                            <label for="plans">Select Subs packages</label>
                            <select class="form-control" id="plans" required="true" name="plans">
                                <?php 
                                $plans = plans();
                                foreach ($plans as $key => $value): ?>
                                <option value="<?php echo $value['id']; ?>">
                                    <?php echo $value['name']; ?>
                                </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Expired Day</label>
                            <input type="number" class="form-control" name='days' placeholder="30" required="true">
                        </div>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal" style="margin: 20px auto;">Add & Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    var add_subs = new bootstrap.Modal(document.getElementById('add_subs'));
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="assets/adminjs/admin.js" crossorigin="anonymous"></script>
</body>

</html>