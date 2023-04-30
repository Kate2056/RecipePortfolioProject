<?php
    session_start();
    
    if( isset($_SESSION['validuser']) ){
        
    }

    else{
        header('Location: index.php');      
    }

    $recipeID = $_GET['recipeID'];    

    
    try{
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

    //echo "<script>alert('The recipe was successfuly deleted.');</script>";

    header('Location: adminSignOn.php');
    }catch (PDOException $e){
        echo "Issues with progam, error message: " . $e->getMessage();
        //echo "<script>alert('There was an issue, the recipe was not deleted. Error: " . $e->getMessage() . "');</script>";
    }

?>