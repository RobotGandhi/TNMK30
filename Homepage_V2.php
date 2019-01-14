
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
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
	<div class="wrapper">
    <div class ="header">
       
        <h1>Lego finder</h1>
		<h2>Search for a lego part and see what set it's in</h2>
		</div>
		
		<nav>

<ul>
<li><a href="Homepage_V2.php">Home</a></li>
<li><a href="howtosearch.php">How To Search</a></li>
<li><a href="aboutus.php">About Us</a></li>
</ul>

</nav>
        
        <!-- Sökruta -->
        <div class="searchdiv">
        <form class="formbitch" action="Homepage_V2.php" method="get" >
        <input class="searchbar" type="text" name="searchkey" placeholder="Search for a lego part using name or PartID" size="40">
		<input type="hidden" name="pagenumber" value="1">
		<button class ="button" type="submit"> Search </button> 
        
        </form>
        </div>
        
        <!-- Sökresultat efter string management -->
        
        <?php
if(isset($_GET['searchkey']) && $_GET['searchkey'] != NULL) {
	$searchkey = $_GET['searchkey'];
	$pagenumber = $_GET['pagenumber'];
	$offset = ($pagenumber-1) * 15;
	$previous_page = $pagenumber - 1;
	$next_page = $pagenumber + 1;
	$prefix        = "http://www.itn.liu.se/~stegu76/img.bricklink.com/PL/";
	$prefix_colors = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
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
      
        
        <div class="content">

            <?php
	$connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
	if (!$connection) {
		die("No connection to the lego database could be established.");
	}
	$searchkey = mysqli_real_escape_string($connection, $searchkey);
	$result = mysqli_query($connection, "SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '%$searchkey%' OR parts.PartID LIKE '%$searchkey%') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC");
	$amount_of_results = 0;
	while(mysqli_fetch_array($result)) {
		$amount_of_results ++;
	}
 
	if($amount_of_results != 0) {
		$amount_of_resultpages = ceil(($amount_of_results/15));

		$visible_result = mysqli_query($connection, "SELECT DISTINCT parts.PartID, parts.CatID, parts.Partname, inventory.ItemID FROM parts, inventory WHERE (parts.partname LIKE '%$searchkey%' OR parts.PartID LIKE '%$searchkey%') AND parts.PartID=inventory.ItemID ORDER BY length(CatID), CatID, partname ASC LIMIT 15 OFFSET $offset");
		echo"<table>\n<tr>
<th>Image </th> <th>PartID</th> <th>Partname</th>
</tr>\n";
		while ($row = mysqli_fetch_array($visible_result)) {
			$PartID   = $row['PartID'];
			$Partname = $row['Partname'];
			$Imagesource = $prefix . $PartID;
			echo"<tr>
			<td><img src='$Imagesource.gif' onerror='this.onerror=null;this.src=\"$Imagesource.jpg\"' alt='No image avaliable!'> </td>
			<td>$PartID</td>
			<td><a href=\"Searchresult_parts.php?PartID=" . $PartID . "\">$Partname</a></td>
			</tr>";
		}
		print("</table>");
		if($pagenumber != 1 && $pagenumber != $amount_of_resultpages) {
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
			echo "$pagenumber/$amount_of_resultpages";
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button type='submit' name='pagenumber' value='$next_page'>Next page</button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
		} else if($pagenumber == $amount_of_resultpages) {
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
			echo"$pagenumber/$amount_of_resultpages";
		}
		else {
			echo "$pagenumber/$amount_of_resultpages";
			echo "<form action='Homepage_V2.php' method='get'>\n
			<button type='submit' name='pagenumber' value='$next_page'>Next page</button>
			<input type='hidden' name='searchkey' value='$searchkey'>";
		}
	} else {
		print(" <p> Sorry, no results were found for \"$searchkey\". Try checking for misspellings and try again. <br>
		If you're unsure how to search. Click the \"How to search\" button at the top of your screen. </p> ");
	}
}
?>


</form> 
       </div>
        
        <div class="footer">
            <p>This is the footer</p>
			<p>Contact</p>
<p>Email: questions@liu.se</p>
<p>Phone: 013 28 10 00</p>
        </div>
		</div>
    </body>
</html>