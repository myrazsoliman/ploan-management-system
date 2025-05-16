<?php // notifications_backend.php - This file will act as our simple backend

// --- Database Simulation ---
// In a real application, you would fetch these from a database.
// For this example, we'll use a static array.
$all_notifications = [
    ['id' => 1, 'user_id' => 1, 'message' => 'New comment on your post "My Awesome Trip".', 'is_read' => 0, 'created_at' => '2025-05-14 10:00:00', 'link' => '#comment-123'],
    ['id' => 2, 'user_id' => 1, 'message' => 'Your order #ABC-123 has shipped!', 'is_read' => 0, 'created_at' => '2025-05-14 09:30:00', 'link' => '#order-abc-123'],
    ['id' => 3, 'user_id' => 1, 'message' => 'John Doe started following you.', 'is_read' => 1, 'created_at' => '2025-05-13 15:00:00', 'link' => '#profile-john'],
    ['id' => 4, 'user_id' => 1, 'message' => 'Reminder: Meeting at 3 PM today.', 'is_read' => 0, 'created_at' => '2025-05-14 08:00:00', 'link' => '#meeting-reminder'],
    ['id' => 5, 'user_id' => 1, 'message' => 'System update scheduled for tonight.', 'is_read' => 1, 'created_at' => '2025-05-12 12:00:00', 'link' => '#system-update'],
];

// --- API Endpoint Simulation ---
// This part simulates an API endpoint that your JavaScript will call.
if (isset($_GET['action'])) {
    header('Content-Type: application/json'); // Set content type to JSON

    if ($_GET['action'] === 'get_notifications') {
        // Simulate fetching for a specific user (e.g., user_id = 1)
        $user_notifications = array_filter($all_notifications, function ($notification) {
            return $notification['user_id'] === 1; // In a real app, use the logged-in user's ID
        });

        // Sort by creation date, newest first
        usort($user_notifications, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        echo json_encode([
            'success' => true,
            'notifications' => array_values($user_notifications) // Re-index array
        ]);
        exit; // Important to stop further script execution
    }

    if ($_GET['action'] === 'mark_as_read' && isset($_GET['id'])) {
        $notification_id = (int) $_GET['id'];
        // In a real app, update the database here.
        // For this simulation, we're not persisting the change.
        // You would find the notification by ID and set 'is_read' to 1.
        echo json_encode([
            'success' => true,
            'message' => "Notification {$notification_id} marked as read (simulated)."
        ]);
        exit;
    }

    if ($_GET['action'] === 'mark_all_as_read') {
        // In a real app, update all unread notifications for the user in the database.
        echo json_encode([
            'success' => true,
            'message' => "All notifications marked as read (simulated)."
        ]);
        exit;
    }
}

// If no action is specified, this file won't output anything directly if included.
// The main HTML file will handle the display.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Notification Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom font */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Styling for the notification dot */
        .notification-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            border: 2px solid white;
            display: none;
            /* Hidden by default, shown if unread notifications exist */
        }

        /* Basic transition for the notification panel */
        #notificationPanel {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f0f4f8;
            /* Light blue-gray hover */
        }

        .notification-item.unread {
            background-color: #e6f0ff;
            /* Lighter blue for unread */
            font-weight: bold;
        }

        .notification-item.unread .message-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #3b82f6;
            /* Tailwind blue-500 */
            border-radius: 50%;
            margin-right: 8px;
        }

        /* Scrollbar styling for webkit browsers */
        #notificationList::-webkit-scrollbar {
            width: 6px;
        }

        #notificationList::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #notificationList::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        #notificationList::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">My Application</h1>
        <p class="text-center text-gray-600 mb-8">Click the bell icon to see your notifications.</p>

        <div class="relative ml-auto" id="notificationContainer">
            <button id="notificationBell"
                class="relative p-3 bg-blue-500 hover:bg-blue-600 text-white rounded-full shadow-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                <i class="fas fa-bell text-xl"></i>
                <span id="notificationDot" class="notification-dot"></span>
            </button>

            <div id="notificationPanel"
                class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50 hidden origin-top-right transform scale-95 opacity-0">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-700">Notifications</h3>
                        <button id="markAllReadBtn"
                            class="text-sm text-blue-500 hover:text-blue-700 hover:underline">Mark all as read</button>
                    </div>
                </div>
                <div id="notificationList" class="max-h-96 overflow-y-auto">
                    <div id="loadingSpinner" class="p-4 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                    </div>
                    <div id="noNotificationsMessage" class="p-4 text-center text-gray-500 hidden">
                        No new notifications.
                    </div>
                </div>
                <div class="p-3 bg-gray-50 border-t border-gray-200 text-center">
                    <a href="#" class="text-sm text-blue-500 hover:text-blue-700 hover:underline">View all
                        notifications</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- DOM Elements ---
        const notificationBell = document.getElementById('notificationBell');
        const notificationPanel = document.getElementById('notificationPanel');
        const notificationList = document.getElementById('notificationList');
        const notificationDot = document.getElementById('notificationDot');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const noNotificationsMessage = document.getElementById('noNotificationsMessage');
        const markAllReadBtn = document.getElementById('markAllReadBtn');

        // --- State ---
        let isPanelOpen = false;
        const backendUrl = 'notifications_backend.php'; // Path to your PHP backend script

        // --- Functions ---

        /**
         * Toggles the visibility of the notification panel.
         */
        function toggleNotificationPanel() {
            isPanelOpen = !isPanelOpen;
            if (isPanelOpen) {
                notificationPanel.classList.remove('hidden', 'opacity-0', 'scale-95');
                notificationPanel.classList.add('opacity-100', 'scale-100');
                fetchNotifications(); // Fetch notifications when panel is opened
            } else {
                notificationPanel.classList.add('opacity-0', 'scale-95');
                // Wait for animation to finish before hiding
                setTimeout(() => {
                    notificationPanel.classList.add('hidden');
                }, 300); // Corresponds to transition duration
            }
        }

        /**
         * Fetches notifications from the backend.
         */
        async function fetchNotifications() {
            loadingSpinner.style.display = 'block';
            noNotificationsMessage.classList.add('hidden');
            notificationList.innerHTML = ''; // Clear previous notifications before loading new ones (except spinner)
            notificationList.appendChild(loadingSpinner);


            try {
                // Make sure the backendUrl points to the PHP file that outputs JSON
                const response = await fetch(`${backendUrl}?action=get_notifications&cache_bust=${new Date().getTime()}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                loadingSpinner.style.display = 'none';

                if (data.success && data.notifications.length > 0) {
                    renderNotifications(data.notifications);
                    updateNotificationDot(data.notifications);
                } else if (data.success && data.notifications.length === 0) {
                    noNotificationsMessage.classList.remove('hidden');
                    notificationDot.style.display = 'none';
                } else {
                    displayError('Failed to load notifications.');
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
                loadingSpinner.style.display = 'none';
                displayError('Could not connect to server.');
            }
        }

        /**
         * Renders notifications in the list.
         * @param {Array} notifications - Array of notification objects.
         */
        function renderNotifications(notifications) {
            notificationList.innerHTML = ''; // Clear list (including spinner/no message)
            notifications.forEach(notification => {
                const item = document.createElement('a');
                item.href = notification.link || '#';
                item.classList.add('block', 'p-4', 'border-b', 'border-gray-100', 'transition-colors', 'duration-150', 'notification-item');
                if (parseInt(notification.is_read) === 0) {
                    item.classList.add('unread');
                }
                item.dataset.id = notification.id; // Store ID for marking as read

                // Format date (simple version)
                const date = new Date(notification.created_at);
                const formattedDate = `${date.toLocaleDateString()} ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;

                item.innerHTML = `
                    <div class="flex items-start">
                        ${parseInt(notification.is_read) === 0 ? '<span class="message-dot"></span>' : '<span class="w-2 mr-2"></span>'}
                        <div class="flex-1">
                            <p class="text-sm text-gray-700 ${parseInt(notification.is_read) === 0 ? 'font-semibold' : ''}">${notification.message}</p>
                            <p class="text-xs text-gray-500 mt-1">${formattedDate}</p>
                        </div>
                        ${parseInt(notification.is_read) === 0 ? '<i class="fas fa-circle text-blue-500 text-xs ml-2" title="Unread"></i>' : ''}
                    </div>
                `;
                item.addEventListener('click', (e) => {
                    // Allow default link behavior if it's not just a placeholder
                    if (item.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                    if (parseInt(notification.is_read) === 0) {
                        markNotificationAsRead(notification.id, item);
                    }
                    // Optionally close panel on click:
                    // toggleNotificationPanel();
                });
                notificationList.appendChild(item);
            });
        }

        /**
         * Updates the notification dot visibility based on unread notifications.
         * @param {Array} notifications - Array of notification objects.
         */
        function updateNotificationDot(notifications) {
            const hasUnread = notifications.some(n => parseInt(n.is_read) === 0);
            notificationDot.style.display = hasUnread ? 'block' : 'none';
        }

        /**
         * Marks a single notification as read.
         * @param {number} notificationId - The ID of the notification.
         * @param {HTMLElement} itemElement - The DOM element of the notification.
         */
        async function markNotificationAsRead(notificationId, itemElement) {
            // Optimistically update UI
            if (itemElement) {
                itemElement.classList.remove('unread');
                itemElement.classList.remove('font-semibold'); // If bold was used for unread text
                const unreadIcon = itemElement.querySelector('.fa-circle');
                if (unreadIcon) unreadIcon.remove();
                const messageDot = itemElement.querySelector('.message-dot');
                if (messageDot) messageDot.style.display = 'none'; // or remove it
            }


            try {
                const response = await fetch(`${backendUrl}?action=mark_as_read&id=${notificationId}`);
                const data = await response.json();
                if (data.success) {
                    console.log(`Notification ${notificationId} marked as read on server.`);
                    // Re-fetch or update local state to ensure dot is accurate
                    const remainingUnread = Array.from(notificationList.querySelectorAll('.notification-item.unread')).length;
                    if (remainingUnread === 0) {
                        notificationDot.style.display = 'none';
                    }
                } else {
                    console.error('Failed to mark notification as read on server.');
                    // Revert UI change if server update failed (optional)
                    if (itemElement) itemElement.classList.add('unread');
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
                // Revert UI change
                if (itemElement) itemElement.classList.add('unread');
            }
        }

        /**
         * Marks all notifications as read.
         */
        async function markAllNotificationsAsRead() {
            // Optimistically update UI
            const unreadItems = notificationList.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => {
                item.classList.remove('unread');
                item.classList.remove('font-semibold');
                const unreadIcon = item.querySelector('.fa-circle');
                if (unreadIcon) unreadIcon.remove();
                const messageDot = item.querySelector('.message-dot');
                if (messageDot) messageDot.style.display = 'none';
            });
            notificationDot.style.display = 'none';

            try {
                const response = await fetch(`${backendUrl}?action=mark_all_as_read`);
                const data = await response.json();
                if (data.success) {
                    console.log('All notifications marked as read on server.');
                    // No need to re-fetch if all are visually marked read
                } else {
                    console.error('Failed to mark all notifications as read on server.');
                    // Revert UI changes (more complex, might involve re-fetching)
                    fetchNotifications(); // Simple way to revert: re-fetch
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
            notificationList.innerHTML = `<div class="p-4 text-center text-red-500">${message}</div>`;
        }

        // --- Event Listeners ---
        notificationBell.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent click from bubbling to document
            toggleNotificationPanel();
        });

        markAllReadBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            markAllNotificationsAsRead();
        });

        // Close panel if clicked outside
        document.addEventListener('click', (event) => {
            if (isPanelOpen && !notificationContainer.contains(event.target)) {
                toggleNotificationPanel();
            }
        });

        // Close panel with Escape key
        document.addEventListener('keydown', (event) => {
            if (isPanelOpen && event.key === 'Escape') {
                toggleNotificationPanel();
            }
        });

        // --- Initial Load (optional, if you want to check for unread on page load) ---
        // (async () => {
        //     try {
        //         const response = await fetch(`${backendUrl}?action=get_notifications&cache_bust=${new Date().getTime()}`);
        //         if (!response.ok) return;
        //         const data = await response.json();
        //         if (data.success && data.notifications) {
        //             updateNotificationDot(data.notifications);
        //         }
        //     } catch (error) {
        //         console.error("Initial notification check failed:", error);
        //     }
        // })();

    </script>
</body>

</html>