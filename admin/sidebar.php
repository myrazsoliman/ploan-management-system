<?php
// Start session if not already started. This is important for user-specific features.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// notifications_backend.php should be in the same directory or an accessible path
// For this example, we assume it's in the same directory.
// The actual PHP logic for fetching notifications will be in notifications_backend.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PLOAN Admin Dashboard</title>
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
            --text-dark: #343a40;
            --text-gray-400: #d1d3e2;
            --text-gray-500: #b7b9cc;
            --text-gray-600: #858796;
            --notification-unread-bg: #e9f5ff;
            /* Light blue for unread notifications */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styles (from your original code) */
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

        .navbar.topbar {
            height: 70px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background-color: #fff;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .sidebar-toggle-btn {
            color: var(--primary-maroon);
            font-size: 1.25rem;
        }

        .sidebar-toggle-btn:hover {
            color: var(--primary-maroon-darker);
        }

        /* Topbar Notification/Message Icons (from your original code with slight adjustments) */
        .topbar .nav-item .nav-link {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
        }

        .topbar .nav-item .nav-link:hover {
            background-color: #eaecf4;
        }

        /* Styles for the dynamic notification dropdown */
        .topbar .dropdown-list {
            /* width: 20rem !important; */
            /* Let content define width or use a wider one for notifications */
            width: 320px !important;
            /* A bit wider for notification content */
            max-width: 90vw;
            /* Ensure it doesn't overflow on small screens */
            padding: 0 !important;
            overflow: hidden;
            border: 1px solid #e3e6f0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .topbar .dropdown-list .dropdown-header {
            background-color: var(--primary-maroon);
            border: 1px solid var(--primary-maroon);
            padding: 0.75rem 1rem;
            /* Adjusted padding */
            color: #fff;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar .dropdown-list .dropdown-header .mark-all-read-btn {
            font-size: 0.8rem;
            color: #ffc107;
            /* A contrasting color like yellow/amber */
            text-decoration: none;
            font-weight: normal;
        }

        .topbar .dropdown-list .dropdown-header .mark-all-read-btn:hover {
            color: #fff;
            text-decoration: underline;
        }


        /* Notification Item Styling */
        #notificationItemsContainer {
            max-height: 350px;
            /* Limit height and make scrollable */
            overflow-y: auto;
        }

        /* Scrollbar styling for webkit browsers */
        #notificationItemsContainer::-webkit-scrollbar {
            width: 6px;
        }

        #notificationItemsContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #notificationItemsContainer::-webkit-scrollbar-thumb {
            background: #aaa;
            border-radius: 10px;
        }

        #notificationItemsContainer::-webkit-scrollbar-thumb:hover {
            background: #888;
        }


        .notification-item-link {
            text-decoration: none;
            color: inherit;
        }

        .notification-item-link:hover .dropdown-item {
            background-color: #f8f9fa;
            /* Standard Bootstrap hover */
        }

        .topbar .dropdown-list .dropdown-item {
            white-space: normal;
            padding: 0.75rem 1rem;
            /* Adjusted padding */
            border-bottom: 1px solid #e3e6f0;
            /* Separator for items */
            line-height: 1.4;
            /* Improved line height */
        }

        .topbar .dropdown-list .dropdown-item:last-child {
            border-bottom: none;
        }


        .topbar .dropdown-list .dropdown-item.unread-notification {
            background-color: var(--notification-unread-bg);
            font-weight: 500;
            /* Slightly bolder for unread */
        }

        .topbar .dropdown-list .dropdown-item.unread-notification .small {
            font-weight: normal;
            /* Keep date normal weight */
        }


        .topbar .dropdown-list .dropdown-item .message-content {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Limit to 2 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.9rem;
            color: #3a3b45;
        }

        .topbar .dropdown-list .dropdown-item.unread-notification .message-content {
            color: #2a2b35;
            /* Darker for unread message */
        }


        .topbar .dropdown-list .dropdown-item .small {
            font-size: 0.75rem;
            color: var(--text-gray-600);
        }

        .topbar .dropdown-list .dropdown-item .icon-circle {
            /* For icons next to messages if you use them */
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .notification-icon-placeholder {
            /* For unread dot or similar */
            width: 24px;
            /* Adjust as needed */
            text-align: center;
        }

        .unread-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: var(--primary-maroon);
            border-radius: 50%;
        }


        .topbar .dropdown-list .show-all-notifications-link {
            display: block;
            padding: 0.75rem 1rem;
            /* Consistent padding */
            font-size: 0.85rem;
            color: var(--primary-maroon);
            text-align: center;
            font-weight: 500;
            text-decoration: none;
            background-color: #f8f9fa;
            /* Light background for footer link */
        }

        .topbar .dropdown-list .show-all-notifications-link:hover {
            background-color: #e9ecef;
            text-decoration: underline;
        }


        .topbar .nav-item.dropdown .nav-link .badge-counter {
            position: absolute;
            transform: scale(0.7);
            transform-origin: top right;
            right: 0.25rem;
            /* Fine-tuned position */
            margin-top: -0.65rem;
            /* Fine-tuned position */
        }

        /* Loading and No Notifications Message */
        .notification-status-message {
            padding: 1rem;
            text-align: center;
            color: var(--text-gray-600);
            font-size: 0.9rem;
        }


        /* Main Content Area */
        #main-content {
            flex: 1 0 auto;
            padding: 1.5rem;
        }

        /* Footer */
        .sticky-footer {
            padding: 1.5rem;
            background-color: #e9ecef;
            color: var(--text-dark);
            flex-shrink: 0;
        }

        /* Responsive: Offcanvas sidebar for small screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                position: fixed;
            }

            #content-wrapper {
                padding-left: 0;
            }

            #main-content {
                margin-top: 70px;
            }

            .sidebar .text-center.d-none.d-md-inline {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <ul class="navbar-nav sidebar d-none d-md-flex" id="accordionSidebar">
        <a class="sidebar-brand" href="dashboard.php">
            <span class="sidebar-brand-text">PLOAN ADMIN</span>
        </a>
        <hr class="sidebar-divider my-0">
        <li
            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php' || basename($_SERVER['PHP_SELF']) == '../homepage.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="dashboard.php">
                <i class="fas fa-fw fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="loan.php">
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
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan_plan.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="loan_plan.php">
                <i class="fas fa-fw fa-credit-card"></i>
                <span>Loan Plans</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'user.php') ? 'active' : ''; ?>">
            <a class="nav-link" href="user.php">
                <i class="fas fa-fw fa-users"></i> <span>Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-fw fa-sign-out-alt"></i>
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
                        <span class="badge bg-danger badge-counter" id="notificationBadge"
                            style="display: none;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-list shadow animated--grow-in"
                        aria-labelledby="alertsDropdown" id="notificationPanelDynamic">
                        <div class="dropdown-header">
                            Notifications
                            <a href="#" class="mark-all-read-btn" id="markAllNotificationsAsReadBtn"
                                style="display:none;">Mark all as read</a>
                        </div>
                        <div id="notificationItemsContainer">
                            <div id="notificationLoadingSpinner" class="notification-status-message">
                                <i class="fas fa-spinner fa-spin me-1"></i>Loading...
                            </div>
                            <div id="noNotificationsMsg" class="notification-status-message" style="display: none;">
                                No new notifications.
                            </div>
                        </div>
                        <a class="dropdown-item text-center small text-gray-500 show-all-notifications-link" href="#"
                            id="showAllNotificationsPageLink">Show All Notifications</a>
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
                                <img class="rounded-circle" src="https://placehold.co/60x60/800000/FFFFFF?text=U1"
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
                                <img class="rounded-circle" src="https://placehold.co/60x60/600000/FFFFFF?text=U2"
                                    alt="...">
                                <div class="status-indicator"></div>
                            </div>
                            <div>
                                <div class="text-truncate">I have the photos that you ordered last month, how would you
                                    like them sent to you?</div>
                                <div class="small text-gray-500">Jae Chun · 1d</div>
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
                            <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
                        </span>
                        <img class="img-profile rounded-circle" src="https://placehold.co/60x60/800000/FFFFFF?text=A"
                            alt="Admin Profile" width="32" height="32">
                    </a>
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
        <div id="main-content" class="container-fluid">
            <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel"
                style="background-color: var(--sidebar-bg); color: var(--sidebar-text-color); width: 260px;">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="mobileSidebarLabel" style="color: var(--sidebar-text-hover-color);">
                        <i class="fas fa-landmark me-2"></i>PLOAN ADMIN
                    </h5>
                    <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <ul class="navbar-nav">
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="dashboard.php"><i
                                    class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="loan.php"><i
                                    class="fas fa-fw fa-comment-dollar"></i><span>Loans</span></a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'payment.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="payment.php"><i
                                    class="fas fa-fw fa-coins"></i><span>Payments</span></a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'history.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="history.php"><i
                                    class="fas fa-fw fa-history"></i><span>History</span></a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan_plan.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="loan_plan.php"><i class="fas fa-fw fa-credit-card"></i><span>Loan
                                    Plans</span></a>
                        </li>
                        <hr class="sidebar-divider">
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="users.php"><i
                                    class="fas fa-fw fa-users"></i><span>Users</span></a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="profile.php"><i class="fas fa-fw fa-user-circle"></i><span>My
                                    Profile</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="fas fa-fw fa-sign-out-alt"></i><span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                        </div>
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
                    // --- Desktop Sidebar Toggle Logic (from your original code) ---
                    const sidebar = document.getElementById('accordionSidebar');
                    const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
                    const angleIcon = sidebarToggleDesktop ? sidebarToggleDesktop.querySelector('i') : null;

                    if (sidebar && sidebarToggleDesktop && angleIcon) {
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

                        if (window.innerWidth > 768) {
                            const isSidebarToggled = localStorage.getItem('sidebarToggled') === 'true';
                            applyToggleState(isSidebarToggled);
                        }

                        sidebarToggleDesktop.addEventListener('click', function () {
                            const newState = !sidebar.classList.contains('toggled');
                            applyToggleState(newState);
                            if (window.innerWidth > 768) {
                                localStorage.setItem('sidebarToggled', newState);
                            }
                        });
                    }

                    // --- Tooltip Initialization (from your original code) ---
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });

                    // --- Notification System JavaScript ---
                    const notificationBellDropdown = document.getElementById('alertsDropdown');
                    const notificationPanel = document.getElementById('notificationPanelDynamic'); // The dropdown menu itself
                    const notificationItemsContainer = document.getElementById('notificationItemsContainer');
                    const notificationBadge = document.getElementById('notificationBadge');
                    const loadingSpinner = document.getElementById('notificationLoadingSpinner');
                    const noNotificationsMsg = document.getElementById('noNotificationsMsg');
                    const markAllReadBtn = document.getElementById('markAllNotificationsAsReadBtn');
                    const showAllNotificationsPageLink = document.getElementById('showAllNotificationsPageLink');

                    // Configure this to point to your PHP backend script
                    const backendUrl = 'notifications_backend.php'; // MAKE SURE THIS FILE EXISTS AND IS ACCESSIBLE
                    let activeNotifications = []; // To store current notifications

                    /**
                     * Fetches notifications from the backend.
                     * @param {boolean} isInitialCheck - True if this is just for badge update, false if panel is open.
                     */
                    async function fetchNotifications(isInitialCheck = false) {
                        if (!isInitialCheck) {
                            loadingSpinner.style.display = 'block';
                            noNotificationsMsg.style.display = 'none';
                            notificationItemsContainer.innerHTML = ''; // Clear previous items
                            notificationItemsContainer.appendChild(loadingSpinner); // Add spinner back
                        }

                        try {
                            const response = await fetch(`${backendUrl}?action=get_notifications&cache_bust=${new Date().getTime()}`);
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            const data = await response.json();

                            if (!isInitialCheck) loadingSpinner.style.display = 'none';

                            if (data.success && data.notifications) {
                                activeNotifications = data.notifications; // Store fetched notifications
                                if (!isInitialCheck) {
                                    renderNotifications(activeNotifications);
                                }
                                updateNotificationBadge(activeNotifications);
                                markAllReadBtn.style.display = activeNotifications.some(n => parseInt(n.is_read) === 0) ? 'inline' : 'none';

                            } else if (data.success && data.notifications.length === 0) {
                                if (!isInitialCheck) {
                                    noNotificationsMsg.style.display = 'block';
                                    notificationItemsContainer.innerHTML = ''; // Clear spinner
                                    notificationItemsContainer.appendChild(noNotificationsMsg);
                                }
                                updateNotificationBadge([]); // Update badge to 0
                                markAllReadBtn.style.display = 'none';
                            } else {
                                if (!isInitialCheck) displayError('Failed to load notifications.');
                            }
                        } catch (error) {
                            console.error('Error fetching notifications:', error);
                            if (!isInitialCheck) {
                                loadingSpinner.style.display = 'none';
                                displayError('Could not connect to server or invalid response.');
                            }
                        }
                    }

                    /**
                     * Renders notifications in the Bootstrap dropdown.
                     * @param {Array} notifications - Array of notification objects.
                     */
                    function renderNotifications(notifications) {
                        notificationItemsContainer.innerHTML = ''; // Clear previous content (spinner/no message)

                        if (notifications.length === 0) {
                            noNotificationsMsg.style.display = 'block';
                            notificationItemsContainer.appendChild(noNotificationsMsg);
                            return;
                        }
                        noNotificationsMsg.style.display = 'none';

                        notifications.forEach(notification => {
                            const notificationLink = document.createElement('a');
                            notificationLink.classList.add('notification-item-link');
                            notificationLink.href = notification.link || '#'; // Link from notification data
                            if (notification.link === '#' || !notification.link) {
                                notificationLink.addEventListener('click', e => e.preventDefault());
                            }

                            const item = document.createElement('div');
                            item.classList.add('dropdown-item', 'd-flex', 'align-items-start'); // Use align-items-start for better layout
                            item.dataset.id = notification.id;

                            const isUnread = parseInt(notification.is_read) === 0;
                            if (isUnread) {
                                item.classList.add('unread-notification');
                            }

                            // Format date
                            const date = new Date(notification.created_at);
                            const formattedDate = `${date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' })}, ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })}`;

                            // Icon placeholder (can be dynamic based on notification type later)
                            let iconHtml = `<div class="notification-icon-placeholder me-3">`;
                            if (isUnread) {
                                iconHtml += `<span class="unread-dot" title="Unread"></span>`;
                            } else {
                                iconHtml += `<i class="fas fa-check-circle text-muted"></i>`; // Example for read
                            }
                            iconHtml += `</div>`;


                            item.innerHTML = `
                        ${iconHtml}
                        <div class="flex-grow-1">
                            <p class="message-content mb-1">${notification.message}</p>
                            <p class="small text-gray-500 mb-0">${formattedDate}</p>
                        </div>
                    `;

                            item.addEventListener('click', (e) => {
                                if (isUnread) {
                                    markNotificationAsRead(notification.id, item);
                                }
                                // If it's a real link, it will navigate. If '#', it's handled.
                                // Optionally close dropdown:
                                // bootstrap.Dropdown.getInstance(notificationBellDropdown).hide();
                            });
                            notificationLink.appendChild(item);
                            notificationItemsContainer.appendChild(notificationLink);
                        });
                    }

                    /**
                     * Updates the notification badge counter.
                     * @param {Array} notifications - Array of notification objects.
                     */
                    function updateNotificationBadge(notifications) {
                        const unreadCount = notifications.filter(n => parseInt(n.is_read) === 0).length;
                        if (unreadCount > 0) {
                            notificationBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                            notificationBadge.style.display = 'inline-block';
                        } else {
                            notificationBadge.style.display = 'none';
                        }
                    }

                    /**
                     * Marks a single notification as read.
                     * @param {number} notificationId - The ID of the notification.
                     * @param {HTMLElement} itemElement - The DOM element of the notification item.
                     */
                    async function markNotificationAsRead(notificationId, itemElement) {
                        // Optimistically update UI
                        if (itemElement) {
                            itemElement.classList.remove('unread-notification');
                            const unreadDot = itemElement.querySelector('.unread-dot');
                            if (unreadDot) {
                                // Replace dot with a check or similar
                                const iconPlaceholder = itemElement.querySelector('.notification-icon-placeholder');
                                if (iconPlaceholder) iconPlaceholder.innerHTML = `<i class="fas fa-check-circle text-muted"></i>`;
                            }
                        }

                        // Update local activeNotifications array
                        const notificationIndex = activeNotifications.findIndex(n => n.id === notificationId);
                        if (notificationIndex > -1) {
                            activeNotifications[notificationIndex].is_read = 1;
                        }
                        updateNotificationBadge(activeNotifications); // Update badge based on local change
                        markAllReadBtn.style.display = activeNotifications.some(n => parseInt(n.is_read) === 0) ? 'inline' : 'none';


                        try {
                            const response = await fetch(`${backendUrl}?action=mark_as_read&id=${notificationId}`);
                            const data = await response.json();
                            if (!data.success) {
                                console.error('Failed to mark notification as read on server.');
                                // Revert UI (optional, or re-fetch)
                                if (itemElement) itemElement.classList.add('unread-notification'); // crude revert
                                if (notificationIndex > -1) activeNotifications[notificationIndex].is_read = 0; // revert local
                                updateNotificationBadge(activeNotifications);
                            }
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                            if (itemElement) itemElement.classList.add('unread-notification'); // crude revert
                            if (notificationIndex > -1) activeNotifications[notificationIndex].is_read = 0; // revert local
                            updateNotificationBadge(activeNotifications);
                        }
                    }

                    /**
                     * Marks all notifications as read.
                     */
                    async function markAllNotificationsAsRead() {
                        // Optimistically update UI
                        const unreadItems = notificationItemsContainer.querySelectorAll('.unread-notification');
                        unreadItems.forEach(item => {
                            item.classList.remove('unread-notification');
                            const unreadDot = item.querySelector('.unread-dot');
                            if (unreadDot) {
                                const iconPlaceholder = item.querySelector('.notification-icon-placeholder');
                                if (iconPlaceholder) iconPlaceholder.innerHTML = `<i class="fas fa-check-circle text-muted"></i>`;
                            }
                        });
                        activeNotifications.forEach(n => n.is_read = 1);
                        updateNotificationBadge(activeNotifications);
                        markAllReadBtn.style.display = 'none';

                        try {
                            const response = await fetch(`${backendUrl}?action=mark_all_as_read`);
                            const data = await response.json();
                            if (!data.success) {
                                console.error('Failed to mark all notifications as read on server.');
                                fetchNotifications(); // Re-fetch to get correct state
                            }
                        } catch (error) {
                            console.error('Error marking all notifications as read:', error);
                            fetchNotifications(); // Re-fetch on error
                        }
                    }

                    /**
                     * Displays an error message in the notification list.
                     * @param {string} message - The error message to display.
                     */
                    function displayError(message) {
                        notificationItemsContainer.innerHTML = `<div class="notification-status-message text-danger">${message}</div>`;
                    }

                    // --- Event Listeners for Notifications ---
                    if (notificationBellDropdown) {
                        // Bootstrap handles the toggle. We fetch content when it's shown.
                        notificationBellDropdown.addEventListener('show.bs.dropdown', function () {
                            fetchNotifications();
                        });
                    }

                    if (markAllReadBtn) {
                        markAllReadBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            e.stopPropagation(); // Prevent dropdown from closing
                            markAllNotificationsAsRead();
                        });
                    }

                    // --- Initial check for notifications (for badge) & Polling ---
                    fetchNotifications(true); // Initial check for badge without opening panel
                    setInterval(() => {
                        fetchNotifications(true); // Periodically check for badge updates
                    }, 30000); // Check every 30 seconds

                    // Optional: Set the "Show All Notifications" link
                    if (showAllNotificationsPageLink) {
                        // showAllNotificationsPageLink.href = 'all_notifications.php'; // Set your actual link
                    }

                }); // End DOMContentLoaded
            </script>

</body>

</html>