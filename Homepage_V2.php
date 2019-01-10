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
        <div class="searchbar">
        <form action="Homepage_V2.php" method="get" >
        <table class ="searchtable">
        <tr><td> <input class="search" type="text" name="searchkey" placeholder="Search for a lego part using name or PartID" size="40"></td></tr>
		<input type="hidden" name="pagenumber" value="1">
        </table>
        </form>
        </div>
        
        <!-- Sökresultat efter string management -->
        
        <?php
		if(isset($_GET['searchkey']) && $_GET['searchkey'] != NULL)
		{
$searchkey = $_GET['searchkey'];
$pagenumber = $_GET['pagenumber'];
$offset = ($pagenumber-1) * 15;
$previous_page = $pagenumber - 1;
$next_page = $pagenumber + 1;
var_dump($offset);
trim($searchkey);
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
$result = mysqli_query($connection, "SELECT DISTINCT * FROM parts WHERE partname LIKE '%$searchkey%' OR PartID LIKE '%$searchkey%' ORDER BY length(CatID), CatID, partname ASC LIMIT 100");
$number_of_results_parts = 0;
while(mysqli_fetch_array($result))
{
	$number_of_results_parts++;
}
echo $number_of_results_parts;

$visible_result = mysqli_query($connection, "SELECT DISTINCT * FROM parts WHERE partname LIKE '%$searchkey%' OR PartID LIKE '%$searchkey%' ORDER BY length(CatID), CatID, partname ASC LIMIT 15 OFFSET $offset"); 

print("<table>\n<tr>");
print("<th>PartID</th> <th>Partname</th>");
print("</tr>\n");
while ($row = mysqli_fetch_array($visible_result)) {
    $PartID   = $row['PartID'];
    $Partname = $row['Partname'];
    print("<tr>");
    print("<td>$PartID</td>");
    print("<td><a href=\"Searchresult_parts.php?PartID=" . $PartID . "\">$Partname</a></td>");
    print("</tr>");
}
print("</table>");
echo
" 
<form action='Homepage_V2.php' method='get'>\n
<button type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
<input type='hidden' name='searchkey' value='$searchkey'>
";
echo
"
<form action='Homepage_V2.php' method='get'>\n
<button type='submit' name='pagenumber' value='$next_page'>Next page</button>
<input type='hidden' name='searchkey' value='$searchkey'>
";
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