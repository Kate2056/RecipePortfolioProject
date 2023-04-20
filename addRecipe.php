<?php

    $date = date('Y');
    
    session_start();
    
    if(isset($_SESSION['validuser'])){

    }
    else{
        header("Location: adminSignOn.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/styles.scss">
    <link rel="stylesheet" href="stylesheets/styles.css">
    <title>Add Recipe</title>
</head>
<body>
    <header>
        <h1>WDV341 and WDV321 Portfolio Project: Recipe Manager</h1>
        <h3 id="signOut"><a href="logout.php">Sign Out </a></h3>
        <h3>Signed in as: <?php echo $_SESSION['username']; ?></h3>
    </header>
    <nav>
        <ul>
            <li><a href="adminSignOn.php">Home</a></li>
            <li><a href="addRecipe.php">Add Recipe</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <div id="addRecipeContainer">

    </div>
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
</body>
</html>