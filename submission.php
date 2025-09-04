<?php
include 'database.php';
session_start();

// Checking Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name            = $_POST['name'];
    $category        = $_POST['category'];
    $description     = $_POST['description'];
    $life_expectancy = $_POST['life_expectancy'];
    $captcha         = $_POST['captcha'];

    if ($captcha != $_SESSION['captcha_answer']) {
        die("Captcha is invalid.");  
    }

  
    $image_path = "";  
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/";
        $filename   = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $filename;

       
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }



    //Inserting Data
  
    $stmt = $conn->prepare("
        INSERT INTO animal (name, category, image, description, life_expectancy)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssss", $name, $category, $image_path, $description, $life_expectancy);
    $stmt->execute();

    
    header("Location: index.php");
    exit;
}


$num1 = rand(1, 10);
$num2 = rand(1, 10);
$_SESSION['captcha_answer'] = $num1 + $num2;
?>
  <link rel="stylesheet" href="assets/style.css">
<form action="submission.php" method="post" enctype="multipart/form-data">
    <label>
        Name:
        <input type="text" name="name" required>
    </label>
    <br><br>

    <label>Category:</label><br>
    <label><input type="radio" name="category" value="herbivores" required> Herbivores</label>
    <label><input type="radio" name="category" value="omnivores"> Omnivores</label>
    <label><input type="radio" name="category" value="carnivores"> Carnivores</label>
    <br><br>

    <label>
        Image:
        <input type="file" name="image" accept="image/*">
    </label>
    <br><br>

    <label>
        Description:<br>
        <textarea name="description" required rows="4" cols="50"></textarea>
    </label>
    <br><br>

    <label>
        Life Expectancy:
        <select name="life_expectancy" required>
            <option value="0-1 year">0-1 year</option>
            <option value="1-5 years">1-5 years</option>
            <option value="5-10 years">5-10 years</option>
            <option value="10+ years">10+ years</option>
        </select>
    </label>
    <br><br>

    <label>
        Captcha: What is <?= $num1 ?> + <?= $num2 ?>?
        <input type="text" name="captcha" required>
    </label>
    <br><br>

    <button type="submit">Submit</button>
</form>

