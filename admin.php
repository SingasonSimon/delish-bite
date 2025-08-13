<?php
// 1. ESTABLISH DATABASE CONNECTION
include 'db_connect.php';

$message = '';
$message_type = ''; // To handle success or error styling

// Check for status messages from the redirect
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $message = "New menu item added successfully!";
        $message_type = 'success';
    } elseif ($_GET['status'] == 'error') {
        $message = "Error: Could not add the item.";
        $message_type = 'error';
    } elseif ($_GET['status'] == 'upload_error') {
        $message = "Sorry, there was an error uploading your file.";
        $message_type = 'error';
    }
}

// 2. PROCESS FORM SUBMISSION
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Handle File Upload ---
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["food_image"]["name"]);

    if (move_uploaded_file($_FILES["food_image"]["tmp_name"], $target_file)) {
        // File upload was successful
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image_path = $target_file;
        $alt_text = $_POST['alt_text'];

        $stmt = $conn->prepare("INSERT INTO menu_items (name, price, image_path, alt_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $image_path, $alt_text);

        if ($stmt->execute()) {
            // On success, redirect to the same page with a success message
            header("Location: admin.php?status=success");
            exit(); // Always call exit() after a header redirect
        } else {
            // On database error, redirect with an error message
            header("Location: admin.php?status=error");
            exit();
        }
        $stmt->close();
    } else {
        // On upload error, redirect with an upload error message
        header("Location: admin.php?status=upload_error");
        exit();
    }
}

// 3. INCLUDE THE HEADER
include 'header.php';
?>

<main class="admin-container">
    <h2>Add New Menu Item</h2>

    <?php
    // Display the success or error message if it exists
    if (!empty($message)) {
        // Use the message_type to apply a success or error class
        echo "<p class='message {$message_type}'>$message</p>";
    }
    ?>
    
    <form action="admin.php" method="POST" enctype="multipart/form-data" class="add-item-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>
        
        <label for="food_image">Image:</label>
        <input type="file" id="food_image" name="food_image" required>
        
        <label for="alt_text">Alt Text:</label>
        <input type="text" id="alt_text" name="alt_text" required>
        
        <button type="submit">Add Item</button>
    </form>

    <hr class="divider">
    <h2>Manage Menu Items</h2>
    <table class="item-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, name, price FROM menu_items ORDER BY id DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>KSh <?php echo number_format($row['price'], 2); ?></td>
                    <td class="actions">
                        <a href="#" class="edit-btn">Edit</a>
                        <a href="#" class="delete-btn">Delete</a>
                    </td>
                </tr>
            <?php
                } // end while
            } else {
                echo "<tr><td colspan='4'>No menu items found.</td></tr>";
            } // end if
            ?>
        </tbody>
    </table>
</main>

<?php
// CLOSE CONNECTION AND INCLUDE FOOTER
$conn->close();
include 'footer.php';
?>