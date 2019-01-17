<!DOCTYPE html>
<?php
	error_reporting(E_ALL);
	ini_set("display_errors",1);
?>
<html>
<head>
        <meta charset="UTF-8"/>
        <title>Homepage</title>
        <meta name="description" content="A site where you can find your lego parts and see what set they're in!"/>
        <meta name="author" content="Viktor Carlsson, Uma Eriksson, Ruben Bromee, Jessie Chow, Alma Fernvik"/>
        <link rel="stylesheet" href="proj.css" type="text/css"/>
		<link href="https://fonts.googleapis.com/css?family=Bree+Serif" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<script type="text/javascript" src="script.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
	<div class="wrapper">
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
	$pagenumber = $_GET['pagenumber'];
	$offset = ($pagenumber-1) * 15;
	$previous_page = $pagenumber - 1;
	$next_page = $pagenumber + 1;
    $part_selected = $_GET['PartID'];
    $prefix        = "http://www.itn.liu.se/~stegu76/img.bricklink.com/PL/";
    $prefix_colors = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
	
	//Checking connection to the database
    $connection    = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
    if (!$connection) {
        die("No connection to the lego database could be established.");
    }
    
	
    $result        = mysqli_query($connection, "SELECT DISTINCT parts.PartID, parts.partname, images.ItemTypeID, images.ItemID, images.has_largegif, images.has_largejpg FROM parts, images WHERE parts.PartID = '$part_selected' AND images.ItemID=parts.PartID AND images.ItemTypeID='P'");
    
    if (isset($_GET['searchkey']) && !empty($_GET['searchkey'])) {
    $color_search = $_GET['searchkey'];
    $result_colors = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, colors.Colorname FROM inventory, colors WHERE inventory.ItemID='$part_selected' AND inventory.ColorID=colors.ColorID AND colors.Colorname LIKE '%$color_search%'");
	$amount_of_results = $result_colors->num_rows;
    } else {
	$result_colors = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, colors.Colorname FROM inventory, colors WHERE inventory.ItemID='$part_selected' AND inventory.ColorID=colors.ColorID");
	//Räkna totalt antal resultat och antal resultatssidor
	$amount_of_results = $result_colors->num_rows;
	$amount_of_resultpages = ceil($amount_of_results/15);
	//Query for each page visible in the result
	$result_colors = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, colors.Colorname FROM inventory, colors WHERE inventory.ItemID='$part_selected' AND inventory.ColorID=colors.ColorID LIMIT 15 OFFSET $offset");
    }
	//If no image is found for the part you have selected then only the PartID and the Partname is shown 
    if ($result->num_rows == 0) {
        $result = mysqli_query($connection, "SELECT DISTINCT PartID, partname FROM parts WHERE PartID = '$part_selected'");
    }
	$row = mysqli_fetch_array($result);
	$Partname    = $row['partname'];
	//Printing breadcrumbs
	print("<div class=\"breadcrumbs\">");
	print("<a href=\"Homepage_V2.php\"> Home </a>");
	print("/");
	print("<a href='Homepage_V2.php?searchkey=$searchkey_breadcrumbs&pagenumber=1'>Searchresult of \"$searchkey_breadcrumbs\" </a>");
	print("/");
	print("$Partname");
	print("</div>");
	//Printing the part you have chosen
	print("<div class=\"content\">");
    print("<table>\n<tr>");
    print("<th>Image</th> <th>PartID</th> <th>Partname</th> ");
    print("</tr>\n");
    
        $PartID      = $row['PartID'];
        
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
        print("<tr>");
        if ($Imageexists)
            print("<td> <img src=\"$Imagesource\" alt='No image found!'></td>");
        else
            print("<td> <img src=\"No_image_available.svg\" alt='No image found!'></td>");
        print("<td>$PartID</td> <td>$Partname</td>");
        print("</tr>");
    
    print("</table>");
    print("</div>");
	
    
   //Form for filtering colors
    print("<div class='searchdiv'>");
    print("<form action='Searchresult_parts.php' method='get'>");
    print("<input class='searchbar' type='text' name='searchkey' placeholder='Filter colors' size='40'>");
    print("<input type='hidden' name='PartID' value='$part_selected'>");
	print("<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>");
	print("<button class='button' type='submit'> Filter </button>");
    print("</form>");
    print("</div>");
    
	if($amount_of_results != 0) {
	//Print all the available colors for the selected part
    print("<div class='content'>");
	print("<h1 class='informational_headers'>Available colors:</h1>");
    print("<table>\n<tr>");
    print("<th>Image</th> <th>Partname</th> <th>Colorname</th>");
    print("</tr>");
    while ($row_colors = mysqli_fetch_array($result_colors)) {
        $Colorname          = $row_colors['Colorname'];
        $ColorID            = $row_colors['ColorID'];
        $Imagesource_colors = $prefix_colors . "/" . $ColorID . "/" . $part_selected;
        print("<tr>");
		//If no gif file is found search for a jpg file
        print("<td><img class='smallimages' src='$Imagesource_colors.gif' onerror='this.src=FixImage(this)' alt='No image avaliable!'>");
        print("<td>$Partname</td>");
		//Print a link to the selected color of the selected part
        print("<td><a href='searchresult_colors.php?ItemID=" . $part_selected . "&ColorID=" . $ColorID . "&pagenumber=1&searchkey_breadcrumbs=".$searchkey_breadcrumbs."'> $Colorname </a> </td>");
        print("</tr>");
    }
    print("</table>");
    
	
	/*Print only a "Next page" button if you're on page 1. Print both a "Next page" and a "Previous page" button if you're on any page between the first and the last. 
	Print only a "Next page" button if you're on the last page. */
	if($amount_of_resultpages == 1 || $amount_of_resultpages == null) {}
	else if($pagenumber != 1 && $pagenumber != $amount_of_resultpages) {
		print("<div class='pages'>");
		echo "<form action='Searchresult_parts.php' method='get'>\n";
		echo"<input type='hidden' name='PartID' value='$PartID'>"; 
		print("<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>");
		//echo"<button type='submit' name='pagenumber' value='$next_page'>Next page</button>";
		echo"<button  class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>";
		echo"</form>";
		echo "$pagenumber/$amount_of_resultpages";
		echo "<form action='Searchresult_parts.php' method='get'>\n";
		echo"<input type='hidden' name='PartID' value='$PartID'>";
		print("<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>");
		//echo"<button type='submit' name='pagenumber' value='$next_page'>Next page</button>";
		echo"<button class='pagebuttons' type='submit' name='pagenumber' value='$next_page'>Next page</button>";
		echo"</form>";
		print("</div>");
	} else if($pagenumber == $amount_of_resultpages) {
		print("<div class='pages'>");
		echo "<form action='Searchresult_parts.php' method='get'>\n";
		echo"<input type='hidden' name='PartID' value='$PartID'>"; 
		print("<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>");
		//echo"<button type='submit' name='pagenumber' value='$next_page'>Next page</button>";
		echo"<button class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>";
		echo"</form>";
		echo"$pagenumber/$amount_of_resultpages";
		print("</div>");
	} else {
		print("<div class='pages'>");
		echo "$pagenumber/$amount_of_resultpages";
		echo "<form action='Searchresult_parts.php' method='get'>\n";
		echo"<input type='hidden' name='PartID' value='$PartID'>";	
		print("<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>");
		//echo"<button type='submit' name='pagenumber' value='$next_page'>Next page</button>";
		echo"<button class='pagebuttons' type='submit' name='pagenumber' value='$next_page'>Next page</button>";
		echo"</form>";
		print("</div>");
	}
	}
	else{
		print("This part is not available in the color \"$color_search\". Try going back and checking for misspellings in your filtering.");
		print("<br>");
		print("<a href='Searchresult_parts.php?PartID=".$part_selected."&pagenumber=1&searchkey_breadcrumbs=".$searchkey_breadcrumbs."'>Back</a>");
	}
	print("</div>");

}
?>
   
  
    
    <!-- wrapper content div closing tag  -->
    </div> 
	<?php include("footer.txt");?>
</body>

</html>