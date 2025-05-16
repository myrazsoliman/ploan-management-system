<?php
session_start();
// CONFIGURATION
define('DATA_FILE', __DIR__ . '/loan_plan.json'); // Assumes loan_plan.json is in the same directory as this admin script.

// DEFAULT DATA (matches the structure from your user page)
function get_default_loan_plan()
{
	return [
		[
			'title' => 'Personal Loan',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>',
			'description' => 'Flexible financing for your diverse personal needs, from consolidating debt to funding a major purchase. Often unsecured for quicker access.',
			'terms' => [
				'6_months' => [
					'interest_rate' => '1.5% / month',
					'penalty' => '5% of overdue amount + P500',
				],
				'12_months' => [
					'interest_rate' => '1.3% / month',
					'penalty' => '5% of overdue amount + P500',
				],
			],
		],
		[
			'title' => 'Home Loan',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" /></svg>',
			'description' => 'Secure the keys to your dream home or invest in property with our competitive mortgage options. Tailored for real estate purchases.',
			'terms' => [
				'6_months' => [
					'interest_rate' => '0.8% / month',
					'penalty' => '1% of outstanding balance monthly',
					'promotional' => true,
				],
				'12_months' => [
					'interest_rate' => '0.75% / month',
					'penalty' => '1% of outstanding balance monthly',
				],
			],
		],
		[
			'title' => 'Education Loan',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.25c2.291 0 4.545-.16 6.731-.469a60.437 60.437 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>',
			'description' => 'Invest in your future or that of your loved ones by financing education costs with our supportive student loan plans.',
			'terms' => [
				'6_months' => [
					'interest_rate' => '0.9% / month',
					'penalty' => '3% of unpaid amount (due date)',
				],
				'12_months' => [
					'interest_rate' => '0.7% / month',
					'penalty' => '3% of unpaid amount (due date)',
				],
			],
		],
		[
			'title' => 'Auto Loan',
			'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.988-1.13A6.004 6.004 0 0 0 12 4.5v0c0 .568.422 1.048.988 1.13a6.004 6.004 0 0 0 1.962 0Z M12 14.25v4.5" /></svg>',
			'description' => 'Drive your dream car sooner with our straightforward auto loans, designed for purchasing new or pre-owned vehicles.',
			'terms' => [
				'6_months' => [
					'interest_rate' => '1.2% / month',
					'penalty' => '4% on overdue amortization',
				],
				'12_months' => [
					'interest_rate' => '1.0% / month',
					'penalty' => '4% on overdue amortization',
				],
			],
		],
	];
}

// HELPER FUNCTIONS
function get_loan_plan()
{
	if (!file_exists(DATA_FILE)) {
		// Create file with default data if it doesn't exist
		if (save_loan_plan(get_default_loan_plan())) {
			$_SESSION['message'] = "<code>loan_plan.json</code> created with default data.";
			$_SESSION['message_type'] = "success";
		} else {
			$_SESSION['message'] = "Error: Could not create <code>loan_plan.json</code>. Check directory permissions.";
			$_SESSION['message_type'] = "error";
			return []; // Return empty if save fails
		}
	}
	$json_data = file_get_contents(DATA_FILE);
	$plans = json_decode($json_data, true);
	return ($plans === null || json_last_error() !== JSON_ERROR_NONE) ? [] : $plans;
}

function save_loan_plan($plans)
{
	// Ensure array keys are reset if any deletions happened, to maintain 0-indexed array for JSON
	$plans_reindexed = array_values($plans);
	$json_data = json_encode($plans_reindexed, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	if ($json_data === false) {
		$_SESSION['message'] = "Error encoding loan plans to JSON: " . json_last_error_msg();
		$_SESSION['message_type'] = "error";
		return false;
	}
	if (file_put_contents(DATA_FILE, $json_data)) {
		return true;
	} else {
		$_SESSION['message'] = "Error saving loan plans to <code>" . basename(DATA_FILE) . "</code>. Check file permissions.";
		$_SESSION['message_type'] = "error";
		return false;
	}
}

function generate_unique_id()
{
	return uniqid('plan_', true);
}

// --- HANDLE ACTIONS ---
$action = $_REQUEST['action'] ?? 'list';
$id = $_REQUEST['id'] ?? null; // Using 'id' which will be the array index

$loan_plan = get_loan_plan();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	switch ($action) {
		case 'save_plan':
			$title = trim($_POST['title'] ?? '');
			$icon = trim($_POST['icon'] ?? '');
			$description = trim($_POST['description'] ?? '');
			$plan_terms_input = $_POST['terms'] ?? [];

			if (empty($title) || empty($icon) || empty($description)) {
				$_SESSION['message'] = "Title, Icon SVG, and Description are required.";
				$_SESSION['message_type'] = "error";
				// Retain form data to repopulate
				$_SESSION['form_data'] = $_POST;
				header("Location: ?action=show_add_form");
				exit;
			}

			$new_plan = [
				'id' => generate_unique_id(), // Add a unique ID
				'title' => $title,
				'icon' => $icon, // Storing raw SVG
				'description' => $description,
				'terms' => []
			];

			foreach ($plan_terms_input as $term_data) {
				$key = trim($term_data['key'] ?? '');
				$interest = trim($term_data['interest_rate'] ?? '');
				$penalty = trim($term_data['penalty'] ?? '');
				$promotional = isset($term_data['promotional']);

				if (!empty($key) && !empty($interest) && !empty($penalty)) {
					$new_plan['terms'][$key] = [
						'interest_rate' => $interest,
						'penalty' => $penalty
					];
					if ($promotional) {
						$new_plan['terms'][$key]['promotional'] = true;
					}
				}
			}
			// Add the new plan to the beginning of the array or end
			array_unshift($loan_plan, $new_plan); // Add to the beginning
			// $loan_plan[] = $new_plan; // Add to the end

			if (save_loan_plan($loan_plan)) {
				$_SESSION['message'] = "Loan plan '{$title}' added successfully.";
				$_SESSION['message_type'] = "success";
			}
			unset($_SESSION['form_data']);
			header("Location: ?action=list");
			exit;

		case 'update_plan':
			$plan_id_to_update = $_POST['plan_id'] ?? null;
			$title = trim($_POST['title'] ?? '');
			$icon = trim($_POST['icon'] ?? '');
			$description = trim($_POST['description'] ?? '');
			$plan_terms_input = $_POST['terms'] ?? [];

			$plan_index = null;
			foreach ($loan_plan as $index => $plan) {
				if (isset($plan['id']) && $plan['id'] === $plan_id_to_update) {
					$plan_index = $index;
					break;
				}
			}

			if ($plan_index === null) {
				$_SESSION['message'] = "Error: Plan ID not found for update.";
				$_SESSION['message_type'] = "error";
				header("Location: ?action=list");
				exit;
			}

			if (empty($title) || empty($icon) || empty($description)) {
				$_SESSION['message'] = "Title, Icon SVG, and Description are required.";
				$_SESSION['message_type'] = "error";
				// Retain form data to repopulate (consider how to handle this with existing plan data)
				header("Location: ?action=show_edit_form&id=" . $plan_index); // Or plan_id
				exit;
			}

			$updated_plan_data = [
				'id' => $loan_plan[$plan_index]['id'], // Keep original ID
				'title' => $title,
				'icon' => $icon,
				'description' => $description,
				'terms' => []
			];

			foreach ($plan_terms_input as $term_data) {
				$key = trim($term_data['key'] ?? '');
				$interest = trim($term_data['interest_rate'] ?? '');
				$penalty = trim($term_data['penalty'] ?? '');
				$promotional = isset($term_data['promotional']);

				if (!empty($key) && !empty($interest) && !empty($penalty)) {
					$updated_plan_data['terms'][$key] = [
						'interest_rate' => $interest,
						'penalty' => $penalty
					];
					if ($promotional) {
						$updated_plan_data['terms'][$key]['promotional'] = true;
					}
				}
			}

			$loan_plan[$plan_index] = $updated_plan_data;

			if (save_loan_plan($loan_plan)) {
				$_SESSION['message'] = "Loan plan '{$title}' updated successfully.";
				$_SESSION['message_type'] = "success";
			}
			header("Location: ?action=list");
			exit;

		case 'delete_plan':
			$plan_id_to_delete = $_POST['plan_id'] ?? null;
			$deleted_title = "Unknown Plan";

			$plan_index_to_delete = null;
			foreach ($loan_plan as $index => $plan) {
				if (isset($plan['id']) && $plan['id'] === $plan_id_to_delete) {
					$plan_index_to_delete = $index;
					$deleted_title = $plan['title'];
					break;
				}
			}

			if ($plan_index_to_delete !== null) {
				array_splice($loan_plan, $plan_index_to_delete, 1);
				if (save_loan_plan($loan_plan)) {
					$_SESSION['message'] = "Loan plan '{$deleted_title}' deleted successfully.";
					$_SESSION['message_type'] = "success";
				}
			} else {
				$_SESSION['message'] = "Error: Plan ID not found for deletion.";
				$_SESSION['message_type'] = "error";
			}
			header("Location: ?action=list");
			exit;
	}
}

// Get a specific plan for editing (using array index as $id for simplicity from GET link)
$current_plan_data = null;
$is_editing = false;
if ($action === 'show_edit_form' && $id !== null) {
	$plan_to_edit_id = $id; // $id is the unique plan ID here
	foreach ($loan_plan as $plan) {
		if (isset($plan['id']) && $plan['id'] === $plan_to_edit_id) {
			$current_plan_data = $plan;
			$is_editing = true;
			break;
		}
	}
	if (!$is_editing) { // Plan with that ID not found
		$_SESSION['message'] = "Plan with ID '{$id}' not found for editing.";
		$_SESSION['message_type'] = "error";
		header("Location: ?action=list");
		exit;
	}
}

// Retrieve stashed form data on error
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

if ($action === 'show_add_form' && !empty($form_data)) {
	$current_plan_data = $form_data; // Repopulate form with stashed data
	$current_plan_data['terms_input'] = $current_plan_data['terms'] ?? []; // Prepare terms for form
	unset($current_plan_data['terms']); // Avoid conflict with terms display logic
} elseif ($is_editing && !empty($form_data)) {
	// If there was an error during an update, POST data might be stashed
	// Prioritize stashed data over $current_plan_data from file if available
	$current_plan_data['title'] = $form_data['title'] ?? $current_plan_data['title'];
	$current_plan_data['icon'] = $form_data['icon'] ?? $current_plan_data['icon'];
	$current_plan_data['description'] = $form_data['description'] ?? $current_plan_data['description'];
	// Special handling for terms if they were also in form_data
	if (isset($form_data['terms'])) {
		$current_plan_data['terms_input'] = $form_data['terms'];
	}
}


// Prepare terms for form display (convert associative to indexed for template)
if ($is_editing && isset($current_plan_data['terms'])) {
	$current_plan_data['terms_input'] = [];
	foreach ($current_plan_data['terms'] as $key => $details) {
		$current_plan_data['terms_input'][] = array_merge(['key' => $key], $details);
	}
} elseif (isset($current_plan_data['terms_input'])) {
	// Already prepared from stashed form data
} else {
	$current_plan_data['terms_input'] = [['key' => '', 'interest_rate' => '', 'penalty' => '', 'promotional' => false]]; // Default for new plan
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Loan Plan Management</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet">
	<style>
		body {
			font-family: 'Inter', sans-serif;
			background-color: #f7f8fc;
		}

		:root {
			--maroon-primary: #8C1C1C;
			--maroon-dark: #6F1616;
			--maroon-light-accent: #f5eaea;
			--text-primary: #2d3748;
			--text-secondary: #4a5568;
			--border-color: #e2e8f0;
			/* Softer border color */
			--success-green: #28a745;
			--error-red: #dc2626;
			--white: #ffffff;
			--light-gray-bg: #f8f9fa;
		}

		.text-maroon-primary {
			color: var(--maroon-primary);
		}

		.bg-maroon-primary {
			background-color: var(--maroon-primary);
		}

		.hover\:bg-maroon-dark:hover {
			background-color: var(--maroon-dark);
		}

		.border-maroon-primary {
			border-color: var(--maroon-primary);
		}

		.btn {
			padding: 0.5rem 1rem;
			border-radius: 0.375rem;
			font-weight: 500;
			transition: background-color 0.2s ease;
			text-decoration: none;
			display: inline-block;
			text-align: center;
		}

		.btn-primary {
			background-color: var(--maroon-primary);
			color: var(--white);
		}

		.btn-primary:hover {
			background-color: var(--maroon-dark);
		}

		.btn-secondary {
			background-color: var(--text-secondary);
			color: var(--white);
		}

		.btn-secondary:hover {
			background-color: var(--text-primary);
		}

		.btn-danger {
			background-color: var(--error-red);
			color: var(--white);
		}

		.btn-danger:hover {
			background-color: #b91c1c;
			/* Darker red */
		}

		.table-wrapper {
			background-color: var(--white);
			border: 1px solid var(--border-color);
			border-radius: 0.5rem;
			/* 8px */
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
			overflow-x: auto;
		}

		th,
		td {
			padding: 0.75rem 1rem;
			/* 12px 16px */
			text-align: left;
			border-bottom: 1px solid var(--border-color);
			vertical-align: middle;
		}

		th {
			background-color: var(--maroon-light-accent);
			color: var(--maroon-primary);
			font-weight: 600;
			/* semibold */
		}

		tr:last-child td {
			border-bottom: none;
		}

		tr:hover {
			background-color: #fdf7f7;
			/* var(--maroon-ultralight-bg) */
		}

		.form-input,
		.form-textarea {
			width: 100%;
			padding: 0.625rem 0.875rem;
			/* 10px 14px */
			border: 1px solid var(--border-color);
			border-radius: 0.375rem;
			/* 6px */
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
			transition: border-color 0.2s ease, box-shadow 0.2s ease;
		}

		.form-input:focus,
		.form-textarea:focus {
			border-color: var(--maroon-primary);
			box-shadow: 0 0 0 3px rgba(140, 28, 28, 0.15);
			outline: none;
		}

		.form-label {
			display: block;
			margin-bottom: 0.375rem;
			/* 6px */
			font-weight: 500;
			/* medium */
			color: var(--text-primary);
		}

		.form-check-input {
			margin-right: 0.5rem;
			accent-color: var(--maroon-primary);
		}

		.message {
			padding: 1rem;
			margin-bottom: 1.5rem;
			border-radius: 0.375rem;
			font-weight: 500;
		}

		.message.success {
			background-color: #d1fae5;
			/* Tailwind green-100 */
			color: #065f46;
			/* Tailwind green-800 */
			border: 1px solid #6ee7b7;
			/* Tailwind green-300 */
		}

		.message.error {
			background-color: #fee2e2;
			/* Tailwind red-100 */
			color: #991b1b;
			/* Tailwind red-800 */
			border: 1px solid #fca5a5;
			/* Tailwind red-300 */
		}

		.icon-preview {
			width: 32px;
			height: 32px;
			color: var(--maroon-primary);
			border: 1px solid var(--border-color);
			padding: 4px;
			border-radius: 4px;
		}

		.term-block {
			background-color: var(--light-gray-bg);
			border: 1px solid var(--border-color);
			padding: 1rem;
			margin-bottom: 1rem;
			border-radius: 0.375rem;
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

	<main class="flex-grow p-6 md:p-8 lg:p-10">
		<div class="container mx-auto max-w-screen-lg">
			<header class="mb-8">
				<h1 class="text-3xl font-bold text-maroon-primary">Loan Plan Management</h1>
			</header>

			<?php if (isset($_SESSION['message'])): ?>
				<div class="message <?php echo htmlspecialchars($_SESSION['message_type']); ?>">
					<?php echo $_SESSION['message'];
					unset($_SESSION['message'], $_SESSION['message_type']); ?>
				</div>
			<?php endif; ?>

			<?php if ($action === 'list'): ?>
				<div class="mb-6 text-right">
					<a href="?action=show_add_form" class="btn btn-primary">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
							class="w-5 h-5 inline-block mr-1 -ml-1">
							<path
								d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
						</svg>
						Add New Loan Plan
					</a>
				</div>
				<div class="table-wrapper">
					<table class="min-w-full">
						<thead>
							<tr>
								<th>Icon</th>
								<th>Title</th>
								<th>Description</th>
								<th>Terms Count</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($loan_plan)): ?>
								<tr>
									<td colspan="5" class="text-center text-gray-500 py-8">No loan plans found. <a
											href="?action=show_add_form" class="text-maroon-primary hover:underline">Add one
											now</a>.</td>
								</tr>
							<?php else: ?>
								<?php foreach ($loan_plan as $index => $plan): // $index is numeric, $plan['id'] is the unique string ID ?>
									<tr>
										<td>
											<div class="icon-preview"><?php echo $plan['icon'] ?? ''; ?></div>
										</td>
										<td class="font-medium text-maroon-primary">
											<?php echo htmlspecialchars($plan['title']); ?>
										</td>
										<td class="text-sm text-gray-600 max-w-xs truncate"
											title="<?php echo htmlspecialchars($plan['description']); ?>">
											<?php echo htmlspecialchars(mb_strimwidth($plan['description'], 0, 70, "...")); ?>
										</td>
										<td class="text-sm"><?php echo count($plan['terms'] ?? []); ?></td>
										<td class="space-x-2 whitespace-nowrap">
											<a href="?action=show_edit_form&id=<?php echo htmlspecialchars($plan['id'] ?? $index); ?>"
												class="btn btn-secondary btn-sm py-1 px-2 text-xs">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
													class="w-4 h-4 inline-block mr-1">
													<path
														d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774 newfound error_recovering_property_owner M6.75 6.774v2.475h2.475l4.263-4.262a1.75 1.75 0 0 0 0-2.475Z" />
													<path
														d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774v2.475h2.475l4.263-4.262a1.75 1.75 0 0 0 0-2.475Z" />
													<path
														d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9.5A.75.75 0 0 1 14 9.5v1.75A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H6.5a.75.75 0 0 1 0 1.5H4.75Z" />
												</svg>Edit
											</a>
											<form action="?action=delete_plan" method="POST" class="inline-block"
												onsubmit="return confirm('Are you sure you want to delete this loan plan: <?php echo htmlspecialchars(addslashes($plan['title'])); ?>?');">
												<input type="hidden" name="plan_id"
													value="<?php echo htmlspecialchars($plan['id'] ?? $index); ?>">
												<button type="submit" class="btn btn-danger btn-sm py-1 px-2 text-xs">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
														class="w-4 h-4 inline-block mr-1">
														<path fill-rule="evenodd"
															d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.31l.94 7.522a2.25 2.25 0 0 0 2.244 2.003h3.512a2.25 2.25 0 0 0 2.244-2.003l.94-7.522h.31a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM4.56 6h6.88l-.867 6.934a.75.75 0 0 1-.748.666H7.175a.75.75 0 0 1-.748-.666L4.56 6Z"
															clip-rule="evenodd" />
													</svg>Delete
												</button>
											</form>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>

			<?php elseif ($action === 'show_add_form' || $action === 'show_edit_form'): ?>
				<?php
				$form_action_url = $is_editing ? '?action=update_plan' : '?action=save_plan';
				$form_title = $is_editing ? 'Edit Loan Plan' : 'Add New Loan Plan';
				$submit_button_text = $is_editing ? 'Save Changes' : 'Add Plan';
				?>
				<div class="bg-white p-6 md:p-8 rounded-lg shadow-lg border border-gray-200">
					<h2 class="text-2xl font-semibold text-maroon-primary mb-6"><?php echo $form_title; ?></h2>
					<form action="<?php echo $form_action_url; ?>" method="POST">
						<?php if ($is_editing && isset($current_plan_data['id'])): ?>
							<input type="hidden" name="plan_id"
								value="<?php echo htmlspecialchars($current_plan_data['id']); ?>">
						<?php endif; ?>

						<div class="mb-4">
							<label for="title" class="form-label">Plan Title <span class="text-red-500">*</span></label>
							<input type="text" id="title" name="title" class="form-input"
								value="<?php echo htmlspecialchars($current_plan_data['title'] ?? ''); ?>" required>
						</div>

						<div class="mb-4">
							<label for="icon" class="form-label">Icon SVG Code <span class="text-red-500">*</span></label>
							<textarea id="icon" name="icon" rows="3" class="form-textarea font-mono text-xs"
								placeholder='<svg xmlns=...>...</svg>'
								required><?php echo htmlspecialchars($current_plan_data['icon'] ?? ''); ?></textarea>
							<p class="text-xs text-gray-500 mt-1">Paste the full SVG code for the icon.</p>
						</div>

						<div class="mb-6">
							<label for="description" class="form-label">Description <span
									class="text-red-500">*</span></label>
							<textarea id="description" name="description" rows="4" class="form-textarea"
								required><?php echo htmlspecialchars($current_plan_data['description'] ?? ''); ?></textarea>
						</div>

						<hr class="my-6 border-gray-300">

						<div class="mb-4">
							<h3 class="text-lg font-semibold text-maroon-primary mb-3">Loan Terms</h3>
							<div id="terms-container" class="space-y-4">
								<?php
								$terms_to_display = $current_plan_data['terms_input'] ?? [['key' => '', 'interest_rate' => '', 'penalty' => '', 'promotional' => false]];
								if (empty($terms_to_display)) { // Ensure at least one block if terms_input is empty
									$terms_to_display = [['key' => '', 'interest_rate' => '', 'penalty' => '', 'promotional' => false]];
								}
								foreach ($terms_to_display as $idx => $term):
									?>
									<div class="term-block" data-term-index="<?php echo $idx; ?>">
										<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
											<div>
												<label for="term_key_<?php echo $idx; ?>" class="form-label">Term Key (e.g.,
													6_months) <span class="text-red-500">*</span></label>
												<input type="text" id="term_key_<?php echo $idx; ?>"
													name="terms[<?php echo $idx; ?>][key]" class="form-input"
													value="<?php echo htmlspecialchars($term['key'] ?? ''); ?>"
													placeholder="e.g., 6_months, 1_year_promo">
											</div>
											<div>
												<label for="term_interest_<?php echo $idx; ?>" class="form-label">Interest
													Rate <span class="text-red-500">*</span></label>
												<input type="text" id="term_interest_<?php echo $idx; ?>"
													name="terms[<?php echo $idx; ?>][interest_rate]" class="form-input"
													value="<?php echo htmlspecialchars($term['interest_rate'] ?? ''); ?>"
													placeholder="e.g., 1.5% / month">
											</div>
										</div>
										<div class="mt-3">
											<label for="term_penalty_<?php echo $idx; ?>" class="form-label">Late Penalty
												<span class="text-red-500">*</span></label>
											<input type="text" id="term_penalty_<?php echo $idx; ?>"
												name="terms[<?php echo $idx; ?>][penalty]" class="form-input"
												value="<?php echo htmlspecialchars($term['penalty'] ?? ''); ?>"
												placeholder="e.g., 5% of overdue + P500">
										</div>
										<div class="mt-3 flex items-center">
											<input type="checkbox" id="term_promotional_<?php echo $idx; ?>"
												name="terms[<?php echo $idx; ?>][promotional]"
												class="form-check-input h-4 w-4 rounded border-gray-300 text-maroon-primary focus:ring-maroon-primary"
												<?php echo (isset($term['promotional']) && $term['promotional']) ? 'checked' : ''; ?>>
											<label for="term_promotional_<?php echo $idx; ?>"
												class="text-sm text-gray-700">Promotional Term</label>
										</div>
										<?php if ($idx > 0): // Show remove button for terms beyond the first ?>
											<div class="mt-3 text-right">
												<button type="button"
													class="text-sm text-red-600 hover:text-red-800 font-medium remove-term-btn">Remove
													Term</button>
											</div>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
							<button type="button" id="add-term-btn" class="mt-4 btn btn-secondary text-sm py-2 px-3">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
									class="w-4 h-4 inline-block mr-1">
									<path
										d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
								</svg>
								Add Another Term
							</button>
						</div>

						<hr class="my-8 border-gray-300">

						<div class="flex justify-end space-x-3">
							<a href="?action=list" class="btn bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</a>
							<button type="submit" class="btn btn-primary">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
									class="w-5 h-5 inline-block mr-1 -ml-1">
									<path fill-rule="evenodd"
										d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
										clip-rule="evenodd" />
								</svg>
								<?php echo $submit_button_text; ?>
							</button>
						</div>
					</form>
				</div>
				<script>
					document.addEventListener('DOMContentLoaded', function () {
						const termsContainer = document.getElementById('terms-container');
						const addTermBtn = document.getElementById('add-term-btn');
						let termIndex = termsContainer.children.length > 0 ? termsContainer.querySelectorAll('.term-block').length : 0;

						addTermBtn.addEventListener('click', function () {
							const newTermBlock = `
								<div class="term-block" data-term-index="${termIndex}">
									<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
										<div>
											<label for="term_key_${termIndex}" class="form-label">Term Key <span class="text-red-500">*</span></label>
											<input type="text" id="term_key_${termIndex}" name="terms[${termIndex}][key]" class="form-input" placeholder="e.g., 24_months">
										</div>
										<div>
											<label for="term_interest_${termIndex}" class="form-label">Interest Rate <span class="text-red-500">*</span></label>
											<input type="text" id="term_interest_${termIndex}" name="terms[${termIndex}][interest_rate]" class="form-input" placeholder="e.g., 1.0% / month">
										</div>
									</div>
									<div class="mt-3">
										<label for="term_penalty_${termIndex}" class="form-label">Late Penalty <span class="text-red-500">*</span></label>
										<input type="text" id="term_penalty_${termIndex}" name="terms[${termIndex}][penalty]" class="form-input" placeholder="e.g., 3% of overdue">
									</div>
									<div class="mt-3 flex items-center">
										<input type="checkbox" id="term_promotional_${termIndex}" name="terms[${termIndex}][promotional]" class="form-check-input h-4 w-4 rounded border-gray-300 text-maroon-primary focus:ring-maroon-primary">
										<label for="term_promotional_${termIndex}" class="text-sm text-gray-700">Promotional Term</label>
									</div>
									<div class="mt-3 text-right">
										<button type="button" class="text-sm text-red-600 hover:text-red-800 font-medium remove-term-btn">Remove Term</button>
									</div>
								</div>`;
							termsContainer.insertAdjacentHTML('beforeend', newTermBlock);
							termIndex++;
							attachRemoveListeners();
						});

						function attachRemoveListeners() {
							termsContainer.querySelectorAll('.remove-term-btn').forEach(button => {
								// Remove existing listener to prevent duplicates if called multiple times
								button.replaceWith(button.cloneNode(true));
							});
							termsContainer.querySelectorAll('.remove-term-btn').forEach(button => {
								button.addEventListener('click', function (event) {
									// Ensure we don't remove the very first term block if it's the only one.
									// Or, always allow removal and let the user manage. For now, always allow.
									if (termsContainer.children.length > 0) { // Check if there's at least one to remove safely
										event.target.closest('.term-block').remove();
									}
								});
							});
						}
						attachRemoveListeners(); // Initial attachment for existing edit form terms
					});
				</script>

			<?php endif; ?>
		</div>
	</main>
	</div>
</body>

</html>