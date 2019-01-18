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
//Declaring variables
$pagenumber            = $_GET['pagenumber'];
$offset                = ($pagenumber - 1) * 15;
$previous_page         = $pagenumber - 1;
$next_page             = $pagenumber + 1;
$searchkey_breadcrumbs = $_GET['searchkey_breadcrumbs'];
$ItemID                = $_GET['ItemID'];
$ColorID               = $_GET['ColorID'];
$sort_order            = $_GET['sort_order'];
$sort_by               = $_GET['sort_by'];
$prefix_colors         = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
        
//Checking connection to database
$connection            = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
if (!$connection) {
    die("No connection to the lego database could be established.");
}

//Query for total amount of results
$query_prefix = "SELECT inventory.SetID, inventory.ItemID, inventory.ColorID, sets.SetID, sets.Setname, sets.Year FROM inventory, sets WHERE inventory.ItemID='$ItemID' AND inventory.ColorID='$ColorID' AND inventory.SetID=sets.SetID";
if (($sort_order == "ASC" || $sort_order == "DESC") && ($sort_by == "Setname" || $sort_by == "Year")) {
    $query = $query_prefix . " ORDER BY sets.$sort_by $sort_order";
} else {
    $query = $query_prefix . " ORDER BY sets.Setname DESC";
}
$query .= " LIMIT 15 OFFSET $offset";

$result_sets = mysqli_query($connection, $query_prefix);
//Query for each page visible in the result
$result_sets_visible = mysqli_query($connection, $query);

//Counting total amount of results
$amount_of_results = $result_sets->num_rows;

$amount_of_resultpages = ceil(($amount_of_results / 15));

//Query for information about the part you have chosen
$result_part = mysqli_query($connection, "SELECT colors.ColorID, colors.Colorname, parts.PartID, parts.Partname FROM colors, parts WHERE colors.ColorID='$ColorID' AND parts.PartID='$ItemID'");

$row_part  = mysqli_fetch_array($result_part);
$Partname  = $row_part['Partname'];
$Colorname = $row_part['Colorname'];
//Printing breadcrumbs
echo "<div class='breadcrumbs'>
<a href='Homepage_V2.php'> Home </a> /
<a href='Homepage_V2.php?searchkey=$searchkey_breadcrumbs&pagenumber=1'>Searchresult of \"$searchkey_breadcrumbs\" </a> /
<a href='Searchresult_parts.php?PartID=" . $ItemID . "&pagenumber=1&searchkey_breadcrumbs=$searchkey_breadcrumbs'>$Partname</a> /
$Colorname
</div>";
//Displaying information about the part you have chosen
echo "<div class='content'>
<table>
<tr> <th>Image</th> <th>Partname</th> <th>Colorname</th> </tr>
<tr> <td><img src='$prefix_colors/$ColorID/$ItemID.gif' onerror='this.src=FixImage(this)' alt='No image available!'></td> <td>$Partname</td> <td>$Colorname</td> </tr>
</table>
</div>";

//Displaying information about the sets the chosen part is included in
echo "<div class='content'>
<h1 class='informational_headers'>Sets this part is included in:</h1>";
if ($sort_by == "Setname" || $sort_by == "Year") {
    echo "Showing $amount_of_results results, sorted by $sort_by";
    if ($sort_order == "ASC") {
        echo ", sorted in ascending order";
    } else if ($sort_order == "DESC") {
        echo ", sorted in descending order";
    }
} else {
    echo "Showing $amount_of_results results";
}
echo"<table>
<tr>
<th>SetID</th>";
if ($sort_by == "Setname" && $sort_order == "DESC") {
    echo "<th><a class='sort' href='searchresult_colors.php?ItemID=$ItemID&ColorID=$ColorID&pagenumber=1&searchkey_breadcrumbs=$searchkey_breadcrumbs&sort_by=Setname&sort_order=ASC'>Setname (sort Ascending)</a></th>";
} else {
    echo "<th><a class='sort' href='searchresult_colors.php?ItemID=$ItemID&ColorID=$ColorID&pagenumber=1&searchkey_breadcrumbs=$searchkey_breadcrumbs&sort_by=Setname&sort_order=DESC'>Setname (sort Descending)</a></th>";
}

if ($sort_by == "Year" && $sort_order == "DESC") {
    echo "<th><a class='sort' href='searchresult_colors.php?ItemID=$ItemID&ColorID=$ColorID&pagenumber=1&searchkey_breadcrumbs=$searchkey_breadcrumbs&sort_by=Year&sort_order=ASC'>Year (sort Ascending)</a></th>";
} else {
    echo "<th><a class='sort' href='searchresult_colors.php?ItemID=$ItemID&ColorID=$ColorID&pagenumber=1&searchkey_breadcrumbs=$searchkey_breadcrumbs&sort_by=Year&sort_order=DESC'>Year (sort Descending)</a></th>";
}
echo "</tr>";
while ($row = mysqli_fetch_array($result_sets_visible)) {
    $SetID   = $row['SetID'];
    $Setname = $row['Setname'];
    $Year    = $row['Year'];
    echo "<tr>
    <td>$SetID</td>
    <td>$Setname</td>
    <td>$Year</td>
    </tr>";
}
echo "</table>";
/*Print only a "Next page" button if you're on page 1. Print both a "Next page" and a "Previous page" button if you're on any page between the first and the last. 
Print only a "Next page" button if you're on the last page. */
if ($amount_of_resultpages == 1 || $amount_of_resultpages == null) {} 
else if ($pagenumber != 1 && $pagenumber != $amount_of_resultpages) {
    echo "<div class='pages'>
    <form action='searchresult_colors.php' method='get'>
    <input type='hidden' name='ItemID' value='$ItemID'>
    <input type='hidden' name='ColorID' value='$ColorID'>
    <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
    <button class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
    </form>";
    echo "$pagenumber/$amount_of_resultpages";
    echo "<form action='searchresult_colors.php' method='get'>
    <input type='hidden' name='ItemID' value='$ItemID'>
    <input type='hidden' name='ColorID' value='$ColorID'>
    <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
    <button class='pagebuttons' type='submit' name='pagenumber' value='$next_page'>Next page</button>
    </form>
    </div>";             
} else if ($pagenumber == $amount_of_resultpages) {
    echo "<div class='pages'>
    <form action='searchresult_colors.php' method='get'>
    <input type='hidden' name='ItemID' value='$ItemID'>
    <input type='hidden' name='ColorID' value='$ColorID'>
    <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
    <button class='pagebuttons' type='submit' name='pagenumber' value='$previous_page'> Previous page </button>
    </form>";
    echo "$pagenumber/$amount_of_resultpages";
    echo "</div>";
} else {
    echo "<div class='pages'>";
    echo "$pagenumber/$amount_of_resultpages";
    echo "<form action='searchresult_colors.php' method='get'>
    <input type='hidden' name='ItemID' value='$ItemID'>
    <input type='hidden' name='ColorID' value='$ColorID'>
    <input type='hidden' name='searchkey_breadcrumbs' value='$searchkey_breadcrumbs'>
    <button class='pagebuttons' type='submit' name='pagenumber' value='$next_page'>Next page</button>
    </form>
    </div>";
}
echo "</div>";
?>
<?php include("footer.txt"); ?>
    </body>
</html>