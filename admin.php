<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "kp_bikes");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ====================== LOGIN HANDLING ======================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Check for login submission
if (isset($_POST['login'])) {
    $username = "admin"; // Change these in production!
    $password = "admin123";
    
    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $login_error = "Invalid credentials";
    }
}

// Show login form if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f5f5f5; }
            .login-box { width: 300px; padding: 30px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            h1 { text-align: center; margin-bottom: 20px; color: #333; }
            input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; }
            button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
            .error { color: red; text-align: center; margin-bottom: 15px; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h1>Admin Login</h1>
            <?php if (isset($login_error)) echo '<p class="error">'.$login_error.'</p>'; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// ====================== ADMIN PANEL FUNCTIONALITY ======================
$message = '';
$edit_mode = isset($_GET['edit']);
$current_bike = null;

// Handle edit mode
if ($edit_mode) {
    $stmt = $conn->prepare("SELECT * FROM bikes WHERE id = ?");
    $stmt->bind_param("i", $_GET['edit']);
    $stmt->execute();
    $current_bike = $stmt->get_result()->fetch_assoc();
}

// Handle delete action
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("SELECT image FROM bikes WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    $result = $stmt->get_result();
    $bike = $result->fetch_assoc();
    
    $stmt = $conn->prepare("DELETE FROM bikes WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    
    if ($stmt->execute()) {
        if (!empty($bike['image']) && file_exists("uploads/" . $bike['image'])) {
            unlink("uploads/" . $bike['image']);
        }
        $message = "Bike deleted successfully!";
    } else {
        $message = "Error deleting bike: " . $conn->error;
    }
}

// Handle form submission (add/edit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_bike'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $specs = $conn->real_escape_string($_POST['specs']);
    $price = floatval($_POST['price']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Handle file upload
    $image = $edit_mode ? $current_bike['image'] : null;
    
    if (!empty($_FILES['image']['name'])) {
        // Delete old image if editing
        if ($edit_mode && !empty($current_bike['image'])) {
            unlink("uploads/" . $current_bike['image']);
        }
        
        // Upload new image
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0755, true);
        
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $ext;
        $target_file = $target_dir . $new_filename;
        
        // Validate image
        $valid = true;
        $check = getimagesize($_FILES['image']['tmp_name']);
        if (!$check) {
            $message = "File is not an image.";
            $valid = false;
        } elseif ($_FILES['image']['size'] > 2000000) {
            $message = "File too large (max 2MB).";
            $valid = false;
        } elseif (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $message = "Invalid file type.";
            $valid = false;
        }
        
        if ($valid && move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $new_filename;
        }
    }
    
    if ($edit_mode) {
        // Update existing bike
        $stmt = $conn->prepare("UPDATE bikes SET name=?, specs=?, price=?, image=?, status=? WHERE id=?");
        $stmt->bind_param("ssdssi", $name, $specs, $price, $image, $status, $_POST['id']);
    } else {
        // Add new bike
        $stmt = $conn->prepare("INSERT INTO bikes (name, specs, price, image, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $name, $specs, $price, $image, $status);
    }
    
    if ($stmt->execute()) {
        $message = "Bike " . ($edit_mode ? "updated" : "added") . " successfully!";
        $edit_mode = false;
    } else {
        $message = "Error: " . $conn->error;
        if (isset($new_filename)) unlink($target_file);
    }
}

// Get all bikes
$bikes = $conn->query("SELECT * FROM bikes ORDER BY id DESC");
?>

<!-- ====================== ADMIN PANEL HTML ====================== -->
<!DOCTYPE html>
<html>
<head>
    <title>Bike Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f9f9f9; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        form { margin-bottom: 30px; padding: 20px; background: #f5f5f5; border-radius: 5px; }
        input, textarea, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 15px; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-primary { background: #4CAF50; }
        .btn-secondary { background: #2196F3; }
        .btn-danger { background: #f44336; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .error { background: #ffdddd; color: #f44336; }
        .success { background: #ddffdd; color: #4CAF50; }
        .bike-image { max-width: 100px; max-height: 100px; }
        .image-preview { max-width: 200px; margin-top: 10px; display: block; }
        .actions { white-space: nowrap; }
        .actions a { margin-right: 5px; text-decoration: none; padding: 5px 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bike Management Admin Panel</h1>
            <a href="?logout" class="btn-danger" style="padding: 8px 12px;">Logout</a>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <h2><?= $edit_mode ? 'Edit Bike' : 'Add New Bike' ?></h2>
        <form method="post" enctype="multipart/form-data">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?= $current_bike['id'] ?>">
            <?php endif; ?>
            
            <label>Name:</label>
            <input type="text" name="name" value="<?= $edit_mode ? htmlspecialchars($current_bike['name']) : '' ?>" required>
            
            <label>Specifications:</label>
            <textarea name="specs" rows="3" required><?= $edit_mode ? htmlspecialchars($current_bike['specs']) : '' ?></textarea>
            
            <label>Price:</label>
            <input type="number" step="0.01" name="price" value="<?= $edit_mode ? $current_bike['price'] : '' ?>" required>
            
            <label>Image:</label>
            <?php if ($edit_mode && !empty($current_bike['image'])): ?>
                <p>Current Image:</p>
                <img src="uploads/<?= $current_bike['image'] ?>" class="image-preview">
                <p>Upload new image to replace:</p>
            <?php endif; ?>
            <input type="file" name="image" id="imageInput" accept="image/*" <?= !$edit_mode ? 'required' : '' ?>>
            <img id="imagePreview" class="image-preview" src="#" alt="Preview" style="display: none;">
            
            <label>Status:</label>
            <select name="status" required>
                <option value="Available" <?= $edit_mode && $current_bike['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="Sold" <?= $edit_mode && $current_bike['status'] == 'Sold' ? 'selected' : '' ?>>Sold</option>
                <option value="Pending" <?= $edit_mode && $current_bike['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            </select>
            
            <button type="submit" name="submit_bike" class="btn-primary">
                <?= $edit_mode ? 'Update Bike' : 'Add Bike' ?>
            </button>
            
            <?php if ($edit_mode): ?>
                <a href="?" class="btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>

        <h2>All Bikes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($bike = $bikes->fetch_assoc()): ?>
                <tr>
                    <td><?= $bike['id'] ?></td>
                    <td><?= htmlspecialchars($bike['name']) ?></td>
                    <td>$<?= number_format($bike['price'], 2) ?></td>
                    <td>
                        <?php if (!empty($bike['image'])): ?>
                            <img src="uploads/<?= htmlspecialchars($bike['image']) ?>" class="bike-image">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                    <td><?= $bike['status'] ?></td>
                    <td class="actions">
                        <a href="?edit=<?= $bike['id'] ?>" class="btn-secondary">Edit</a>
                        <a href="?delete=<?= $bike['id'] ?>" class="btn-danger" onclick="return confirm('Delete this bike?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('imageInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>