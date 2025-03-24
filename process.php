<?php
// process.php

// Database connection details
$host = 'sql12.freesqldatabase.com';  // e.g., 'localhost'
$dbname = 'sql12769224'; // The name of your database
$user = 'sql12769224';    // Your MySQL username
$pass = 'XqVTDF9ASr';  // Your MySQL password
$charset = 'utf8';

// Data from the form
$name = $_POST['name'];
$email = $_POST['email'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$phone = $_POST['phone'];

// Data validation (same as in the HTML form, but repeated in PHP for security)
$errors = array();

if (empty($name)) {
    $errors[] = "Name is required";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid Email";
}
if (empty($phone) || !preg_match("/^\d{10}$/", $phone)) {
    $errors[] = "Invalid Phone Number";
}
if (empty($dob)) {
    $errors[] = "Date of Birth is required";
}
if (empty($gender)) {
    $errors[] = "Gender is required";
}
if (empty($address)) {
    $errors[] = "Address is required";
}


if (!empty($errors)) {
    // If there are errors, redirect back to the form with error messages
    session_start();
    $_SESSION['errors'] = $errors;
    header("Location: index.php"); //  "index.php"
    exit();
}

// Attempt to connect to the database
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $opt);
} catch (PDOException $e) {
    // Handle connection error (very important!)
    echo "Database connection failed: " . $e->getMessage();
    exit(); // Stop execution if the connection fails
}

// Prepare the SQL statement.  Use prepared statements to prevent SQL injection.
$sql = "INSERT INTO students (name, email, dob, gender, address, phone) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

// Execute the prepared statement with the form data.
try {
    $stmt->execute([$name, $email, $dob, $gender, $address, $phone]);
     echo "New record created successfully"; // success message
} catch (PDOException $e) {
    // Handle errors during the database insertion.
    echo "Error inserting data: " . $e->getMessage();
    exit();
}

// Close the database connection.  Good practice, although PDO will often do this automatically.
$pdo = null;
?>
