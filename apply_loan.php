<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application Form - Advanced Features</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f8fc;
            color: var(--text-primary);
        }

        :root {
            --maroon-primary: #8C1C1C;
            --maroon-dark: #6F1616;
            --maroon-light-accent: #f5eaea;
            --maroon-ultralight-bg: #fdf7f7;
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --border-color: #e2e8f0;
            --input-border-focus: var(--maroon-primary);
            --white: #ffffff;
            --success-green: #28a745;
            --danger-red: #dc3545;
            --info-blue: #007bff;
        }

        .form-container {
            background-color: var(--white);
            border-radius: 0.875rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .form-header {
            background-color: var(--maroon-ultralight-bg);
            border-bottom: 1px solid var(--border-color);
        }

        .form-header h1 {
            color: var(--maroon-primary);
            font-weight: 700;
        }

        .form-header p {
            color: var(--text-secondary);
        }

        .form-section-title {
            color: var(--maroon-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            font-size: 0.95rem;
            color: var(--text-primary);
            background-color: var(--white);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--input-border-focus);
            box-shadow: 0 0 0 2px rgba(140, 28, 28, 0.2);
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #a0aec0;
        }

        .form-select:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .required-asterisk {
            color: var(--danger-red);
            margin-left: 0.125rem;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-button {
            border: 1px solid var(--maroon-primary);
            color: var(--maroon-primary);
            background-color: var(--white);
            padding: 0.625rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
            font-weight: 500;
        }

        .file-input-button:hover {
            background-color: var(--maroon-light-accent);
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .file-input-text {
            margin-left: 0.75rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            max-width: calc(100% - 130px);
            /* Adjust based on button width */
            vertical-align: middle;
        }

        #idFileName,
        #addressProofFileName {
            padding-top: 0.25rem;
        }

        .submit-button {
            background-color: var(--maroon-primary);
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.875rem 1.5rem;
            border-radius: 0.5rem;
            text-align: center;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            letter-spacing: 0.5px;
            width: 100%;
        }

        .submit-button:hover {
            background-color: var(--maroon-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(140, 28, 28, 0.3);
        }

        .submit-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .checkbox-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .checkbox-label a {
            color: var(--maroon-primary);
            text-decoration: underline;
        }

        .checkbox-label a:hover {
            color: var(--maroon-dark);
        }

        .loan-calculation-details {
            background-color: var(--maroon-ultralight-bg);
            border: 1px solid var(--maroon-light-accent);
            border-radius: 0.5rem;
            padding: 1.25rem;
            /* Increased padding */
            margin-top: 1.5rem;
            /* Increased margin */
        }

        .loan-calculation-details p {
            margin-bottom: 0.75rem;
            /* Increased spacing */
            font-size: 0.95rem;
            display: flex;
            /* For better alignment */
            justify-content: space-between;
            /* For label and value alignment */
        }

        .loan-calculation-details p:last-of-type {
            /* Remove margin from last p if it's not the note */
            margin-bottom: 0.75rem;
        }

        .loan-calculation-details .note {
            margin-bottom: 0;
            /* Ensure note doesn't have extra bottom margin if it's the very last child */
        }


        .loan-calculation-details .label {
            font-weight: 500;
            color: var(--text-secondary);
            margin-right: 0.5rem;
            /* Space between label and value */
        }

        .loan-calculation-details .value {
            font-weight: 600;
            color: var(--maroon-primary);
            text-align: right;
            /* Align value to the right */
        }

        .loan-calculation-details .note {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 1rem;
            /* Increased top margin for the note */
            display: block;
            /* Ensure it takes full width */
            text-align: left;
            /* Reset text-align if needed */
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
    <div class="container mx-auto max-w-3xl">
        <div class="form-container">
            <header class="form-header p-6 md:p-8 text-center">
                <h1 class="text-2xl md:text-3xl mb-2">Loan Application</h1>
                <p class="text-sm md:text-base">Please fill out the form below to apply for your loan.</p>
            </header>

            <form id="loanApplicationForm" class="p-6 md:p-8 space-y-8" action="#" method="POST"
                enctype="multipart/form-data">

                <fieldset class="space-y-6">
                    <legend class="text-xl form-section-title">Personal Information</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label for="firstName" class="form-label">First Name<span
                                    class="required-asterisk">*</span></label><input type="text" id="firstName"
                                name="firstName" class="form-input" placeholder="e.g., Juan" required></div>
                        <div><label for="lastName" class="form-label">Last Name<span
                                    class="required-asterisk">*</span></label><input type="text" id="lastName"
                                name="lastName" class="form-input" placeholder="e.g., Dela Cruz" required></div>
                    </div>
                    <div><label for="email" class="form-label">Email Address<span
                                class="required-asterisk">*</span></label><input type="email" id="email" name="email"
                            class="form-input" placeholder="e.g., juan.delacruz@example.com" required></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label for="phone" class="form-label">Phone Number<span
                                    class="required-asterisk">*</span></label><input type="tel" id="phone" name="phone"
                                class="form-input" placeholder="e.g., 09171234567" required></div>
                        <div><label for="dob" class="form-label">Date of Birth<span
                                    class="required-asterisk">*</span></label><input type="date" id="dob" name="dob"
                                class="form-input" required></div>
                    </div>
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl form-section-title">Residential Address</legend>
                    <div>
                        <label for="province" class="form-label">Province<span
                                class="required-asterisk">*</span></label>
                        <select id="province" name="province" class="form-select" required>
                            <option value="" disabled selected>Select Province</option>
                            <option value="Abra">Abra</option>
                            <option value="Agusan del Norte">Agusan del Norte</option>
                            <option value="Agusan del Sur">Agusan del Sur</option>
                            <option value="Aklan">Aklan</option>
                            <option value="Albay">Albay</option>
                            <option value="Antique">Antique</option>
                            <option value="Apayao">Apayao</option>
                            <option value="Aurora">Aurora</option>
                            <option value="Basilan">Basilan</option>
                            <option value="Bataan">Bataan</option>
                            <option value="Batanes">Batanes</option>
                            <option value="Batangas">Batangas</option>
                            <option value="Benguet">Benguet</option>
                            <option value="Biliran">Biliran</option>
                            <option value="Bohol">Bohol</option>
                            <option value="Bukidnon">Bukidnon</option>
                            <option value="Bulacan">Bulacan</option>
                            <option value="Cagayan">Cagayan</option>
                            <option value="Camarines Norte">Camarines Norte</option>
                            <option value="Camarines Sur">Camarines Sur</option>
                            <option value="Camiguin">Camiguin</option>
                            <option value="Capiz">Capiz</option>
                            <option value="Catanduanes">Catanduanes</option>
                            <option value="Cavite">Cavite</option>
                            <option value="Cebu">Cebu</option>
                            <option value="Cotabato">Cotabato</option>
                            <option value="Davao de Oro">Davao de Oro</option>
                            <option value="Davao del Norte">Davao del Norte</option>
                            <option value="Davao del Sur">Davao del Sur</option>
                            <option value="Davao Occidental">Davao Occidental</option>
                            <option value="Davao Oriental">Davao Oriental</option>
                            <option value="Dinagat Islands">Dinagat Islands</option>
                            <option value="Eastern Samar">Eastern Samar</option>
                            <option value="Guimaras">Guimaras</option>
                            <option value="Ifugao">Ifugao</option>
                            <option value="Ilocos Norte">Ilocos Norte</option>
                            <option value="Ilocos Sur">Ilocos Sur</option>
                            <option value="Iloilo">Iloilo</option>
                            <option value="Isabela">Isabela</option>
                            <option value="Kalinga">Kalinga</option>
                            <option value="La Union">La Union</option>
                            <option value="Laguna">Laguna</option>
                            <option value="Lanao del Norte">Lanao del Norte</option>
                            <option value="Lanao del Sur">Lanao del Sur</option>
                            <option value="Leyte">Leyte</option>
                            <option value="Maguindanao del Norte">Maguindanao del Norte</option>
                            <option value="Maguindanao del Sur">Maguindanao del Sur</option>
                            <option value="Marinduque">Marinduque</option>
                            <option value="Masbate">Masbate</option>
                            <option value="Metro Manila">Metro Manila</option>
                            <option value="Misamis Occidental">Misamis Occidental</option>
                            <option value="Misamis Oriental">Misamis Oriental</option>
                            <option value="Mountain Province">Mountain Province</option>
                            <option value="Negros Occidental">Negros Occidental</option>
                            <option value="Negros Oriental">Negros Oriental</option>
                            <option value="Northern Samar">Northern Samar</option>
                            <option value="Nueva Ecija">Nueva Ecija</option>
                            <option value="Nueva Vizcaya">Nueva Vizcaya</option>
                            <option value="Occidental Mindoro">Occidental Mindoro</option>
                            <option value="Oriental Mindoro">Oriental Mindoro</option>
                            <option value="Palawan">Palawan</option>
                            <option value="Pampanga">Pampanga</option>
                            <option value="Pangasinan">Pangasinan</option>
                            <option value="Quezon">Quezon</option>
                            <option value="Quirino">Quirino</option>
                            <option value="Rizal">Rizal</option>
                            <option value="Romblon">Romblon</option>
                            <option value="Samar">Samar</option>
                            <option value="Sarangani">Sarangani</option>
                            <option value="Siquijor">Siquijor</option>
                            <option value="Sorsogon">Sorsogon</option>
                            <option value="South Cotabato">South Cotabato</option>
                            <option value="Southern Leyte">Southern Leyte</option>
                            <option value="Sultan Kudarat">Sultan Kudarat</option>
                            <option value="Sulu">Sulu</option>
                            <option value="Surigao del Norte">Surigao del Norte</option>
                            <option value="Surigao del Sur">Surigao del Sur</option>
                            <option value="Tarlac">Tarlac</option>
                            <option value="Tawi-Tawi">Tawi-Tawi</option>
                            <option value="Zambales">Zambales</option>
                            <option value="Zamboanga del Norte">Zamboanga del Norte</option>
                            <option value="Zamboanga del Sur">Zamboanga del Sur</option>
                            <option value="Zamboanga Sibugay">Zamboanga Sibugay</option>
                        </select>
                    </div>
                    <div>
                        <label for="cityMunicipality" class="form-label">City / Municipality<span
                                class="required-asterisk">*</span></label>
                        <select id="cityMunicipality" name="cityMunicipality" class="form-select" required disabled>
                            <option value="" disabled selected>Select City / Municipality</option>
                        </select>
                    </div>
                    <div>
                        <label for="barangay" class="form-label">Barangay<span
                                class="required-asterisk">*</span></label>
                        <select id="barangay" name="barangay" class="form-select" required disabled>
                            <option value="" disabled selected>Select Barangay</option>
                        </select>
                    </div>
                    <div>
                        <label for="streetAddress" class="form-label">Street Name, Building, House No.<span
                                class="required-asterisk">*</span></label>
                        <textarea id="streetAddress" name="streetAddress" rows="2" class="form-textarea"
                            placeholder="e.g., Main Street, Unit 123" required></textarea>
                    </div>
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl form-section-title">Loan Details</legend>
                    <div>
                        <label for="loanType" class="form-label">Loan Type<span
                                class="required-asterisk">*</span></label>
                        <select id="loanType" name="loanType" class="form-select" required>
                            <option value="" disabled selected>Select Loan Type</option>
                            <option value="Personal Loan">Personal Loan</option>
                            <option value="Home Loan">Home Loan</option>
                            <option value="Education Loan">Education Loan</option>
                            <option value="Auto Loan">Auto Loan</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="loanTerm" class="form-label">Loan Term<span
                                    class="required-asterisk">*</span></label>
                            <select id="loanTerm" name="loanTerm" class="form-select" required>
                                <option value="" disabled selected>Select Term</option>
                                <option value="6 Months">6 Months</option>
                                <option value="12 Months">12 Months</option>

                            </select>
                        </div>
                        <div>
                            <label for="loanAmount" class="form-label">Desired Loan Amount (PHP)<span
                                    class="required-asterisk">*</span></label>
                            <input type="number" id="loanAmount" name="loanAmount" class="form-input"
                                placeholder="e.g., 50000" min="1000" step="1000" required>
                        </div>
                    </div>
                    <div>
                        <label for="loanPurpose" class="form-label">Purpose of Loan (Optional)</label>
                        <textarea id="loanPurpose" name="loanPurpose" rows="2" class="form-textarea"
                            placeholder="Briefly describe the purpose of your loan"></textarea>
                    </div>
                    <div id="loanCalculationDisplay" class="loan-calculation-details" style="display: none;">
                        <h4 class="text-md font-semibold text-maroon-primary mb-4">Loan Estimate:</h4>
                        <p><span class="label">Monthly Interest Rate:</span> <span class="value"
                                id="monthlyInterestRateDisplay">0.00%</span></p>
                        <p><span class="label">Estimated Monthly Payment:</span> <span class="value"
                                id="estimatedMonthlyPayment">PHP 0.00</span></p>
                        <p><span class="label">Estimated Total Interest:</span> <span class="value"
                                id="estimatedTotalInterest">PHP 0.00</span></p>
                        <p><span class="label">Total Amount Payable:</span> <span class="value"
                                id="estimatedTotalPayable">PHP 0.00</span></p>
                        <p class="note">This is an estimate based on the selected terms and standard interest rates.
                            Actual amounts may vary upon loan approval.</p>
                    </div>
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl form-section-title">Document Upload</legend>
                    <div>
                        <label for="idUpload" class="form-label">Upload Valid ID<span
                                class="required-asterisk">*</span></label>
                        <div class="file-input-wrapper"><button type="button" class="file-input-button">Choose
                                File</button><span class="file-input-text" id="idFileName">No file chosen</span><input
                                type="file" id="idUpload" name="idUpload" accept=".pdf,.jpg,.jpeg,.png" required
                                onchange="updateFileName('idUpload', 'idFileName')"></div>
                        <p class="text-xs text-gray-500 mt-1">Max file size: 5MB. PDF, JPG, PNG.</p>
                    </div>
                    <div>
                        <label for="addressProofUpload" class="form-label">Upload Proof of Address<span
                                class="required-asterisk">*</span></label>
                        <div class="file-input-wrapper"><button type="button" class="file-input-button">Choose
                                File</button><span class="file-input-text" id="addressProofFileName">No file
                                chosen</span><input type="file" id="addressProofUpload" name="addressProofUpload"
                                accept=".pdf,.jpg,.jpeg,.png" required
                                onchange="updateFileName('addressProofUpload', 'addressProofFileName')"></div>
                        <p class="text-xs text-gray-500 mt-1">Max file size: 5MB. PDF, JPG, PNG.</p>
                    </div>
                </fieldset>

                <div class="pt-4">
                    <div class="flex items-start">
                        <input id="termsAndConditions" name="termsAndConditions" type="checkbox"
                            class="h-5 w-5 text-maroon-primary border-gray-300 rounded focus:ring-maroon-primary mt-0.5"
                            required>
                        <label for="termsAndConditions" class="ml-3 checkbox-label">I agree to the <a href="#"
                                target="_blank" class="hover:text-maroon-dark">Terms & Conditions</a> and <a href="#"
                                target="_blank" class="hover:text-maroon-dark">Privacy Policy</a>.<span
                                class="required-asterisk">*</span></label>
                    </div>
                </div>
                <div class="pt-6"><button type="submit" id="submitButton" class="submit-button">Submit
                        Application</button></div>
            </form>
        </div>
        <footer class="text-center mt-12 py-8">
            <p class="text-sm text-gray-600">&copy; <span id="currentYear"></span> Your Financial Institution. All
                rights
                reserved.</p>
            <p class="text-xs text-gray-500 mt-2">Loan applications are subject to approval.</p>
        </footer>
    </div>

    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();

        function updateFileName(inputId, displayId) {
            const input = document.getElementById(inputId);
            const display = document.getElementById(displayId);
            display.textContent = input.files && input.files.length > 0 ? input.files[0].name : 'No file chosen';
        }

        const loanAppForm = document.getElementById('loanApplicationForm');
        const submitBtn = document.getElementById('submitButton');
        loanAppForm.addEventListener('submit', function (event) {
            if (!loanAppForm.checkValidity()) {
                event.preventDefault();
                // Modern approach: Let browser validation UI handle detailed messages
                // Or, iterate through invalid fields and focus the first one
                let firstInvalidField = null;
                for (let i = 0; i < loanAppForm.elements.length; i++) {
                    const element = loanAppForm.elements[i];
                    if (element.willValidate && !element.checkValidity()) {
                        if (!firstInvalidField) {
                            firstInvalidField = element;
                        }
                        // Optionally, add a custom error style to invalid fields
                        element.classList.add('border-danger-red'); // Example: using a Tailwind color
                    } else if (element.willValidate) {
                        element.classList.remove('border-danger-red');
                    }
                }

                if (firstInvalidField) {
                    firstInvalidField.focus();
                    alert('Please fill out all required fields marked with an asterisk (*) and ensure uploads are complete.');
                } else {
                    alert('Please fill out all required fields and agree to the terms.');
                }
            } else {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
                // Simulate submission (replace with actual AJAX call)
                setTimeout(() => {
                    alert('Application submitted successfully! (This is a demo)');
                    loanAppForm.reset(); // Reset form after successful "submission"
                    updateFileName('idUpload', 'idFileName'); // Reset file names
                    updateFileName('addressProofUpload', 'addressProofFileName');
                    provinceDropdown.dispatchEvent(new Event('change')); // Reset dependent dropdowns to initial state
                    calculationDisplayDiv.style.display = 'none';
                    // Reset dropdowns explicitly
                    cityMunicipalityDropdown.innerHTML = '<option value="" disabled selected>Select City / Municipality</option>';
                    cityMunicipalityDropdown.disabled = true;
                    barangayDropdown.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
                    barangayDropdown.disabled = true;

                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit Application';
                }, 2000);
                event.preventDefault(); // Prevent actual form submission for demo
            }
        });

        // --- Cascading Dropdown Logic for Address ---
        const provinceDropdown = document.getElementById('province');
        const cityMunicipalityDropdown = document.getElementById('cityMunicipality');
        const barangayDropdown = document.getElementById('barangay');

        // **IMPORTANT**: This data is illustrative. A real application would use a comprehensive, server-side database.
        // For brevity, only a few cities/barangays are listed for the added provinces.
        const addressData = {
            "Laguna": {
                "Calauan": ["Balayhangin", "Bangyas", "Dayap", "Imok", "Lamot 1", "Lamot 2", "Limao", "Mabacan", "Masiit", "Paliparan", "Perez", "Kanluran (Pob.)", "Silangan (Pob.)", "Santol", "Santo Tomas", "Rukpok"],
                "Santa Rosa City": ["Market Area", "Don Jose", "Tagapo", "Dita", "Caingin", "Pook", "Aplaya", "Balibago", "Kanluran (Pob.)", "Macabling", "Malitlit", "Pulong Santa Cruz", "Santo Domingo", "Sinalhan", "Ibaba (Pob.)"],
                "Biñan City": ["Canlalay", "De La Paz", "Ganado", "San Francisco (Halang)", "Zapote", "Biñan (Pob.)", "Langkiwa", "Malamig", "San Antonio", "Santo Tomas (Pob.)", "Soro-Soro", "Tubigan"],
                "San Pablo City": ["I-A", "I-B", "II-A", "II-B", "III-A", "III-B", "IV-A", "IV-B", "San Buenaventura", "San Crispin", "San Diego", "San Francisco", "San Gregorio", "San Ignacio", "San Isidro", "San Jose", "San Lorenzo", "San Lucas 1", "San Lucas 2", "San Marcos", "San Mateo", "San Miguel", "San Nicolas", "San Pedro", "San Rafael", "San Roque", "Santa Ana", "Santa Catalina", "Santa Cruz", "Santa Elena", "Santa Filomena", "Santa Isabel", "Santa Maria Magdalena", "Santa Monica", "Santa Veronica", "Santiago I", "Santiago II", "Santo Angel", "Santo Cristo", "Santo Niño", "Soledad", "Concepcion", "Del Remedio", "Dolores", "Santa Maria", "Santissimo Rosario", "Atisan", "Bautista", "Bagong Bayan", "Bagong Pook", "Barleta", "Buenavista", "C C L", "Lumbangan", "Pa trưởng", "San Bartolome", "San Gabriel", "San Joaquin", "San Vicente", "Santa Felicia", "Santa Ines", "Santa Teresita", "Wawa"],
                "Calamba City": ["Banlic", "Barandal", "Batino", "Bubuyan", "Bucal", "Canlubang", "Halang", "Hornalan", "Kay-Anlog", "La Mesa", "Laguerta", "Lawang Bato", "Lecheria", "Lingga", "Looc", "Mabato", "Majada Labas", "Makiling", "Mapagong", "Masili", "Maunong", "Milagrosa", "Paciano Rizal", "Palingon", "Palo-Alto", "Pansol", "Parian", "Prinza", "Punta", "Puting Lupa", "Real", "Saimsim", "Sampiruhan", "San Cristobal", "San Jose", "San Juan", "Sirang Lupa", "Sucol", "Turbina", "Ulango", "Uwisan", "Poblacion", "Poblacion II", "Poblacion III", "Poblacion IV", "Poblacion V", "Poblacion VI", "Poblacion VII"]
            },
            "Metro Manila": {
                "Quezon City": ["Alicia", "Bagong Pag-asa", "Bahay Toro", "Balingasa", "Batasan Hills", "Commonwealth", "Culiat", "Damar", "Diliman", "Holy Spirit", "Loyola Heights", "Maharlika", "Matandang Balara", "Nagkaisang Nayon", "Novaliches Proper", "Pasong Putik Proper (Pasong Putik)", "Payatas", "Phil-Am", "Project 6", "San Roque", "Santa Lucia", "Santo Domingo (Matalahib)", "Socorro", "Tandang Sora", "UP Campus", "UP Village", "Vasra", "White Plains"],
                "Makati": ["Poblacion", "Bel-Air", "San Lorenzo", "Urdaneta", "Dasmarinas Village", "Forbes Park", "Bangkal", "Carmona", "Kasilawan", "Magallanes", "Olympia", "Palanan", "Pembo", "Pinagkaisahan", "Pitogo", "Rizal", "San Antonio", "Santa Cruz", "Singkamas", "Tejeros", "Valenzuela", "West Rembo", "East Rembo", "Comembo", "Guadalupe Nuevo", "Guadalupe Viejo", "Cembo"],
                "Manila": ["Tondo I", "Tondo II", "Sampaloc", "Santa Mesa", "Ermita", "Malate", "Binondo", "Intramuros", "Paco", "Pandacan", "Port Area", "Quiapo", "San Andres", "San Miguel", "San Nicolas", "Santa Ana"],
                "Pasig": ["Bagong Ilog", "Bagong Katipunan", "Bambang", "Buting", "Caniogan", "Dela Paz", "Kalawaan", "Kapasigan", "Kapitolyo", "Malinao", "Manggahan", "Maybunga", "Oranbo", "Palatiw", "Pinagbuhatan", "Pineda", "Rosario", "Sagad", "San Antonio", "San Joaquin", "San Jose", "San Miguel", "San Nicolas", "Santa Cruz", "Santa Lucia", "Santa Rosa", "Santo Tomas", "Santolan", "Sumilang", "Ugong"],
                "Caloocan": ["Barangay 1", "Barangay 2", "Barangay 160", "Barangay 171", "Barangay 176 (Bagong Silang)", "Barangay 178", "Barangay 188"],
                "Taguig": ["Bagumbayan", "Bambang", "Calzada", "Central Bicutan", "Central Signal Village", "Fort Bonifacio", "Hagonoy", "Ibayo-Tipas", "Katuparan", "Ligid-Tipas", "Lower Bicutan", "Maharlika Village", "Napindan", "New Lower Bicutan", "North Daang Hari", "North Signal Village", "Palingon", "Pinagsama", "San Miguel", "Santa Ana", "South Daang Hari", "South Signal Village", "Tanyag", "Tuktukan", "Upper Bicutan", "Ususan", "Wawa", "Western Bicutan"]
            },
            "Cavite": {
                "Bacoor": ["Molino I", "Molino II", "Molino III", "Molino IV", "Molino V", "Molino VI", "Molino VII", "Aniban I", "Aniban II", "Aniban III", "Aniban IV", "Aniban V", "Habay I", "Habay II", "Ligas I", "Ligas II", "Ligas III", "Mabolo I", "Mabolo II", "Mabolo III", "Niog I", "Niog II", "Niog III", "Panapaan I", "Panapaan II", "Panapaan III", "Panapaan IV", "Panapaan V", "Panapaan VI", "Panapaan VII", "Panapaan VIII", "Queens Row Central", "Queens Row East", "Queens Row West", "Real I", "Real II", "Salinas I", "Salinas II", "Salinas III", "Salinas IV", "San Nicolas I", "San Nicolas II", "San Nicolas III", "Talaba I", "Talaba II", "Talaba III", "Talaba IV", "Talaba V", "Talaba VI", "Talaba VII", "Zapote I", "Zapote II", "Zapote III", "Zapote IV", "Zapote V"],
                "Imus": ["Anabu I-A", "Anabu I-B", "Anabu I-C", "Anabu I-D", "Anabu I-E", "Anabu I-F", "Anabu I-G", "Anabu II-A", "Anabu II-B", "Anabu II-C", "Anabu II-D", "Anabu II-E", "Anabu II-F", "Bucandala I", "Bucandala II", "Bucandala III", "Bucandala IV", "Bucandala V", "Carsadang Bago I", "Carsadang Bago II", "Malagasang I-A", "Malagasang I-B", "Malagasang I-C", "Malagasang I-D", "Malagasang I-E", "Malagasang I-F", "Malagasang I-G", "Malagasang II-A", "Malagasang II-B", "Malagasang II-C", "Malagasang II-D", "Malagasang II-E", "Malagasang II-F", "Malagasang II-G", "Pag-asa I", "Pag-asa II", "Pag-asa III", "Palico I", "Palico II", "Palico III", "Palico IV", "Poblacion I-A", "Poblacion I-B", "Poblacion I-C", "Poblacion II-A", "Poblacion II-B", "Poblacion III-A", "Poblacion III-B", "Poblacion IV-A", "Poblacion IV-B", "Poblacion IV-C", "Poblacion IV-D", "Tanzang Luma I", "Tanzang Luma II", "Tanzang Luma III", "Tanzang Luma IV", "Tanzang Luma V", "Tanzang Luma VI", "Toclong I-A", "Toclong I-B", "Toclong I-C", "Toclong II-A", "Toclong II-B"],
                "Dasmariñas": ["Burol Main", "Emmanuel Bergado I", "Fatima I", "Luzviminda I", "Paliparan I", "Saint Peter I", "Salawag", "Salitran I", "Sampaloc I", "San Agustin I", "San Andres I", "San Antonio De Padua I", "San Dionisio", "San Esteban", "San Francisco I", "San Isidro Labrador I", "San Jose", "San Juan", "San Lorenzo Ruiz I", "San Luis I", "San Manuel I", "San Mateo", "San Miguel I", "San Nicolas I", "San Roque (Area D)", "Santa Cristina I", "Santa Cruz I", "Santa Fe", "Santa Lucia", "Santa Maria (Area C)", "Santo Cristo (Area G)", "Santo Niño I", "Victoria Reyes", "Zone I (Poblacion)", "Zone II (Poblacion)", "Zone III (Poblacion)", "Zone IV (Poblacion)"]
            },
            "Cebu": {
                "Cebu City": ["Apas", "Bacayan", "Banilad", "Basak San Nicolas", "Bulacao", "Capitol Site", "Carreta", "Cogon Ramos", "Ermita", "Guadalupe", "Hipodromo", "Inayawan", "Kalubihan", "Kamagayan", "Kasambagan", "Lahug", "Lorega San Miguel", "Luz", "Mabolo", "Pahina Central", "Pardo", "Pari-an", "Pasil", "Pit-os", "Poblacion", "Pulangbato", "Punta Princesa", "Quiot", "Sambag I", "Sambag II", "San Antonio", "San Jose", "San Nicolas Proper", "San Roque", "Santa Cruz", "Santo Niño", "Sapangdaku", "Sawang Calero", "Sinsin", "Sirao", "Suba", "Sudlon I", "Sudlon II", "Talamban", "Taptap", "Tejero", "Tinago", "Tisa", "Toong", "Zapatera"],
                "Mandaue City": ["Alang-alang", "Bakilid", "Banilad", "Basak", "Cabancalan", "Cambaro", "Canduman", "Casili", "Casuntingan", "Centro (Poblacion)", "Subangdaku", "Tabok", "Tawason", "Tingub", "Tipolo", "Umapad", "Jagobiao", "Guizo", "Ibabao-Estancia", "Labogon", "Looc", "Maguikay", "Mantuyong", "Opao", "Paknaan", "Pagsabungan"],
                "Lapu-Lapu City": ["Agus", "Babag", "Bankal", "Baring", "Basak", "Buaya", "Calawisan", "Canjulao", "Gun-ob", "Ibo", "Looc", "Mactan", "Maribago", "Marigondon", "Pajac", "Pajo", "Poblacion", "Punta Engaño", "Pusok", "Sabang", "Santa Rosa", "Subabasbas", "Talima", "Tingo", "Tunasan", "San Vicente", "Caubian", "Caw-oy", "Cawhagan"]
            },
            "Pampanga": {
                "Angeles City": ["Agapito del Rosario", "Amsic", "Anunas", "Balibago", "Capaya", "Claro M. Recto", "Cuayan", "Cutcut", "Cutud", "Lourdes North West", "Lourdes Sur", "Lourdes Sur East", "Malabanias", "Margot", "Mining", "Pampang", "Pandan", "Pulungbulu", "Pulung Cacutud", "Pulung Maragul", "Salapungan", "San Jose", "San Nicolas", "Santa Teresita", "Santa Trinidad", "Santo Cristo", "Santo Domingo", "Santo Rosario (Poblacion)", "Sapalibutad", "Sapangbato", "Tabun", "Virgen Delos Remedios", "Ninoy Aquino (Marisol)"],
                "San Fernando": ["Alasas", "Baliti", "Bulaon", "Calulut", "Dela Paz Norte", "Dela Paz Sur", "Del Carmen", "Del Pilar", "Del Rosario", "Dolores", "Juliana", "Lara", "Lourdes", "Magliman", "Maimpis", "Malajacan", "Malino", "Malpitic", "Pandaras", "Panipuan", "Poblacion", "Pulung Bulu", "Quebiauan", "Saguin", "San Agustin", "San Felipe", "San Isidro", "San Jose", "San Juan", "San Nicolas", "San Pedro Cutud", "Santa Lucia", "Santa Teresita", "Santo Niño", "Santo Rosario (Pau)", "Sindalan", "Telabastagan", "Santo Rosario (Poblacion)"],
                "Mabalacat": ["Atlu-Bola", "Bical", "Bundagul", "Cacutud", "Calumpang", "Camachiles", "Dapdap", "Dau", "Dolores", "Duquit", "Lakandula", "Mabiga", "Macapagal Village", "Mamatitang", "Mangalit", "Marcos Village", "Mawaque", "Paralayunan", "Poblacion", "San Francisco", "San Joaquin", "Santa Ines", "Santa Maria", "Santo Rosario", "Sapang Balen", "Sapang Biabas", "Tabun"]
            },
            "Batangas": {
                "Batangas City": ["Alangilan", "Balagtas", "Balete", "Banaba Center", "Banaba Ibaba", "Banaba Kanluran", "Banaba Silangan", "Bolbok", "Bucal", "Calicanto", "Catandala", "Concepcion (Poblacion)", "Conde Itaas", "Conde Labac", "Cumba", "Cuta", "Dalig", "De La Paz Pulot Aplaya", "De La Paz Pulot Itaas", "Dumantay", "Gulod Itaas", "Gulod Labac", "Haligue Silangan", "Ilijan", "Kumba", "Libjo", "Liponpon", "Maapaz", "Mahabang Dahilig", "Mahabang Parang", "Mahacot Silangan", "Malalim", "Malitam", "Maruclap", "Pagkilatan", "Pallocan Kanluran", "Pallocan Silangan", "Pinamucan Ibaba", "Pinamucan Proper", "Pinamucan Silangan", "Poblacion Barangay 1", "Poblacion Barangay 24", "Sampaga", "San Agapito", "San Agustin Silangan", "San Antonio", "San Isidro", "San Jose Sico", "San Miguel", "San Pedro", "Santa Clara", "Santa Rita Aplaya", "Santa Rita Karsada", "Sico", "Simlong", "Sirang Lupa", "Sorosoro Ibaba", "Sorosoro Karsada", "Tabangao Ambulong", "Tabangao Dao", "Talahib Pandayan", "Talahib Payapa", "Talumpok Kanluran", "Talumpok Silangan", "Tinga Itaas", "Tinga Labac", "Wawa"],
                "Lipa City": ["Adya", "Anilao", "Anilao-Labac", "Antipolo Del Norte", "Antipolo Del Sur", "Bagong Pook", "Balintawak", "Banaybanay", "Bolbok", "Bugtong Na Pulo", "Bulacnin", "Bulaklakan", "Calabarson", "Dagatan", "Duhatan", "Halang", "Inosloban", "Kayumanggi", "Latag", "Lipa City (Poblacion Brgy 1-12)", "Lodlod", "Lumbang", "Mabini", "Malagonlong", "Malitlit", "Marauoy", "Mataas Na Lupa", "Munting Pulo", "Pagolingin Bata", "Pagolingin East", "Pagolingin West", "Pangao", "Pinagkawitan", "Plaridel", "Pusil", "Quezon", "Rizal", "Sabang", "Sampaguita", "San Benito", "San Celestino", "San Carlos", "San Francisco", "San Guillermo", "San Isidro (Poblacion)", "San Jose", "San Lucas", "San Salvador", "San Sebastian (Poblacion)", "Santo Niño", "Santo Toribio", "Sapac", "Sico", "Talisay", "Tangjale", "Tanguay", "Tibig", "Tipacan"],
                "Tanauan": ["Altura Bata", "Altura Matanda", "Altura South", "Ambulong", "Bagbag", "Bagumbayan", "Balele", "Banadero", "Banjo East", "Banjo West", "Bilogbilog", "Boot", "Cale", "Darasa", "Pagaspas", "Pantay Matanda", "Pantay Munti", "Poblacion Barangay 1", "Poblacion Barangay 7", "Sala", "Sambat", "San Jose", "Santol (Doña Jacoba)", "Sulpoc", "Suplang", "Talaga", "Tinurik", "Trapiche", "Ulango", "Wawa", "Janopol Occidental", "Janopol Oriental", "Laurel", "Mabini", "Malaking Pulo", "Maria Paz", "Natatas", "Laurel (Poblacion)"]
            },
            "Rizal": {
                "Antipolo City": ["Bagong Nayon", "Beverly Hills", "Calawis", "Cupang", "Dalig", "Dela Paz (Pob.)", "Inarawan", "Mambugan", "Mayamot", "Munisipyo (Pob.)", "San Isidro (Pob.)", "San Jose (Pob.)", "San Juan", "San Luis", "San Roque (Pob.)", "Santa Cruz"],
                "Cainta": ["San Andres (Pob.)", "San Isidro", "San Juan", "San Roque", "Santa Rosa", "Santo Domingo", "Santo Niño"],
                "Taytay": ["Dolores (Pob.)", "Muzon", "San Isidro", "San Juan", "Santa Ana"]
            },
            "Bulacan": {
                "Malolos City": ["Anilao", "Atlag", "Babatnin", "Bagna", "Bagong Bayan", "Balayong", "Balite", "Bangkal", "Barihan", "Babatnin", "Bungahan", "Bustos", "Caingin", "Calero", "Caliligawan", "Canalate", "Caniogan", "Catmon", "Cofradia", "Dakila", "Guinhawa", "Liang", "Ligas", "Longos", "Look 1st", "Look 2nd", "Lugam", "Mabolo", "Mambog", "Masile", "Matimbo", "Mojon", "Namayan", "Niugan", "Pamarawan", "Panasahan", "Pinagbakahan", "San Agustin", "San Gabriel", "San Juan", "San Pablo", "San Vicente (Poblacion)", "Santiago", "Santisima Trinidad", "Santo Cristo", "Santo Niño (Poblacion)", "Santo Rosario (Poblacion)", "Santor", "Sumapang Bata", "Sumapang Matanda", "Taal", "Tikay"],
                "Meycauayan City": ["Bagbaguin", "Bahay Pare", "Bancal", "Banga", "Bayugo", "Caingin", "Calvario", "Camalig", "Hulo", "Iba", "Langka", "Lawa", "Libtong", "Liputan", "Longos", "Malhacan", "Pajo", "Pandayan", "Pantoc", "Perez", "Poblacion", "Saluysoy", "Saint Francis (Gasak)", "Tugatog", "Ubihan", "Zamora"],
                "San Jose del Monte City": ["Assumption", "Bagong Buhay I", "Bagong Buhay II", "Bagong Buhay III", "Bitungol", "Citrus", "Ciudad Real", "Dulong Bayan", "Francisco Homes-Guijo", "Francisco Homes-Mulawin", "Francisco Homes-Narra", "Francisco Homes-Yakal", "Gaya-gaya", "Graceville", "Gumaoc Central", "Gumaoc East", "Gumaoc West", "Kaybanban", "Kaypian", "Lawang Pari", "Maharlika", "Minuyan I", "Minuyan II", "Minuyan III", "Minuyan IV", "Minuyan V", "Minuyan Proper", "Muzon", "Paradise III", "Poblacion", "Poblacion I", "San Isidro", "San Manuel", "San Martin I", "San Martin II", "San Martin III", "San Martin IV", "San Martin V", "San Pedro", "San Rafael I", "San Rafael II", "San Rafael III", "San Rafael IV", "San Rafael V", "Santa Cruz I", "Santa Cruz II", "Santa Cruz III", "Santa Cruz IV", "Santa Cruz V", "Santo Cristo", "Santo Niño I", "Santo Niño II", "Sapang Palay Proper", "St. Martin de Porres", "Tungkong Mangga"]
            }
            // Add more provinces, cities, and barangays as needed. This is just a sample.
        };


        function populateDropdown(dropdown, items, defaultOptionText) {
            dropdown.innerHTML = `<option value="" disabled selected>${defaultOptionText}</option>`;
            if (items && items.length > 0) {
                items.sort(); // Sort items alphabetically
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item;
                    option.textContent = item;
                    dropdown.appendChild(option);
                });
                dropdown.disabled = false;
            } else {
                dropdown.disabled = true;
            }
        }

        provinceDropdown.addEventListener('change', function () {
            const selectedProvince = this.value;
            const cities = selectedProvince && addressData[selectedProvince] ? Object.keys(addressData[selectedProvince]) : [];
            populateDropdown(cityMunicipalityDropdown, cities, "Select City / Municipality");
            populateDropdown(barangayDropdown, [], "Select Barangay"); // Reset barangay
            barangayDropdown.disabled = true; // Keep disabled until city is selected
        });

        cityMunicipalityDropdown.addEventListener('change', function () {
            const selectedProvince = provinceDropdown.value;
            const selectedCity = this.value;
            const barangays = selectedProvince && selectedCity && addressData[selectedProvince] && addressData[selectedProvince][selectedCity] ? addressData[selectedProvince][selectedCity] : [];
            populateDropdown(barangayDropdown, barangays, "Select Barangay");
        });


        // --- Loan Calculation Logic ---
        const loanAmountInput = document.getElementById('loanAmount');
        const loanTypeSelect = document.getElementById('loanType');
        const loanTermSelect = document.getElementById('loanTerm');
        const calculationDisplayDiv = document.getElementById('loanCalculationDisplay');
        const monthlyInterestRateDisplayEl = document.getElementById('monthlyInterestRateDisplay');
        const monthlyInterestAmount = document.getElementById('monthlyInterestAmount');
        const monthlyPaymentEl = document.getElementById('estimatedMonthlyPayment');
        const totalInterestEl = document.getElementById('estimatedTotalInterest');
        const totalPayableEl = document.getElementById('estimatedTotalPayable');

        // SAMPLE MONTHLY INTEREST RATES (decimal format, e.g., 1.5% = 0.015)
        // **IMPORTANT**: These are illustrative and may not reflect real market rates.
        const loanInterestRates = {
            "Personal Loan": { "6 Months": 0.015, "12 Months": 0.013 },
            "Home Loan": { "6 Months": 0.008, "12 Months": 0.0075 }, // Home loans typically have longer terms, not usually 3 months
            "Education Loan": { "6 Months": 0.009, "12 Months": 0.007 },
            "Auto Loan": { "6 Months": 0.012, "12 Months": 0.010 },
        };

        function calculateLoanDetails() {
            const principal = parseFloat(loanAmountInput.value);
            const loanType = loanTypeSelect.value;
            const termString = loanTermSelect.value; // e.g., "6 Months"

            if (!principal || !loanType || !termString || principal <= 0) {
                calculationDisplayDiv.style.display = 'none';
                return;
            }

            const termInMonths = parseInt(termString.split(" ")[0]);
            let monthlyInterestRateDecimal = 0;

            if (loanInterestRates[loanType] && loanInterestRates[loanType][termString]) {
                monthlyInterestRateDecimal = loanInterestRates[loanType][termString];
            } else {
                calculationDisplayDiv.style.display = 'block'; // Show the div
                monthlyInterestRateDisplayEl.textContent = "N/A";
                monthlyPaymentEl.textContent = "PHP N/A";
                totalInterestEl.textContent = "PHP N/A";
                totalPayableEl.textContent = "PHP N/A";
                console.warn("Interest rate not found for selected loan type/term:", loanType, termString);
                return;
            }

            // Standard Amortization Formula: M = P [ i(1 + i)^n ] / [ (1 + i)^n – 1]
            // M = Monthly Payment, P = Principal, i = monthly interest rate, n = number of months
            const i = monthlyInterestRateDecimal;
            const n = termInMonths;
            let monthlyPayment;

            if (i === 0) { // Handles interest-free loan scenario
                monthlyPayment = (n > 0) ? principal / n : 0;
            } else {
                monthlyPayment = (n > 0) ? principal * (i * Math.pow(1 + i, n)) / (Math.pow(1 + i, n) - 1) : 0;
            }

            if (n <= 0 || principal <= 0) { // Ensure valid terms and principal for calculation
                monthlyPayment = 0;
            }


            const totalPayable = monthlyPayment * n;
            const totalInterest = (principal > 0 && n > 0) ? totalPayable - principal : 0;
            const monthlyInterestRatePercent = (i * 100).toFixed(2); // Convert to percentage and format

            monthlyInterestRateDisplayEl.textContent = `${monthlyInterestRatePercent}%`;
            monthlyPaymentEl.textContent = `PHP ${monthlyPayment.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            totalInterestEl.textContent = `PHP ${totalInterest.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            totalPayableEl.textContent = `PHP ${totalPayable.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            calculationDisplayDiv.style.display = 'block';
        }

        loanAmountInput.addEventListener('input', calculateLoanDetails);
        loanTypeSelect.addEventListener('change', calculateLoanDetails);
        loanTermSelect.addEventListener('change', calculateLoanDetails);

        // Initial setup
        // Populate provinces on load (already handled by HTML)
        // Trigger change on province if a value is pre-selected (e.g., by browser)
        if (provinceDropdown.value) {
            // Ensure cities for the pre-selected province are loaded
            const selectedProvince = provinceDropdown.value;
            const cities = selectedProvince && addressData[selectedProvince] ? Object.keys(addressData[selectedProvince]) : [];
            populateDropdown(cityMunicipalityDropdown, cities, "Select City / Municipality");

            if (cityMunicipalityDropdown.value) { // If city is also pre-selected
                const selectedCity = cityMunicipalityDropdown.value;
                const barangays = selectedProvince && selectedCity && addressData[selectedProvince] && addressData[selectedProvince][selectedCity] ? addressData[selectedProvince][selectedCity] : [];
                populateDropdown(barangayDropdown, barangays, "Select Barangay");
            }
        }

        calculateLoanDetails(); // Calculate on load if form fields have values

    </script>
</body>

</html>