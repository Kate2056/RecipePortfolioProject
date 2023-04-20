<?php

    $date = date('Y');
    
    session_start();

    $errorMsg = ""; 
    $validuser = false;


    if(isset($_POST['submit'])){
        $inUsername = $_POST['inUsername'];
        $inPassword = $_POST['inPassword'];

        require 'recipeDbConnect.php';
        $sql = 'SELECT recipeUsername, recipePassword FROM recipe_users WHERE recipeUsername = :username and recipePassword = :password';
    
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':username', $inUsername);
        $stmt->bindParam(':password', $inPassword);

        $stmt->execute();
    
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();

        if($row){
            $errorMsg = "";
            $validuser = true;
            $_SESSION['validuser'] = true;
            $_SESSION['username'] = $inUsername;

        }
        else{
            $errorMsg = "Invalid Username or Password";
            $validuser = false;
        }
    
    }
    else{
        if(isset($_SESSION['validuser'])){
            $validuser = true;
        }
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
    <title>Recipe Home</title>
</head>
<body>
    <header>
        <h1>WDV341 and WDV321 Portfolio Project: Recipe Manager</h1>
    
    <?php
        if($validuser){

    ?>
    <h3 id="signOut"><a href="logout.php">Sign Out </a></h3>
    <h3>Signed in as: <?php echo $inUsername; ?></h3>
    
    </header>
    <nav>
        <ul>
            <li><a href="adminSignOn.php">Home</a></li>
            <li><a href="addRecipe.php">Add Recipe</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
    </body>
    </html>
    <?php
        }else{
    ?>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="adminSignOn.php">Admin Sign-On</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <div id="signOnContainer">
        <form method="POST" action="adminSignOn.php">
            <span><?php echo $errorMsg ?></span>
            <p>
                <label for="inUsername">Username:</label>
                <input name="inUsername" id="inUsername" type="text">
            </p>
            <p>
                <label for="inPassword">Password:</label>
                <input name="inPassword" id="inPassword" type="password">
            </p>
            <p>
                <input name="submit" id="submit" type="submit" value="Submit">
                <input name="reset" id="reset" type="reset" value="Reset">
            </p>
        </form>
    </div>
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
</body>
</html>
<?php
    }
?>