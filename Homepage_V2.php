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
<li><a href="howtosearch.php">How To Search</a></li>
<li><a href="aboutus.php">About Us</a></li>
</ul>

</nav>
        
        <!-- Sökruta -->
        <div class="searchdiv">
        <form action="Homepage_V2.php" method="get" >
        <input class="searchbar" type="text" name="searchkey" placeholder="Search for a lego part using name or PartID" size="40">
		<input class ="pagenumber" type="hidden" name="pagenumber" value="1">
		<button class ="button" type="submit"> Search </button> 
        </form>
        </div>
		
		<div class="content">
        
        <!-- Sökresultat efter string management -->
        
        <?php
if(isset($_GET['searchkey']) && $_GET['searchkey'] != NULL) {
	//Declaring variables
	$searchkey = $_GET['searchkey'];
	$pagenumber = $_GET['pagenumber'];
	$offset = ($pagenumber-1) * 15;
	$previous_page = $pagenumber - 1;
	$next_page = $pagenumber + 1;
	$prefix        = "http://www.itn.liu.se/~stegu76/img.bricklink.com/PL/";
	$prefix_colors = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
	
	//Search key string management and formatting
	trim($searchkey);
	$searchkey = htmlentities($searchkey);
	$searchkeyarray = str_split($searchkey);
	for ($i = 0; $i < count($searchkeyarray); $i++) {
		if ($searchkeyarray[$i] == 'x') {
			if (is_numeric($searchkeyarray[$i + 1])) {
				array_splice($searchkeyarray, $i + 1, 0, ' ');
			}
			if (is_numeric($searchkeyarray[$i - 1])) {
				array_splice($searchkeyarray, $i, 0, ' ');
				$i++;
			}
		}
	}
	$searchkey = "";
	for ($i = 0; $i < count($searchkeyarray); $i++) {
		$searchkey .= $searchkeyarray[$i];
	}
?>
      
        

            <?php
			//Checking connection to database
	$connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
	if (!$connection) {
		die("No connection to the lego database could be established.");
	}
	//Preventing sql injections
	$searchkey = mysqli_real_escape_string($connection, $searchkey);
	
	//Counting amount of total results
	$result = mysqli_query($connection, "(SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '$searchkey' OR parts.PartID LIKE '$searchkey') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC) UNION (SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '$searchkey%' OR parts.PartID LIKE '$searchkey%') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC) UNION (SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '%$searchkey%' OR parts.PartID LIKE '%$searchkey%') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC) ");
	$amount_of_results = $result->num_rows;
	

//SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '%$searchkey%' OR parts.PartID LIKE '%$searchkey%') AND parts.PartID=inventory.ItemID

	//Show results if there are any
	if($amount_of_results != 0) {
		$amount_of_resultpages = ceil(($amount_of_results/15));
		//Query for each page visible in the result
		$visible_result = mysqli_query($connection, "(SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '$searchkey' OR parts.PartID LIKE '$searchkey') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC) UNION (SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '$searchkey%' OR parts.PartID LIKE '$searchkey%') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC) UNION (SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '%$searchkey%' OR parts.PartID LIKE '%$searchkey%') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC)  LIMIT 15 OFFSET $offset");
		//Print the current resultpage
		echo"<table>\n<tr>
		<th>Image </th> <th>PartID</th> <th>Partname</th>
		</tr>\n";
		$searchkey = str_replace(" ","_",$searchkey);
		while ($row = mysqli_fetch_array($visible_result)) {
			$PartID   = $row['PartID'];
			$Partname = $row['Partname'];
			$Imagesource = $prefix . $PartID;
			//If no gif file is found, try finding a jpg file. Then print the PartID. Then print a link to the part which has the current PartID.
			echo"<tr>
			<td><img src='$Imagesource.gif' onerror='this.src=FixImage(this)' alt='No image avaliable!'> </td>
			<td>$PartID</td>
			<td><a href=\"Searchresult_parts.php?PartID=" . $PartID . "&pagenumber=1&searchkey_breadcrumbs=".$searchkey."\">$Partname</a></td>
			</tr>";
		}
		print("</table>");
		
		
		/*Print only a "Next page" button if you're on page 1. Print both a "Next page" and a "Previous page" button if you're on any page between the first and the last. 
		Print only a "Next page" button if you're on the last page. */ 
		if($amount_of_resultpages == 1) {}
		else if($pagenumber != 1 && $pagenumber != $amount_of_resultpages) {
			print("<div class='pages'>");
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
			echo"</form> ";
			echo "$pagenumber/$amount_of_resultpages";
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button class='pagebuttons'  type='submit' name='pagenumber' value='$next_page'>Next page</button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
			echo"</form> ";
			print("</div>");
		} else if($pagenumber == $amount_of_resultpages) {
			print("<div class='pages'>");
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
			echo"$pagenumber/$amount_of_resultpages";
			echo"</form> ";
			print("</div>");
		}
		else {
			print("<div class='pages'>");
			echo "$pagenumber/$amount_of_resultpages";
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button  class='pagebuttons' type='submit' name='pagenumber' value='$next_page'>Next page</button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
			echo"</form> ";
			print("</div>");
		}
	} else {
		//Print an error message if no searchresults were found.
		print(" <p> Sorry, no results were found for \"$searchkey\". Try checking for misspellings and try again. <br>
		If you're unsure how to search. Click the \"How to search\" button at the top of your screen. </p> ");
	}
}
else {
	 print(" 
			<div class='informational_text'>
            <h1>How to search:</h1>
			<p><h2>Step 1:</h2> Search for the type of part you're looking for by typing in either its name or its PartID into the searchbar.  
			Then press either the \"Search\" button with your mouse or press the \"Enter\" button on your keyboard</p>
			<p><h2>Step 2:</h2> Choose the type of part you're looking by clicking the partname with your mouse.</p>
			<p><h2>Step 3:</h2> Choose the color of the part you have chosen. You can filter the colors by typing any available color in the filter bar and either pressing the \"Filter\" button with your mouse or the \"Enter\" button on your keyboard.</p>
			<p>You can navigate back to the type of part you have chosen by using the breadcrumb menu that appears at the upper part of your screen. You can navigate to the homepage at any time by pressing the \"Home\" button in either the breadcrumb menu, the header menu or by pressing the red lego brick at the top of your screen. 
			</p>
			</div>
         ");
}


?>

</div>



      <?php include("footer.txt");?>
		</div>
    </body>
</html>