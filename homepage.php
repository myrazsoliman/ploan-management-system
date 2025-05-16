<?php
date_default_timezone_set("Etc/GMT+8");
session_start();

// --- Loan Data Array (from your loan_plans.php) ---
$loan_plans_data = [
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
        'apply_link' => 'index.php' // Example link
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
        'apply_link' => 'index.php' // Example link
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
        'apply_link' => 'index.php' // Example link
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
        'apply_link' => '../apply.php' // Example link
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Loan Management System</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        /* Enhanced Maroon Theme & UI/UX Improvements */
        :root {
            --primary-maroon: #800000;
            /* Maroon */
            --secondary-maroon: #B30000;
            /* Darker Red */
            --light-maroon-accent: #D9534F;
            /* Lighter, slightly desaturated red for accents/hovers */
            --off-white: #f8f9fa;
            /* Bootstrap's light gray */
            --light-gray-bg: #f1f3f5;
            /* A slightly different light gray for section alternation */
            --dark-gray-text: #343a40;
            /* Bootstrap's dark gray */
            --medium-gray-text: #6c757d;
            /* Bootstrap's secondary text color */
            --light-gray-border: #dee2e6;
            /* Bootstrap's default border color */
            --footer-background: #2c0000;
            /* Very dark maroon */
            --footer-text: #e9ecef;
            --footer-link: var(--light-maroon-accent);

            --navbar-height: 70px;
            /* Define navbar height for reuse - ADJUST IF NEEDED */
        }

        .navbar {
            overflow: hidden;
            background-color: #333;
            position: fixed;
            /* Set the navbar to fixed position */
            top: 0;
            /* Position the navbar at the top of the page */
            width: 100%;
            /* Full width */
            height: var(--navbar-height);
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: var(--navbar-height);
            /* <<< Uses the CSS variable. ADJUST --navbar-height if your navbar is different! */
        }

        body {
            background-color: var(--off-white);
            color: var(--dark-gray-text);
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            /* Improved default line height */
            font-size: 1rem;
            /* Base font size for easier rem calculations */
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--primary-maroon);
            font-weight: 600;
            /* Bolder headings */
        }

        h2 {
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            margin-bottom: 0.75em;
        }

        h3 {
            /* For section sub-titles if needed outside cards */
            font-size: clamp(1.5rem, 3.5vw, 2rem);
            margin-bottom: 0.6em;
        }

        /* h4 is used inside cards, its style will be specific to .loan-plan-title */

        p {
            margin-bottom: 1.25rem;
            /* More space after paragraphs */
            color: var(--medium-gray-text);
        }

        .lead {
            font-size: 1.15rem;
            font-weight: 400;
        }

        .card {
            /* General card styling from homepage */
            border: 1px solid var(--light-gray-border);
            border-radius: 0.5rem;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        }

        /* Navigation */
        .navbar.sticky-top {
            min-height: var(--navbar-height);
            transition: box-shadow 0.3s ease-in-out;
        }

        .navbar-brand img {
            height: calc(var(--navbar-height) - 20px);
            /* Responsive to navbar height */
            /* height: 100px; Remove redundant fixed height */
            margin-right: 1rem;
        }

        .navbar-nav .nav-link {
            color: var(--dark-gray-text);
            transition: color 0.2s ease, background-color 0.2s ease;
            padding: 0.6rem 1.1rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 0.3rem;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus,
        .navbar-nav .nav-link.active {
            /* Ensure active link also gets maroon color */
            color: var(--primary-maroon);
            background-color: rgba(128, 0, 0, 0.05);
        }


        .navbar-toggler {
            border-color: rgba(128, 0, 0, 0.2);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(128, 0, 0, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }


        /* Hero Section */
        .hero-section {
            background-image: linear-gradient(rgba(128, 0, 0, 0.7), rgba(128, 0, 0, 0.85)), url('images/maroon_background_highres.jpg');
            background-size: cover;
            background-position: center center;
            color: white;
            text-align: center;
            padding: clamp(5rem, 12vh, 8rem) 1rem;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            max-width: 700px;
        }

        .hero-text {
            font-size: clamp(2.2rem, 6vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
            color: #fff;
        }

        .hero-subtext {
            font-size: clamp(1rem, 2.5vw, 1.3rem);
            margin-bottom: 2.5rem;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
            line-height: 1.8;
            color: var(--off-white);
            opacity: 0.9;
        }

        .get-started-button {
            background-color: var(--light-maroon-accent);
            color: white;
            padding: 0.9rem clamp(1.5rem, 5vw, 2.5rem);
            border: none;
            border-radius: 0.3rem;
            font-weight: 600;
            font-size: clamp(1rem, 2.5vw, 1.15rem);
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .get-started-button:hover,
        .get-started-button:focus {
            background-color: var(--secondary-maroon);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .get-started-button .fa-magnifying-glass {
            margin-right: 0.6rem;
        }

        /* Section Styling */
        .section-padding {
            padding: clamp(3rem, 8vh, 5rem) 0;
        }

        .section-bg-light {
            background-color: white;
        }

        .section-bg-alt {
            background-color: var(--light-gray-bg);
        }

        .section-title {
            /* This is the main h2 for sections */
            text-align: center;
            margin-bottom: 3rem;
            /* color: var(--primary-maroon); Already applied by global h2 */
        }

        /* Offer Section */
        .offer-item {
            background-color: #fff;
            padding: 2rem 1.5rem;
            border-radius: 0.5rem;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--light-gray-border);
            margin-bottom: 1.5rem;
        }

        .offer-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.1);
        }

        .offer-item i.fa-3x {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            color: var(--primary-maroon);
        }

        .offer-item h4 {
            /* Title inside offer item card */
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-gray-text);
            /* Changed from primary-maroon for less intensity inside card */
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .offer-item p {
            font-size: 0.95rem;
            color: var(--medium-gray-text);
            line-height: 1.6;
        }

        /* === Integrated Loan Plans Section CSS === */
        .loan-plans-section-title {
            /* Specific title for this section, styled like .section-title */
            text-align: center;
            margin-bottom: 1rem;
            /* Reduced bottom margin if a lead paragraph follows */
            color: var(--primary-maroon);
            font-size: clamp(1.8rem, 4vw, 2.4rem);
            font-weight: 600;
        }

        .loan-plans-section-lead {
            text-align: center;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 3rem;
            /* Space before cards */
        }

        .loan-plan-card {
            background-color: var(--white);
            border: 1px solid var(--light-gray-border);
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            /* Softer shadow to match offer-item */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: 100%;
            /* For d-flex align-items-stretch to work well */
        }

        .loan-plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(128, 0, 0, 0.1);
        }

        .loan-plan-header {
            background-color: rgba(128, 0, 0, 0.03);
            /* Very light maroon tint */
            padding: 1.25rem;
            /* Slightly reduced padding */
            border-bottom: 1px solid var(--light-gray-border);
            text-align: center;
        }

        .loan-plan-icon {
            margin-bottom: 0.75rem;
            transition: transform 0.3s ease;
            line-height: 1;
            /* Prevent extra space from SVG */
        }

        .loan-plan-card:hover .loan-plan-icon svg {
            transform: scale(1.1);
        }

        .loan-plan-icon svg {
            color: var(--primary-maroon);
            width: 2.8rem;
            height: 2.8rem;
        }

        .loan-plan-card-title {
            /* h4 inside card header */
            font-size: 1.35rem;
            /* Adjusted size */
            font-weight: 600;
            color: var(--primary-maroon);
            margin-bottom: 0;
            /* Removed bottom margin */
        }

        .loan-plan-body {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .loan-plan-description {
            color: var(--medium-gray-text);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1rem;
            /* flex-grow: 1; Let terms wrapper push it up or down */
        }

        .loan-plan-terms-wrapper {
            margin-top: auto;
        }

        .loan-plan-term-block {
            background-color: var(--light-gray-bg);
            padding: 0.75rem 1rem;
            /* Adjusted padding */
            border-radius: 0.3rem;
            border: 1px solid #e9ecef;
            /* Slightly lighter border for internal block */
            margin-bottom: 0.75rem;
        }

        .loan-plan-term-block:last-child {
            margin-bottom: 0;
        }

        .loan-plan-term-title {
            /* h5 for term duration */
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark-gray-text);
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .loan-plan-term-title .badge {
            font-size: 0.65rem;
            /* Smaller badge */
            font-weight: 600;
            padding: 0.2rem 0.4rem;
            vertical-align: middle;
        }


        .loan-plan-term-details {
            font-size: 0.85rem;
            list-style: none;
            /* Ensure no bullets */
            padding-left: 0;
            /* Remove default padding */
        }

        .loan-plan-term-details li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.3rem 0;
            border-bottom: 1px dashed var(--light-gray-border);
        }

        .loan-plan-term-details li:last-child {
            border-bottom: none;
        }

        .loan-plan-term-details .label {
            color: var(--medium-gray-text);
            font-weight: 500;
            margin-right: 0.5rem;
            /* Add some space */
        }

        .loan-plan-term-details .value {
            color: var(--dark-gray-text);
            font-weight: 600;
            text-align: right;
        }

        .loan-plan-footer {
            padding: 1.25rem;
            margin-top: auto;
            background-color: var(--white);
            /* Ensure clean background */
            border-top: 1px solid var(--light-gray-border);
        }

        .loan-plan-apply-button.btn {
            /* Target Bootstrap button specifically */
            background-color: var(--primary-maroon);
            color: white;
            font-weight: 500;
            /* Adjusted weight */
            font-size: 0.95rem;
            /* Adjusted size */
            padding: 0.6rem 1.25rem;
            border-radius: 0.3rem;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid var(--primary-maroon);
            /* Explicit border */
            width: 100%;
        }

        .loan-plan-apply-button.btn:hover,
        .loan-plan-apply-button.btn:focus {
            background-color: var(--secondary-maroon);
            border-color: var(--secondary-maroon);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        .loan-plan-apply-button.btn .fas {
            transition: transform 0.2s ease;
            font-size: 0.85em;
            /* Adjust icon size within button */
        }

        .loan-plan-apply-button.btn:hover .fas {
            transform: translateX(3px);
        }


        /* Original Promotion Section (commented out as it's being replaced by Loan Plans) */
        /*
        .promotion-card { ... }
        .promotion-card:hover { ... }
        .promotion-card h4 { ... }
        .promotion-card p { ... }
        .promotion-details { ... }
        .promotion-details li { ... }
        .promotion-card .learn-more-link { ... }
        .promotion-card .learn-more-link:hover,
        .promotion-card .learn-more-link:focus { ... }
        .promotion-card .learn-more-link .fas { ... }
        */

        /* Contact Form */
        .contact-form .form-control {
            padding: 0.9rem 1rem;
            font-size: 1rem;
            border-radius: 0.3rem;
            border: 1px solid var(--light-gray-border);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .contact-form .form-control:focus {
            border-color: var(--primary-maroon);
            box-shadow: 0 0 0 0.25rem rgba(128, 0, 0, 0.2);
        }

        .contact-form textarea.form-control {
            min-height: 150px;
        }

        .contact-form .btn-primary {
            width: 100%;
            padding: 0.9rem 1.5rem;
            font-size: 1.05rem;
            font-weight: 600;
            border-radius: 0.3rem;
            background-color: var(--primary-maroon);
            border-color: var(--primary-maroon);
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .contact-form .btn-primary:hover,
        .contact-form .btn-primary:focus {
            background-color: var(--secondary-maroon);
            border-color: var(--secondary-maroon);
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.15);
        }

        /* FAQ Section */
        .faq-item {
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--light-gray-border);
            padding-bottom: 1rem;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            font-size: 1.15rem;
            font-weight: 600;
            color: var(--dark-gray-text);
            cursor: pointer;
            transition: color 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 0.75rem 0;
        }

        .faq-question:hover,
        .faq-question:focus {
            color: var(--primary-maroon);
        }

        .faq-question i.fas {
            transition: transform 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
            color: var(--medium-gray-text);
        }

        .faq-question[aria-expanded="true"] i.fas {
            transform: rotate(180deg);
            color: var(--primary-maroon);
        }

        .faq-answer {
            font-size: 1rem;
            color: var(--medium-gray-text);
            line-height: 1.7;
            padding: 0.5rem 0.25rem 0.25rem;
        }

        .faq-answer[hidden] {
            display: none;
        }


        /* Footer Styles */
        .site-footer {
            background-color: #800000;
            color: var(--footer-text);
            padding: 2.5rem 1rem;
            text-align: center;
            margin-top: 3rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.75rem 1.25rem;
        }

        .footer-links li a {
            color: white;
            /* For direct footer links, not var(--footer-text) to ensure contrast */
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease, text-decoration 0.2s ease;
        }

        .footer-links li a:hover,
        .footer-links li a:focus {
            color: var(--footer-link);
            text-decoration: underline;
        }

        .footer-social-links {
            margin-bottom: 1.5rem;
        }

        .footer-social-links a {
            color: white;
            /* For direct social links, not var(--footer-text) */
            font-size: 1.5rem;
            margin: 0 0.6rem;
            transition: color 0.2s ease, transform 0.2s ease;
            display: inline-block;
        }

        .footer-social-links a:hover,
        .footer-social-links a:focus {
            color: var(--footer-link);
            transform: scale(1.1);
        }

        .footer-text {
            font-size: 0.9rem;
            color: var(--footer-text);
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="images/back1.png" alt="PLOAN Company Logo" style="height: 100px;"> </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#loans">Loans</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about-us">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#faq">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Login/Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-text">Unlock Your Financial Goals</h1>
                <p class="hero-subtext">
                    Discover a range of personalized loan solutions designed to help you achieve
                    your dreams. From personal loans to home financing, we're here to guide you.
                </p>
                <button class="get-started-button" onclick="document.getElementById('loans').scrollIntoView();">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Explore Loan Options
                </button>
            </div>
        </section>

        <section class="offer-section section-padding section-bg-light">
            <div class="container">
                <h2 class="section-title">Why Choose Our Loan Services?</h2>
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="offer-item text-center h-100">
                            <i class="fas fa-check-circle fa-3x"></i>
                            <h4>Fast Approval Process</h4>
                            <p>Get quick decisions and access funds when you need them most.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="offer-item text-center h-100">
                            <i class="fas fa-percentage fa-3x"></i>
                            <h4>Competitive Interest Rates</h4>
                            <p>Enjoy favorable rates that help you save money over time.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="offer-item text-center h-100">
                            <i class="fas fa-calendar-alt fa-3x"></i>
                            <h4>Flexible Repayment Terms</h4>
                            <p>Choose a repayment schedule that fits your budget and lifestyle.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-padding section-bg-alt" id="loans">
            <div class="container">
                <div class="text-center">
                    <h2 class="loan-plans-section-title">Our Tailored Loan Plans</h2>
                    <p class="lead loan-plans-section-lead">
                        Find the perfect financial solution, meticulously designed to support your aspirations and
                        growth.
                    </p>
                </div>

                <div class="row">
                    <?php foreach ($loan_plans_data as $plan): ?>
                        <div class="col-lg-6 col-xl-3 mb-4 d-flex align-items-stretch">
                            <div class="loan-plan-card w-100">
                                <div class="loan-plan-header">
                                    <div class="loan-plan-icon">
                                        <?php echo $plan['icon']; ?>
                                    </div>
                                    <h4 class="loan-plan-card-title"><?php echo htmlspecialchars($plan['title']); ?></h4>
                                </div>
                                <div class="loan-plan-body">
                                    <p class="loan-plan-description">
                                        <?php echo htmlspecialchars($plan['description']); ?>
                                    </p>
                                    <div class="loan-plan-terms-wrapper">
                                        <?php foreach ($plan['terms'] as $term_key => $term_details): ?>
                                            <?php $term_duration = str_replace('_', ' ', $term_key); ?>
                                            <div class="loan-plan-term-block">
                                                <h5 class="loan-plan-term-title">
                                                    <?php echo ucwords($term_duration); ?> Term
                                                    <?php if (isset($term_details['promotional']) && $term_details['promotional']): ?>
                                                        <span class="badge bg-success">Promo</span>
                                                    <?php endif; ?>
                                                </h5>
                                                <ul class="loan-plan-term-details">
                                                    <li>
                                                        <span class="label">Interest:</span>
                                                        <span
                                                            class="value"><?php echo htmlspecialchars($term_details['interest_rate']); ?></span>
                                                    </li>
                                                    <li>
                                                        <span class="label">Penalty:</span>
                                                        <span
                                                            class="value"><?php echo htmlspecialchars($term_details['penalty']); ?></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="loan-plan-footer">
                                    <button type="button" class="btn loan-plan-apply-button"
                                        onclick="window.location.href='<?php echo htmlspecialchars($plan['apply_link']); ?>'">
                                        Apply Now
                                        <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="about-us-section section-padding section-bg-light" id="about-us">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <h2 class="section-title">About Us</h2>
                        <p>
                            Welcome to PLOAN, your trusted financial partner. We are dedicated to
                            providing innovative and accessible loan solutions to individuals and businesses. With years
                            of industry
                            experience, we understand the diverse financial needs of our clients.
                        </p>
                        <p>
                            Our mission is to empower you to achieve your goals by offering personalized loan products
                            with
                            competitive rates and flexible terms. We believe in building long-term relationships with
                            our
                            clients based on trust, transparency, and mutual success.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-us-section section-padding section-bg-alt" id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <h2 class="section-title">Contact Us</h2>
                        <p class="contact-intro lead"> <strong>Have questions or need assistance? We’re here to
                                help.</strong>
                            Our dedicated team is ready to provide you with the support you need. Whether you have
                            inquiries about our loan products, application process, or anything else, feel free to reach
                            out to us.
                        </p>
                        <div class="contact-details mb-4">
                            <p><strong>Address:</strong> 123 Loan Street, Finance City, FC 12345</p>
                            <p><strong>Phone:</strong> +63-9707738218</p>
                            <p><strong>Email:</strong> ploansystem@gmail.com</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                        <form class="contact-form" action="submit_contact_form.php" method="POST">
                            <div class="mb-3">
                                <label for="contactName" class="form-label visually-hidden">Your Name</label>
                                <input type="text" id="contactName" name="contactName" class="form-control"
                                    placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactEmail" class="form-label visually-hidden">Your Email</label>
                                <input type="email" id="contactEmail" name="contactEmail" class="form-control"
                                    placeholder="Your Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactPhone" class="form-label visually-hidden">Your Phone Number</label>
                                <input type="tel" id="contactPhone" name="contactPhone" class="form-control"
                                    placeholder="Your Phone Number">
                            </div>
                            <div class="mb-3">
                                <label for="contactMessage" class="form-label visually-hidden">Your Message</label>
                                <textarea id="contactMessage" name="contactMessage" class="form-control"
                                    placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section section-padding section-bg-light" id="faq">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h2 class="section-title text-center">Frequently Asked Questions</h2>
                        <div class="faq-container">
                            <div class="faq-item">
                                <h3>
                                    <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-1">
                                        What types of loans do you offer?
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </h3>
                                <div id="faq-answer-1" class="faq-answer" role="region" hidden>
                                    We offer a variety of loan products, including personal loans, home loans, business
                                    loans, and auto loans. Each loan type is designed to meet specific financial needs.
                                    You can find more details in our <a href="#loans">Loans section</a>.
                                </div>
                            </div>
                            <div class="faq-item">
                                <h3>
                                    <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-2">
                                        What are the requirements for getting a loan?
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </h3>
                                <div id="faq-answer-2" class="faq-answer" role="region" hidden>
                                    Loan requirements vary depending on the type of loan and your financial situation.
                                    Generally, you'll need to provide proof of income, identification, and other
                                    relevant
                                    documents. Specific requirements can be discussed upon application.
                                </div>
                            </div>
                            <div class="faq-item">
                                <h3>
                                    <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-3">
                                        How long does the loan approval process take?
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </h3>
                                <div id="faq-answer-3" class="faq-answer" role="region" hidden>
                                    We strive to provide a fast approval process. For some loans, you may get a decision
                                    within 24-48 hours. However, more complex loans like home loans may take longer.
                                </div>
                            </div>
                            <div class="faq-item">
                                <h3>
                                    <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-4">
                                        What are your interest rates?
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </h3>
                                <div id="faq-answer-4" class="faq-answer" role="region" hidden>
                                    Our interest rates are competitive and vary based on the type of loan, loan amount,
                                    your creditworthiness, and current market conditions. Please check our specific loan
                                    product details in the <a href="#loans">Loans section</a> or contact us for detailed
                                    rates.
                                </div>
                            </div>
                            <div class="faq-item">
                                <h3>
                                    <button class="faq-question" aria-expanded="false" aria-controls="faq-answer-5">
                                        How do I apply for a loan?
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </h3>
                                <div id="faq-answer-5" class="faq-answer" role="region" hidden>
                                    You can apply for a loan by clicking the "Apply Now" button on any of our loan plans
                                    listed in the <a href="../user/apply_loan.php">Loans section</a>.
                                    Alternatively, you can visit one of our branches or contact us for assistance.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container">
            <ul class="footer-links">
                <li><a href="#">Home</a></li>
                <li><a href="#about-us">About Us</a></li>
                <li><a href="#loans">Loans</a></li>
                <li><a href="#contact">Contact Us</a></li>
                <li><a href="#faq">FAQ</a></li>
                <li><a href="privacy-policy.php">Privacy Policy</a></li>
            </ul>
            <div class="footer-social-links">
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer"
                    aria-label="Our Facebook page">
                    <i class="fab fa-facebook-f"></i> </a>
                <a href="https://twitter.com/yourprofile" target="_blank" rel="noopener noreferrer"
                    aria-label="Our Twitter profile">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://linkedin.com/company/yourprofile" target="_blank" rel="noopener noreferrer"
                    aria-label="Our LinkedIn page">
                    <i class="fab fa-linkedin-in"></i> </a>
                <a href="https://instagram.com/yourprofile" target="_blank" rel="noopener noreferrer"
                    aria-label="Our Instagram profile">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
            <p class="footer-text">&copy; <?php echo date("Y"); ?> PLOAN. All rights reserved. Your Trusted Financial
                Partner.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
        </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // FAQ Accordion
            const faqQuestions = document.querySelectorAll('.faq-question');
            faqQuestions.forEach(button => {
                button.addEventListener('click', () => {
                    const answerId = button.getAttribute('aria-controls');
                    const answer = document.getElementById(answerId);
                    const isExpanded = button.getAttribute('aria-expanded') === 'true';

                    // Optional: Close other open FAQs
                    /*
                    if (!isExpanded) { // If we are about to open this one
                        faqQuestions.forEach(otherButton => {
                            if (otherButton !== button && otherButton.getAttribute('aria-expanded') === 'true') {
                                otherButton.setAttribute('aria-expanded', 'false');
                                document.getElementById(otherButton.getAttribute('aria-controls')).hidden = true;
                            }
                        });
                    }
                    */

                    button.setAttribute('aria-expanded', !isExpanded);
                    answer.hidden = isExpanded; // Toggle: if was true (expanded), now hidden. if was false (collapsed), now not hidden.
                });
            });

            // Active Nav Link Updater
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            const sections = document.querySelectorAll('main > section[id]');
            const navbarHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--navbar-height'), 10) || 70;


            function changeNav() {
                let currentSectionId = '';
                let heroSection = document.querySelector('.hero-section'); // Get hero section for top-of-page logic
                let heroBottom = heroSection ? heroSection.offsetTop + heroSection.offsetHeight - navbarHeight - 50 : 0;


                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    // Check if section is in viewport considering navbar height and a small buffer
                    if (window.scrollY >= sectionTop - navbarHeight - 50) {
                        currentSectionId = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    const linkHref = link.getAttribute('href');

                    if (linkHref === '#' && (currentSectionId === '' || window.scrollY < heroBottom)) {
                        // Special handling for home link: active if no section is specifically active OR if scrolled near top (within hero)
                        link.classList.add('active');
                    } else if (linkHref === `#${currentSectionId}`) {
                        link.classList.add('active');
                    }
                });

                // If after checking all sections, no specific section link is active,
                // and we are at the very top, ensure 'Home' link (#) is active.
                const isActiveLinkFound = Array.from(navLinks).some(link => link.classList.contains('active'));
                if (!isActiveLinkFound && window.scrollY < (sections[0] ? sections[0].offsetTop - navbarHeight - 50 : 500)) {
                    const homeLink = document.querySelector('.navbar-nav .nav-link[href="#"]');
                    if (homeLink) {
                        // Deactivate others first before activating home
                        navLinks.forEach(l => l.classList.remove('active'));
                        homeLink.classList.add('active');
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const contactForm = document.getElementById('contactForm');
                const alertContainer = document.getElementById('alert-container');
                const submitButton = document.getElementById('submitButton');

                if (contactForm) {
                    contactForm.addEventListener('submit', function (event) {
                        event.preventDefault(); // Prevent default form submission
                        clearAlerts(); // Clear previous alerts

                        const formData = new FormData(contactForm);
                        const originalButtonText = submitButton.innerHTML;

                        submitButton.disabled = true;
                        submitButton.innerHTML = 'Sending... <span class="spinner"></span>';

                        fetch('contact.php', { // Path to your PHP script
                            method: 'POST',
                            body: formData
                        })
                            .then(response => {
                                return response.text().then(text => ({
                                    status: response.status,
                                    text: text,
                                    ok: response.ok
                                }));
                            })
                            .then(data => {
                                if (data.status === 200) {
                                    showAlert(data.text, 'success');
                                    contactForm.reset();
                                } else if (data.status === 400 || data.status === 403) {
                                    showAlert(data.text, 'warning');
                                } else if (data.status === 500) {
                                    console.error("Server Error Details (from PHP):", data.text);
                                    showAlert("Oops! We couldn't send your message due to a technical issue. Please try again later.", 'error');
                                } else {
                                    showAlert(`Error: ${data.text || 'An unexpected error occurred.'}`, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Fetch Submission Error:', error);
                                showAlert('Network error or server unavailable. Please check your connection and try again.', 'error');
                            })
                            .finally(() => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalButtonText;
                            });
                    });
                }

                function showAlert(message, type = 'info', duration = 7000) {
                    clearAlerts();

                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert alert-${type}`;
                    alertDiv.setAttribute('role', 'alert');

                    const messageSpan = document.createElement('span');
                    // Use innerHTML if you want to allow basic HTML in messages (e.g., links)
                    // Be cautious with this if messages can be user-generated to prevent XSS.
                    // For messages from your PHP script, it should be safe if you control them.
                    messageSpan.textContent = message; // Safer: textContent

                    const closeButton = document.createElement('button');
                    closeButton.className = 'alert-close-btn';
                    closeButton.innerHTML = '&times;';
                    closeButton.setAttribute('aria-label', 'Close');
                    closeButton.onclick = () => {
                        alertDiv.classList.add('fade-out');
                        alertDiv.addEventListener('animationend', () => alertDiv.remove());
                    };

                    alertDiv.appendChild(messageSpan);
                    alertDiv.appendChild(closeButton);
                    alertContainer.appendChild(alertDiv);

                    if (type === 'success' || type === 'info') {
                        const timer = setTimeout(() => {
                            if (alertDiv.parentElement) {
                                alertDiv.classList.add('fade-out');
                                alertDiv.addEventListener('animationend', () => alertDiv.remove());
                            }
                        }, duration);
                        // Optional: clear timer if closed manually
                        closeButton.addEventListener('click', () => clearTimeout(timer));
                    }
                }

                function clearAlerts() {
                    while (alertContainer.firstChild) {
                        alertContainer.removeChild(alertContainer.firstChild);
                    }
                }
            });


            window.addEventListener('scroll', changeNav);
            changeNav(); // Initial call to set active link on page load
        });
    </script>
</body>

</html>