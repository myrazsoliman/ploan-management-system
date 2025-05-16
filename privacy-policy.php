<?php
date_default_timezone_set("Etc/GMT+8"); // Or your desired timezone
session_start(); // If you use sessions for anything relevant on this page
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Privacy Policy - PLOAN</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        /* Copied styles from your main page for consistency */
        :root {
            --primary-maroon: #800000;
            --secondary-maroon: #B30000;
            --light-maroon-accent: #D9534F;
            --off-white: #f8f9fa;
            --light-gray-bg: #f1f3f5;
            --dark-gray-text: #343a40;
            --medium-gray-text: #6c757d;
            --light-gray-border: #dee2e6;
            --footer-background: #2c0000;
            --footer-text: #e9ecef;
            --footer-link: var(--light-maroon-accent);
            --navbar-height: 70px;
            /* Ensure this matches your main page's navbar height */
        }

        html {
            scroll-behavior: smooth;
            /* scroll-padding-top is mainly for single-page anchor links.
               If this page is very long and has internal anchors, you might need it.
               Otherwise, it's less critical here. For consistency, you can keep it. */
            scroll-padding-top: var(--navbar-height);
        }

        body {
            background-color: var(--off-white);
            color: var(--dark-gray-text);
            font-family: 'Inter', sans-serif;
            line-height: 1.7;
            font-size: 1rem;
            padding-top: var(--navbar-height);
            /* Offset for the fixed navbar */
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--primary-maroon);
            font-weight: 600;
        }

        h1 {
            font-size: clamp(2rem, 5vw, 2.8rem);
            margin-bottom: 1em;
            margin-top: 1em;
        }

        h2 {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            margin-bottom: 0.75em;
            margin-top: 1.5em;
        }

        h3 {
            font-size: clamp(1.3rem, 3.5vw, 1.8rem);
            margin-bottom: 0.6em;
            margin-top: 1.2em;
        }

        p {
            margin-bottom: 1.25rem;
            color: var(--medium-gray-text);
        }

        .lead {
            font-size: 1.15rem;
            font-weight: 400;
        }

        /* Navbar styles (copied from previous version) */
        .navbar.sticky-top {
            min-height: var(--navbar-height);
            transition: box-shadow 0.3s ease-in-out;
        }

        .navbar {
            background-color: var(--off-white);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: var(--navbar-height);
        }

        .navbar-brand img {
            width: 100%;
            height: 100px;
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
        .navbar-nav .nav-link:focus {
            color: var(--primary-maroon);
            background-color: rgba(128, 0, 0, 0.05);
        }

        .navbar-nav .nav-link.active {
            /* Active state for current page */
            color: white;
            background-color: var(--primary-maroon);
            font-weight: 600;
        }

        .navbar-toggler {
            border-color: rgba(128, 0, 0, 0.2);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(128, 0, 0, 0.7)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }


        /* Footer Styles (copied from previous version) */
        .site-footer {
            background-color: #800000;
            color: var(--footer-text);
            padding: 2.5rem 1rem;
            text-align: center;
            margin-top: 3rem;
            /* Ensure space above footer */
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
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease, text-decoration 0.2s ease;
        }

        .footer-links li a:hover,
        .footer-links li a:focus {
            color: var(--light-maroon-accent);
            text-decoration: underline;
        }

        .footer-social-links {
            margin-bottom: 1.5rem;
            color: white;
        }

        .footer-social-links a {
            color: white;
            font-size: 1.5rem;
            margin: 0 0.6rem;
            transition: color 0.2s ease, transform 0.2s ease;
            display: inline-block;
        }

        .footer-social-links a:hover,
        .footer-social-links a:focus {
            color: var(--light-maroon-accent);
            transform: scale(1.1);
        }

        .footer-text {
            font-size: 0.9rem;
            color: var(--footer-text);
            opacity: 0.8;
        }

        /* Privacy Policy Specific Styles */
        .privacy-policy-container {
            background-color: #fff;
            padding: clamp(2rem, 5vw, 3rem) clamp(1rem, 5vw, 4rem);
            border-radius: 0.5rem;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .privacy-policy-container ul {
            padding-left: 1.5rem;
            list-style-type: disc;
            /* Or 'circle' or 'square' */
        }

        .privacy-policy-container ul li {
            margin-bottom: 0.5rem;
        }

        .privacy-policy-container strong {
            color: var(--dark-gray-text);
        }

        .last-updated {
            font-style: italic;
            color: var(--medium-gray-text);
            margin-bottom: 2rem;
            display: block;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="index.php"> <img src="images/back1.png" alt="PLOAN Company Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="homepage.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="homepage.php#loans">Loans</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#about-us">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#faq">FAQ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login/Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main role="main" class="container">
        <div class="privacy-policy-container">
            <h1>Privacy Policy</h1>
            <p class="last-updated">Last updated: <?php echo date("F j, Y"); ?></p>

            <p>Welcome to PLOAN ("us", "we", or "our"). We operate <a href="homepage.php">ploan

                </a>. Our Privacy Policy governs your visit to <a href="homepage.php">ploan.com
                </a> and explains how we collect,
                safeguard, and disclose information that results from your use of our Service.</p>
            <p>We use your data to provide and improve the Service. By using the Service, you agree to the collection
                and use of information in accordance with this policy. Unless otherwise defined in this Privacy Policy,
                the terms used in this Privacy Policy have the same meanings as in our Terms and Conditions.</p>

            <h2>1. Definitions</h2>
            <ul>
                <li><strong>Service</strong> means the <a href="homepage.php">ploan.com
                    </a> website operated by PLOAN.</li>
                <li><strong>Personal Data</strong> means data about a living individual who can be identified from those
                    data (or from those and other information either in our possession or likely to come into our
                    possession).</li>
                <li><strong>Usage Data</strong> is data collected automatically either generated by the use of the
                    Service or from the Service infrastructure itself (for example, the duration of a page visit).</li>
                <li><strong>Cookies</strong> are small files stored on your device (computer or mobile device).</li>
                <li><strong>Data Controller</strong> means a natural or legal person who (either alone or jointly or in
                    common with other persons) determines the purposes for which and the manner in which any personal
                    data are, or are to be, processed. For the purpose of this Privacy Policy, we are a Data Controller
                    of your data.</li>
                <li><strong>Data Processors (or Service Providers)</strong> means any natural or legal person who
                    processes the data on behalf of the Data Controller. We may use the services of various Service
                    Providers in order to process your data more effectively.</li>
                <li><strong>Data Subject</strong> is any living individual who is the subject of Personal Data.</li>
                <li><strong>The User</strong> is the individual using our Service. The User corresponds to the Data
                    Subject, who is the subject of Personal Data.</li>
            </ul>

            <h2>2. Information Collection and Use</h2>
            <p>We collect several different types of information for various purposes to provide and improve our Service
                to you.</p>

            <h3>Types of Data Collected</h3>
            <h4>Personal Data</h4>
            <p>While using our Service, we may ask you to provide us with certain personally identifiable information
                that can be used to contact or identify you ("Personal Data"). Personally identifiable information may
                include, but is not limited to:</p>
            <ul>
                <li>Email address</li>
                <li>First name and last name</li>
                <li>Phone number</li>
                <li>Address, State, Province, ZIP/Postal code, City</li>
                <li>Cookies and Usage Data</li>
                <li>Financial information (for loan applications, specify what is collected, e.g., income, bank details
                    - be very careful and explicit here)</li>
                <li>Identification documents (e.g., government-issued ID for loan applications)</li>
            </ul>
            <p>We may use your Personal Data to contact you with newsletters, marketing or promotional materials, and
                other information that may be of interest to you. You may opt out of receiving any, or all, of these
                communications from us by following the unsubscribe link or instructions provided in any email we send
                or by contacting us.</p>

            <h4>Usage Data</h4>
            <p>We may also collect information that your browser sends whenever you visit our Service or when you access
                the Service by or through a mobile device ("Usage Data").</p>
            <p>This Usage Data may include information such as your computer's Internet Protocol address (e.g. IP
                address), browser type, browser version, the pages of our Service that you visit, the time and date of
                your visit, the time spent on those pages, unique device identifiers and other diagnostic data.</p>
            <p>When you access the Service with a mobile device, this Usage Data may include information such as the
                type of mobile device you use, your mobile device unique ID, the IP address of your mobile device, your
                mobile operating system, the type of mobile Internet browser you use, unique device identifiers and
                other diagnostic data.</p>

            <h4>Tracking & Cookies Data</h4>
            <p>We use cookies and similar tracking technologies to track the activity on our Service and we hold certain
                information.</p>
            <p>Cookies are files with a small amount of data which may include an anonymous unique identifier. Cookies
                are sent to your browser from a website and stored on your device. Other tracking technologies are also
                used such as beacons, tags and scripts to collect and track information and to improve and analyze our
                Service.</p>
            <p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However,
                if you do not accept cookies, you may not be able to use some portions of our Service.</p>
            <p>Examples of Cookies we use:</p>
            <ul>
                <li><strong>Session Cookies:</strong> We use Session Cookies to operate our Service.</li>
                <li><strong>Preference Cookies:</strong> We use Preference Cookies to remember your preferences and
                    various settings.</li>
                <li><strong>Security Cookies:</strong> We use Security Cookies for security purposes.</li>
                <li><strong>Advertising Cookies:</strong> Advertising Cookies are used to serve you with advertisements
                    that may be relevant to you and your interests (if applicable).</li>
            </ul>

            <h2>3. Use of Data</h2>
            <p>PLOAN uses the collected data for various purposes:</p>
            <ul>
                <li>To provide and maintain our Service;</li>
                <li>To notify you about changes to our Service;</li>
                <li>To allow you to participate in interactive features of our Service when you choose to do so;</li>
                <li>To provide customer support;</li>
                <li>To gather analysis or valuable information so that we can improve our Service;</li>
                <li>To monitor the usage of our Service;</li>
                <li>To detect, prevent and address technical issues;</li>
                <li>To fulfill the purpose for which you provide it (e.g., to process your loan application);</li>
                <li>To carry out our obligations and enforce our rights arising from any contracts entered into between
                    you and us, including for billing and collection (if applicable);</li>
                <li>To provide you with notices about your account and/or subscription, including expiration and renewal
                    notices, email-instructions, etc.;</li>
                <li>To provide you with news, special offers and general information about other goods, services and
                    events which we offer that are similar to those that you have already purchased or enquired about
                    unless you have opted not to receive such information;</li>
                <li>In any other way we may describe when you provide the information;</li>
                <li>For any other purpose with your consent.</li>
            </ul>

            <h2>4. Retention of Data</h2>
            <p>We will retain your Personal Data only for as long as is necessary for the purposes set out in this
                Privacy Policy. We will retain and use your Personal Data to the extent necessary to comply with our
                legal obligations (for example, if we are required to retain your data to comply with applicable laws),
                resolve disputes, and enforce our legal agreements and policies.</p>
            <p>We will also retain Usage Data for internal analysis purposes. Usage Data is generally retained for a
                shorter period, except when this data is used to strengthen the security or to improve the functionality
                of our Service, or we are legally obligated to retain this data for longer time periods.</p>

            <h2>5. Transfer of Data</h2>
            <p>Your information, including Personal Data, may be transferred to – and maintained on – computers located
                outside of your state, province, country or other governmental jurisdiction where the data protection
                laws may differ from those of your jurisdiction.</p>
            <p>If you are located outside [Country where your company is based, e.g., Philippines] and choose to provide
                information to us, please note that we transfer the data, including Personal Data, to [Country where
                your company is based] and process it there.</p>
            <p>Your consent to this Privacy Policy followed by your submission of such information represents your
                agreement to that transfer.</p>
            <p>PLOAN will take all the steps reasonably necessary to ensure that your data is treated securely and in
                accordance with this Privacy Policy and no transfer of your Personal Data will take place to an
                organisation or a country unless there are adequate controls in place including the security of your
                data and other personal information.</p>

            <h2>6. Disclosure of Data</h2>
            <p>We may disclose personal information that we collect, or you provide:</p>
            <ul>
                <li><strong>Disclosure for Law Enforcement.</strong> Under certain circumstances, we may be required to
                    disclose your Personal Data if required to do so by law or in response to valid requests by public
                    authorities.</li>
                <li><strong>Business Transaction.</strong> If we or our subsidiaries are involved in a merger,
                    acquisition or asset sale, your Personal Data may be transferred.</li>
                <li><strong>Other cases. We may disclose your information also:</strong>
                    <ul>
                        <li>to our subsidiaries and affiliates;</li>
                        <li>to contractors, service providers, and other third parties we use to support our business;
                        </li>
                        <li>to fulfill the purpose for which you provide it;</li>
                        <li>for the purpose of including your company’s logo on our website (if applicable);</li>
                        <li>for any other purpose disclosed by us when you provide the information;</li>
                        <li>with your consent in any other cases;</li>
                        <li>if we believe disclosure is necessary or appropriate to protect the rights, property, or
                            safety of the Company, our customers, or others.</li>
                    </ul>
                </li>
            </ul>

            <h2>7. Security of Data</h2>
            <p>The security of your data is important to us but remember that no method of transmission over the
                Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable
                means to protect your Personal Data, we cannot guarantee its absolute security. We implement security
                measures such as [mention specific measures like SSL encryption, access controls, regular security
                audits - be specific if possible, otherwise keep it general].</p>

            <h2>8. Your Data Protection Rights</h2>
            <p>Depending on your jurisdiction, you may have certain data protection rights. These may include:</p>
            <ul>
                <li><strong>The right to access, update or to delete</strong> the information we have on you.</li>
                <li><strong>The right of rectification.</strong> You have the right to have your information rectified
                    if that information is inaccurate or incomplete.</li>
                <li><strong>The right to object.</strong> You have the right to object to our processing of your
                    Personal Data.</li>
                <li><strong>The right of restriction.</strong> You have the right to request that we restrict the
                    processing of your personal information.</li>
                <li><strong>The right to data portability.</strong> You have the right to be provided with a copy of
                    your Personal Data in a structured, machine-readable and commonly used format.</li>
                <li><strong>The right to withdraw consent.</strong> You also have the right to withdraw your consent at
                    any time where we rely on your consent to process your personal information.</li>
            </ul>
            <p>Please note that we may ask you to verify your identity before responding to such requests. Please note,
                we may not able to provide Service without some necessary data.</p>
            <p>You may have the right to complain to a Data Protection Authority about our collection and use of your
                Personal Data. For more information, please contact your local data protection authority.</p>

            <h2>9. Analytics</h2>
            <p>We may use third-party Service Providers to monitor and analyze the use of our Service. (e.g., Google
                Analytics).</p>
            <p><strong>Google Analytics:</strong> Google Analytics is a web analytics service offered by Google that
                tracks and reports website traffic. Google uses the data collected to track and monitor the use of our
                Service. This data is shared with other Google services. Google may use the collected data to
                contextualise and personalise the ads of its own advertising network. For more information on the
                privacy practices of Google, please visit the Google Privacy & Terms web page: <a
                    href="https://policies.google.com/privacy?hl=en" target="_blank"
                    rel="noopener noreferrer">https://policies.google.com/privacy?hl=en</a></p>

            <h2>10. Links to Other Sites</h2>
            <p>Our Service may contain links to other sites that are not operated by us. If you click a third party
                link, you will be directed to that third party's site. We strongly advise you to review the Privacy
                Policy of every site you visit.</p>
            <p>We have no control over and assume no responsibility for the content, privacy policies or practices of
                any third party sites or services.</p>

            <h2>11. Children's Privacy</h2>
            <p>Our Service does not address anyone under the age of 21.</p>
            <p>We do not knowingly collect personally identifiable information from anyone under the age of 21. If you
                are a parent or guardian and you are aware that your Child has provided us with Personal Data, please
                contact us. If we become aware that we have collected Personal Data from children without verification
                of parental consent, we take steps to remove that information from our servers.</p>

            <h2>12. Changes to This Privacy Policy</h2>
            <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new
                Privacy Policy on this page.</p>
            <p>We will let you know via email and/or a prominent notice on our Service, prior to the change becoming
                effective and update the "last updated" date at the top of this Privacy Policy.</p>
            <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy
                Policy are effective when they are posted on this page.</p>

            <h2>13. Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us:</p>
            <li>By email: ploansystem@gmail.com</li>
            <li>By visiting this page on our website: <a href="homepage.php">ploan.com

                </a></li>
            <li>By phone number: +63-9707738218</li>
            </ul>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <ul class="footer-links">
                <li><a href="homepage.php">Home</a></li>
                <li><a href="homepage.php#about-us">About Us</a></li>
                <li><a href="homepage.php#loans">Loans</a></li>
                <li><a href="homepage.php#contact">Contact Us</a></li>
                <li><a href="homepage.php#faq">FAQ</a></li>
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
        // Basic script for navbar toggler, if needed for other interactions on this page.
        // The active link highlighting script from the main page is not directly applicable here
        // for static page highlighting without modification or server-side logic.
        document.addEventListener('DOMContentLoaded', () => {
            // You can add any page-specific JS here if needed.
            // For example, to mark the "Privacy Policy" link in footer as active (if you add one to main nav):
            // const privacyLink = document.querySelector('.navbar-nav .nav-link[href="privacy-policy.php"]');
            // if (privacyLink) {
            //     // Remove active from other links first
            //     document.querySelectorAll('.navbar-nav .nav-link.active').forEach(link => link.classList.remove('active'));
            //     privacyLink.classList.add('active');
            // }
        });
    </script>
</body>

</html>