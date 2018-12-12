<!DOCTYPE html>

<html>  
    <head>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="proj.css" type="text/css"/>
    </head>
    <body>
        <div class ="header">
            <h1>This is the header</h1>
        </div>
        
        <div class= "content">
            <?php
            $connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
            if (!$connection){
                die("No connection to the lego database could be established.");
            }
            print("Yey");
            ?>
        </div>
        
        <div class = "footer">
            <p>This is the footer</p>
        </div>
    </body>
</html>