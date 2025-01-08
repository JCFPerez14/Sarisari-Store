<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: SSSSJC.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "shop_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            $message = "User added successfully";
        }
    }
    
    if (isset($_POST['update_user'])) {
        $id = $_POST['id'];
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username='$username', password='$password' WHERE id=$id";
        } else {
            $sql = "UPDATE users SET username='$username' WHERE id=$id";
        }
        if ($conn->query($sql) === TRUE) {
            $message = "User updated successfully";
        }
    }
    
    if (isset($_POST['delete_user'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM users WHERE id=$id AND username != 'admin'";
        if ($conn->query($sql) === TRUE) {
            $message = "User deleted successfully";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; }
        input[type="text"], input[type="password"] {
            padding: 5px;
            width: 200px;
        }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .message { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>User Management</h2>
    <?php if(isset($message)) echo "<div class='message'>$message</div>"; ?>

    <h3>Add New User</h3>
    <form method="post">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <input type="submit" name="add_user" value="Add User">
    </form>

    <h3>User List</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>New Password</th>
            <th>Actions</th>
        </tr>
        <?php
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='id' value='".$row['id']."'>";
            echo "<td>".$row['id']."</td>";
            echo "<td><input type='text' name='username' value='".$row['username']."'></td>";
            echo "<td><input type='password' name='password' placeholder='Leave empty to keep current'></td>";
            echo "<td>";
            echo "<input type='submit' name='update_user' value='Update'>";
            if($row['username'] != 'admin') {
                echo " <input type='submit' name='delete_user' value='Delete'>";
            }
            echo "</td>";
            echo "</form>";
            echo "</tr>";
        }
        ?>
    </table>
    <p><a href="shop_interface.php">Back to Shop Interface</a></p>
</body>
</html>
