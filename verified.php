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

		.card-body h3 {
			color: black;
			font-size: 1.5rem;
		}

		.btn {
			width: 100%;
			color: blue;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						Email Verification Successful
					</div>
					<div class="card-body">
						<?php
						if (isset($_REQUEST['email'])) {
							$email = $_REQUEST['email'];
							?>
							<center>
								<h3>Your email has been successfully verified!</h3>
							</center>
							<p>Thank you for completing this step in your loan application process.</p>
							<p>You may now access your account and continue your application by clicking the link below:</p>
							<center><a href="confirm_account.php?email=<?php echo $email; ?>" class="btn btn-success">Login
									to Your
									Account</a></center>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>