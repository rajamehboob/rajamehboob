//config.php
 <?php
$servername = "localhost";
$username = "mehboob";
$password = "";
$dbname = "userdb";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable error reporting

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

//signup.php
 <?php
include "config.php";

if (isset($_POST['submit'])) {
    // Validate inputs
    $first_name = $conn->real_escape_string(trim($_POST['firstname']));
    $last_name = $conn->real_escape_string(trim($_POST['lastname']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $gender = $conn->real_escape_string($_POST['gender']);

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (firstname, lastname, email, password, gender) 
            VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$gender')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully.";
        header("Location: view.php"); // Redirect to view page after success
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Signup Form</h2>
    <form action="" method="POST">
        <fieldset>
            <legend>Personal Information:</legend>
            First name:<br>
            <input type="text" name="firstname" required>
            <br>
            Last name:<br>
            <input type="text" name="lastname" required>
            <br>
            Email:<br>
            <input type="email" name="email" required>
            <br>
            Password:<br>
            <input type="password" name="password" required>
            <br>
            Gender:<br>
            <input type="radio" name="gender" value="Male" required> Male
            <input type="radio" name="gender" value="Female" required> Female
            <br><br>
            <input type="submit" name="submit" value="Submit">
        </fieldset>
    </form>
</body>
</html>


//view.php
 <?php
include "config.php";

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Users</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['firstname']}</td>
                                <td>{$row['lastname']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['gender']}</td>
                                <td>
                                    <a class='btn btn-info' href='update.php?id={$row['id']}'>Edit</a>
                                    <a class='btn btn-danger' href='delete.php?id={$row['id']}'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>


//update.php

 <?php
include "config.php";

if (isset($_POST['update'])) {
    $firstname = $conn->real_escape_string(trim($_POST['firstname']));
    $lastname = $conn->real_escape_string(trim($_POST['lastname']));
    $user_id = $_POST['id'];
    $email = $conn->real_escape_string(trim($_POST['email']));
    $gender = $conn->real_escape_string($_POST['gender']);

    $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email', gender='$gender' 
            WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully.";
        header("Location: view.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id=$user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $gender = $row['gender'];
    } else {
        header("Location: view.php");
    }
}
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Update User</h2>
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo $user_id; ?>">
        First name:<br>
        <input type="text" name="firstname" value="<?php echo $firstname; ?>" required>
        <br>
        Last name:<br>
        <input type="text" name="lastname" value="<?php echo $lastname; ?>" required>
        <br>
        Email:<br>
        <input type="email" name="email" value="<?php echo $email; ?>" required>
        <br>
        Gender:<br>
        <input type="radio" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>> Male
        <input type="radio" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>> Female
        <br><br>
        <input type="submit" name="update" value="Update">
    </form>
</body>
</html>


//delete.php
 <?php
include "config.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully.";
        header("Location: view.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
