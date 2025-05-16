<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PLOAN User Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
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
            /* Light background for content area */
            --text-dark: #343a40;
            --text-gray-400: #d1d3e2;
            /* Added for consistency */
            --text-gray-500: #b7b9cc;
            /* Added for consistency */
            --text-gray-600: #858796;
            /* Added for consistency */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text-color);
            transition: width 0.3s ease;
            position: fixed;
            /* Fixed position */
            top: 0;
            left: 0;
            z-index: 1030;
            /* Ensure it's above content but below modals if any */
            overflow-y: auto;
            /* Scroll for long sidebars */
        }

        .sidebar .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            /* Center brand text/logo */
            padding: 1.25rem 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--sidebar-text-hover-color);
            text-decoration: none;
            white-space: nowrap;
            /* Prevent text wrapping when collapsing */
        }

        .sidebar .sidebar-brand .sidebar-brand-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
            /* Space between icon and text */
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
            /* Font Awesome fixed width */
            margin-right: 0.75rem;
            /* Increased space */
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
            /* Slightly bolder active link */
        }

        .sidebar .nav-item.active>.nav-link .fa-fw {
            color: var(--sidebar-active-text-color);
        }

        .sidebar .sidebar-divider {
            margin: 0.75rem 1rem;
            border-top: 1px solid var(--sidebar-divider-color);
        }

        /* Sidebar Toggled State (Collapsed) */
        .sidebar.toggled {
            width: 90px;
            /* Width when collapsed */
        }

        .sidebar.toggled .sidebar-brand .sidebar-brand-text {
            display: none;
            /* Hide text when collapsed */
        }

        .sidebar.toggled .sidebar-brand .sidebar-brand-icon {
            margin-right: 0;
            /* Remove margin when text is hidden */
        }

        .sidebar.toggled .nav-item .nav-link span {
            display: none;
            /* Hide link text */
        }

        .sidebar.toggled .nav-item .nav-link {
            justify-content: center;
            /* Center icon when collapsed */
        }

        .sidebar.toggled .nav-item .nav-link .fa-fw {
            margin-right: 0;
            font-size: 1.2rem;
            /* Slightly larger icons when collapsed */
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
            /* Same as sidebar width */
            transition: padding-left 0.3s ease;
        }

        .sidebar.toggled+#content-wrapper {
            padding-left: 90px;
            /* Same as toggled sidebar width */
        }

        /* Topbar */
        .navbar.topbar {
            height: 70px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background-color: #fff;
            /* White topbar */
            position: sticky;
            /* Make topbar sticky */
            top: 0;
            z-index: 1020;
            /* Below sidebar overlay, above content */
        }

        .sidebar-toggle-btn {
            color: var(--primary-maroon);
            font-size: 1.25rem;
        }

        .sidebar-toggle-btn:hover {
            color: var(--primary-maroon-darker);
        }

        /* --- START: Added for Notification/Message Icons --- */
        .topbar .nav-item .nav-link {
            height: 70px;
            /* Match topbar height */
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
            /* Adjust padding */
        }

        .topbar .nav-item .nav-link:hover {
            background-color: #eaecf4;
            /* Light hover effect */
        }

        .topbar .dropdown-list {
            width: 20rem !important;
            /* Set a width for dropdown */
            padding: 0 !important;
            overflow: hidden;
            /* Prevent overflow issues */
        }

        .topbar .dropdown-list .dropdown-header {
            background-color: var(--primary-maroon);
            border: 1px solid var(--primary-maroon);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            color: #fff;
            font-weight: 600;
        }

        .topbar .dropdown-list .dropdown-item {
            white-space: normal;
            /* Allow text wrapping */
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            border-left: 1px solid #e3e6f0;
            border-right: 1px solid #e3e6f0;
            border-bottom: 1px solid #e3e6f0;
            line-height: 1.3rem;
        }

        .topbar .dropdown-list .dropdown-item .text-truncate {
            /* Bootstrap class for ellipsis */
            max-width: 13.375rem;
        }

        .topbar .dropdown-list .dropdown-item:active {
            background-color: #eaecf4;
            color: #3a3b45;
        }

        .topbar .dropdown-list .dropdown-item .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            /* Icon color inside circle */
        }

        .topbar .dropdown-list .dropdown-item .small {
            font-size: 0.75rem;
            /* Make timestamp smaller */
        }

        /* Badge Counter Styling */
        .topbar .nav-item.dropdown .nav-link .badge-counter {
            position: absolute;
            transform: scale(0.7);
            transform-origin: top right;
            right: 0.5rem;
            /* Adjust horizontal position */
            margin-top: -0.5rem;
            /* Adjust vertical position */
            font-size: 0.7rem;
            /* Badge font size */
        }

        /* --- END: Added for Notification/Message Icons --- */


        /* Main Content Area */
        #main-content {
            flex: 1 0 auto;
            padding: 1.5rem;
            margin-top: 70px;
            /* Add margin top equal to topbar height */
        }

        /* Footer */
        .sticky-footer {
            padding: 1.5rem;
            background-color: #e9ecef;
            /* Light gray footer */
            color: var(--text-dark);
            flex-shrink: 0;
            /* Prevent footer from shrinking */
        }

        /* Responsive: Offcanvas sidebar for small screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                /* Hide sidebar by default */
                position: fixed;
                /* Keep it fixed for offcanvas */
            }

            /* Offcanvas takes over, so .toggled logic for width on mobile isn't needed here */

            #content-wrapper {
                padding-left: 0;
                /* Full width content */
            }

            /* Adjust top padding for smaller screens if needed */
            #main-content {
                margin-top: 70px;
                /* Ensure content starts below sticky topbar */
            }

            /* Hide desktop toggle on mobile */
            .sidebar .text-center.d-none.d-md-inline {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <ul class="navbar-nav sidebar d-none d-md-flex" id="accordionSidebar">

        <a class="sidebar-brand" href="dashboard.php">
            <span class="sidebar-brand-text">PLOAN USER</span>
        </a>

        <hr class="sidebar-divider my-0">

        <li
            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php' || basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="apply_loan.php">
                <i class="fas fa-fw fa-comment-dollar"></i>
                <span>Loans</span>
            </a>
        </li>

        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'payment.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="payment.php">
                <i class="fas fa-fw fa-coins"></i>
                <span>Payments</span>
            </a>
        </li>

        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'history.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="history.php">
                <i class="fas fa-fw fa-history"></i>
                <span>History</span>
            </a>
        </li>
        <hr>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="profile.php">
                <i class="fas fa-fw fa-user-circle"></i> <span>My Profile</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"> <i
                    class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="btn rounded-circle border-0 p-2" id="sidebarToggleDesktop" title="Toggle sidebar"
                style="background-color: var(--primary-maroon-lighter);">
                <i class="fas fa-angle-left text-white"></i>
            </button>
        </div>

    </ul>
    <div id="content-wrapper">

        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

            <button class="btn btn-link d-md-none rounded-circle me-3 sidebar-toggle-btn" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i class="fa fa-bars"></i>
            </button>

            <ul class="navbar-nav ms-auto">

                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <span class="badge bg-danger badge-counter">3+</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-list shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                            Notifications
                        </h6>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="me-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">May 12, 2025</div>
                                <span class="fw-bold">A new monthly report is ready to download!</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="me-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-donate text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">May 7, 2025</div>
                                ₱290.29 has been deposited into your account!
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="me-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">May 2, 2025</div>
                                Spending Alert: We've noticed unusually high spending for your account.
                            </div>
                        </a>
                        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Notifications</a>
                    </div>
                </li>

                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope fa-fw"></i>
                        <span class="badge bg-warning badge-counter">7</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-list shadow animated--grow-in"
                        aria-labelledby="messagesDropdown">
                        <h6 class="dropdown-header">
                            Messages
                        </h6>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image me-3">
                                <img class="rounded-circle" src="https://via.placeholder.com/60/800000/FFFFFF?text=U1"
                                    alt="...">
                                <div class="status-indicator bg-success"></div>
                            </div>
                            <div class="fw-bold">
                                <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                    problem I've been having.</div>
                                <div class="small text-gray-500">Emily Fowler · 58m</div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image me-3">
                                <img class="rounded-circle" src="https://via.placeholder.com/60/600000/FFFFFF?text=U2"
                                    alt="...">
                                <div class="status-indicator"></div>
                            </div>
                            <div>
                                <div class="text-truncate">I have the photos that you ordered last month, how would you
                                    like them sent to you?</div>
                                <div class="small text-gray-500">Jae Chun · 1d</div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image me-3">
                                <img class="rounded-circle" src="https://via.placeholder.com/60/A00000/FFFFFF?text=U3"
                                    alt="...">
                                <div class="status-indicator bg-warning"></div>
                            </div>
                            <div>
                                <div class="text-truncate">Last month's report looks great, I am very happy with the
                                    progress so far, keep up the good work!</div>
                                <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                            </div>
                        </a>
                        <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                    </div>
                </li>

                <div class="topbar-divider d-none d-sm-block mx-1"></div>
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="me-2 d-none d-lg-inline text-gray-600 small">
                            <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                        </span>
                        <img class="img-profile rounded-circle"
                            src="https://via.placeholder.com/60/800000/FFFFFF?text=A" alt="User Profile" width="32"
                            height="32"> </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="profile.php">
                            <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>
        </nav>
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
                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'apply_loan.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="apply_loan.php"><i
                                class="fas fa-fw fa-comment-dollar"></i><span>Loans</span></a>
                    </li>
                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'payment.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="payment.php"><i
                                class="fas fa-fw fa-coins"></i><span>Payments</span></a>
                    </li>
                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'history.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="hisdtory.php"><i
                                class="fas fa-fw fa-history"></i><span>History</span></a>
                    </li>
                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan_plan.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="loan_plan.php"><i class="fas fa-fw fa-credit-card"></i><span>Loan
                                Plans</span></a>
                    </li>
                    <hr class="sidebar-divider">
                    <li
                        class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="profile.php"><i class="fas fa-fw fa-user-circle"></i><span>My
                                Profile</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"> <i
                                class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to Logout?</div>
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
            document.addEventListener('DOMContentLoaded', function () {
                const sidebar = document.getElementById('accordionSidebar');
                const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
                // const contentWrapper = document.getElementById('content-wrapper'); // Not strictly needed for toggle logic here
                const angleIcon = sidebarToggleDesktop ? sidebarToggleDesktop.querySelector('i') : null; // Check if exists

                // --- Desktop Sidebar Toggle Logic ---
                if (sidebar && sidebarToggleDesktop && angleIcon) { // Ensure all elements exist
                    // Function to apply toggle state
                    const applyToggleState = (isToggled) => {
                        if (isToggled) {
                            sidebar.classList.add('toggled');
                            angleIcon.classList.remove('fa-angle-left');
                            angleIcon.classList.add('fa-angle-right');
                        } else {
                            sidebar.classList.remove('toggled');
                            angleIcon.classList.remove('fa-angle-right');
                            angleIcon.classList.add('fa-angle-left');
                        }
                    };

                    // Check localStorage on load (only for desktop)
                    if (window.innerWidth > 768) {
                        const isSidebarToggled = localStorage.getItem('sidebarToggled') === 'true';
                        applyToggleState(isSidebarToggled);
                    } else {
                        // On mobile, ensure sidebar starts hidden visually if using toggle class (though offcanvas handles this better)
                        // sidebar.classList.add('toggled'); // Optional: Force toggled state if needed visually before offcanvas kicks in
                    }


                    // Add click listener for desktop toggle
                    sidebarToggleDesktop.addEventListener('click', function () {
                        const newState = !sidebar.classList.contains('toggled');
                        applyToggleState(newState);

                        // Persist state only on desktop
                        if (window.innerWidth > 768) {
                            localStorage.setItem('sidebarToggled', newState);
                        }
                    });
                }

                // --- Mobile Sidebar (Offcanvas) ---
                // Bootstrap's data-bs-toggle handles the offcanvas show/hide.
                // No extra JS is typically needed unless you want to react to its events.

                // --- Tooltip Initialization (Keep if you use tooltips) ---
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            });
        </script>

</body>

</html>