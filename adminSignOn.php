<?php

    $date = date('Y');
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Home</title>
</head>
<body>
    <header>
        <h1>WDV341 and WDV321 Portfolio Project: Recipe Manager</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="adminSignOn.php">Admin Sign-On</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <div id="signOnContainer">

    </div>
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
</body>
</html>