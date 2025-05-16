<?php
date_default_timezone_set("Etc/GMT+8");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../class.php'; // Database class
// require_once '../session.php'; // If you have specific session validation/handling

$db = new db_class();

// --- Authentication and User Data ---
$user_id = $_SESSION['user_id'] ?? null;
$firstname = $_SESSION['firstname'] ?? 'Guest';

if (!$user_id) {
    // For a real application, redirect to login if user_id is not set.
    // header('Location: ../login.php'); // Adjust path as needed
    // exit;
    // For demonstration, we'll show a message or limited functionality.
    // For this page, we'll assume user_id is critical.
    $_SESSION['error_message'] = "You must be logged in to make a payment.";
    // header('Location: ../login.php'); // Or a generic error page
    // exit();
}

// --- Profile Picture Logic ---
$profile_pic_filename = $_SESSION['profile_pic_path'] ?? null;
$profile_pic_base_web_path = '../uploads/profile_pictures/';
$profile_pic_server_base_path = __DIR__ . '/../uploads/profile_pictures/';
$default_profile_pic_placeholder = 'https://placehold.co/40x40/EBF4FF/7F9CF5?text=' . strtoupper(substr($firstname, 0, 1));

if ($profile_pic_filename && file_exists($profile_pic_server_base_path . basename($profile_pic_filename))) {
    $profile_pic_web_url = $profile_pic_base_web_path . htmlspecialchars(basename($profile_pic_filename));
} else {
    if ($profile_pic_filename) {
        error_log("Profile picture not found: " . $profile_pic_server_base_path . basename($profile_pic_filename));
    }
    $profile_pic_web_url = $default_profile_pic_placeholder;
}

// --- Fetch Borrower's Active Loans for the Modal Dropdown ---
$active_loans = [];
if ($user_id) {
    // Assuming 'borrower_id' in the 'loan' table corresponds to 'user_id' from session.
    // Fetch loans that are not fully paid (e.g., status 0=pending, 1=approved/ongoing, 2=released, 3=denied, 4=paid)
    // You might need to adjust the status codes based on your system.
    // We want loans that are 'approved', 'released', or 'overdue' but not 'paid' or 'denied'.
    $loan_stmt = $db->conn->prepare("
        SELECT l.loan_id, 
            lp.title AS loan_plan_name,  -- Fetches the 'title' column from 'loan_plans' table and aliases it
            l.amount, 
            l.status, 
            l.date_created,
            (SELECT SUM(pay_amount) FROM payment WHERE loan_id = l.loan_id) as total_paid,
            (l.amount + 
             IFNULL((SELECT SUM(amount) FROM penalty WHERE loan_id = l.loan_id), 0) - 
             IFNULL((SELECT SUM(pay_amount) FROM payment WHERE loan_id = l.loan_id), 0)
            ) as remaining_balance
        FROM 
            loan l
        INNER JOIN 
            loan_plans lp ON l.lplan_id = lp.id -- Corrected join: lp.id is the PK of loan_plans
        WHERE 
            l.borrower_id = ? AND l.status IN (1, 2) /* 1=Approved, 2=Released, adjust as needed */
        ORDER BY 
            l.date_created DESC
    ");
    if ($loan_stmt) {
        $loan_stmt->bind_param("i", $user_id);
        $loan_stmt->execute();
        $loan_result = $loan_stmt->get_result();
        while ($loan_row = $loan_result->fetch_assoc()) {
            // Only add loans that still have a balance or are not marked as fully paid
            if ($loan_row['remaining_balance'] > 0.009) { // Check if remaining balance is greater than a small epsilon
                $active_loans[] = $loan_row;
            }
        }
        $loan_stmt->close();
    } else {
        error_log("Failed to prepare statement to fetch active loans: " . $db->conn->error);
    }
}

// --- Flash Messages for Payment Status ---
$payment_success_message = $_SESSION['payment_success_message'] ?? null;
$payment_error_message = $_SESSION['payment_error_message'] ?? null;
unset($_SESSION['payment_success_message'], $_SESSION['payment_error_message']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Make a Payment - Loan Management System</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'sans': ['Inter', 'sans-serif'], },
                    colors: {
                        primary: { DEFAULT: '#007AFF', light: '#EBF4FF', dark: '#0056b3' },
                        secondary: { DEFAULT: '#FF9500', light: '#FFEACC', dark: '#CC7A00' },
                        success: { DEFAULT: '#34C759', light: '#E7F8E8', dark: '#28A745' },
                        danger: { DEFAULT: '#FF3B30', light: '#FFEBEE', dark: '#D32F2F' },
                        warning: { DEFAULT: '#FFCC00', light: '#FFF9E6', dark: '#FFA000' },
                        gray: { 50: '#F9FAFB', 100: '#F3F4F6', 200: '#E5E7EB', 300: '#D1D5DB', 400: '#9CA3AF', 500: '#6B7280', 600: '#4B5563', 700: '#374151', 800: '#1F2937', 900: '#111827' }
                    }
                }
            }
        }
    </script>
    <link href="../fontawesome-free/css/all.min.css" rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
            background-color: tailwind.theme.colors.gray[100];
        }

        #wrapper {
            display: flex;
            flex-grow: 1;
            overflow: hidden;
        }

        #content-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            overflow-y: auto;
        }

        .modal.fade {
            transition: opacity 0.2s ease-out;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: tailwind.theme.colors.primary.DEFAULT !important;
            color: white !important;
            border-color: tailwind.theme.colors.primary.DEFAULT !important;
        }

        /* Custom styling for file input */
        input[type="file"]::file-selector-button {
            margin-right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            font-size: 0.875rem;
            font-weight: 600;
            background-color: tailwind.theme.colors.primary.light;
            color: tailwind.theme.colors.primary.dark;
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: tailwind.theme.colors.primary.DEFAULT;
            color: white;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">
        <!-- Include the sidebar -->
        <?php include('sidebar.php'); ?>


        <main class="flex-1 p-6 space-y-6">
            <?php if (!$user_id): ?>
                <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-4 rounded-md shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><i class="fas fa-exclamation-triangle fa-lg mr-3"></i></div>
                        <div>
                            <p class="font-bold">Access Denied</p>
                            <p class="text-sm">You need to be logged in to access this page. Please <a href="../login.php"
                                    class="font-medium underline hover:text-danger">login here</a>.
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($payment_success_message): ?>
                    <div class="bg-success-light border-l-4 border-success text-success-dark p-4 rounded-md shadow-md"
                        role="alert">
                        <div class="flex">
                            <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3"></i></div>
                            <div>
                                <p class="font-bold">Payment Successful</p>
                                <p class="text-sm"><?= htmlspecialchars($payment_success_message) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($payment_error_message): ?>
                    <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-4 rounded-md shadow-md"
                        role="alert">
                        <div class="flex">
                            <div class="py-1"><i class="fas fa-times-circle fa-lg mr-3"></i></div>
                            <div>
                                <p class="font-bold">Payment Failed</p>
                                <p class="text-sm"><?= htmlspecialchars($payment_error_message) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>


                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">
                        Your Loan Payments
                    </h2>
                    <?php if (!empty($active_loans)): ?>
                        <button
                            class="bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-primary-light focus:ring-opacity-50"
                            data-toggle="modal" data-target="#addPaymentModal">
                            <i class="fas fa-credit-card"></i>
                            <span>Make a Payment</span>
                        </button>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 bg-gray-200 p-3 rounded-md">You have no active loans eligible for
                            payment at this time.</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-700">
                            <i class="fas fa-history mr-2 text-primary"></i>Your Payment History
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="paymentHistoryTable">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            #</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Loan Ref.</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Method</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Proof</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $payment_history_stmt = $db->conn->prepare("
                                            SELECT p.payment_id, p.loan_id, p.payment_method, p.pay_amount, p.proof_of_payment, p.payment_date
                                            FROM payment p
                                            INNER JOIN loan l ON p.loan_id = l.loan_id
                                            WHERE l.borrower_id = ?
                                            ORDER BY p.payment_date DESC
                                        ");
                                    if ($payment_history_stmt) {
                                        $payment_history_stmt->bind_param("i", $user_id);
                                        $payment_history_stmt->execute();
                                        $user_payments = $payment_history_stmt->get_result();

                                        if ($user_payments && $user_payments->num_rows > 0) {
                                            $i = 1;
                                            while ($row = $user_payments->fetch_assoc()) {
                                                ?>
                                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $i++; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        LOAN-<?= htmlspecialchars(str_pad((string) $row['loan_id'], 5, "0", STR_PAD_LEFT)); ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $row['payment_method'] == 'GCash' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                                            <?= htmlspecialchars($row['payment_method']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                                        &#8369;<?= number_format((float) $row['pay_amount'], 2); ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <?php if (!empty($row['proof_of_payment'])): ?>
                                                            <a href="../uploads/proofs/<?= htmlspecialchars($row['proof_of_payment']); ?>"
                                                                target="_blank"
                                                                class="text-primary hover:text-primary-dark hover:underline font-medium flex items-center space-x-1">
                                                                <i class="fas fa-receipt text-xs"></i>
                                                                <span>View Proof</span>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-gray-400 italic text-xs">N/A</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= date("M d, Y h:i A", strtotime($row['payment_date'])); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            $payment_history_stmt->close();
                                        } else {
                                            echo '<tr><td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">You have not made any payments yet.</td></tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="px-6 py-10 text-center text-red-500 italic">Error fetching payment history.</td></tr>';
                                        error_log("Failed to prepare statement for payment history: " . $db->conn->error);
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; // End $user_id check ?>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-auto p-4 text-center">
            <div class="text-sm text-gray-500">
                Copyright &copy; Loan Management System <?= date("Y"); ?>
            </div>
        </footer>
    </div>
    </div>

    <?php if ($user_id && !empty($active_loans)): // Only show modal if user is logged in and has active loans ?>
        <div class="modal fade fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300"
            id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
            <div
                class="modal-dialog relative top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-2 sm:p-5 border-0 w-full max-w-lg shadow-2xl rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0">
                <form method="POST" action="save_payment.php" enctype="multipart/form-data" id="paymentForm">
                    <div class="modal-content">
                        <div
                            class="modal-header flex justify-between items-center p-5 border-b border-gray-200 rounded-t-xl bg-primary-light">
                            <h5 class="modal-title text-xl font-semibold text-primary-dark flex items-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/GCash_logo.svg/100px-GCash_logo.svg.png"
                                    alt="GCash Logo" class="h-7 mr-2">
                                Pay with GCash
                            </h5>
                            <button
                                class="modal-close close text-gray-500 hover:text-gray-800 text-3xl font-light focus:outline-none"
                                type="button" data-dismiss="modal" aria-label="Close">&times;</button>
                        </div>

                        <div class="modal-body p-6 space-y-5 overflow-y-auto" style="max-height: 70vh;">
                            <div
                                class="text-center p-4 border-2 border-dashed border-primary rounded-lg bg-primary-light/30">
                                <p class="text-sm text-gray-700 mb-3">Scan QR or pay to the GCash number below:</p>
                                <img src="https://placehold.co/280x280/007AFF/FFFFFF?text=GCash+QR+Code"
                                    alt="GCash QR Code Placeholder"
                                    class="mx-auto mb-4 rounded-md border-2 border-primary shadow-lg" id="gcashQRCode">
                                <p class="text-md font-semibold text-gray-800">Account Name: <span
                                        class="text-primary-dark">LMS Payments</span></p>
                                <p class="text-md font-semibold text-gray-800">GCash Number: <span
                                        class="text-primary-dark">0900 123 4567</span></p>
                                <p class="mt-2 text-xs text-gray-500">After payment, fill out the form and upload your
                                    proof.</p>
                            </div>

                            <input type="hidden" name="payment_method" value="GCash">

                            <div>
                                <label for="loan_id" class="block text-sm font-medium text-gray-700 mb-1">Select Loan to
                                    Pay</label>
                                <select name="loan_id" id="loan_id"
                                    class="mt-1 block w-full py-2.5 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                    required>
                                    <option value="" disabled selected>-- Select Your Loan --</option>
                                    <?php foreach ($active_loans as $loan): ?>
                                        <option value="<?= htmlspecialchars($loan['loan_id']) ?>">
                                            LOAN-<?= htmlspecialchars(str_pad((string) $loan['loan_id'], 5, "0", STR_PAD_LEFT)) ?>
                                            (<?= htmlspecialchars($loan['loan_plan_name']) ?>) - Bal:
                                            &#8369;<?= number_format((float) $loan['remaining_balance'], 2) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="pay_amount" class="block text-sm font-medium text-gray-700 mb-1">Amount Paid
                                    (&#8369;)</label>
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">&#8369;</span>
                                    </div>
                                    <input type="number" name="pay_amount" id="pay_amount" step="0.01" min="0.01"
                                        class="block w-full py-2.5 pl-8 pr-3 border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                        placeholder="0.00" required>
                                </div>
                            </div>

                            <div>
                                <label for="reference_no" class="block text-sm font-medium text-gray-700 mb-1">GCash
                                    Reference No.</label>
                                <input type="text" name="reference_no" id="reference_no"
                                    class="mt-1 block w-full py-2.5 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                    placeholder="13-digit reference number" required pattern="\d{13}"
                                    title="Reference number must be 13 digits.">
                            </div>

                            <div>
                                <label for="other_details" class="block text-sm font-medium text-gray-700 mb-1">Payment
                                    Notes (Optional)</label>
                                <textarea name="other_details" id="other_details" rows="2"
                                    class="mt-1 block w-full py-2.5 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                    placeholder="e.g., January installment"></textarea>
                            </div>

                            <div>
                                <label for="proof_of_payment" class="block text-sm font-medium text-gray-700 mb-1">Upload
                                    Proof of Payment</label>
                                <input type="file" name="proof_of_payment" id="proof_of_payment"
                                    class="mt-1 block w-full text-sm text-gray-600" accept="image/jpeg,image/jpg,image/png"
                                    required>
                                <p class="mt-1 text-xs text-gray-500">Max file size: 5MB. Accepted: JPG, PNG.</p>
                            </div>
                        </div>

                        <div
                            class="modal-footer flex justify-end items-center p-5 border-t border-gray-200 space-x-3 rounded-b-xl bg-gray-50">
                            <button
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400"
                                type="button" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="save_payment"
                                class="bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-light focus:ring-opacity-50">
                                <i class="fas fa-check-circle mr-2"></i>Submit Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; // End modal conditional rendering ?>


    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#paymentHistoryTable').DataTable({
                "paging": true, "lengthChange": true, "searching": true,
                "ordering": true, "info": true, "autoWidth": false, "responsive": true,
                "language": { "search": "_INPUT_", "searchPlaceholder": "Search history...", "lengthMenu": "Show _MENU_" }
            });

            $('#sidebarToggleTop').on('click', function () {
                $('body').toggleClass('sidebar-toggled'); // Adjust based on your sidebar's JS
                $('.sidebar').toggleClass('toggled');     // Adjust based on your sidebar's JS
            });

            const modals = document.querySelectorAll('.modal.fade');
            function showModal(modalElement) {
                if (!modalElement) return;
                modalElement.classList.remove('hidden');
                setTimeout(() => {
                    modalElement.classList.remove('opacity-0');
                    const dialog = modalElement.querySelector('.modal-dialog');
                    if (dialog) {
                        dialog.classList.remove('scale-95', 'opacity-0');
                        dialog.classList.add('scale-100', 'opacity-100');
                    }
                }, 10);
                modalElement.setAttribute('aria-hidden', 'false');
                const firstFocusable = modalElement.querySelector('input, select, textarea, button, [href]');
                if (firstFocusable) firstFocusable.focus();
            }

            function hideModal(modalElement) {
                if (!modalElement) return;
                const dialog = modalElement.querySelector('.modal-dialog');
                if (dialog) {
                    dialog.classList.remove('scale-100', 'opacity-100');
                    dialog.classList.add('scale-95', 'opacity-0');
                }
                modalElement.classList.add('opacity-0');
                setTimeout(() => { modalElement.classList.add('hidden'); }, 300);
                modalElement.setAttribute('aria-hidden', 'true');
            }

            document.querySelectorAll('[data-toggle="modal"]').forEach(trigger => {
                trigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetModalId = this.getAttribute('data-target');
                    const targetModal = document.querySelector(targetModalId);
                    if (targetModal) showModal(targetModal);
                });
            });

            modals.forEach(modal => {
                modal.querySelectorAll('[data-dismiss="modal"], .modal-close').forEach(closeBtn => {
                    closeBtn.addEventListener('click', () => hideModal(modal));
                });
                modal.addEventListener('click', function (event) {
                    if (event.target === modal) hideModal(modal);
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === "Escape") {
                    modals.forEach(modal => {
                        if (!modal.classList.contains('hidden')) hideModal(modal);
                    });
                }
            });

            const refNoInput = document.getElementById('reference_no');
            if (refNoInput) {
                refNoInput.addEventListener('input', function () {
                    this.value = this.value.replace(/\D/g, ''); // Allow only digits
                    if (this.value.length > 13) this.value = this.value.slice(0, 13);

                    if (this.value.length === 13) this.setCustomValidity('');
                    else if (this.value.length > 0) this.setCustomValidity('Reference number must be 13 digits.');
                    else this.setCustomValidity('');
                });
            }

            const paymentForm = document.getElementById('paymentForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function (e) {
                    const submitButton = paymentForm.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                    }
                });
            }
            // Placeholder for actual QR code image source
            // document.getElementById('gcashQRCode').src = 'your_real_gcash_qr_code_image_url.png';
        });
    </script>
</body>

</html>