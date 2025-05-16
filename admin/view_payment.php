<?php
// Database connection (adjust parameters accordingly)
$pdo = new PDO('mysql:host=localhost;dbname=dm_lms', 'username', 'password');

// Query to get all the payments (including proof of payment path)
$sql = "SELECT * FROM payment";
$stmt = $pdo->query($sql);

echo "<div class='container'>";
while ($payment = $stmt->fetch()) {
    // Display payment details
    echo "<div class='payment'>";
    echo "<p><strong>Amount Paid:</strong> " . htmlspecialchars($payment['amount']) . "</p>";
    echo "<p><strong>Proof of Payment:</strong></p>";

    // Display the uploaded image
    $image_path = 'uploads/' . $payment['proof_of_payment_path'];
    echo "<img src='$image_path' alt='Proof of Payment' width='300'>";
    echo "</div><hr>";
}
echo "</div>";
?>