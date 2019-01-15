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
<?php
//Declaring variables
$searchkey_breadcrumbs = $_GET['searchkey_breadcrumbs'];
$pagenumber = $_GET['pagenumber'];
$offset = ($pagenumber-1) * 15;
$previous_page = $pagenumber - 1;
$next_page = $pagenumber + 1;
$ItemID        = $_GET['ItemID'];
$ColorID       = $_GET['ColorID'];
$prefix_colors = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
//Checking connection to database
$connection    = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
if (!$connection) {
	die("No connection to the lego database could be established.");
}

//Query for total amount of results
$result_sets = mysqli_query($connection, "SELECT inventory.SetID, inventory.ItemID, inventory.ColorID, sets.SetID, sets.Setname, sets.Year FROM inventory, sets WHERE inventory.ItemID='$ItemID' AND inventory.ColorID='$ColorID' AND inventory.SetID=sets.SetID");

//Query for each page visible in the result
$result_sets_visible = mysqli_query($connection, "SELECT inventory.SetID, inventory.ItemID, inventory.ColorID, sets.SetID, sets.Setname, sets.Year FROM inventory, sets WHERE inventory.ItemID='$ItemID' AND inventory.ColorID='$ColorID' AND inventory.SetID=sets.SetID ORDER BY sets.Setname ASC LIMIT 15 OFFSET $offset");

//Counting total amount of results
$amount_of_results = $result_sets->num_rows;

$amount_of_resultpages = ceil(($amount_of_results/15));

//Query for information about the part you have chosen
$result_part = mysqli_query($connection, "SELECT colors.ColorID, colors.Colorname, parts.PartID, parts.Partname FROM colors, parts WHERE colors.ColorID='$ColorID' AND parts.PartID='$ItemID'");

//Displaying information about the part you have chosen
$row_part = mysqli_fetch_array($result_part);
$Partname = $row_part['Partname'];
$Colorname = $row_part['Colorname'];
print("<div class=\"breadcrumbs\">");
print("<a href=\"Homepage_V2.php\"> Home </a>");
print("/");
print("<a href='Homepage_V2.php?searchkey_breadcrumbs=$searchkey_breadcrumbs&pagenumber=1'>Searchresult of \"$searchkey_breadcrumbs\" </a>");
print("/");
print("<a href=\"Searchresult_parts.php?PartID=" . $ItemID . "&pagenumber=1&searchkey_breadcrumbs=$searchkey_breadcrumbs\">$Partname</a>");
print("/");
print("$Colorname");
print("</div>");
print("<div class=\"content\">");
print("<table>");
print("<tr>");
print("<th>Image</th> <th> Partname </th> <th> Colorname </th>");
print("</tr>");
print("<tr>");
print("<td><img src='$prefix_colors/$ColorID/$ItemID.gif' onerror='this.src=FixImage(this)' alt='No image available!'></td> <td>" . $Partname . "</td> <td>" . $Colorname . "</td>");
print("</tr>");
print("</table>");
print("</div>");
print("<div class=\"content\">");

//Displaying information about the sets the chosen part is included in
print("<div>");
print("<h1 class='informational_headers'>Sets this part is included in:</h1>");

print("<table>");
print("<tr>");
print("<th>SetID</th> <th>Setname</th> <th>Year</th>");
print("</tr>");
while ($row = mysqli_fetch_array($result_sets_visible)) {
				$SetID   = $row['SetID'];
                $Setname = $row['Setname'];
                $Year    = $row['Year'];
                print("<tr>");
                print("<td>$SetID</td>");
                print("<td>$Setname</td>");
                print("<td>$Year</td>");
                print("</tr>");
}
print("</table>");
if($amount_of_resultpages == 1 || $amount_of_resultpages == null) {}
else if($pagenumber != 1 && $pagenumber != $amount_of_resultpages)
{
echo" 
<form action='searchresult_colors.php' method='get'>
<input type='hidden' name='ItemID' value='$ItemID'>
<input type='hidden' name='ColorID' value='$ColorID'>
<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
<button type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
</form>
";
echo"$pagenumber/$amount_of_resultpages";
echo"
<form action='searchresult_colors.php' method='get'>
<input type='hidden' name='ItemID' value='$ItemID'>
<input type='hidden' name='ColorID' value='$ColorID'>
<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
<button type='submit' name='pagenumber' value='$next_page'>Next page</button>
</form>
";

}
else if($pagenumber == $amount_of_resultpages)
{
	echo" 
<form action='searchresult_colors.php' method='get'>
<input type='hidden' name='ItemID' value='$ItemID'>
<input type='hidden' name='ColorID' value='$ColorID'>
<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
<button type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
</form>
";
echo"$pagenumber/$amount_of_resultpages";
}
else
{
echo"$pagenumber/$amount_of_resultpages";
echo
"
<form action='searchresult_colors.php' method='get'>
<input type='hidden' name='ItemID' value='$ItemID'>
<input type='hidden' name='ColorID' value='$ColorID'>
<input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
<button type='submit' name='pagenumber' value='$next_page'>Next page</button>
</form>
";
}
print("</div>");

?>
</div>
</div>
   <?php include("footer.txt");?>
</body>
</html>