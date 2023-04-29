<?php
    session_start();
    
    if( isset($_SESSION['validuser']) ){
        
    }

    else{
        header('Location: login.php');      
    }

    $recipeID = $_GET['recipeID'];    

    

    require 'recipeDbConnect.php';       

    $sql = "DELETE FROM recipes WHERE recipeID=:recipeID";   
    
    $stmt = $conn->prepare($sql);   
    $stmt->bindParam(':recipeID', $recipeID); 
    $stmt->execute();       
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    $sql = "DELETE FROM recipe_ingredients WHERE recipeID=:recipeID";   
    
    $stmt = $conn->prepare($sql);   
    $stmt->bindParam(':recipeID', $recipeID); 
    $stmt->execute();       
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    header('Location: adminSignOn.php');

?>