<?php

    $date = date('Y');
    
    if(isset($_POST['submit']) && $_POST['phone'] == ""){
           

        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $fullDate = date_create();
        
        $fromEmail = "kaitlynbriggs@kaitlynbriggs.name";
        $headers = "MIME-Version: 1.0" . "\r\n"; 
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
	    $headers .= "From: $fromEmail" . "\r\n";

        $emailContent = "We recieved your message from our Recipe Manager site.";

        $contactEmailMessage = "New Email Contact Form Entry: ";
        $contactEmailMessage .= "Contact Name: " . $name;
        $contactEmailMessage .= "Email Address: " . $email;
        $contactEmailMessage .= "Message: " . $message;
        $contactEmailMessage .= "Date of Response: " . date_format($fullDate, "m/d/Y");


        mail($email, "Your message was recieved!", $emailContent, $headers);
        mail("kaitlynbriggs99@gmail.com", "New Contact Form Response", $contactEmailMessage, $headers);

    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylesheets/styles.scss">
    <link rel="stylesheet" href="stylesheets/styles.css">
    <title>Thanks!</title>
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
    <div id="contactContainer">
        <h3 class="title">Thank you for your message!</h3>
        <p>We recieved your message and should contact you soon!</p>
    </div>
    <footer>
        <p>Copyright © <?php echo $date; ?> Recipe Manager</p>
    </footer>
</body>
</html>
    <?php
    } else{
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
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="adminSignOn.php">Admin Sign-On</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <div id="contactContainer">
        <h3 class="title">Contact</h3>
        <form action="contact.php" method="POST">
            <p>
                <label for="name">Name: </label>
                <input name="name" id="name" type="text">
            </p>
            <p>
                <label for="email">Email: </label>
                <input name="email" id="email" type="email">
            </p>
            <p class="hide">
                <label for="phone">Phone: </label>
                <input name="phone" id="phone" type="text" value="">
            </p>
            <p>
                <label for="message">Message: </label>
                <textarea name="message" id="message"></textarea>
            </p>
            <p>
                <input type="submit" name="submit" value="Submit">
                <input type="reset" value="Reset">
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