<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "kp_bikes");

// Add new bike
if ($_POST['add_bike']) {
    $query = "INSERT INTO bikes (name, price, image, specs, status) VALUES (?, ?, ?, ?, 'Available')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdss", $_POST['name'], $_POST['price'], $_POST['image'], $_POST['specs']);
    $stmt->execute();
}

// Mark as sold
if ($_GET['mark_sold']) {
    $conn->query("UPDATE bikes SET status='Sold' WHERE id=" . $_GET['mark_sold']);
}
?>

<!-- Form to add bike -->
<form method="POST">
    <input type="text" name="name" placeholder="Bike Model" required>
    <input type="number" name="price" placeholder="Price ($)" required>
    <input type="text" name="image" placeholder="Image URL">
    <textarea name="specs" placeholder="Specs..."></textarea>
    <button type="submit" name="add_bike">Add Bike</button>
</form>

<!-- List of bikes -->
<table>
    <?php
    $bikes = $conn->query("SELECT * FROM bikes");
    while ($bike = $bikes->fetch_assoc()) {
        echo "<tr>
            <td>{$bike['name']}</td>
            <td>\${$bike['price']}</td>
            <td><a href='?mark_sold={$bike['id']}'>Mark Sold</a></td>
        </tr>";
    }
    ?>
</table>
