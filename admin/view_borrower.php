<?php
// view_borrower.php

// Include database connection
include '../connect/connection.php'; // Adjust the path as necessary
include '../class.php'; // Make sure to create this file to handle your DB connection

// Check if the borrower_id is set in the URL
if (isset($_GET['id'])) {
    $borrower_id = intval($_GET['id']); // Sanitize input

    // Prepare and execute the query
    $query = "SELECT * FROM borrowers WHERE borrower_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $borrower_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a borrower was found
    if ($result->num_rows > 0) {
        $borrower = $result->fetch_assoc();
    } else {
        echo "No borrower found.";
        exit;
    }
} else {
    echo "Invalid borrower ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Borrower</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Add your Bootstrap CSS path -->
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        width: 1500px;
        max-width: 100%;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
    }


    .table {
        width: 100%;
        margin-top: 20px;
    }

    .table th,

    .table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .table tr:hover {
        background-color: #f1f1f1;
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-bordered th,

    .table-bordered td {
        border: 1px solid #ddd;
    }


    .table-bordered th {
        background-color: #f9f9f9;
    }

    .table-bordered td {
        background-color: #fff;
    }


    .table-bordered tr:hover {
        background-color: #f1f1f1;
    }
</style>

<body>
    <div class="container">
        <h1>Borrower Details</h1>

        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Borrower ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1</td>
                    <td><?php echo htmlspecialchars($borrower['borrower_id']); ?></td>
                    <td><?php echo htmlspecialchars($borrower['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($borrower['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($borrower['email']); ?></td>
                    <td><?php echo htmlspecialchars($borrower['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($borrower['address']); ?></td>
                    <td>
                        <a
                            href="edit_borrower.php?id=<?php echo htmlspecialchars($borrower['borrower_id']); ?>">Edit</a>
                        |
                        <a
                            href="delete_borrower.php?id=<?php echo htmlspecialchars($borrower['borrower_id']); ?>">Delete</a>
                </tr>
    </div>
</body>

</html>