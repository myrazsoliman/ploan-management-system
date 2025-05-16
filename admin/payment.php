<?php
date_default_timezone_set("Etc/GMT+8"); // Set your desired timezone
require_once '../session.php'; // Ensure user is logged in and is an admin
require_once '../class.php'; // Your database class

$db = new db_class();

// --- Recommended: Add security check ---
// if (!isset($_SESSION['admin_id'])) { // Or whatever your admin session variable is
//     header("Location: ../login.php"); // Redirect to login if not admin
//     exit;
// }

// Handle delete action (more robustly with a POST request usually, but for simplicity with GET for now)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    // It's good practice to verify the payment exists before attempting to delete
    // And also to check if there are related records that might prevent deletion or need cascading deletes
    $stmt = $db->conn->prepare("DELETE FROM payment WHERE payment_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Payment successfully deleted.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error deleting payment: ' . $stmt->error];
    }
    $stmt->close();
    header("Location: payment_management.php"); // Redirect back to the page itself to clear GET params and show message
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Payment Management</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link href="../fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            /* Light gray background for better contrast */
        }

        .card-header {
            background-color: #007bff;
            /* Primary color for card headers */
            color: white;
        }

        .btn-action-icon {
            margin-right: 5px;
        }

        #proofImageModal .modal-dialog {
            max-width: 800px;
            /* Adjust as needed */
        }

        #proofImageModal img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .table-actions {
            white-space: nowrap;
            /* Prevent action buttons from wrapping */
        }

        .status-paid {
            color: green;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        /* Add some padding if sidebar is present */
        body.has-sidebar #main-content-wrapper {
            padding-left: 250px;
            /* Adjust to your sidebar's width */
        }
    </style>
</head>

<body
    class="antialiased <?php echo (file_exists('sidebar.php') || file_exists('../sidebar.php')) ? 'has-sidebar' : ''; ?>">
    <?php
    // --- Assume Sidebar is included here ---
    if (file_exists('sidebar.php')) {
        include('sidebar.php');
    } else if (file_exists('../sidebar.php')) {
        include('../sidebar.php');
    }
    ?>

    <div id="main-content-wrapper" class="container-fluid mt-4">
        <h2>Payment Management</h2>
    </div>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . htmlspecialchars($_SESSION['message']['type']) . ' alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['message']['text']) . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-list"></i> All Payments</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Loan Ref #</th>
                            <th>Borrower</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Proof</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $payments_query = "
                                SELECT 
                                    p.payment_id,
                                    p.payment_method,
                                    p.pay_amount,
                                    p.proof_of_payment,
                                    p.payment_date,
                                    p.status, /* Assuming you add a status column to your payment table */
                                    l.ref_no, /* Example: from loan table */
                                    CONCAT(b.firstname, ' ', b.lastname) AS borrower_name /* Example: from borrower table */
                                FROM payment p
                                INNER JOIN loan l ON p.loan_id = l.loan_id
                                INNER JOIN borrowers b ON l.borrower_id = b.borrower_id /* Adjust if borrower info is directly in loan table */
                                ORDER BY p.payment_date DESC, p.payment_id DESC
                            ";
                        $payments_result = $db->conn->query($payments_query);
                        $i = 1;
                        if ($payments_result && $payments_result->num_rows > 0) {
                            while ($row = $payments_result->fetch_assoc()) { // Use fetch_assoc for named array keys
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($row['ref_no'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['borrower_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                    <td>&#8369; <?php echo number_format($row['pay_amount'], 2); ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($row['proof_of_payment'])) { ?>
                                            <button type="button" class="btn btn-info btn-sm view-proof-btn"
                                                data-proof-url="../uploads/<?php echo htmlspecialchars($row['proof_of_payment']); ?>"
                                                title="View Proof of Payment">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        <?php } else { ?>
                                            <span class="text-muted">None</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo date("M d, Y", strtotime($row['payment_date'])); ?></td>
                                    <td>
                                        <?php
                                        // Example status display - you'd fetch this from DB
                                        $status = strtolower($row['status'] ?? 'pending'); // Default to pending if no status
                                        $status_class = 'status-pending';
                                        if ($status == 'approved' || $status == 'paid' || $status == 'completed') {
                                            $status_class = 'status-paid';
                                        }
                                        echo '<span class="' . $status_class . '">' . htmlspecialchars(ucfirst($status)) . '</span>';
                                        ?>
                                    </td>
                                    <td class="text-center table-actions">
                                        <a href="edit_payment.php?id=<?php echo $row['payment_id']; ?>"
                                            class="btn btn-success btn-sm btn-action-icon" title="Edit Payment">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-action-icon delete-payment-btn"
                                            data-payment-id="<?php echo $row['payment_id']; ?>"
                                            data-loan-ref="<?php echo htmlspecialchars($row['loan_ref_no'] ?? 'this payment'); ?>"
                                            title="Delete Payment">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <a href="view_payment_details.php?id=<?php echo $row['payment_id']; ?>"
                                            class="btn btn-primary btn-sm btn-action-icon" title="View Details">
                                            <i class="fas fa-search-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="9" class="text-center">No payments found.</td></tr>'; // Updated colspan
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">System Confirmation</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
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

    <div class="modal fade" id="proofImageModal" tabindex="-1" aria-labelledby="proofImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="proofImageModalLabel">Proof of Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="proofImage" alt="Proof of Payment" class="img-fluid" style="max-height: 70vh;">
                    <p class="mt-2"><a href="#" id="proofDirectLink" target="_blank"
                            class="btn btn-outline-primary btn-sm">Open image in new tab</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel"><i
                            class="fas fa-exclamation-triangle"></i> Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this payment? This action cannot be undone.
                    <p class="mt-2"><strong>Loan Reference:</strong> <span id="paymentLoanRefToDelete"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delete Payment</a>
                </div>
            </div>
        </div>
    </div>


    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#dataTable').DataTable({
                "order": [[6, "desc"]], // Default sort by Payment Date descending
                "pageLength": 10, // Default number of rows to display
                "language": {
                    "search": "Filter records:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "<i class='fas fa-chevron-right'></i>",
                        "previous": "<i class='fas fa-chevron-left'></i>"
                    }
                }
            });

            // Handle View Proof of Payment Modal
            $('.view-proof-btn').on('click', function () {
                var proofUrl = $(this).data('proof-url');
                $('#proofImage').attr('src', proofUrl);
                $('#proofDirectLink').attr('href', proofUrl);
                $('#proofImageModal').modal('show');
            });

            // Handle Delete Confirmation Modal
            var paymentIdToDelete;
            $('.delete-payment-btn').on('click', function () {
                paymentIdToDelete = $(this).data('payment-id');
                var loanRef = $(this).data('loan-ref');
                $('#paymentLoanRefToDelete').text(loanRef);
                // Construct the delete URL. Assumes current page is payment_management.php
                var deleteUrl = 'payment_management.php?action=delete&id=' + paymentIdToDelete;
                $('#confirmDeleteButton').attr('href', deleteUrl);
                $('#deleteConfirmationModal').modal('show');
            });

            // Optional: If you want to clear modal content after it's hidden to prevent issues
            $('#proofImageModal').on('hidden.bs.modal', function () {
                $('#proofImage').attr('src', '');
                $('#proofDirectLink').attr('href', '#');
            });
        });
    </script>
</body>

</html>