<?php
// Ensure this is at the very top before any HTML output
date_default_timezone_set("Etc/GMT+8"); // Or your desired timezone
session_start(); // If you use sessions for anything relevant

// --- Admin Authentication Check (Conceptual) ---
// This is crucial for an admin page.
// In a real application, you'd have a robust check here.
/*
if (!isset($_SESSION['admin_id']) ) { // Or check a specific role: && $_SESSION['user_role'] !== 'admin'
    // Redirect to admin login page or show an access denied message and exit
    header('Location: admin_login.php'); // Example redirect
    exit;
}
*/
// For this example, we'll assume the admin is authenticated.

// Simulate database connection and class inclusion for standalone example
// In your actual setup, these would be proper includes
if (!class_exists('DBConnection')) {
    // Mock DBConnection class if not available
    class DBConnection
    {
        public $conn;
        public function __construct()
        {
            // Mock connection - replace with your actual connection logic
            // IMPORTANT: Use proper error handling for database connections in production
            $this->conn = @new mysqli('localhost', 'root', '', 'db_lms'); // @ to suppress errors if DB doesn't exist
            if ($this->conn->connect_error) {
                // error_log("Database Connection Error: " . $this->conn->connect_error);
                $this->conn = null; // Set conn to null if connection fails
            }
        }
        public function connect()
        {
            return $this->conn;
        }
    }
}

// Attempt to establish a connection
$db = new DBConnection();
$conn = $db->connect();

$loan_id = isset($_GET['loan_id']) ? (int) $_GET['loan_id'] : 0; // Sanitize loan_id

$settled = [];
$unsettled = [];
$all = [];
$loan_borrower_info = null; // To store borrower info

$page_error = null;

if (!$conn) {
    $page_error = "Database connection failed. Cannot load loan history.";
} elseif ($loan_id <= 0) {
    $page_error = "No Loan ID specified or invalid Loan ID. Cannot retrieve history.";
} else {
    // Fetch borrower info for the given loan_id (Admin context)
    // This query assumes you have a way to link loans to users (e.g., a 'users' table and a 'user_id' in 'loans' table)
    // Adjust table and column names as per your database schema
    $info_sql = "SELECT l.loan_id, l.user_id, u.firstname, u.lastname, u.email 
                 FROM loans l
                 JOIN users u ON l.user_id = u.user_id 
                 WHERE l.loan_id = ?";
    $stmt_info = $conn->prepare($info_sql);
    if ($stmt_info) {
        $stmt_info->bind_param("i", $loan_id);
        $stmt_info->execute();
        $result_info = $stmt_info->get_result();
        if ($result_info->num_rows > 0) {
            $loan_borrower_info = $result_info->fetch_assoc();
        } else {
            $page_error = "Loan ID " . htmlspecialchars($loan_id) . " not found or no associated user.";
        }
        $stmt_info->close();
    } else {
        // error_log("Failed to prepare statement for borrower info: " . $conn->error);
        $page_error = "Error retrieving loan details.";
    }

    // Proceed to fetch loan schedule if loan info was found (or if you don't need borrower info displayed here)
    if (!$page_error || $loan_borrower_info) { // Fetch schedule if no error yet or if borrower info is optional here
        $sql = "SELECT loan_sched_id, loan_id, due_date, amount_due, status, remarks
                FROM loan_schedule
                WHERE loan_id = ?
                ORDER BY due_date ASC";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $loan_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $all[] = $row;
                    if (strtolower($row['status']) === 'settled') {
                        $settled[] = $row;
                    } else {
                        $unsettled[] = $row;
                    }
                }
            } else {
                // This is not an error, just means no schedule entries. The generate_payment_table function will handle this.
            }
            $stmt->close();
        } else {
            // error_log("Failed to prepare statement for loan schedule: " . $conn->error);
            $page_error = "Error retrieving payment schedule for Loan ID " . htmlspecialchars($loan_id) . ".";
        }
    }
    // $conn->close(); // Consider closing connection at the end of the script if not needed elsewhere.
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - Loan Payment History - PLOAN</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico"> <!-- Adjust path if needed -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include('sidebar.php');
    ?>


    <div id="main-content" class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Loan Payment History</h1>
            <a href="admin_all_loans.php" class="btn btn-sm shadow-sm"
                style="background-color: var(--primary-maroon); color: white;">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to All Loans
            </a>
        </div>

        <?php if ($page_error): ?>
            <div class="alert alert-danger"><?php echo $page_error; ?></div>
        <?php else: ?>
            <?php if ($loan_borrower_info): ?>
                <div class="borrower-info-box">
                    <h5>Borrower & Loan Information</h5>
                    <p><strong>Loan ID:</strong> <?php echo htmlspecialchars($loan_borrower_info['loan_id']); ?></p>
                    <p><strong>Borrower Name:</strong>
                        <?php echo htmlspecialchars($loan_borrower_info['firstname'] . ' ' . $loan_borrower_info['lastname']); ?>
                    </p>
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($loan_borrower_info['user_id']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($loan_borrower_info['email']); ?></p>
                    <!-- Add more loan details here if needed, e.g., loan amount, type, application date -->
                </div>
            <?php elseif ($loan_id > 0): // If loan_id was provided but info not found and no other page_error ?>
                <div class="alert alert-warning">Details for Loan ID <?php echo htmlspecialchars($loan_id); ?> could not be
                    fully loaded, but payment schedule might be available below.</div>
            <?php endif; ?>


            <div class="card payment-history-card">
                <div class="card-header">
                    Payment Schedule for Loan ID: <?php echo htmlspecialchars($loan_id); ?>
                </div>
                <div class="card-body">
                    <div class="date-filter-section mb-3 p-3 border rounded bg-light">
                        <form class="row g-3 align-items-end"> <!-- Use align-items-end for better alignment -->
                            <div class="col-md-auto">
                                <label for="from" class="form-label">From:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control form-control-sm" id="from" onchange="filterTable()">
                            </div>
                            <div class="col-md-auto">
                                <label for="to" class="form-label">To:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control form-control-sm" id="to" onchange="filterTable()">
                            </div>
                            <div class="col-md-auto">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="clearDateFilters()">Clear Filters</button>
                            </div>
                        </form>
                    </div>

                    <ul class="nav nav-tabs mb-3" id="paymentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane"
                                type="button" role="tab" aria-controls="all-tab-pane" aria-selected="true">All
                                Payments</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unsettled-tab" data-bs-toggle="tab"
                                data-bs-target="#unsettled-tab-pane" type="button" role="tab"
                                aria-controls="unsettled-tab-pane" aria-selected="false">Unsettled</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="settled-tab" data-bs-toggle="tab"
                                data-bs-target="#settled-tab-pane" type="button" role="tab" aria-controls="settled-tab-pane"
                                aria-selected="false">Settled</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="paymentTabsContent">
                        <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab"
                            tabindex="0">
                            <?php generate_payment_table($all, "No payment schedule entries found for this loan."); ?>
                        </div>
                        <div class="tab-pane fade" id="unsettled-tab-pane" role="tabpanel" aria-labelledby="unsettled-tab"
                            tabindex="0">
                            <?php generate_payment_table($unsettled, "No unsettled payment schedule entries found."); ?>
                        </div>
                        <div class="tab-pane fade" id="settled-tab-pane" role="tabpanel" aria-labelledby="settled-tab"
                            tabindex="0">
                            <?php generate_payment_table($settled, "No settled payment schedule entries found."); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; // end of else for page_error ?>
    </div> <!-- End main-content -->
    </div> <!-- End content-wrapper -->

    <!-- Mobile Admin Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start admin-sidebar" tabindex="-1" id="mobileAdminSidebar"
        aria-labelledby="mobileAdminSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileAdminSidebarLabel" style="color: var(--sidebar-text-hover-color);">
                <i class="fas fa-landmark me-2"></i>PLOAN ADMIN
            </h5>
            <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- Reuse or adapt the admin sidebar UL structure here -->
            <ul class="navbar-nav">
                <li class="nav-item active"> <!-- Example: Make Dashboard active -->
                    <a class="nav-link" href="admin_dashboard.php"><i
                            class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
                </li>
                <hr class="sidebar-divider">
                <div class="sidebar-heading text-white-50 px-3 small text-uppercase">Loan Management</div>
                <li class="nav-item">
                    <a class="nav-link" href="admin_loan_applications.php"><i
                            class="fas fa-fw fa-file-contract"></i><span>Loan Applications</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_all_loans.php"><i class="fas fa-fw fa-list-alt"></i><span>All
                            Loans</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_payment_tracking.php"><i
                            class="fas fa-fw fa-cash-register"></i><span>Payment Tracking</span></a>
                </li>
                <hr class="sidebar-divider">
                <div class="sidebar-heading text-white-50 px-3 small text-uppercase">User Management</div>
                <li class="nav-item">
                    <a class="nav-link" href="admin_manage_users.php"><i class="fas fa-fw fa-users-cog"></i><span>Manage
                            Users</span></a>
                </li>
                <hr class="sidebar-divider">
                <div class="sidebar-heading text-white-50 px-3 small text-uppercase">Settings</div>
                <li class="nav-item">
                    <a class="nav-link" href="admin_loan_plans.php"><i class="fas fa-fw fa-cogs"></i><span>Loan
                            Plans</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i
                            class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>


    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current admin session.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="admin_logout.php">Logout</a>
                    <!-- Ensure this points to admin logout -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <script>
        // Date Filtering Logic (same as before, should work fine)
        window.filterTable = function () {
            const fromDateStr = document.getElementById('from').value;
            const toDateStr = document.getElementById('to').value;
            const fromDate = fromDateStr ? new Date(fromDateStr) : null;
            let toDate = toDateStr ? new Date(toDateStr) : null;

            if (toDate) { // Adjust toDate to include the whole day
                toDate.setHours(23, 59, 59, 999);
            }

            document.querySelectorAll('.tab-content .tab-pane').forEach(tabPane => {
                const table = tabPane.querySelector('table');
                if (!table) return;

                const tbody = table.querySelector("tbody");
                if (!tbody) return;
                const rows = tbody.querySelectorAll("tr");
                let visibleRows = 0;

                rows.forEach(row => {
                    // Check if it's the "no records" row from PHP, not a data row
                    if (row.classList.contains('no-records-php-row')) { // Add this class in your PHP helper if needed
                        return; // Skip processing for PHP-generated "no records" row
                    }

                    const dateCell = row.cells[1]; // Due Date is the second cell (index 1)
                    if (!dateCell) return;

                    const dateText = dateCell.innerText;
                    try {
                        // Attempt to parse date assuming "Month Day, Year" format like "May 14, 2025"
                        const rowDate = new Date(dateText);
                        if (isNaN(rowDate.getTime())) { // Check if date is invalid
                            // console.warn("Invalid date format in table row:", dateText);
                            row.style.display = ""; // Show if date can't be parsed, or decide to hide
                            visibleRows++; // Or not, depending on desired behavior for unparsable dates
                            return;
                        }

                        let show = true;
                        if (fromDate && rowDate < fromDate) show = false;
                        if (toDate && rowDate > toDate) show = false;

                        row.style.display = show ? "" : "none";
                        if (show) visibleRows++;
                    } catch (e) {
                        // console.error("Error parsing date for filtering:", dateText, e);
                        row.style.display = ""; // Fallback: show row if date parsing fails
                        visibleRows++;
                    }
                });

                const noRecordsMessageDiv = tabPane.querySelector('.alert.no-records-row'); // The one from PHP
                const jsNoRecordsRow = tabPane.querySelector('.js-no-records-row'); // JS-managed message placeholder

                if (noRecordsMessageDiv) { // If PHP initially said "no records"
                    noRecordsMessageDiv.style.display = (visibleRows === 0 && (fromDateStr || toDateStr)) ? '' : 'none';
                    // Hide PHP message if filters are active and still no results, or if results appear
                }

                let currentJsNoRecordsRow = tabPane.querySelector('.js-no-records-row-instance');
                if (currentJsNoRecordsRow) {
                    currentJsNoRecordsRow.remove();
                }

                if (visibleRows === 0 && (fromDateStr || toDateStr)) { // Only show JS message if filters are active and yield no results
                    if (tbody) { // Ensure tbody exists
                        const newNoRecordsRow = tbody.insertRow();
                        newNoRecordsRow.className = 'js-no-records-row-instance';
                        const cell = newNoRecordsRow.insertCell();
                        cell.colSpan = table.tHead.rows[0].cells.length; // Match column count
                        cell.textContent = "No records match the selected date range.";
                        cell.className = 'text-center alert alert-info';
                    }
                }
            });
        }

        window.clearDateFilters = function () {
            document.getElementById('from').value = '';
            document.getElementById('to').value = '';
            filterTable(); // Re-apply filter to show all rows and clear JS "no records" messages
        }

        // Sidebar Toggle Logic (Example for a button with id="sidebarToggle")
        const sidebar = document.getElementById('adminSidebar');
        const contentWrapper = document.getElementById('content-wrapper');
        const sidebarToggleDesktop = document.getElementById('sidebarToggle'); // If you add a desktop toggle

        // This is for the mobile toggle already in the topbar
        // const sidebarToggleMobile = document.getElementById('sidebarToggleTop');


        if (sidebar && contentWrapper) { // Check if elements exist
            // For a desktop toggle button:
            if (sidebarToggleDesktop) {
                sidebarToggleDesktop.addEventListener('click', function () {
                    sidebar.classList.toggle('toggled');
                    contentWrapper.classList.toggle('toggled');
                });
            }
        }

        // Initialize tooltips (if any)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Call filterTable on page load if dates are pre-filled (e.g., from server-side or localStorage)
        // document.addEventListener('DOMContentLoaded', filterTable);

    </script>
</body>

</html>
<?php
// Helper function to generate payment table HTML
function generate_payment_table($data_array, $no_data_message)
{
    if (count($data_array) > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped table-hover table-bordered caption-top">';
        echo '<caption>List of payments</caption>';
        echo '<thead class="table-dark">'; // Bootstrap's dark header for contrast
        // For admin, loan_id in the table might be redundant if it's already in the card header for a single loan view
        // But if this function were used for a list of *different* loans' schedules, it would be useful.
        // For now, keeping it for consistency with your original structure.
        echo '<tr><th>Schedule ID</th><th>Due Date</th><th>Amount Due</th><th>Status</th><th>Remarks</th></tr>'; // Added Remarks
        echo '</thead>';
        echo '<tbody>';
        foreach ($data_array as $row) {
            $status_class = '';
            $status_text = htmlspecialchars(ucfirst($row['status']));
            switch (strtolower($row['status'])) {
                case 'settled':
                    $status_class = 'status-settled';
                    break;
                case 'unsettled':
                    $status_class = 'status-unsettled';
                    // Could add logic here for overdue items if due_date < today and unsettled
                    // For example:
                    // $due_date = new DateTime($row['due_date']);
                    // $today = new DateTime('today');
                    // if ($due_date < $today) {
                    //    $status_class = 'status-overdue';
                    //    $status_text = 'Overdue';
                    // }
                    break;
                case 'pending':
                    $status_class = 'status-pending';
                    break;
                // Add more cases as needed
            }
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['loan_sched_id']) . '</td>';
            echo '<td>' . date("F j, Y", strtotime($row['due_date'])) . '</td>';
            echo '<td>₱' . number_format($row['amount_due'], 2) . '</td>';
            echo '<td class="' . $status_class . '">' . $status_text . '</td>';
            echo '<td>' . (!empty($row['remarks']) ? htmlspecialchars($row['remarks']) : 'N/A') . '</td>'; // Display remarks
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        // Add class 'no-records-php-row' to distinguish from JS-generated message if needed
        echo '<div class="alert alert-info no-records-row">' . $no_data_message . '</div>';
    }
}

// Close connection if it was opened by this script
if ($conn) {
    // $conn->close(); // Might be closed too early if other parts of the page need it.
    // Best practice is to close it when absolutely done.
}
?>