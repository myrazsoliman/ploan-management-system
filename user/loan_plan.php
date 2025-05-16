<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Loan Plans</title>
	<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet">
	<style>
		body {
			font-family: 'Inter', sans-serif;
			background-color: #f7f8fc;
			/* Slightly cooler and brighter light gray */
		}

		/* Professional Maroon Theme Colors (Refined) */
		:root {
			--maroon-primary: #8C1C1C;
			--maroon-dark: #6F1616;
			--maroon-light-accent: #f5eaea;
			--maroon-ultralight-bg: #fdf7f7;
			/* For very subtle backgrounds or hover states */
			--text-primary: #2d3748;
			/* Darker gray for better contrast */
			--text-secondary: #4a5568;
			/* Slightly darker for readability */
			--border-color: #e2e8f0;
			/* Softer border color */
			--success-green: #28a745;
			/* For promotional tags or positive highlights */
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

		.loan-card {
			background-color: var(--white);
			border: 1px solid var(--border-color);
			border-radius: 0.875rem;
			/* 14px - slightly more rounded */
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
			transition: transform 0.3s ease, box-shadow 0.3s ease;
			display: flex;
			flex-direction: column;
			overflow: hidden;
			/* Ensures child elements conform to rounded corners */
		}

		.loan-card:hover {
			transform: translateY(-6px);
			box-shadow: 0 10px 25px rgba(140, 28, 28, 0.12);
			/* More pronounced maroon shadow */
		}

		.loan-card-header {
			background-color: var(--maroon-ultralight-bg);
			/* Lighter accent */
			padding: 1.5rem;
			/* 24px */
			border-bottom: 1px solid var(--border-color);
		}

		.icon-style {
			/* Added class for icon container */
			margin-bottom: 0.75rem;
			/* 12px */
			transition: transform 0.3s ease;
		}

		.loan-card:hover .icon-style svg {
			transform: scale(1.1);
		}

		.icon-style svg {
			color: var(--maroon-primary);
			width: 3rem;
			/* 48px - slightly larger */
			height: 3rem;
			/* 48px */
		}

		.card-title {
			font-size: 1.625rem;
			/* 26px */
			font-weight: 700;
			/* Bold */
			color: var(--maroon-primary);
			margin-bottom: 0.25rem;
			/* 4px */
		}

		.card-subtitle {
			/* New class for subtitle if needed, or for short description */
			font-size: 0.875rem;
			/* 14px */
			color: var(--text-secondary);
			margin-bottom: 0.75rem;
			/* 12px */
		}

		.card-description {
			color: var(--text-secondary);
			font-size: 0.9rem;
			/* 15px for better readability */
			line-height: 1.65;
			padding: 1.25rem 1.5rem 0;
			/* Adjust padding */
			flex-grow: 1;
		}

		.term-section-wrapper {
			padding: 1.25rem 1.5rem;
			/* Consistent padding */
		}

		.term-block {
			/* New class for individual term blocks */
			background-color: var(--light-gray-bg);
			padding: 1rem;
			/* 16px */
			border-radius: 0.5rem;
			/* 8px */
			border: 1px solid #edf2f7;
			/* Lighter border for term block */
			margin-bottom: 1rem;
			/* Space between term blocks */
		}

		.term-block:last-child {
			margin-bottom: 0;
		}

		.term-title {
			font-size: 1rem;
			/* 16px */
			font-weight: 600;
			/* semibold */
			color: var(--maroon-primary);
			margin-bottom: 0.625rem;
			/* 10px */
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.term-details li {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 0.375rem 0;
			/* 6px - slightly more space */
			font-size: 0.875rem;
			/* 14px */
			border-bottom: 1px dashed #e2e8f0;
			/* Dashed separator for items */
		}

		.term-details li:last-child {
			border-bottom: none;
		}

		.term-details .label {
			color: var(--text-secondary);
			font-weight: 500;
			/* medium */
		}

		.term-details .value {
			color: var(--text-primary);
			font-weight: 600;
			/* semibold for value */
			text-align: right;
		}

		.promotional-badge {
			background-color: var(--success-green);
			color: var(--white);
			font-size: 0.7rem;
			/* 11px */
			font-weight: 600;
			padding: 0.2rem 0.5rem;
			border-radius: 0.25rem;
			/* 4px */
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}


		.apply-button-container {
			padding: 1.5rem;
			/* 24px */
			margin-top: auto;
			background-color: var(--white);
			/* Keep it clean */
			border-top: 1px solid var(--border-color);
		}

		.apply-button {
			display: block;
			width: 100%;
			background-color: var(--maroon-primary);
			color: white;
			font-weight: 600;
			/* semibold */
			font-size: 1rem;
			/* 16px */
			padding: 0.875rem 1.5rem;
			/* py-3.5 px-6 */
			border-radius: 0.5rem;
			/* rounded-lg */
			text-align: center;
			transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
			letter-spacing: 0.5px;
			/* Subtle letter spacing */
		}

		.apply-button:hover {
			background-color: var(--maroon-dark);
			transform: translateY(-2px) scale(1.01);
			/* Slight scale effect */
			box-shadow: 0 6px 12px rgba(140, 28, 28, 0.3);
		}

		.apply-button svg {
			/* For potential icon in button */
			margin-left: 0.5rem;
			transition: transform 0.2s ease;
		}

		.apply-button:hover svg {
			transform: translateX(3px);
		}

		.page-header {
			padding-bottom: 2rem;
			/* More space below header */
		}

		.page-header h1 {
			font-size: 3rem;
			/* 48px - more impactful */
			font-weight: 800;
			/* extrabold */
			color: var(--maroon-primary);
			/* Already set by class, but good to have here */
			letter-spacing: -0.5px;
		}

		.page-header p {
			font-size: 1.125rem;
			/* 18px - slightly larger */
			color: var(--text-secondary);
			max-width: 600px;
			/* Constrain width for readability */
			margin-left: auto;
			margin-right: auto;
		}

		/* Footer styling */
		.site-footer {
			background-color: var(--maroon-ultralight-bg);
			color: var(--text-secondary);
			padding: 2.5rem 1rem;
			/* Increased padding */
		}

		.site-footer p {
			margin-bottom: 0.5rem;
		}

		.site-footer .disclaimer {
			font-size: 0.8rem;
			/* Smaller for disclaimer */
			color: #718096;
			/* Lighter gray for disclaimer */
			max-width: 800px;
			margin: 0.5rem auto 0;
		}

		/* Responsive adjustments for card grid */
		@media (min-width: 1280px) {

			/* xl screens */
			.xl\:grid-cols-4 .loan-card {
				/* If using 4 columns on XL */
				/* Consider adding a max-width if cards get too wide,
				   or ensure content inside can handle it.
				   For now, let's assume content is fine. */
			}
		}
	</style>
</head>

<body>
	<div id="wrapper">
		<?php
		if (file_exists('sidebar.php')) {
			include('sidebar.php');
		} else if (file_exists('../sidebar.php')) {
			include('../sidebar.php');
		}
		?>

		<div class="container mx-auto max-w-screen-xl">
			<header class="text-center mb-12 md:mb-20 page-header">
				<h1 class="text-maroon-primary">Our Tailored Loan Plans</h1>
				<p class="mt-4 text-lg">Find the perfect financial solution, meticulously designed to support your
					aspirations and growth.</p>
			</header>

			<?php
			// --- Loan Data Array ---
			$loan_plans = [
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
							'interest_rate' => '0.8% / month', // Simplified, badge will say "promotional"
							'penalty' => '1% of outstanding balance monthly',
							'promotional' => true, // Added flag for styling
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
							'interest_rate' => '1.0% /	 month',
							'penalty' => '4% on overdue amortization',
						],
					],
				],
			];
			?>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-x-8 gap-y-12">
				<?php foreach ($loan_plans as $plan): ?>
					<div class="loan-card">
						<div class="loan-card-header text-center">
							<div class="inline-block icon-style">
								<?php echo $plan['icon']; ?>
							</div>
							<h2 class="card-title"><?php echo htmlspecialchars($plan['title']); ?></h2>
						</div>

						<p class="card-description">
							<?php echo htmlspecialchars($plan['description']); ?>
						</p>

						<div class="term-section-wrapper">
							<?php foreach ($plan['terms'] as $term_key => $term_details): ?>
								<?php
								$term_duration = str_replace('_', ' ', $term_key); // e.g., "6 months"
								?>
								<div class="term-block">
									<h3 class="term-title">
										<?php echo ucwords($term_duration); ?> Term
										<?php if (isset($term_details['promotional']) && $term_details['promotional']): ?>
											<span class="promotional-badge">Promo</span>
										<?php endif; ?>
									</h3>
									<ul class="term-details space-y-1">
										<li>
											<span class="label">Interest Rate:</span>
											<span
												class="value"><?php echo htmlspecialchars($term_details['interest_rate']); ?></span>
										</li>
										<li>
											<span class="label">Late Penalty:</span>
											<span class="value"><?php echo htmlspecialchars($term_details['penalty']); ?></span>
										</li>
									</ul>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="apply-button-container">
							<button class="apply-button">
								Apply
								<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
									stroke="currentColor" class="w-5 h-5 inline-block">
									<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
								</svg>
							</button>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<footer class="text-center mt-20 py-10 site-footer">
				<p class="text-base font-medium text-gray-700">&copy; <?php echo date("Y"); ?> Your Esteemed Financial
					Institution. All rights reserved.</p>
				<p class="text-sm text-gray-600">Your trusted partner in achieving financial milestones.</p>
				<p class="disclaimer mt-4">
					Disclaimer: The loan terms, interest rates, and penalties displayed are indicative and subject to
					final
					assessment and prevailing institutional policies.
					Eligibility criteria and other conditions apply. We encourage you to consult with a financial
					advisor
					for personalized information before making any financial decisions.
				</p>
			</footer>

		</div>

</body>

</html>