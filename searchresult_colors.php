<!DOCTYPE html>
<?php
	error_reporting(E_ALL);
	ini_set("display_errors",1);
?>
<html>  
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="proj.css" type="text/css"/>
    <title>Search result colors</title>
	<link href="https://fonts.googleapis.com/css?family=Bree+Serif" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<div class="wrapper">
<div class ="header">
        <h1>This is the header</h1>
        </div>
		<nav>

<ul>
<li><a href="Homepage_V2.php">Home</a></li>
<li><a href="howtosearch.php">How To Search</a></li>
<li><a href="aboutus.php">About Us</a></li>
</ul>

</nav>
<?php
$pagenumber = $_GET['pagenumber'];
$offset = ($pagenumber-1) * 15;
var_dump($offset);
$previous_page = $pagenumber - 1;
$next_page = $pagenumber + 1;
$ItemID        = $_GET['ItemID'];
$ColorID       = $_GET['ColorID'];
$prefix_colors = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
$connection    = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
if (!$connection) {
	die("No connection to the lego database could be established.");
}

$result_sets = mysqli_query($connection, "SELECT inventory.SetID, inventory.ItemID, inventory.ColorID, sets.SetID, sets.Setname, sets.Year FROM inventory, sets WHERE inventory.ItemID='$ItemID' AND inventory.ColorID='$ColorID' AND inventory.SetID=sets.SetID");

$result_sets_visible = mysqli_query($connection, "SELECT inventory.SetID, inventory.ItemID, inventory.ColorID, sets.SetID, sets.Setname, sets.Year FROM inventory, sets WHERE inventory.ItemID='$ItemID' AND inventory.ColorID='$ColorID' AND inventory.SetID=sets.SetID LIMIT 15 OFFSET $offset");

$amount_of_results = 0;

while(mysqli_fetch_array($result_sets))
{
	$amount_of_results ++;
}
$amount_of_resultpages = ceil(($amount_of_results/15));

$result_part = mysqli_query($connection, "SELECT colors.ColorID, colors.Colorname, parts.PartID, parts.Partname FROM colors, parts WHERE colors.ColorID='$ColorID' AND parts.PartID='$ItemID'");

$row_part = mysqli_fetch_array($result_part);
$Partname = $row_part['Partname'];
$Colorname = $row_part['Colorname'];
print("<div class=\"breadcrumbs\">");
print("<a href=\"Homepage_V2.php\"> Home </a>");
print("/");
print("<a href=\"Searchresult_parts.php?PartID=" . $ItemID . "\">$Partname</a>");
print("/");
print("$Colorname");
print("</div>");
print("<div class=\"content\">");
print("<table>");
print("<tr>");
print("<th>Image</th> <th> Partname </th> <th> Colorname </th>");
print("</tr>");
print("<tr>");
print("<td><img src='$prefix_colors/$ColorID/$ItemID.gif' onerror='this.onerror=null;this.src=\"$prefix_colors/$ColorID/$ItemID.jpg\"'></td> <td>" . $Partname . "</td> <td>" . $Colorname . "</td>");
print("</tr>");
print("</table>");
print("</div>");
print("<div class=\"content\">");
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
print("</div>");
if($pagenumber != 1 && $pagenumber != $amount_of_resultpages)
{
echo" 
<form action='searchresult_colors.php' method='get'>
<input type='hidden' name='ItemID' value='$ItemID'>
<input type='hidden' name='ColorID' value='$ColorID'>
<button type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
</form>

";
echo"$pagenumber";
echo"/";
echo"$amount_of_resultpages";
echo
"
<form action='searchresult_colors.php' method='get'>
<input type='hidden' name='ItemID' value='$ItemID'>
<input type='hidden' name='ColorID' value='$ColorID'>
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
<button type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
</form>
";
echo"$pagenumber";
echo"/";
echo"$amount_of_resultpages";
}
else
{
echo"$pagenumber";
echo"/";
echo"$amount_of_resultpages";
echo
"
<form action='searchresult_colors.php' method='get'>
<input type='hidden' name='ItemID' value='$ItemID'>
<input type='hidden' name='ColorID' value='$ColorID'>
<button type='submit' name='pagenumber' value='$next_page'>Next page</button>
</form>
";
}

?>
</div>
</body>
</html>