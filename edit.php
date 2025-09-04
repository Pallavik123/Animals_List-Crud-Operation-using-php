<?php
include 'database.php';
$id = $_GET['id'] ?? 0;
$result = $conn->query("SELECT * FROM animal WHERE id = $id");

if (!$result || $result->num_rows == 0) {
    echo "Animal not found.";
    exit;
}

$row = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $life = $_POST['life_expectancy'];


    $stmt = $conn->prepare("UPDATE animal SET name=?, category=?, description=?, life_expectancy=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $category, $description, $life, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<h2>Edit Animal</h2>
  <link rel="stylesheet" href="assets/style.css">
<form method="post">
    <p>
        <label>Name:<br>
            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
        </label>
    </p>

    <p>
        <label>Category:<br>
            <input type="text" name="category" value="<?= htmlspecialchars($row['category']) ?>" required>
        </label>
    </p>

    <p>
        <label>Description:<br>
            <textarea name="description" rows="4"><?= htmlspecialchars($row['description']) ?></textarea>
        </label>
    </p>

    <p>
        <label>Life Expectancy:<br>
            <input type="text" name="life_expectancy" value="<?= htmlspecialchars($row['life_expectancy']) ?>" required>
        </label>
    </p>

    <button type="submit">Update</button>
</form>

<p><a href="index.php">⬅ Back to List</a></p>
