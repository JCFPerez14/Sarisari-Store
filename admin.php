<?php
require_once 'db_to_php.php';
startSecureSession();
requireAdmin();
$conn = connectDB();

// Add cache control headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Add admin verification before any modification operations
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['username'] === 'admin') {
    if (isset($_POST['add_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            $message = "User added successfully";
        }
    }
    
    if (isset($_POST['update_user'])) {
        $id = (int)$_POST['id'];
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
        $id = (int)$_POST['id'];
        $sql = "DELETE FROM users WHERE id=$id AND username != 'admin'";
        if ($conn->query($sql) === TRUE) {
            $message = "User deleted successfully";
        }
    }
}
?>
 <!doctype html>
 <html lang="en">
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
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
 
        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>
 
    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
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
                    
                    // Only admin can see editable fields, others see plain text
                    if ($_SESSION['username'] === 'admin') {
                        echo "<td><input type='text' name='username' value='".$row['username']."'></td>";
                        echo "<td><input type='password' name='password' placeholder='Leave empty to keep current'></td>";
                        echo "<td>";
                        echo "<input type='submit' name='update_user' value='Update'>";
                        if($row['username'] != 'admin') {
                            echo " <input type='submit' name='delete_user' value='Delete'>";
                        }
                        echo "</td>";
                    } else {
                        echo "<td>".$row['username']."</td>";
                        echo "<td>********</td>";
                        echo "<td>No actions available</td>";
                    }
                    
                    echo "</form>";
                    echo "</tr>";
                }
                ?>
            </table>
            <p><a href="shop_interface.php">Back to Shop Interface</a></p>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>
 
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
 </html>
 