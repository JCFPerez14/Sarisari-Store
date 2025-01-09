<?php
require_once 'db_to_php.php';
startSecureSession();
$conn = connectDB();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username already exists";
        } else {
            // Hash password and insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            
            if ($stmt->execute()) {
                $_SESSION['message'] = "Registration successful! Please login.";
                header("Location: SSSSJC.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Sign Up - Shop Management System</title>
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
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-body p-5">
                                <h2 class="text-center mb-4">Sign Up</h2>

                                <?php if (isset($error)): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username:</label>
                                        <input type="text" class="form-control" name="username" id="username" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password:</label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">Sign Up</button>
                                    </div>
                                </form>
                                
                                <p class="text-center mt-4">
                                    Already have an account? <a href="SSSSJC.php" class="text-decoration-none">Login here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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


<?php
if(isset($_SESSION['username'])) {
    header("Location: shop_interface.php");
    exit();
}