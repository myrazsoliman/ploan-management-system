<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<title>Email Verification</title>
	<style>
		body {
			background-color: #f8f9fa;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			margin: 0;
		}

		.card {
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
			width:
				<?php echo isset($cardWidth) ? $cardWidth : '500px'; ?>
			;
			/* Dynamic width */
			height:
				<?php echo isset($cardHeight) ? $cardHeight : 'auto'; ?>
			;
			/* Dynamic height */
		}

		.card-header {
			background-color:
				<?php echo isset($headerBgColor) ? $headerBgColor : '#800000'; ?>
			;
			/* Dynamic header color */
			color: white;
			font-size: 1.25rem;
			text-align: center;
			border-top-left-radius: 8px;
			border-top-right-radius: 8px;
			padding-top: 10px;
			padding-bottom: 10px;
			font-weight: bold;
		}

		.card-body {
			padding: 2rem;
		}

		.btn-primary {
			width: 100%;
			color: blue;
		}
	</style>
</head>

<body>
	<?php
	// Dynamic variables
	$cardWidth = '600px';
	$cardHeight = '400px';
	$headerBgColor = '#007bff'; // Example: Blue header
	?>

	<div class="card">
		<div class="card-header">
			Verify Your Email
		</div>
		<div class="card-body">
			<?php
			if (isset($_REQUEST['firstname']) && isset($_REQUEST['lastname']) && isset($_REQUEST['email'])) {
				$firstname = htmlspecialchars($_REQUEST['firstname']);
				$lastname = htmlspecialchars($_REQUEST['lastname']);
				$email = htmlspecialchars($_REQUEST['email']);
				?>
				<p class="text-center">Hi,
					<strong><?php echo $firstname . " " . $lastname; ?></strong>
				</p>
				<p class="text-center">We appreciate your interest in applying for a loan with us.</p>
				<p class="text-center">A confirmation email has been sent to your registered email address:</p>
				<p class="text-center"><strong><?php echo $email; ?></strong></p>
				<p>To continue with your loan application, please open the email and complete the verification process.<br>
					If you don’t receive the email shortly, we recommend checking your spam or junk folder. <br>
				</p>
				<center><a class="btn btn-primary" href="https://<?php echo $email; ?>" target="_blank">Verify Email</a>
				</center>
				<?php
			} else {
				?>
				<p class="text-center text-danger">Invalid request. Please try again.</p>
				<?php
			}
			?>
		</div>
	</div>
</body>

</html>