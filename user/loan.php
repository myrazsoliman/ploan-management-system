<?php
// Ensure this is at the very top before any HTML output
date_default_timezone_set("Etc/GMT+8"); // Or your desired timezone
session_start(); // Start the session

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
            // IMPORTANT: Use your actual database credentials and details
            $this->conn = @new mysqli('localhost', 'root', '', 'db_lms'); // @ to suppress errors if DB doesn't exist
            if ($this->conn->connect_error) {
                // Allow page to render with a notice perhaps
                // error_log("DB Connection Error: " . $this->conn->connect_error);
            }
        }
        public function connect()
        {
            return $this->conn;
        }
    }
}

// Establish database connection
$db = new DBConnection();
$conn = $db->connect();

// --- Fetch user's active loans ---
$user_id = $_SESSION['user_id'] ?? 0; // Get user_id from session, default to 0 if not set
$active_loans = [];
$db_error_message = null;

if (!$conn) {
    $db_error_message = "Database connection failed. Cannot load loan information.";
} elseif ($user_id > 0) {
    // Fetch active loans for the logged-in user
    $sql = "SELECT l.loan_id, l.amount, l.status AS loan_status, l.date_released,
                   lp.plan_name
            FROM loan l
            JOIN loan_plans lp ON l.loan_plan_id = lp.loan_plan_id
            WHERE l.user_id = ? AND (l.status = 'Active' OR l.status = 'Ongoing' OR l.status = 'Approved')
            ORDER BY l.date_released DESC, l.loan_id DESC";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            // For each loan, try to get the next due date and remaining balance
            // This is a simplified approach. For exact remaining balance, you'd sum paid amounts.
            // For next due date, you'd query the loan_schedule.

            // Placeholder for Next Payment Due and Amount Remaining
            // These would typically require additional queries to loan_schedule table
            $row['next_payment_due'] = 'N/A'; // Placeholder
            $row['amount_remaining'] = $row['total_payable']; // Placeholder, needs calculation

            // Attempt to find the next unsettled payment for 'Next Payment Due'
            $next_due_sql = "SELECT MIN(due_date) as next_due 
                             FROM loan_schedule 
                             WHERE loan_id = ? AND status = 'Unsettled' AND due_date >= CURDATE()";
            $next_due_stmt = $conn->prepare($next_due_sql);
            if ($next_due_stmt) {
                $next_due_stmt->bind_param("i", $row['loan_id']);
                $next_due_stmt->execute();
                $next_due_result = $next_due_stmt->get_result();
                if ($next_due_row = $next_due_result->fetch_assoc()) {
                    if ($next_due_row['next_due']) {
                        $row['next_payment_due'] = date("M j, Y", strtotime($next_due_row['next_due']));
                    }
                }
                $next_due_stmt->close();
            }

            // Attempt to calculate remaining balance
            $paid_amount_sql = "SELECT SUM(amount_due) as total_paid 
                                FROM loan_schedule 
                                WHERE loan_id = ? AND status = 'Settled'";
            $paid_amount_stmt = $conn->prepare($paid_amount_sql);
            if ($paid_amount_stmt) {
                $paid_amount_stmt->bind_param("i", $row['loan_id']);
                $paid_amount_stmt->execute();
                $paid_amount_result = $paid_amount_stmt->get_result();
                if ($paid_row = $paid_amount_result->fetch_assoc()) {
                    $total_paid = $paid_row['total_paid'] ?? 0;
                    $row['amount_remaining'] = $row['total_payable'] - $total_paid;
                }
                $paid_amount_stmt->close();
            }

            $active_loans[] = $row;
        }
        $stmt->close();
    } else {
        // Handle statement preparation error
        $db_error_message = "Error preparing to fetch loans: " . $conn->error;
        error_log("Failed to prepare statement for loans: " . $conn->error);
    }
    // It's generally better to close the connection at the end of the script or when it's no longer needed.
    // $conn->close(); 
} elseif ($user_id === 0) {
    // User not logged in or session expired
    // Redirect to login page or show an error
    // For this example, we'll just show a message, but redirection is better.
    $db_error_message = "User not identified. Please log in to view your loans.";
    // header('Location: login.php'); // Example redirect
    // exit;
}

// Helper function to generate loan card HTML
function generate_loan_card($loan_data)
{
    $status_class = '';
    $status_text = ucfirst(htmlspecialchars($loan_data['loan_status']));
    switch (strtolower($loan_data['loan_status'])) {
        case 'active':
        case 'ongoing':
            $status_class = 'bg-success text-white'; // Green for active
            break;
        case 'pending':
        case 'approved': // Assuming 'Approved' means active but maybe not yet released or first payment not due
            $status_class = 'bg-warning text-dark'; // Yellow for pending/approved
            break;
        case 'paid off':
            $status_class = 'bg-secondary text-white'; // Grey for paid off
            break;
        case 'defaulted':
            $status_class = 'bg-danger text-white'; // Red for defaulted
            break;
        default:
            $status_class = 'bg-light text-dark'; // Default
    }

    $principal = number_format($loan_data['principal_amount'], 2);
    $total_payable = number_format($loan_data['total_payable'], 2);
    $amount_remaining = number_format($loan_data['amount_remaining'], 2);
    $date_released = $loan_data['date_released'] ? date("F j, Y", strtotime($loan_data['date_released'])) : 'N/A';

    $card_html = '
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm loan-card">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: var(--primary-maroon-lighter); color: white;">
                <h6 class="mb-0">Loan ID: ' . htmlspecialchars($loan_data['loan_id']) . '</h6>
                <span class="badge ' . $status_class . '">' . $status_text . '</span>
            </div>
            <div class="card-body">
                <h5 class="card-title">' . htmlspecialchars($loan_data['plan_name']) . '</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Principal: <span class="fw-bold">₱' . $principal . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Payable: <span class="fw-bold">₱' . $total_payable . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Amount Remaining: <span class="fw-bold text-danger">₱' . $amount_remaining . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Next Payment Due: <span class="fw-bold">' . htmlspecialchars($loan_data['next_payment_due']) . '</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Date Released: <span class="fw-bold">' . $date_released . '</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer bg-light text-center">
                <a href="payment_history.php?loan_id=' . htmlspecialchars($loan_data['loan_id']) . '" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i> View Payment History
                </a>
            </div>
        </div>
    </div>';
    return $card_html;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Loans - PLOAN</title>
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
            /* Slightly lighter maroon */
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
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styles (Copied from payment_history.php for consistency) */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text-color);
            transition: width 0.3s ease;
            position: fixed;
            /* Or sticky, depending on overall layout strategy */
            top: 0;
            left: 0;
            z-index: 1030;
            /* Ensure sidebar is above other content */
            overflow-y: auto;
            /* Allow scrolling within sidebar if content exceeds height */
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
            /* Takes remaining width */
            min-height: 100vh;
            padding-left: 260px;
            /* Space for the sidebar */
            transition: padding-left 0.3s ease;
        }

        /* Adjust content padding when sidebar is toggled */
        body.sidebar-toggled #content-wrapper {
            /* If 'sidebar-toggled' is on body */
            padding-left: 90px;
        }

        /* Fallback if sidebar.php adds .toggled class directly to .sidebar */
        .sidebar.toggled+#content-wrapper {
            padding-left: 90px;
        }


        /* Topbar (Placeholder styles if you have a topbar) */
        .navbar.topbar {
            height: 70px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background-color: #fff;
            /* Or var(--card-bg) */
            position: sticky;
            /* Makes topbar stay at top */
            top: 0;
            z-index: 1020;
            /* Below sidebar but above content */
        }

        .sidebar-toggle-btn {
            /* For the button in topbar to toggle sidebar */
            color: var(--primary-maroon);
            font-size: 1.25rem;
        }

        .sidebar-toggle-btn:hover {
            color: var(--primary-maroon-darker);
        }


        /* Main Content Area */
        #main-content {
            flex: 1 0 auto;
            /* Allows footer to stick to bottom */
            padding: 1.5rem;
            /* Standard padding */
        }

        /* Footer */
        .sticky-footer {
            padding: 1.5rem;
            background-color: #e9ecef;
            /* Or var(--card-bg) for consistency */
            color: var(--text-dark);
            flex-shrink: 0;
            /* Prevents footer from shrinking */
        }

        /* Loan Card Specific Styles */
        .loan-card .card-header {
            font-weight: 600;
        }

        .loan-card .list-group-item {
            background-color: transparent;
            /* Make items blend with card body */
            border-left: 0;
            border-right: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .loan-card .list-group-item:first-child {
            border-top: 0;
        }

        .loan-card .list-group-item:last-child {
            border-bottom: 0;
        }

        .loan-card .btn-outline-primary {
            color: var(--primary-maroon);
            border-color: var(--primary-maroon);
        }

        .loan-card .btn-outline-primary:hover {
            background-color: var(--primary-maroon);
            color: white;
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 767.98px) {
            #content-wrapper {
                padding-left: 0;
                /* No padding for content when sidebar is off-canvas or hidden */
            }

            .sidebar {
                /* On mobile, sidebar might be an offcanvas or hidden by default. 
                   This example assumes it's fixed or managed by Bootstrap's offcanvas for mobile.
                   If using Bootstrap offcanvas, it will handle its own positioning. */
            }

            /* If sidebar.php uses #accordionSidebar and it's not offcanvas on mobile but fixed, then: */
            body:not(.sidebar-toggled) #content-wrapper {
                padding-left: 0;
                /* Full width content if sidebar is not toggled (mobile might hide it) */
            }
        }
    </style>
</head>

<body class="antialiased">
    <div id="wrapper" class="d-flex"> <?php
    // Include sidebar - Ensure sidebar.php generates the <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar"> structure
    if (file_exists('sidebar.php')) {
        include('sidebar.php');
    } else if (file_exists('../sidebar.php')) { // Check parent directory
        include('../sidebar.php');
    } else {
        // Fallback minimal sidebar if file not found (for testing purposes)
        echo '<ul class="sidebar navbar-nav" id="accordionSidebar" style="background-color: var(--sidebar-bg); color: var(--sidebar-text-color); width:260px; padding-top:1rem;">
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard (Fallback)</span></a></li>
                  </ul>';
    }
    ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <?php
            // --- Topbar ---
            // You would include your topbar.php here if you have one.
            // Example: include('topbar.php');
            // For this example, a placeholder topbar:
            echo '
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 sidebar-toggle-btn">
                    <i class="fa fa-bars"></i>
                </button>
                <span class="ms-3 d-none d-md-inline">PLOAN System</span> 
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">' . ($_SESSION['user_name'] ?? 'User') . '</span>
                            <img class="img-profile rounded-circle" src="https://placehold.co/60x60/800000/FFF?text=' . strtoupper(substr(($_SESSION['user_name'] ?? 'U'), 0, 1)) . '" style="width:30px; height:30px;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="profile.php"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>
                        </div>
                    </li>
                </ul>
            </nav>';
            ?>

            <div id="main-content" class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">My Active Loans</h1>
                </div>

                <?php if ($db_error_message): ?>
                    <div class="alert alert-danger"><?php echo $db_error_message; ?></div>
                <?php endif; ?>

                <?php if ($user_id > 0 && !$db_error_message): // Only show loan content if user is identified and no major DB error ?>
                    <?php if (count($active_loans) > 0): ?>
                        <div class="row">
                            <?php foreach ($active_loans as $loan): ?>
                                <?php echo generate_loan_card($loan); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            You currently have no active loans.
                            <?php if (file_exists('loan_plan.php')): // Suggest applying if loan_plan.php exists ?>
                                <br> You can <a href="loan_plan.php" class="alert-link">explore our loan plans</a> to apply for a
                                new one.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php elseif ($user_id === 0 && !$db_error_message): ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Please <a href="login.php" class="alert-link">log in</a> to view your loan information.
                    </div>
                <?php endif; ?>

            </div>

            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; PLOAN <?php echo date("Y"); ?></span>
                    </div>
                </div>
            </footer>
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
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'sched.php') ? 'active' : ''; ?>"> <a
                        class="nav-link" href="sched.php"><i class="fas fa-fw fa-history"></i><span>History</span></a>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar Toggle Logic for Desktop (targets #accordionSidebar from sidebar.php)
            const desktopSidebar = document.getElementById('accordionSidebar'); // Main desktop sidebar
            const sidebarToggleDesktopBtn = document.getElementById('sidebarToggle'); // Button in your main sidebar (if any)
            const sidebarToggleTopBtn = document.getElementById('sidebarToggleTop'); // Button in topbar (usually for mobile or alternative toggle)

            function toggleDesktopSidebar() {
                if (desktopSidebar) {
                    document.body.classList.toggle('sidebar-toggled');
                    desktopSidebar.classList.toggle('toggled');
                    // Optional: Store state in localStorage
                    if (window.innerWidth > 768) { // Only for desktop
                        localStorage.setItem('sidebarToggled', desktopSidebar.classList.contains('toggled'));
                    }
                }
            }

            if (sidebarToggleDesktopBtn) {
                sidebarToggleDesktopBtn.addEventListener('click', toggleDesktopSidebar);
            }

            // This button is often used in SB Admin templates for toggling from the topbar
            // It can also trigger the main sidebar or an offcanvas sidebar on mobile
            if (sidebarToggleTopBtn) {
                sidebarToggleTopBtn.addEventListener('click', function () {
                    if (window.innerWidth < 768) { // On mobile, trigger offcanvas
                        var mobileSidebar = new bootstrap.Offcanvas(document.getElementById('mobileSidebar'));
                        mobileSidebar.toggle();
                    } else { // On desktop, toggle the main sidebar
                        toggleDesktopSidebar();
                    }
                });
            }


            // Restore sidebar state on desktop
            if (window.innerWidth > 768 && desktopSidebar) {
                const isSidebarToggled = localStorage.getItem('sidebarToggled');
                if (isSidebarToggled === 'true') {
                    document.body.classList.add('sidebar-toggled');
                    desktopSidebar.classList.add('toggled');
                }
            }

            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>

</html>
<?php
if ($conn) {
    // $conn->close(); // Close connection if it was opened and is no longer needed.
}
?>