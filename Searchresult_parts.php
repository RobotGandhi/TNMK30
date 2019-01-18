<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>Results | Lego Finder</title>
        <meta name="description" content="A site where you can find your lego parts and see what set they're in!"/>
        <meta name="author" content="Viktor Carlsson, Uma Eriksson, Ruben Bromee, Jessie Chow, Alma Fernvik"/>
        <link rel="stylesheet" href="style.css" type="text/css"/>
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <script type="text/javascript" src="script.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        <a class="icon" href="Homepage_V2.php">
        <div class ="header">
        <h1>Lego finder</h1>
        </div>
        </a>
        
        <nav>
        <ul>
        <li><a href="Homepage_V2.php">Home</a></li>
        <li><a href="aboutus.php">About Us</a></li>
        </ul>
        </nav>
        
<?php
//Print an error message if no PartID is set.
if (!isset($_GET['PartID']) || empty($_GET['PartID'])) {
    die("<p>No PartID found!</p>");
} else {
    //Declaring variables
    $searchkey_breadcrumbs = $_GET['searchkey_breadcrumbs'];
    $pagenumber            = $_GET['pagenumber'];
    $offset                = ($pagenumber - 1) * 15;
    $previous_page         = $pagenumber - 1;
    $next_page             = $pagenumber + 1;
    $part_selected         = $_GET['PartID'];
    $prefix                = "http://www.itn.liu.se/~stegu76/img.bricklink.com/PL/";
    $prefix_colors         = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
                
    //Checking connection to the database
    $connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
    if (!$connection) {
        die("No connection to the lego database could be established.");
    }
    
    $result = mysqli_query($connection, "SELECT DISTINCT parts.PartID, parts.partname, images.ItemTypeID, images.ItemID, images.has_largegif, images.has_largejpg FROM parts, images WHERE parts.PartID = '$part_selected' AND images.ItemID=parts.PartID AND images.ItemTypeID='P'");
                
    if (isset($_GET['searchkey']) && !empty($_GET['searchkey'])) {
        $color_search      = $_GET['searchkey'];
        $result_colors     = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, colors.Colorname FROM inventory, colors WHERE inventory.ItemID='$part_selected' AND inventory.ColorID=colors.ColorID AND colors.Colorname LIKE '%$color_search%'");
        $amount_of_results = $result_colors->num_rows;
    } else {
        $result_colors         = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, colors.Colorname FROM inventory, colors WHERE inventory.ItemID='$part_selected' AND inventory.ColorID=colors.ColorID");
        //Räkna totalt antal resultat och antal resultatssidor
        $amount_of_results     = $result_colors->num_rows;
        $amount_of_resultpages = ceil($amount_of_results / 15);
        //Query for each page visible in the result
        $result_colors         = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, colors.Colorname FROM inventory, colors WHERE inventory.ItemID='$part_selected' AND inventory.ColorID=colors.ColorID LIMIT 15 OFFSET $offset");
    }
    //If no image is found for the part you have selected then only the PartID and the Partname is shown 
    if ($result->num_rows == 0) {
        $result = mysqli_query($connection, "SELECT DISTINCT PartID, partname FROM parts WHERE PartID = '$part_selected'");
    }
    $row      = mysqli_fetch_array($result);
    $Partname = $row['partname'];
    //Printing breadcrumbs
    echo "<div class='breadcrumbs'>
    <a href='Homepage_V2.php'> Home </a> /
    <a href='Homepage_V2.php?searchkey=$searchkey_breadcrumbs&pagenumber=1'>Searchresult of \"$searchkey_breadcrumbs\" </a> /
    $Partname
    </div>";
    //Printing the part you have chosen
    echo "<div class='content'>
    <table>\n<tr>
    <th>Image</th> <th>PartID</th> <th>Partname</th>
    </tr>\n";
                
    $PartID = $row['PartID'];
                
    $Imagesource = $prefix . $PartID;
    if ($row['has_largejpg']) {
        $Imagesource .= ".jpg";
        $Imageexists = true;
    } else if ($row['has_largegif']) {
        $Imagesource .= ".gif";
        $Imageexists = true;
    } else {
        $Imageexists = false;
    }
    echo "<tr>";
    if ($Imageexists)
        echo "<td> <img src=\"$Imagesource\" alt='No image found!'></td>";
    else
        echo "<td> <img src=\"No_image_available.svg\" alt='No image found!'></td>";
    echo "<td>$PartID</td> <td>$Partname</td>
    </tr>        
    </table>
    </div>";        
                
    //Form for filtering colors
    echo "<div class='searchdiv'>
    <form action='Searchresult_parts.php' method='get'>
    <input class='searchbar' type='text' name='searchkey' placeholder='Filter colors' size='40'>
    <input type='hidden' name='PartID' value='$part_selected'>
    <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
    <button class='button' type='submit'> Filter </button>
    </form>
    </div>";
                
    if (isset($_GET['searchkey'])) {
        echo "<a href='Searchresult_parts.php?PartID=$PartID&pagenumber=1&searchkey_breadcrumbs=$searchkey_breadcrumbs'> Back to all colors. </a> <br>";
    }
    if ($amount_of_results != 0) {
        //Print all the available colors for the selected part
        echo "<div class='content'>
        <h1 class='informational_headers'>Available colors:</h1>
        <p class='results'>Showing $amount_of_results results</p>
        <table>
        <tr> <th>Image</th> <th>Partname</th> <th>Colorname</th> </tr>";
        
        while ($row_colors = mysqli_fetch_array($result_colors)) {
        $Colorname          = $row_colors['Colorname'];
        $ColorID            = $row_colors['ColorID'];
        $Imagesource_colors = $prefix_colors . "/" . $ColorID . "/" . $part_selected;
        //FixImage(): If no gif file is found search for a jpg file. If no jpg image is found, replace the image with a placeholder.
        //Print a link to the selected color of the selected part
        echo "<tr>
        <td><img class='smallimages' src='$Imagesource_colors.gif' onerror='this.src=FixImage(this)' alt='No image avaliable!'>
        <td>$Partname</td>
        <td><a href='searchresult_colors.php?ItemID=" . $part_selected . "&ColorID=" . $ColorID . "&pagenumber=1&searchkey_breadcrumbs=" . $searchkey_breadcrumbs . "'> $Colorname </a> </td>
        </tr>";
        }
        echo "</table>";
        /*Print only a "Next page" button if you're on page 1. Print both a "Next page" and a "Previous page" button if you're on any page between the first and the last. 
        Print only a "Next page" button if you're on the last page. */
        if ($amount_of_resultpages == 1 || $amount_of_resultpages == null) { } 
        else if ($pagenumber != 1 && $pagenumber != $amount_of_resultpages) {
            echo "<div class='pages'>
            <form action='Searchresult_parts.php' method='get'>\n
            <input type='hidden' name='PartID' value='$PartID'>
            <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
            <button  class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
            </form>
            $pagenumber/$amount_of_resultpages
            <form action='Searchresult_parts.php' method='get'>\n
            <input type='hidden' name='PartID' value='$PartID'>
            <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
            <button class='pagebuttons' type='submit' name='pagenumber' value='$next_page'>Next page</button>
            </form>
            </div>";
        } else if ($pagenumber == $amount_of_resultpages) {
            echo "<div class='pages'>
            <form action='Searchresult_parts.php' method='get'>\n
            <input type='hidden' name='PartID' value='$PartID'>
            <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
            <button class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
            </form>
            $pagenumber/$amount_of_resultpages
            </div>";
        } else {
            echo "<div class='pages'>
            $pagenumber/$amount_of_resultpages
            <form action='Searchresult_parts.php' method='get'>\n
            <input type='hidden' name='PartID' value='$PartID'>
            <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
            <button class='pagebuttons' type='submit' name='pagenumber' value='$next_page'>Next page</button>
            </form>
            </div>";
        }
    } else {
        print("This part is not available in the color \"$color_search\". Try going back and checking for misspellings in your filtering.");
    }
echo "</div>";
}
?>
<?php include("footer.txt"); ?>
    </body>
</html>