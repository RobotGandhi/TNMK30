<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="proj.css" type="text/css"/>
    <title>Search result</title>
	<link href="https://fonts.googleapis.com/css?family=Bree+Serif" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    
<body>
        
    <!-- Parent wrapper div -->
    <div class="wrapper">
     
		<!-- header wrapper div -->
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
        

       
		<div class= "content">
     <?php
if (!isset($_GET['PartID']) || empty($_GET['PartID'])) {
    die("<p>No results found!</p>"); 
} else {
    $part_selected          = $_GET['PartID'];
    $prefix        = "http://www.itn.liu.se/~stegu76/img.bricklink.com/PL/";
    $prefix_colors = "http://www.itn.liu.se/~stegu76/img.bricklink.com/P/";
    $connection    = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
    if (!$connection) {
        die("No connection to the lego database could be established.");
    }
    
    $result        = mysqli_query($connection, "SELECT DISTINCT parts.PartID, parts.partname, images.ItemTypeID, images.ItemID, images.has_largegif, images.has_largejpg FROM parts, images WHERE parts.PartID = '$part_selected' AND images.ItemID=parts.PartID AND images.ItemTypeID='P'");
    $result_colors = mysqli_query($connection, "SELECT DISTINCT inventory.ItemID, colors.ColorID, colors.Colorname, images.ItemID, images.has_gif, images.has_jpg FROM inventory, colors, images WHERE inventory.ItemID='$part_selected' AND inventory.ColorID=colors.ColorID AND inventory.ItemID=images.ItemID LIMIT 10");
    if ($result->num_rows == 0) {
        $result = mysqli_query($connection, "SELECT DISTINCT PartID, partname FROM parts WHERE PartID = '$part_selected'");
    }
    print("<table>\n<tr>");
    print("<th>Image</th> <th>PartID</th> <th>Partname</th> ");
    print("</tr>\n");
    while ($row = mysqli_fetch_array($result)) {
        $PartID      = $row['PartID'];
        $Partname    = $row['partname'];
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
            print("<td> <img src=\"$Imagesource\"></td>");
        else
            print("<td>No image avaliable!</td>");
        print("<td>$PartID</td> <td>$Partname</td>");
        print("</tr>");
    }
    print("</table>");
    
    
    print("</div>");
    print("<div class='content'>");
    print("<p>Available colors</p>");
    print("<table>\n<tr>");
    print("<th>Image</th> <th>Partname</th> <th>Colorname</th>");
    print("</tr>");
    while ($row_colors = mysqli_fetch_array($result_colors)) {
        $Colorname          = $row_colors['Colorname'];
        $ColorID            = $row_colors['ColorID'];
        $Imagesource_colors = $prefix_colors . "/" . $ColorID . "/" . $part_selected;
        print("<tr>");
        if ($row_colors['has_gif']) {
            print("<td><img src='$Imagesource_colors.gif' onerror='this.onerror=null;this.src=\"$Imagesource_colors.jpg\"'>");
        } else if ($row_colors['has_jpg']) {
            print("<td><img src='$Imagesource_colors.jpg' onerror='this.onerror=null;this.src=\"$Imagesource_colors.gif\"'>");
        } else {
            print("<td> No image available! </td>");
        }
        print("<td>$Partname</td>");
        print("<td><a href='searchresult_colors.php?ItemID=" . $part_selected . "&ColorID=" . $ColorID . "'> $Colorname </a> </td>");
        print("</tr>");
    }
    print("</table>");
    print("</div>");

}
?>
   
    <div class = "footer">
        <p>This is the footer</p>
    <!-- Footer content div closing tag  -->
    </div>
    <!-- wrapper content div closing tag  -->
    </div> 
</body>

</html>