<?php
// Ensure this is at the very top before any HTML output
date_default_timezone_set("Etc/GMT+8"); // Or your desired timezone
session_start(); // If you use sessions for anything relevant

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
            $this->conn = @new mysqli('localhost', 'root', '', 'db_lms'); // @ to suppress errors if DB doesn't exist
            if ($this->conn->connect_error) {
                // Don't die, but allow page to render with a notice perhaps
                // For this example, we'll proceed as if data fetching might fail gracefully
            }
        }
        public function connect()
        {
            return $this->conn;
        }
    }
    // $db = new DBConnection(); // Instantiate if needed globally, or handle in functions
    // $conn = $db->connect();
}
if (!isset($conn) || !$conn) { // If connection wasn't established or is invalid
    // Fallback for when DB connection is not available in this example
    // This allows the page to render without fatal errors if DB is down or not configured
    // You'd have more robust error handling in a real app.
    $conn = null; // Set to null to avoid errors in prepare() if connection failed
}


// --- Your existing PHP logic for fetching data ---
$user_id = $_SESSION['user_id'] ?? 0; // Assuming user_id is set in session
$loan_id = isset($_GET['loan_id']) ? (int) $_GET['loan_id'] : 0; // Sanitize loan_id

$settled = [];
$unsettled = [];
$all = [];

if ($conn && $loan_id > 0) { // Proceed only if connection exists and loan_id is valid
    $sql = "SELECT loan_sched_id, loan_id, due_date, amount_due, status 
            FROM loan_schedule
            WHERE loan_id = ?
            ORDER BY due_date ASC";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $loan_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $all[] = $row;
            if (strtolower($row['status']) === 'settled') {
                $settled[] = $row;
            } else {
                $unsettled[] = $row;
            }
        }
        $stmt->close();
    } else {
        // Handle statement preparation error, e.g., log it
        // For this example, arrays will remain empty
        error_log("Failed to prepare statement: " . $conn->error);
    }
    // $conn->close(); // Close connection if it was opened by this script specifically
} elseif (!$conn) {
    // Optional: Display a message if DB connection failed
    // $db_error_message = "Database connection failed. Payment history cannot be loaded.";
} elseif ($loan_id === 0) {
    // Optional: Display a message if loan_id is missing
    // $loan_id_missing_message = "Loan ID not specified. Payment history cannot be loaded.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>History - PLOAN</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-maroon: #800000;
            --primary-maroon-darker: #600000;
            --primary-maroon-lighter: #a00000;
            --sidebar-bg: var(--primary-maroon);
            --sidebar-text-color: #f8f9fa;
            --sidebar-text-hover-color: #ffffff;
            --sidebar-icon-color: rgba(255, 255, 255, 0.7);
            --sidebar-icon-hover-color: #ffffff;
            --sidebar-active-bg: var(--primary-maroon-darker);
            --sidebar-active-text-color: #ffffff;
            --sidebar-divider-color: rgba(255, 255, 255, 0.15);
            --content-bg: #f4f7fc;
            --text-dark: #343a40;
            --card-bg: #ffffff;
            --card-border-color: #dee2e6;
            --table-header-bg: var(--primary-maroon);
            --table-header-color: #ffffff;
            --table-hover-bg: rgba(128, 0, 0, 0.05);
            /* Light maroon for hover */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text-color);
            transition: width 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            overflow-y: auto;
        }

        .sidebar .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--sidebar-text-hover-color);
            text-decoration: none;
            white-space: nowrap;
        }

        .sidebar .sidebar-brand .sidebar-brand-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
            transition: margin 0.3s ease;
        }

        .sidebar .sidebar-brand .sidebar-brand-text {
            display: inline;
            transition: opacity 0.2s ease;
        }

        .sidebar .nav-item .nav-link {
            color: var(--sidebar-text-color);
            padding: 0.9rem 1.25rem;
            display: flex;
            align-items: center;
            white-space: nowrap;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .sidebar .nav-item .nav-link .fa-fw {
            width: 1.25em;
            margin-right: 0.75rem;
            color: var(--sidebar-icon-color);
            transition: color 0.2s ease;
        }

        .sidebar .nav-item .nav-link:hover,
        .sidebar .nav-item .nav-link:focus {
            background-color: var(--primary-maroon-lighter);
            color: var(--sidebar-text-hover-color);
        }

        .sidebar .nav-item .nav-link:hover .fa-fw,
        .sidebar .nav-item .nav-link:focus .fa-fw {
            color: var(--sidebar-icon-hover-color);
        }

        .sidebar .nav-item.active>.nav-link {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-active-text-color);
            font-weight: 500;
        }

        .sidebar .nav-item.active>.nav-link .fa-fw {
            color: var(--sidebar-active-text-color);
        }

        .sidebar .sidebar-divider {
            margin: 0.75rem 1rem;
            border-top: 1px solid var(--sidebar-divider-color);
        }

        .sidebar.toggled {
            width: 90px;
        }

        .sidebar.toggled .sidebar-brand .sidebar-brand-text {
            display: none;
        }

        .sidebar.toggled .sidebar-brand .sidebar-brand-icon {
            margin-right: 0;
        }

        .sidebar.toggled .nav-item .nav-link span {
            display: none;
        }

        .sidebar.toggled .nav-item .nav-link {
            justify-content: center;
        }

        .sidebar.toggled .nav-item .nav-link .fa-fw {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .sidebar.toggled .sidebar-divider {
            margin: 0.75rem 0.5rem;
        }

        /* Content Wrapper */
        #content-wrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
            min-height: 100vh;
            padding-left: 260px;
            transition: padding-left 0.3s ease;
        }

        .sidebar.toggled+#content-wrapper {
            padding-left: 90px;
        }

        /* Topbar */
        .navbar.topbar {
            height: 70px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background-color: #fff;
        }

        .sidebar-toggle-btn {
            color: var(--primary-maroon);
            font-size: 1.25rem;
        }

        .sidebar-toggle-btn:hover {
            color: var(--primary-maroon-darker);
        }

        /* Main Content Area */
        #main-content {
            flex: 1 0 auto;
            padding: 1.5rem;
        }

        .btn .btn-sm {
            background-color: var(--primary-maroon);
            color: white;
            padding: 10px 20px;
            border-radius: 0.5rem;
        }

        /* Payment History Specific Styles */
        .payment-history-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border-color);
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }

        .payment-history-card .card-header {
            background-color: var(--primary-maroon);
            color: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--primary-maroon-darker);
        }

        .date-filter-section {
            background-color: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--card-border-color);
            border-radius: 0.5rem 0.5rem 0 0;
            /* Match card header if used above tabs */
        }

        .nav-tabs .nav-link {
            color: var(--primary-maroon);
            border-bottom-width: 2px;
            /* Thicker bottom border for inactive tabs */
            border-color: transparent transparent var(--primary-maroon-lighter);
        }

        .nav-tabs .nav-link.active {
            color: var(--text-dark);
            background-color: var(--card-bg);
            border-color: var(--primary-maroon) var(--primary-maroon) var(--card-bg);
            font-weight: 600;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent transparent var(--primary-maroon);
        }

        .table th {
            background-color: var(--table-header-bg);
            color: var(--table-header-color);
            font-weight: 600;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table-hover tbody tr:hover {
            background-color: var(--table-hover-bg);
        }

        .status-settled {
            color: #198754;
            font-weight: 500;
        }

        /* Bootstrap success color */
        .status-unsettled {
            color: #dc3545;
            font-weight: 500;
        }

        /* Bootstrap danger color */
        .status-pending {
            color: #ffc107;
            font-weight: 500;
        }


        #content-wrapper {
            padding-left: 0;
        }

        .date-filter-section .row>div {
            margin-bottom: 0.5rem;
        }
        }
    </style>
</head>

<body class="antialiased">
    <?php
    // --- Assume Sidebar is included here ---
    // It might affect the layout (e.g., adding padding-left to #main-content-wrapper)
    if (file_exists('sidebar.php')) {
        include('sidebar.php');
    } else if (file_exists('../sidebar.php')) {
        include('../sidebar.php');
    }
    ?>
    <div id="main-content" class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">My Loan History</h1>
            <a href="apply_loan.php" class="btn btn-sm shadow-sm"
                style="background-color: var(--primary-maroon); color: white;">
                <i class="fas fa-plus-circle fa-sm text-white-50"></i> Apply for New Loan
            </a>
        </div>

        <?php if (isset($db_error_message)): ?>
            <div class="alert alert-danger"><?php echo $db_error_message; ?></div>
        <?php endif; ?>
        <?php if (isset($loan_id_missing_message)): ?>
            <div class="alert alert-warning"><?php echo $loan_id_missing_message; ?></div>
        <?php endif; ?>


        <div class="card payment-history-card">
            <div class="card-header">
                Loan ID: <?php echo htmlspecialchars($loan_id); ?> - Payment Schedule
            </div>
            <div class="card-body">
                <div class="date-filter-section mb-3 p-3 border rounded bg-light">
                    <form class="row g-3 align-items-center">
                        <div class="col-md-auto">
                            <label for="from" class="form-label mb-0 me-2">From:</label>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control form-control-sm" id="from" onchange="filterTable()">
                        </div>
                        <div class="col-md-auto">
                            <label for="to" class="form-label mb-0 me-2">To:</label>
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
                        <?php generate_payment_table($all, "No payment records found for this loan."); ?>
                    </div>
                    <div class="tab-pane fade" id="unsettled-tab-pane" role="tabpanel" aria-labelledby="unsettled-tab"
                        tabindex="0">
                        <?php generate_payment_table($unsettled, "No unsettled payments found."); ?>
                    </div>
                    <div class="tab-pane fade" id="settled-tab-pane" role="tabpanel" aria-labelledby="settled-tab"
                        tabindex="0">
                        <?php generate_payment_table($settled, "No settled payments found."); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel"
        style="background-color: var(--sidebar-bg); color: var(--sidebar-text-color); width: 260px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarLabel" style="color: var(--sidebar-text-hover-color);">
                <i class="fas fa-landmark me-2"></i>PLOAN USER
            </h5>
            <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <ul class="navbar-nav">
                <li
                    class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php' || basename($_SERVER['PHP_SELF']) == 'userhomepage.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="home.php"><i class="fas fa-fw fa-home"></i><span>Home</span></a>
                </li>
                <hr class="sidebar-divider my-0" style="border-color: var(--sidebar-divider-color);">
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="loan.php"><i
                            class="fas fa-fw fa-comment-dollar"></i><span>Loans</span></a>
                </li>
                <li
                    class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'payment.php' || basename($_SERVER['PHP_SELF']) == 'payment_history.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="payment.php"><i class="fas fa-fw fa-coins"></i><span>Payments</span></a>
                </li>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'sched.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="sched.php"><i class="fas fa-fw fa-history"></i><span>History</span></a>
                </li>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan_plan.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="loan_plan.php"><i class="fas fa-fw fa-credit-card"></i><span>Loan
                            Plans</span></a>
                </li>
                <hr class="sidebar-divider" style="border-color: var(--sidebar-divider-color);">
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="profile.php"><i class="fas fa-fw fa-user-circle"></i><span>My
                            Profile</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i
                            class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <script>
        // Date Filtering Logic
        window.filterTable = function () {
            const fromDateStr = document.getElementById('from').value;
            const toDateStr = document.getElementById('to').value;
            const fromDate = fromDateStr ? new Date(fromDateStr) : null;
            const toDate = toDateStr ? new Date(toDateStr) : null;

            // Adjust toDate to include the whole day
            if (toDate) {
                toDate.setHours(23, 59, 59, 999);
            }


            document.querySelectorAll('.tab-content').forEach(tabPane => {
                const table = tabPane.querySelector('table');
                if (!table) return;
                const rows = table.querySelectorAll("tbody tr");
                let visibleRows = 0;
                rows.forEach(row => {
                    const dateCell = row.cells[1]; // Assuming due date is the second cell
                    if (!dateCell) { // Handle "No records" row
                        if (row.cells.length === 1 && row.cells[0].colSpan === 4) {
                            // This is a "No records" row, keep it visible initially
                            // It will be hidden if other rows become visible
                        }
                        return;
                    }
                    const dateText = dateCell.innerText;
                    const rowDate = new Date(dateText);

                    let show = true;
                    if (fromDate && rowDate < fromDate) show = false;
                    if (toDate && rowDate > toDate) show = false;

                    row.style.display = show ? "" : "none";
                    if (show) visibleRows++;
                });

                // Handle "No records found" message within each table
                const noRecordsRow = tabPane.querySelector('.no-records-row');
                if (noRecordsRow) {
                    noRecordsRow.style.display = visibleRows === 0 ? '' : 'none';
                }
            });
        }

        window.clearDateFilters = function () {
            document.getElementById('from').value = '';
            document.getElementById('to').value = '';
            filterTable(); // Re-apply filter to show all rows
        }

        // Initialize tooltips (if any)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        });
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
        echo '<thead class="table-dark">'; // Using Bootstrap's dark header for contrast
        echo '<tr><th>Loan ID</th><th>Due Date</th><th>Amount Due</th><th>Status</th></tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($data_array as $row) {
            $status_class = '';
            switch (strtolower($row['status'])) {
                case 'settled':
                    $status_class = 'status-settled';
                    break;
                case 'unsettled':
                    $status_class = 'status-unsettled';
                    break;
                case 'pending':
                    $status_class = 'status-pending';
                    break; // Example for another status
            }
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['loan_id']) . '</td>';
            echo '<td>' . date("F j, Y", strtotime($row['due_date'])) . '</td>';
            echo '<td>₱' . number_format($row['amount_due'], 2) . '</td>';
            echo '<td class="' . $status_class . '">' . htmlspecialchars(ucfirst($row['status'])) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-info no-records-row">' . $no_data_message . '</div>';
    }
}
?>