<?php
date_default_timezone_set("Etc/GMT+8");
require_once '../session.php';
require_once '../class.php';
$db = new db_class();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Borrowers Management</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link href="../fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="../css/select2.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="antialiased">
    <?php
    // --- Sidebar Inclusion (same as before) ---
    if (file_exists('sidebar.php')) {
        include('sidebar.php');
    } else if (file_exists('../sidebar.php')) {
        include('../sidebar.php');
    }
    ?>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Borrowers List</h1>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $borrowers = $db->conn->query("SELECT borrowers.*, loan.amount, loan.status FROM borrowers INNER JOIN loan ON borrowers.borrower_id = loan.borrower_id");
                            $i = 1;
                            while ($row = $borrowers->fetch_array()) {
                                $name = $row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname'];
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $name; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['contact_no']; ?></td>
                                    <td>&#8369; <?php echo number_format($row['amount'], 2); ?></td>
                                    <td><?php echo ($row['status'] == 1) ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-warning">Pending</span>'; ?>
                                    </td>
                                    <td>
                                        <a href="view_borrower.php?id=<?php echo $row['borrower_id']; ?>"
                                            class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="approve_loan.php?id=<?php echo $row['borrower_id']; ?>"
                                            class="btn btn-success btn-sm">Approve</a>
                                    </td>
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
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">System Information</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to logout?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.bundle.js"></script>
    <script src="../js/jquery.dataTables.js"></script>
    <script src="../js/dataTables.bootstrap4.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
</body>

</html>