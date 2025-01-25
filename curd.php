//config.php
<?php
$servername = "localhost";
$username = "mehboob";
$password = "Kh@Ri@n#214s1";
$dbname = "userdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("connection failed" . $conn->connect_error);
}
//signup.php
<?php
include "config.php";

if (isset($_POST['submit'])) {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    // Make sure to hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (`firstname`, `lastname`, `email`, `password`, `gender`) VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$gender')";
    // echo $sql;
    $result = $conn->query($sql);
    //echo __LINE__;
    if ($result == TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<body>

    <h2> Signup Form </h2>

    <form action="" method="POST">
        <fieldset>
            <legend>Personal Information:</legend>
            First name:<br>
            <input type='text' name="firstname" required>
            <br>
            Last name:<br>
            <input type='text' name="lastname" required>
            <br>
            Email:<br>
            <input type='email' name="email" required>
            <br>
            Password:<br>
            <input type='password' name="password" required>
            <br>
            Gender:<br>
            <input type='radio' name="gender" value="Male" required> Male
            <input type='radio' name="gender" value="Female" required> Female
            <br><br>
            <input type='submit' name="submit" value="Submit">
        </fieldset>
    </form>

</body>

</html>

//view.php

<?php
include "config.php";

$sql = "SELECT * FROM users"; // Added space before FROM

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.css"> <!-- Corrected .com to .css -->
</head>

<body>
    <div class="container"> <!-- Corrected the class attribute -->
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
                ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['firstname']; ?></td>
                            <td><?php echo $row['lastname']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td>
                                <a class="btn btn-info" href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                                &nbsp;
                                <a class="btn btn-danger" href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                <?php
                    }
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

if (isset($post['update'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $user_id = $_POST['user_id']; //changed from 'id' to 'user_id'
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];

    $sql = "UPDATE 'users' SET 'firstname' = '$firstname', 'lastname' = '$lastname','id' = '$user_id', 'email' = '$email','gender' = '$gender', 'password' = '$password'";

    $result = $conn->query($sql);

    if ($result == TRUE) {
        echo "record updated succesfuly";
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT *FROM 'users' WHERE 'id'=$user_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $user_id = $row['id'];
            $email = $row['email'];
            $gender = $row['gender'];
            $password = $row['password'];
        }

?>


        <h2>User Update Form</h2>
        <form action="" method="POST">
            <fieldset>
                <legend>Personal information:</legend>
                First name:<br>
                <input type="text" name="firstname" value="<?php echo $first_name; ?>">
                <input type="hidden" name=:"user_id" value="<?php echo $id; ?>>
             <br>
            Last name:<br>
             <input type=" text" name="lastname" value="<?php echo $lastname; ?>">
                Email:<br>
                <input type="email" name="email" value="<?php echo $email; ?>">
                <br>
                Password:<br>
                <input type="password" name="password" value="<?php echo $password; ?>">
                <br>
                Gender:<br>
                <input type="radio" name="gender" value="Male" <?php if ($gender == 'Male') {
                                                                    echo "checked";
                                                                } ?>>Male
                <input type="radio" name="gender" value="Female" <?php if ($gender == 'Female') {
                                                                        echo "checked";
                                                                    } ?>>Female
                <br><br>
                <input type="submit" name="update" value="Update">
            </fieldset>


            </body>

            </html>
    <?php

    } else {
        //If the 'id' value is not valid,redirect the user back to view.php page
        header('Location:view.php');
    }
}

    ?>

//delete.php

<?php
include "config.php";

if (isset($_GET['id'])) { // changed $_get to $_GET
    $user_id = $_Get['id'];

    $sql = "DELETE FROM 'users' WHERE 'id'='$user_id'";

    $result = $conn->query($sql);

    if ($result == TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}
