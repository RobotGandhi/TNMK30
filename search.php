<!DOCTYPE html>

<html>  
    <head>
        <meta charset="UTF-8"/>
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
		<tr><td> <input type="text" name="searchkey"</td></tr>
		</table>
		</form>
		</div>
		
		<!-- Sökresultat efter string management -->
		<div class="stringresult">
		<?php
		$searchkey = $_GET['searchkey'];
		trim($searchkey);
		$searchkeyarray = str_split($searchkey);
		foreach($searchkeyarray as $char)
		{
			echo (" " .$char. " ");
		}
		print(" ".$searchkey." ");
		?>
		</div>
        
        <div class="content">

            <?php
            $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
            if (!$connection){
                die("No connection to the lego database could be established.");
            }
            $result = mysqli_query($connection, "SELECT DISTINCT PartID, partname FROM parts WHERE partname LIKE '%$searchkey%' ORDER BY PartID DESC LIMIT 100");
            print("<table>\n<tr>");
            while($fieldinfo = mysqli_fetch_field($result)) {
	           print("<th>".$fieldinfo->name."</th>");
            }
            print("</tr>\n");
            while($row = mysqli_fetch_row($result)){
                print("<tr>");
                for($i=0;$i<mysqli_num_fields($result);$i++){
                    print("<td>".$row[$i]."</td>");
                }
                print("</tr>");
            }
            ?>
        </div>
        
        <div class="footer">
            <p>This is the footer</p>
        </div>
    </body>
</html>