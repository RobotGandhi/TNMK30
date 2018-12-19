<!DOCTYPE html>

<html>  
    <head>
        <meta charset="UTF-8"/>
        <title>Search results</title>
        <meta name="description" content="A site where you can find your lego pieces!"/>
        <meta name="author" content="Viktor Carlsson, Uma Eriksson, Ruben Bromee, Jessie Chow, Alma Fernvik"/>
        <link rel="stylesheet" href="proj.css" type="text/css"/>
    </head>
    <body>
        <div class ="header">
            <h1>This is the header</h1>
        </div>
		
		<!-- Sökruta -->
		<div class="search">
		<form action="search.php" method="get">
		<table>
		<tr><td> <input type="text" name="searchkey"></td></tr>
		</table>
		</form>
		</div>
		
		<!-- Sökresultat efter string management -->
		<div class="stringresult">
		<?php
		$x_amount = 0;
		$number_amount = 1;
		$searchkey = $_GET['searchkey'];
		trim($searchkey);
		$searchkeyarray = str_split($searchkey);
		$searchkeylength = count($searchkeyarray);
		
		?>
		</div>
        
        <div class="content">

            <?php
            $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
            if (!$connection){
                die("No connection to the lego database could be established.");
            }
            $result = mysqli_query($connection, "SELECT DISTINCT * FROM parts WHERE partname LIKE '$searchkey%' ORDER BY length(CatID), CatID, partname ASC LIMIT 100");
            print("<table>\n<tr>");
	        print("<th>PartID</th> <th>Partname</th>");
            print("</tr>\n");
            while($row = mysqli_fetch_array($result))
			{
            $PartID = $row['PartID'];
			$Partname = $row['Partname'];
			print("<tr>");
			print("<td>$PartID</td>");
			print("<td><a href=\"test.php?part=".$PartID."\">$Partname</a></td>");
			print("</tr>");
			}
			print("</table>");
            ?>
        </div>
        
        <div class="footer">
            <p>This is the footer</p>
        </div>
    </body>
</html>