class Recipe{
    //Properties
    recipeID;
    recipeName;
    recipeDifficulty;
    recipeServingSize;
    recipePrepTime;
    recipeInstructions;
    recipeImage;

    //Constructor
    constructor(){
        this.recipeID = 0;
        this.recipeName = "";
        this.recipeDifficulty = "";
        this.recipeServingSize = 0;
        this.recipePrepTime = "";
        this.recipeInstructions = "";
        this.recipeImage = "";

    }

    //Getters and Setters
    getRecipeID(){
        return this.recipeID;
    }
   
    setRecipeID(inRecipeID){
        this.recipeID = inRecipeID;
    }

    getRecipeName(){
        return this.recipeName;
    }
   
    setRecipeName(inRecipeName){
        this.recipeName = inRecipeName;
    }

    getRecipeServingSize(){
        return this.recipeServingSize;
    }
   
    setRecipeServingSize(inRecipeServingSize){
        this.recipeServingSize = inRecipeServingSize;
    }

    getRecipePrepTime(){
        return this.recipePrepTime;
    }
   
    setRecipePrepTime(inRecipePrepTime){
        this.recipePrepTime = inRecipePrepTime;
    }

    getRecipeDifficulty(){
        return this.recipeDifficulty;
    }
   
    setRecipeDifficulty(inRecipeDifficulty){
        this.recipeDifficulty = inRecipeDifficulty;
    }

    getRecipeInstructions(){
        return this.recipeInstructions;
    }
   
    setRecipeInstructions(inRecipeInstructions){
        this.recipeInstructions = inRecipeInstructions;
    }

    getRecipeImage(){
        return this.recipeImage;
    }
   
    setRecipeImage(inRecipeImage){
        this.recipeImage = inRecipeImage;
    }

    
}