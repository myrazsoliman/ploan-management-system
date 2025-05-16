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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Loan Management System</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link href="../fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    include('sidebar.php');
    ?>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Dashboard Content -->
        <div class="row">
            <!-- Active Loans Card -->
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Active Loans
                                </div>
                                <div class="h1 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $tbl_loan = $db->conn->query("SELECT * FROM `loan` WHERE `status`='2'");
                                    echo $tbl_loan->num_rows > 0 ? $tbl_loan->num_rows : "0";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fw fas fa-comment-dollar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link" href="loan.php">View Loan List</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Payments Today</div>
                                <div class="h1 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    // Update the column name to match your database structure
                                    $tbl_payment = $db->conn->query("SELECT sum(pay_amount) as total FROM payment WHERE date(payment_date)='" . date("Y-m-d") . "'");
                                    echo $tbl_payment->num_rows > 0 ? "&#8369; " . number_format($tbl_payment->fetch_array()['total'], 2) : "&#8369; 0.00";
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fw fas fa-coins fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link" href="payment.php">View Payments</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Borrowers
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h1 mb-0 mr-3 font-weight-bold text-gray-800">
                                            <?php
                                            $tbl_borrower = $db->conn->query("SELECT * FROM borrowers");
                                            echo $tbl_borrower->num_rows > 0 ? $tbl_borrower->num_rows : "0";
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-fw fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small stretched-link" href="borrowers.php">View Borrowers</a>
                        <div class="small">
                            <i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    </div>
    </div>
    </div>

    </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">System Information</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
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
    <script src="../js/jquery.easing.js"></script>
    <script src="../js/sb-admin-2.js"></script>

</body>

</html>