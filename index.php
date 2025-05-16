<?php
date_default_timezone_set("Etc/GMT+8");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Loan Management System</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link href="css/all.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/modal.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="images/back1.png" height="100%" width="100%" />
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5" id="loginForm">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><strong>LOGIN</strong></h1>
                                    </div>
                                    <form method="POST" class="user" action="login.php"
                                        onsubmit="return validateForm()">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username"
                                                placeholder="Username" required>
                                        </div>
                                        <div class="form-group position-relative">
                                            <input type="password" class="form-control form-control-user"
                                                id="loginPassword" name="password" placeholder="Password">
                                            <i class="fa fa-eye-slash" id="loginToggle"
                                                onclick="togglePasswordVisibility('loginPassword', 'loginToggle')"
                                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                        </div>
                                        <div class=" form-group">
                                            <input type="checkbox" id="terms" name="terms">
                                            <label for="terms">I agree to the <a href="#" onclick="showModal()">Terms
                                                    and Conditions</a></label>
                                        </div>
                                        <?php
                                        if (isset($_SESSION['message'])) {
                                            echo "<center><label class='text-danger'>" . $_SESSION['message'] . "</label></center>";
                                            unset($_SESSION['message']); // Clear the session message after displaying it
                                        }
                                        ?>
                                        <button type="submit" class="btn btn-primary btn-user btn-block"
                                            name="login">Login</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a href="forgot_password.php">Forgot
                                            password?</a>
                                    </div>
                                    <div class="text-center">
                                        <span>Don't have an account?</span>
                                        <span> · </span>
                                        <a href="javascript:void(0);" onclick="toggleForm('signupForm')">Sign Up</a>
                                    </div>
                                </div>

                                <div class="p-5" id="signupForm" style="display: none;">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4"><strong>SIGN UP</strong></h1>
                                    </div>
                                    <form method="POST" class="user" action="signup.php"
                                        onsubmit="return validateSignUpForm()">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username"
                                                placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="firstname"
                                                placeholder="First Name" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="lastname"
                                                placeholder="Last Name" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email"
                                                class="form-control" placeholder="Email" required>
                                        </div>
                                        <div class="form-group position-relative">
                                            <input type="password" class="form-control form-control-user"
                                                id="signupPassword" name="password" placeholder="Password" required>
                                            <i class="fa fa-eye-slash" id="signupToggle"
                                                onclick="togglePasswordVisibility('signupPassword', 'signupToggle')"
                                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i></i>
                                        </div>

                                        <div class="form-group position-relative">
                                            <input type="password" class="form-control form-control-user"
                                                id="confirmPassword" name="confirm_password"
                                                placeholder="Confirm Password" required>
                                            <i class="fa fa-eye-slash" id="confirmToggle"
                                                onclick="togglePasswordVisibility('confirmPassword', 'confirmToggle')"
                                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                        </div>

                                        <div class="form-group">
                                            <input type="checkbox" id="signupTerms" name="terms">
                                            <label for="signupTerms">I agree to the <a href="#"
                                                    onclick="showModal()">Terms and Conditions</a></label>
                                        </div>
                                        <?php
                                        if (isset($_SESSION['message'])) {
                                            echo "<center><label class='text-danger'>" . $_SESSION['message'] . "</label></center>";
                                            unset($_SESSION['message']); // Clear the session message after displaying it
                                        }
                                        ?>
                                        <button class="btn btn-primary btn-user btn-block" name="register">Sign
                                            Up</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <span>Already have an account?</span>
                                        <span> · </span>
                                        <a href="javascript:void(0);"
                                            onclick="toggleForm('loginForm')"><strong>Login</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="termsModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div class="terms-content" id="termsContent">
            </div>
        </div>
    </div>

    <script>
        // Function to load Terms and Conditions content
        function showModal() {
            fetch('terms.php')
                .then(response => response.text())
                .then(data => {
                    // Set the terms content
                    const termsContent = document.getElementById('termsContent');
                    termsContent.innerHTML = data;

                    // Add the scrollable class to the terms content
                    termsContent.classList.add('terms-scroll');

                    // Display the modal
                    document.getElementById('termsModal').style.display = 'block';
                });
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('termsModal').style.display = 'none';
        }

        // Validate the login form (checkbox must be checked)
        function validateForm() {
            if (!document.getElementById('terms').checked) {
                alert("You must agree to the Terms and Conditions to proceed.");
                return false;
            }
            return true;
        }

        // Validate the sign-up form (checkbox must be checked)
        function validateSignUpForm() {
            if (!document.getElementById('signupTerms').checked) {
                alert("You must agree to the Terms and Conditions to proceed.");
                return false;
            }
            return true;
        }

        // Toggle between the login and sign-up forms
        function toggleForm(formId) {
            if (formId === 'signupForm') {
                document.getElementById('loginForm').style.display = 'none';
                document.getElementById('signupForm').style.display = 'block';
            } else {
                document.getElementById('signupForm').style.display = 'none';
                document.getElementById('loginForm').style.display = 'block';
            }
        }

        // Function to toggle password visibility
        function togglePasswordVisibility(passwordId, toggleIconId) {
            const passwordInput = document.getElementById(passwordId);
            const toggleIcon = document.getElementById(toggleIconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>

</html>