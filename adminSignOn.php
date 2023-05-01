<?php

    $date = date('Y');
    
    session_start();

    $errorMsg = ""; 
    $validuser = false;


    if(isset($_POST['submit'])){
        $inUsername = $_POST['inUsername'];
        $inPassword = $_POST['inPassword'];

        try{
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
    }catch (PDOException $e){
        $errorMsg = "Issues with progam, error message: " . $e->getMessage();;
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
<?php
        if($validuser){
            try{
            require 'recipeDbConnect.php';

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT recipeID, recipeName, TO_BASE64(recipeImageSrc) AS recipeImageSrc , recipeDifficulty, recipeServingSize, recipePrepTime, recipeDescription FROM recipes";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
    
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {          
                $data[] = $row;  
            }

            $fp = fopen('recipeData.json', 'w');
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fclose($fp);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT recipeID, ingredientID, ingredientMeasurement, ingredientAmount, ingredientName FROM recipe_ingredients";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
    
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {          
                $data[] = $row;  
            }

            $fp = fopen('recipeIngredients.json', 'w');
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
            fclose($fp);
        }catch (PDOException $e){
            $errorMsg = "Issues with progam, error message: " . $e->getMessage();;
        }
    ?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/style.scss">
    <link rel="stylesheet" href="stylesheets/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&display=swap" rel="stylesheet">
    <title>Recipe Home</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="Ingredient.js"></script>
    <script src="Recipe.js"></script>
    <script>

        const ingredients = [];
        const recipes = [];

        let selectedButton = 1;

        function changeIngredientSize(inRecipeIndex, inRecipeID, value){
            //alert("Inside changeIngredientSize");
            selectedButton = parseFloat(value);

            $.getJSON("recipeIngredients.json", function (data) {
                $.each(data, function (key, model) {
                    if(model.recipeID == inRecipeID){
                        let index = ingredients.findIndex(item => item.ingredientID === model.ingredientID);
                        let newIngredient = parseFloat(value) * model.ingredientAmount;
                        ingredients[index].setIngredientAmount(newIngredient);
                        getRecipe(inRecipeID);
                    }
                })
            });

            $.getJSON("recipeData.json", function (data) {
                $.each(data, function (key, model) {
                    if(model.recipeID == inRecipeID){
                        let newServingSize = parseFloat(value) * model.recipeServingSize;
                        recipes[inRecipeIndex].setRecipeServingSize(newServingSize);
                        getRecipe(inRecipeID);
                    }
                })
            });

        }
        
        function getRecipe(inRecipeID){
            let selectedRecipeIngredients = "";
            let selectedRecipe = "";

            for(let i = 0; i < ingredients.length; i++){
                let ingredient = ingredients[i];

                if(ingredient.getRecipeID() == inRecipeID){

                    if(ingredient.getIngredientMeasurement() == null){
                        let currentIngredient = '<li>' + ingredient.getIngredientAmount() + " "  + ingredient.getIngredientName() + "</li>";
                        selectedRecipeIngredients = selectedRecipeIngredients + currentIngredient;
                    }else{
                        let currentIngredient = '<li>' + ingredient.getIngredientAmount() + " "  + ingredient.getIngredientMeasurement() + " " + ingredient.getIngredientName() + "</li>";
                        selectedRecipeIngredients = selectedRecipeIngredients + currentIngredient;
                    }
                }
            }

            for(let i = 0; i < recipes.length; i++){
                let recipe = recipes[i];

                if(recipe.getRecipeID() == inRecipeID){
                    selectedRecipe = '<div class="recipeCard"><h4 class="recipeTitle">' + recipe.getRecipeName() + '</h4><img class="recipeImg" src="data:image/jpeg;base64, ' + recipe.getRecipeImage() + 
                        '" alt="Photo for ' + recipe.getRecipeName() + '"><p><strong>   Difficulty:</strong>  ' + recipe.getRecipeDifficulty() + '</p><p><strong> Serving Size:</strong>  ' + recipe.getRecipeServingSize() +
                        '</p><p><strong>  Prep Time:</strong>  ' + recipe.getRecipePrepTime() + 
                            '</p> <div id="ingredientSizeRadio"> <input type="radio" id="halfSize" name="ingredientSize" value="0.5" onchange="changeIngredientSize(' + i + ", " + recipe.getRecipeID() + 
                            ', this.value )"> <label for="ingredientSize">Half</label> <input type="radio" id="originalSize" name="ingredientSize" value="1" onchange="changeIngredientSize(' + i + ", " + recipe.getRecipeID() + 
                            ', this.value)"> <label for="ingredientSize">1X</label> <input type="radio" id="twoTimesSize" name="ingredientSize" value="2" onchange="changeIngredientSize(' + i + ", " + recipe.getRecipeID() + 
                            ', this.value)"> <label for="ingredientSize">2X</label> <input type="radio" id="threeTimesSize" name="ingredientSize" value="3" onchange="changeIngredientSize(' + i + ", " + recipe.getRecipeID() + 
                        ', this.value)"> <label for="ingredientSize">3X</label></div> <p><strong> Ingredients: </strong> </p> <ul>' 
                        + selectedRecipeIngredients + '</ul><p> <strong> Instructions: </strong> </p><div id="instructions"><p>' + recipe.getRecipeInstructions() + '</p></div></div><a id="deleteRecipeJS" href="deleteRecipe.php?recipeID=' + recipe.getRecipeID() + '"><button id="deleteRecipeButton">Delete Recipe</button></a>';
                    }
            }
            document.querySelector('#selectedRecipe').innerHTML = selectedRecipe;
            document.querySelector("#selectedRecipe").style.visibility = "visible";
            document.querySelector('#deleteRecipeJS').addEventListener("click", 
                function (event) {
                    event.preventDefault();
                    if (confirm('Are you sure you want to delete this recipe?')) {
                        window.location = this.href;
                    }
                    }, 
                false);

            const element = document.querySelector("#selectedRecipe");

            if(selectedButton == 0.5){
                document.querySelector("#halfSize").checked = true;
            }else{
                if(selectedButton == 1){
                    document.querySelector("#originalSize").checked = true;
                }else{
                    if(selectedButton == 2){
                        document.querySelector("#twoTimesSize").checked = true;
                    }else{
                        if(selectedButton == 3){
                            document.querySelector("#threeTimesSize").checked = true;
                        }
                    }
                }   
            }

            element.scrollIntoView();
        }
        


        function pageSetup(){

            let ingUrl = "recipeIngredients.json";

            $.getJSON(ingUrl, function (data) {
                $.each(data, function (key, model) {
                    let currentIngredient = new Ingredient();
                    currentIngredient.setIngredientID(model.ingredientID);
                    currentIngredient.setRecipeID(model.recipeID);
                    currentIngredient.setIngredientName(model.ingredientName);
                    currentIngredient.setIngredientMeasurement(model.ingredientMeasurement);
                    currentIngredient.setIngredientAmount(model.ingredientAmount);
                    ingredients.push(currentIngredient);
                })
            });
    
            $.getJSON("recipeData.json", function (data) {

                $.each(data, function (key, model) {
                    let currentRecipe = new Recipe();
                    currentRecipe.setRecipeID(model.recipeID);
                    currentRecipe.setRecipeName(model.recipeName);
                    currentRecipe.setRecipeDifficulty(model.recipeDifficulty);
                    currentRecipe.setRecipeServingSize(model.recipeServingSize);
                    currentRecipe.setRecipePrepTime(model.recipePrepTime);
                    currentRecipe.setRecipeInstructions(model.recipeDescription);
                    currentRecipe.setRecipeImage(model.recipeImageSrc);
                    recipes.push(currentRecipe);
                    console.log(recipes.length);
                })
            
            document.querySelector("#selectedRecipe").style.visibility = "hidden";
            
            let recipeSection = "";

            recipes.forEach(function(recipe, index){
        
                let currentRecipe = '<div class="recipeCard" onclick="getRecipe(' + recipe.getRecipeID() + ')"><img class="recipeImg" src="data:image/jpeg;base64, ' + recipe.getRecipeImage() + 
                    '" alt="Photo for ' + recipe.getRecipeName() + '"><h4 class="recipeTitle">' + recipe.getRecipeName() + 
                    '</h4><p><strong> Difficulty:</strong> ' + recipe.getRecipeDifficulty() + '</p><p><strong> Serving Size: </strong>  ' + recipe.getRecipeServingSize() +
                    '</p><p><strong> Prep Time:</strong> ' + recipe.getRecipePrepTime() + '</p></div>';

                recipeSection = recipeSection + currentRecipe;
    
                if(recipeSection == "" || recipeSection == null){
                    recipeSection = "No Available Recipes";
                }

                document.querySelector('#recipeBox').innerHTML = recipeSection;

            });
        });
    }
        
    </script>
</head>
<body onload='pageSetup()'>
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
    <div id="recipesContainer">
        <h3 class="title">All Recipies</h3>
        <span><?php echo $errorMsg; ?></span>
        <div id="recipeBox"></div>
    </div>
    <div id="selectedRecipe"></div>
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
    </body>
    </html>
    <?php
        }else{
            ?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="stylesheets/style.scss">
        <link rel="stylesheet" href="stylesheets/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:wght@400;700&display=swap" rel="stylesheet">
        <title>Recipe Home</title>
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
            <div id="signOnContainer">
                <h3 class="title">Admin Sign On</h3>
                <span><?php echo $errorMsg ?></span>
                <form method="POST" action="adminSignOn.php">
                    
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
                <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
            </footer>
        </body>
        </html>
        <?php
            }
        ?>