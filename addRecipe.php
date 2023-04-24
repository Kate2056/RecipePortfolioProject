<?php

    $date = date('Y');
    
    session_start();
    
    $displayForm = true;
    $ingredientCount = 1;

    if(isset($_SESSION['validuser'])){

    }
    else{
        header("Location: adminSignOn.php");
    }

    $recipeNameError = "";
    $recipeDifficultyError = "";
    $recipeServingError = "";
    $recipeTimeError = "";
    $recipeIngredientError = "";
    $recipeInstruError = "";
    $recipeImgError = "";

    if(isset($_POST['submit']) && $_POST['recipeCategory'] == ""){

        if($_POST['recipeName'] == ""){
            $recipeNameError = "Please Enter A Name";
        }else {
            if($_POST['recipeDifficulty'] == ""){
                $recipeDifficultyError = "Please Select Difficulty Level";
            }else {
                if($_POST['recipeServingSize'] == ""){
                    $recipeServingError = "Please Enter Serving Size";
                }else {
                    if($_POST['recipePrepTime'] == ""){
                        $recipeTimeError = "Please Enter Prep Time";
                    }else {
                        if(empty($_FILES["recipeImgSrc"]["name"])) { 
                            $recipeImgError = 'Please select an image file to upload.'; 
                        }else{
                            $fileName = basename($_FILES["recipeImgSrc"]["name"]); 
                            $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
                             
                            $allowTypes = array('jpg','png','jpeg','gif'); 
                            if(!in_array($fileType, $allowTypes)){ 
                                $recipeImgError = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
                            }else{
                                $image = $_FILES['recipeImgSrc']['tmp_name']; 
                                $imgContent = addslashes(file_get_contents($image)); 
    
                                    if($_POST['recipeInstructions'] == ""){
                                        $recipeInstruError = "Please Enter Instructions";
                                    }else if($_POST['recipeIngredientName1'] == "" || $_POST['recipeIngredientAmount1'] == "") {
                                            $recipeIngredientError = "Please enter an ingredient name and amount";
                                        } else{
                                            

                                        $displayForm = false;
        
                                        require 'recipeDbConnect.php';
        
                                        $recipeName = $_POST['recipeName'];
                                        $recipeDifficulty = $_POST['recipeDifficulty'];
                                        $recipeServingSize = $_POST['recipeServingSize'];
                                        $recipePrepTime = $_POST['recipePrepTime'];
                                        $recipeInstructions = $_POST['recipeInstructions'];
        
                                        $sql = 'INSERT INTO recipes (recipeName, recipeDifficulty, recipeServingSize, recipePrepTime, recipeDescription, recipeImageSrc)
                                            VALUES (:recipeName, :recipeDifficulty, :recipeServingSize, :recipePrepTime, :recipeInstructions, :recipeImgSrc) ';
        
                                        $stmt = $conn->prepare($sql);
        
                                        $stmt->bindParam(':recipeName', $recipeName);
                                        $stmt->bindParam(':recipeDifficulty', $recipeDifficulty);
                                        $stmt->bindParam(':recipeServingSize', $recipeServingSize);
                                        $stmt->bindParam(':recipePrepTime', $recipePrepTime);
                                        $stmt->bindParam(':recipeInstructions', $recipeInstructions);
                                        $stmt->bindParam(':recipeImgSrc', $imgContent);
        
                                        $stmt->execute();
                                        
                                        $sql = 'SELECT recipeID FROM recipes WHERE recipeName = :recipeName';
                                        $stmt = $conn->prepare($sql);  
                                        $stmt->bindParam(':recipeName', $recipeName);
                                        $stmt->execute();  
                                        $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                        $row = $stmt->fetch();
                                        $recipeID = $row['recipeID'];

                                        $ingredientCount = $_COOKIE['ingredientCount'];

                                        for($counter = 1; $counter <= $ingredientCount; $counter++){
                                            if($_POST['recipeIngredientName' . $counter] != "" && $_POST['recipeIngredientAmount' . $counter] != ""){
                                                
                                                $ingredientName = $_POST['recipeIngredientName' . $counter];
                                                $ingredientAmount = $_POST['recipeIngredientAmount' . $counter];

                                                if($_POST['recipeIngredientMeasurement' . $counter] != ""){
                                                    $ingredientMeasurement = $_POST['recipeIngredientMeasurement' . $counter];

                                                    $sql = 'INSERT INTO recipe_ingredients (recipeID, ingredientName, ingredientAmount, ingredientMeasurement)
                                                        VALUES (:recipeID, :ingredientName, :ingredientUnit, :ingredientMeasurement) ';
        
                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->bindParam(':recipeID', $recipeID);
                                                    $stmt->bindParam(':ingredientName', $ingredientName);
                                                    $stmt->bindParam(':ingredientUnit', $ingredientAmount);
                                                    $stmt->bindParam(':ingredientMeasurement', $ingredientMeasurement);
        
                                                    $stmt->execute();
                                                    
                                                }
                                                else{
                                                    $sql = 'INSERT INTO recipe_ingredients (recipeID, ingredientName, ingredientAmount, ingredientMeasurement)
                                                        VALUES (:recipeID, :ingredientName, :ingredientUnit, :ingredientMeasurement) ';

                                                    $ingredientMeasurement = null;

                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->bindParam(':recipeID', $recipeID);
                                                    $stmt->bindParam(':ingredientName', $ingredientName);
                                                    $stmt->bindParam(':ingredientUnit', $ingredientAmount);
                                                    $stmt->bindParam(':ingredientMeasurement', $ingredientMeasurement);
        
                                                    $stmt->execute();
                                                }
                                            }
                                            
                                            
                                        }

                                        $displayForm = false;
                                    }
                                }
                            }   
                        }
                    }
             }
    
    }    
}
    else{
        $displayForm = true;
    
    }

    if($displayForm){

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/style.scss">
    <link rel="stylesheet" href="stylesheets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:wght@400;700&display=swap" rel="stylesheet">
    <title>Add Recipe</title>
    <script>
        let ingredientCount = 1;

        function onLoad(){
            document.querySelector("#ingredientButton").onclick = addIngredient;
        }

        function addIngredient(){
            ingredientCount++;

            document.cookie="ingredientCount=" + ingredientCount;

            let newIngredientSection = document.createElement("p");
            
			newIngredientSection.class = "ingredientSection";
            newIngredientSection.id = ingredientCount;	

            //<input type="text" name="recipeIngredientName" class="recipeIngredients" placeholder="Name" >
			let recipeIngredientName = document.createElement("input");
            recipeIngredientName.class = "recipeIngredients";
            recipeIngredientName.name = "recipeIngredientName" + ingredientCount;
            recipeIngredientName.placeholder = "Name";
            recipeIngredientName.type = "text";

            //<input type="text" name="recipeIngredientUnit" class="recipeIngredients" placeholder="Amount">
            let recipeIngredientAmount = document.createElement("input");
            recipeIngredientAmount.class = "recipeIngredients";
            recipeIngredientAmount.name = "recipeIngredientAmount" + ingredientCount;
            recipeIngredientAmount.placeholder = "Amount";
            recipeIngredientAmount.type = "text";

            //<input type="text" name="recipeIngredientMeasurement" class="recipeIngredients" placeholder="Measurement">
            let recipeIngredientMeasurement = document.createElement("input");
            recipeIngredientMeasurement.class = "recipeIngredients";
            recipeIngredientMeasurement.name = "recipeIngredientMeasurement" + ingredientCount;
            recipeIngredientMeasurement.placeholder = "Measurement";
            recipeIngredientMeasurement.type = "text";

			newIngredientSection.appendChild(recipeIngredientName);
            newIngredientSection.appendChild(recipeIngredientAmount);
            newIngredientSection.appendChild(recipeIngredientMeasurement);

			document.querySelector("#addRecipe").insertBefore(newIngredientSection, document.querySelector("#ingredientButton"));
        }


    </script>
</head>
<body onload="onLoad()">
    <header>
    <img src="images/bannerImage.jpg" alt="Banner background image of food"id="bannerImage">
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
    <h3 class="title">Add New Recipe</h3>
        <form method="POST" action="addRecipe.php"  enctype="multipart/form-data" id="addRecipe">
            <p>
                <label for="recipeName">Recipe Name: </label>
                <input type="text" name="recipeName" id="recipeName">
                <span id="recipeNameError"><?php echo $recipeNameError ?></span>
            </p>
            <p>
                <label for="recipeDifficulty">Difficulty Level: </label>
                <select id="recipeDifficulty" name="recipeDifficulty">
                    <option value="">Select Difficulty</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Professional">Professional</option>
                </select>
                <span id="recipeDifficultyError"><?php echo $recipeDifficultyError ?></span>
            </p>
            <p>
                <label for="recipeServingSize">Serving Size: </label>
                <input type="text" name="recipeServingSize" id="recipeServingSize">
                <span id="recipeServingError"><?php echo $recipeServingError ?></span>
            </p>
            <p>
                <label for="recipePrepTime">Prep Time: </label>
                <input type="text" name="recipePrepTime" id="recipePrepTime">
                <span id="recipeTimeError"><?php echo $recipeTimeError ?></span>
            </p>
            <p>
                <label for="recipeImgSrc">Select Image:</label>              
                <input type="file" name="recipeImgSrc" id="recipeImgSrc">
                <span id="recipeImgError"><?php echo $recipeImgError ?></span>
            </p>
            <span id="recipeIngriedientError"><?php echo $recipeIngredientError ?></span>
            <p class="ingredientSection" id="1">
                <label for="recipeIngredients">Ingredients: </label> 
                <input type="text" name="recipeIngredientName1" class="recipeIngredients" placeholder="Name" >
                <input type="text" name="recipeIngredientAmount1" class="recipeIngredients" placeholder="Amount">
                <input type="text" name="recipeIngredientMeasurement1" class="recipeIngredients" placeholder="Measurement">
                
            </p>
            <button type="button" id="ingredientButton" class="button">New Ingredient</button>
            
            <p id="hide">
                <label for="recipeCategory">Category: </label>
                <input type="text" name="recipeCategory" id="recipeCategory" value="">
            </p>
            <p>
                <label for="recipeInstructions">Instructions: </label>
                <textarea name="recipeInstructions" id="recipeInstructions" form="addRecipe" textMode="MultiLine" rows="8" cols="50"></textarea>
                <span id="recipeInstruError"><?php echo $recipeInstruError ?></span>
            </p>
            <p>
                <input type="submit" name="submit" value="Submit" class="button">
                <input type="reset" value="Reset" class="button">
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
    else{
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/style.scss">
    <link rel="stylesheet" href="stylesheets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:wght@400;700&display=swap" rel="stylesheet">
    <title>Recipe Added!</title>
</head>
<body onload="onLoad()">
    <header>
    <img src="images/bannerImage.jpg" alt="Banner background image of food"id="bannerImage">
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
    <h3 class="title">Recipe Added!</h3>
        <p> Your recipe was successfuly added to the database!</p>
    </div>
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
</body>
</html>
<?php } ?>