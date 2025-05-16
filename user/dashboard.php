<!DOCTYPE html>
<html lang="en-PH">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLOAN Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f8fc;
            /* Adjusted for potential sidebar */
            display: flex;
            /* Enable flex for potential sidebar layout */
        }

        /* Wrapper to contain main content, allowing sidebar flexibility */
        #main-content-wrapper {
            flex-grow: 1;
            /* Allows content to take remaining space */
            /* Add padding if sidebar has fixed width, or manage via sidebar CSS */
            /* Example: padding-left: 250px; /* If sidebar is 250px wide */
            transition: padding-left 0.3s ease;
            /* Smooth transition if sidebar collapses */
        }


        /* Professional Maroon Theme Colors */
        :root {
            --maroon-primary: #8C1C1C;
            --maroon-dark: #6F1616;
            --maroon-light-accent: #f5eaea;
            --maroon-ultralight-bg: #fdf7f7;
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --border-color: #e2e8f0;
            --success-green: #28a745;
            --info-blue: #3b82f6;
            /* Added a blue for info */
            --white: #ffffff;
            --light-gray-bg: #f8f9fa;
        }

        /* --- Dashboard Specific Styles --- */
        .dashboard-header {
            padding-bottom: 1.5rem;
            /* Reduced bottom padding */
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
            /* Space below header */
        }

        .dashboard-summary-card {
            background-color: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            /* 12px */
            padding: 1.5rem;
            /* p-6 */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            /* Space below summary */
        }

        .summary-item {
            border: 1px solid var(--border-color);
            background-color: var(--light-gray-bg);
            padding: 1rem 1.25rem;
            /* p-4 md:p-5 */
            border-radius: 0.5rem;
            /* rounded-lg */
            text-align: center;
        }

        .summary-item .label {
            font-size: 0.875rem;
            /* text-sm */
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            /* mb-1 */
            font-weight: 500;
        }

        .summary-item .value {
            font-size: 1.75rem;
            /* text-3xl */
            font-weight: 700;
            /* font-bold */
            color: var(--info-blue);
            /* Use info blue for stats */
            line-height: 1.2;
        }

        .summary-item .value.currency {
            color: var(--maroon-primary);
            /* Maroon for monetary values */
        }

        .summary-item .subtext {
            font-size: 0.75rem;
            /* text-xs */
            color: var(--text-secondary);
            margin-top: 0.25rem;
            /* mt-1 */
        }

        /* --- Reused/Adjusted Styles --- */
        .section-title {
            /* New class for section headings */
            font-size: 1.75rem;
            /* text-2xl */
            font-weight: 700;
            /* font-bold */
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            /* mb-6 */
        }

        .loan-card {
            background-color: var(--white);
            border: 1px solid var(--border-color);
            border-radius: 0.875rem;
            /* 14px */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: 100%;
        }

        .loan-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(140, 28, 28, 0.12);
        }

        .loan-card-header {
            background-color: var(--maroon-ultralight-bg);
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
            /* Centered header content */
        }

        .icon-style {
            margin-bottom: 0.75rem;
            transition: transform 0.3s ease;
            display: inline-block;
            /* Allow centering */
        }

        .loan-card:hover .icon-style svg {
            transform: scale(1.1);
        }

        .icon-style svg {
            color: var(--maroon-primary);
            width: 2.5rem;
            height: 2.5rem;
            /* Slightly smaller icon */
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--maroon-primary);
            margin-bottom: 0.25rem;
            /* text-xl */
        }

        .card-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.6;
            padding: 1rem 1.25rem 0;
            flex-grow: 1;
            /* text-sm */
        }

        .term-section-wrapper {
            padding: 1rem 1.25rem;
        }

        .term-block {
            background-color: var(--light-gray-bg);
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #edf2f7;
            margin-bottom: 0.75rem;
            /* Smaller term blocks */
        }

        .term-block:last-child {
            margin-bottom: 0;
        }

        .term-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--maroon-primary);
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .term-details li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.25rem 0;
            font-size: 0.8rem;
            border-bottom: 1px dashed var(--border-color);
        }

        .term-details li:last-child {
            border-bottom: none;
        }

        .term-details .label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .term-details .value {
            color: var(--text-primary);
            font-weight: 600;
            text-align: right;
        }

        .promotional-badge {
            background-color: var(--success-green);
            color: var(--white);
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .apply-button-container {
            padding: 1rem 1.25rem;
            margin-top: auto;
            background-color: var(--white);
            border-top: 1px solid var(--border-color);
        }

        .apply-button {
            display: block;
            width: 100%;
            background-color: var(--maroon-primary);
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            text-align: center;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            letter-spacing: 0.5px;
        }

        .apply-button:hover {
            background-color: var(--maroon-dark);
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 6px 12px rgba(140, 28, 28, 0.3);
        }

        .apply-button svg {
            margin-left: 0.5rem;
            transition: transform 0.2s ease;
            width: 1rem;
            height: 1rem;
            /* Smaller arrow */
        }

        .apply-button:hover svg {
            transform: translateX(3px);
        }

        /* Footer styling */
        .site-footer {
            background-color: var(--maroon-ultralight-bg);
            color: var(--text-secondary);
            padding: 2rem 1rem;
            text-align: center;
            margin-top: 3rem;
        }

        .site-footer p {
            margin-bottom: 0.5rem;
        }

        .site-footer .disclaimer {
            font-size: 0.8rem;
            color: #718096;
            max-width: 800px;
            margin: 0.5rem auto 0;
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

    <div id="main-content-wrapper">
        <div class="container mx-auto max-w-screen-xl px-4 py-6 md:px-6 md:py-8 lg:px-8 lg:py-10">
            <h3>Dashboard</h3>

            <section class="dashboard-summary-card">
                <h2 class="section-title !text-xl !mb-4">Account Summary</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    <div class="summary-item">
                        <div class="label">Active Loans</div>
                        <div class="value">2</div>
                        <a href="history.php">View Details</a>
                    </div>
                    <div class="summary-item">
                        <div class="label">Total Outstanding</div>
                        <div class="value currency">₱15,234<span class="text-lg">.56</span></div>
                        <div class="subtext">Across all loans</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Next Payment Due</div>
                        <div class="value currency">₱350<span class="text-lg">.78</span></div>
                        <div class="subtext">on June 1, 2025</div>
                    </div>
                    <div class="summary-item">
                        <div class="label">Missed Payment</div>
                        <div class="value currency">₱350<span class="text-lg">.78</span></div>
                        <div class="subtext">on June 1, 2025</div>
                    </div>
            </section>



        </div>
        </section>


    </div>
    </div>
</body>

</html>