<?php
require_once 'db_to_php.php';
startSecureSession();
requireLogin();
$conn = connectDB();
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $isAdmin) {
        // Delete operation
        if (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM items WHERE id=$id";
        
            if ($conn->query($sql) === TRUE) {
                echo "<p>Record deleted successfully</p>";
            }
        }
    
        // Update operation
        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $item = $_POST['item'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $datemodified = date('Y-m-d H:i:s');
        
            $sql = "UPDATE items SET 
                    item_name='$item', 
                    price=$price, 
                    quantity=$quantity, 
                    date_modified='$datemodified' 
                    WHERE id=$id";
        
            if ($conn->query($sql) === TRUE) {
                echo "<p>Record updated successfully</p>";
            }
        }
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <title>Shop Item CRUD</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .form-group { margin-bottom: 15px; }
            label { display: inline-block; width: 120px; }
            input[type="text"], input[type="number"], input[type="datetime-local"] {
                padding: 5px;
                width: 200px;
            }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
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
            <div style="text-align: right; padding: 10px;">
                <form method="post" action="logout.php">
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </header>
        <main>     
            <h2>Add New Item</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label>Item Name:</label>
                    <input type="text" name="item" required>
                </div>
                <div class="form-group">
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" required>
                </div>
                <div class="form-group">
                    <label>Quantity:</label>
                    <input type="number" name="quantity" required>
                </div>
                <input type="submit" name="submit" value="Add Item">
            </form>

            <h2>Item List</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Date Modified</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT * FROM items";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
                        echo "<input type='hidden' name='id' value='".$row['id']."'>";
                        echo "<td>".$row['id']."</td>";
                        
                        if ($isAdmin) {
                            echo "<td><input type='text' name='item' value='".$row['item_name']."'></td>";
                            echo "<td><input type='number' step='0.01' name='price' value='".$row['price']."'></td>";
                            echo "<td><input type='number' name='quantity' value='".$row['quantity']."'></td>";
                            echo "<td>".$row['date_modified']."</td>";
                            echo "<td>
                                    <input type='submit' name='update' value='Update'>
                                    <input type='submit' name='delete' value='Delete'>
                                  </td>";
                        } else {
                            echo "<td>".$row['item_name']."</td>";
                            echo "<td>".$row['price']."</td>";
                            echo "<td>".$row['quantity']."</td>";
                            echo "<td>".$row['date_modified']."</td>";
                            echo "<td></td>";
                        }
                        echo "</form>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
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


