<?php
include 'database.php';


//Show Visiting Count
$counter_file = "pallavi.txt";
if (!file_exists($counter_file)) {
    file_put_contents($counter_file, 0);  
}
$count = (int)file_get_contents($counter_file);
$count++;
file_put_contents($counter_file, $count);

$category_filter = $_GET['category'] ?? '';
$life_filter     = $_GET['life'] ?? '';
$sort_option     = $_GET['sort'] ?? 'date_desc';


$query = "SELECT * FROM animal WHERE 1";

if (!empty($category_filter)) {
    $query .= " AND category = '$category_filter'";
}

if (!empty($life_filter)) {
    $query .= " AND life_expectancy = '$life_filter'";
}

// Sorting
switch ($sort_option) {
    case 'alpha':
        $query .= " ORDER BY name ASC";
        break;
    case 'date_asc':
        $query .= " ORDER BY created_at ASC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
}


$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
     <link rel="stylesheet" href="assets/style.css">
    <title>Animals List</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        img { max-width: 100px; }
    </style>
</head>
<body>

<h2>Animals List</h2>
<p><strong>Visitor Count:</strong> <?= $count ?></p>


<form method="get">
    <label>
        Category:
        <select name="category">
            <option value="">All</option>
            <option value="herbivores" <?= $category_filter == 'herbivores' ? 'selected' : '' ?>>Herbivores</option>
            <option value="omnivores" <?= $category_filter == 'omnivores' ? 'selected' : '' ?>>Omnivores</option>
            <option value="carnivores" <?= $category_filter == 'carnivores' ? 'selected' : '' ?>>Carnivores</option>
        </select>
    </label>

    <label>
        Life Expectancy:
        <select name="life">
            <option value="">All</option>
            <option value="0-1 year" <?= $life_filter == '0-1 year' ? 'selected' : '' ?>>0-1 year</option>
            <option value="1-5 years" <?= $life_filter == '1-5 years' ? 'selected' : '' ?>>1-5 years</option>
            <option value="5-10 years" <?= $life_filter == '5-10 years' ? 'selected' : '' ?>>5-10 years</option>
            <option value="10+ years" <?= $life_filter == '10+ years' ? 'selected' : '' ?>>10+ years</option>
        </select>
    </label>

    <label>
        Sort By:
        <select name="sort">
            <option value="date_desc" <?= $sort_option == 'date_desc' ? 'selected' : '' ?>>Latest</option>
            <option value="date_asc" <?= $sort_option == 'date_asc' ? 'selected' : '' ?>>Old</option>
            <option value="alpha" <?= $sort_option == 'alpha' ? 'selected' : '' ?>>A to Z</option>
        </select>
    </label>

    <button type="submit">Filter</button>
</form>

<p><a href="submission.php">➕ Add New Animal</a></p>


<table>
    <tr>
        <th>Photo</th>
        <th>Name</th>
        <th>Category</th>
        <th>Description</th>
        <th>Life Expectancy</th>
        <th>Actions</th>
    </tr>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Animal image">
                    <?php else: ?>
                        No image
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['life_expectancy']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No animals found.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
