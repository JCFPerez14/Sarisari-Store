  <?php
  session_start();
  // Prevent caching
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  if(isset($_SESSION['username'])) {
      header("Location: shop_interface.php");
      exit();
  }
  $conn = mysqli_connect("localhost", "root", "", "shop_db");

  if(isset($_POST['login'])) {
      $username = mysqli_real_escape_string($conn, $_POST['username']);
      $password = mysqli_real_escape_string($conn, $_POST['password']);
    
      $query = "SELECT * FROM users WHERE username='$username'";
      $result = mysqli_query($conn, $query);
    
      if(mysqli_num_rows($result) == 1) {
          $user = mysqli_fetch_assoc($result);
          if(password_verify($password, $user['password'])) {
              $_SESSION['username'] = $username;
              $_SESSION['role'] = $user['role']; // Store the role in session
              
              // Redirect based on role
              if ($_SESSION['role'] === 'SuperAdmin') {
                  header("Location: admin.php");
              } else {
                  header("Location: shop_interface.php");
              }
              exit();
          } else {
              $error = "Invalid username or password";
          }
      } else {
          $error = "Invalid username or password";
      }
  }
  ?>
<!doctype html>
<html lang="en">
    <head>
    <title>Login Page</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 20px;
            }
            .login-container {
                max-width: 400px;
                margin: 100px auto;
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            input[type="submit"] {
                background: #4CAF50;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                width: 100%;
            }
            .error {
                color: red;
                margin-bottom: 10px;
            }
            .signup-link {
                margin-top: 20px;
    	    }
    	    .signup-link a {
    	        text-decoration: none;
    	        padding: 8px 15px;
    	    }

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
            <div class="login-container">
            <h2>Login</h2>
            <?php if(isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="submit" name="login" value="Login">
            </form>
            </div>
            <div class="signup-link" style="text-align: center; margin-top: 15px;">
                <p>Don't have an account? <a href="signup.php" class="btn btn-secondary">Sign Up</a></p>
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
        <script>
            window.history.pushState(null, null, window.location.href);
            window.onpopstate = function () {
                window.history.pushState(null, null, window.location.href);
            };
        </script>
    </body>
</html>


