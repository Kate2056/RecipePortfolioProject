<?php

    $date = date('Y');
    
    require 'recipeDbConnect.php';
    


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="stylesheets/style.scss" rel="stylesheet">
    <link href="stylesheets/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:wght@400;700&display=swap" rel="stylesheet">
    <title>Recipe Home</title>
</head>
<body>
    <header>
        <img src="images/bannerImage.jpg" alt="Banner background image of food"id="bannerImage">
        <h1>WDV341 and WDV321 Portfolio Project: Recipe Manager</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="adminSignOn.php">Admin Sign-On</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <div id="recipesContainer">
        <h3 class="title">All Recipies</h3>

        <?php
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT recipeName, recipeID, recipeServingSize, recipePrepTime, recipeDifficulty, recipeImageSrc, recipeDescription FROM recipes";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
            while($row = $stmt->fetch()){
                echo '<div class="recipeCard">';
                echo '<img src="data:image/jpg;charset=utf8;base64,' . base64_encode($row['recipeImageSrc']) . '">';
                echo '<h4 class="recipeTitle">' . $row['recipeName'] . '</h4>';
                echo '<p>' . $row['recipeDifficulty']  . '</p>';
                echo '<p>  Serving Size: ' . $row['recipeServingSize'] . '</p>';
                echo '<p> Prep Time: ' . $row['recipePrepTime'] . '</p>';
                echo '</div>';
            }



        ?>
    </div>
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
</body>
</html>