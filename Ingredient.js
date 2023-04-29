class Ingredient{
    //Properties
    recipeID;
    ingredientID;
    ingredientName;
    ingredientMeasurement;
    ingredientAmount;

    //Constructor
    constructor(){
        this.recipeID = 0;
        this.ingredientID = 0;
        this.ingredientName = "";
        this.ingredientMeasurement = "";
        this.ingredientAmount = "";
    }

    //Getters and Setters
    getRecipeID(){
        return this.recipeID;
    }
   
    setRecipeID(inRecipeID){
        this.recipeID = inRecipeID;
    }

    getIngredientID(){
        return this.ingredientID;
    }
   
    setIngredientID(inIngredientID){
        this.ingredientID = inIngredientID;
    }

    getIngredientName(){
        return this.ingredientName;
    }
   
    setIngredientName(inIngredientName){
        this.ingredientName = inIngredientName;
    }

    getIngredientAmount(){
        return this.ingredientAmount;
    }
   
    setIngredientAmount(inIngredientAmount){
        this.ingredientAmount = inIngredientAmount;
    }

    getIngredientMeasurement(){
        return this.ingredientMeasurement;
    }
   
    setIngredientMeasurement(inIngredientMeasurement){
        this.ingredientMeasurement = inIngredientMeasurement;
    }
}