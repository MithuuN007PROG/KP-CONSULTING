<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "kp_bikes");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Login check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $specs = $conn->real_escape_string($_POST['specs']);
    $price = floatval($_POST['price']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Handle file upload
    $target_dir = "uploads/";
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    $original_name = basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    
    // Generate unique filename
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    // Initialize validation
    $uploadOk = 1;
    $message = "";
    
    // Check if image file is an actual image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        $message = "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check file size (2MB max)
    if ($_FILES["image"]["size"] > 2000000) {
        $message = "Sorry, your file is too large (max 2MB).";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if(!in_array($imageFileType, $allowed_types)) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded. " . $message;
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now insert into database
            $sql = "INSERT INTO bikes (name, specs, price, image, status) 
                    VALUES ('$name', '$specs', $price, '$new_filename', '$status')";
            
            if ($conn->query($sql) === TRUE) {
                $message = "Bike added successfully!";
            } else {
                $message = "Error: " . $conn->error;
                // Delete the uploaded file if DB insert failed
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}

// Get all bikes
$bikes = $conn->query("SELECT * FROM bikes ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bike Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        form { margin-bottom: 30px; padding: 20px; background: #f5f5f5; border-radius: 5px; }
        input, textarea, select { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .logout { float: right; margin-bottom: 20px; }
        .error { color: red; }
        .success { color: green; }
        .bike-image-preview { max-width: 100px; max-height: 100px; margin-top: 10px; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_logout.php" class="logout">Logout</a>
        <h1>Bike Management Admin Panel</h1>
        
        <h2>Add New Bike</h2>
        <?php if (isset($message)): ?>
            <p class="<?php echo ($uploadOk == 1 && strpos($message, 'success') !== false) ? 'success' : 'error' ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>
            
            <label>Specifications:</label>
            <textarea name="specs" required rows="3"></textarea>
            
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>
            
            <label>Image:</label>
            <input type="file" name="image" id="imageInput" accept="image/*" required>
            <img id="imagePreview" class="bike-image-preview" src="#" alt="Image preview">
            
            <label>Status:</label>
            <select name="status" required>
                <option value="Available">Available</option>
                <option value="Sold">Sold</option>
                <option value="Pending">Pending</option>
            </select>
            
            <button type="submit">Add Bike</button>
        </form>

        <h2>All Bikes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($bike = $bikes->fetch_assoc()): ?>
            <tr>
                <td><?= $bike['id'] ?></td>
                <td><?= htmlspecialchars($bike['name']) ?></td>
                <td>$<?= number_format($bike['price'], 2) ?></td>
                <td>
                    <?php if (!empty($bike['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($bike['image']) ?>" alt="Bike Image" style="max-width: 100px;">
                    <?php else: ?>
                        No image
                    <?php endif; ?>
                </td>
                <td><?= $bike['status'] ?></td>
                <td>
                    <a href="admin_edit.php?id=<?= $bike['id'] ?>">Edit</a> | 
                    <a href="admin_delete.php?id=<?= $bike['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('imageInput').addEventListener('change', function(e) {
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