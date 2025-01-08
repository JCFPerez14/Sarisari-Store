
<!DOCTYPE html>
<html>
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
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "shop_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['submit'])) {
            $item = $_POST['item'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $datemodified = $_POST['datemodified'];
            
            $sql = "INSERT INTO items (item_name, price, quantity, date_modified) 
                    VALUES ('$item', $price, $quantity, '$datemodified')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p>New record created successfully</p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        
        if (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM items WHERE id=$id";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p>Record deleted successfully</p>";
            }
        }
        
        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $item = $_POST['item'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $datemodified = $_POST['datemodified'];
            
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
        <div class="form-group">
            <label>Date Modified:</label>
            <input type="datetime-local" name="datemodified" required>
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
                echo "<td><input type='text' name='item' value='".$row['item_name']."'></td>";
                echo "<td><input type='number' step='0.01' name='price' value='".$row['price']."'></td>";
                echo "<td><input type='number' name='quantity' value='".$row['quantity']."'></td>";
                echo "<td><input type='datetime-local' name='datemodified' value='".date('Y-m-d\TH:i', strtotime($row['date_modified']))."'></td>";
                echo "<td>
                        <input type='submit' name='update' value='Update'>
                        <input type='submit' name='delete' value='Delete'>
                      </td>";
                echo "</form>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>
</html>
