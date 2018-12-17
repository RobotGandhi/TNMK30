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
		$newsearchkey = "";
		for($i = 0; $i<count($searchkeyarray); $i++)
		{
			if(is_numeric($searchkeyarray[$i]))
			{
				for($j = 0; $j < 6 && $j + $i < count($searchkeyarray); $j++)
				{
					//print(var_dump($searchkeyarray[$i + $j] == 'x'));
					if($searchkeyarray[$i + $j] == 'x')
					{
						if ($j == 0) {
							//print($searchkeyarray[$i + $j] . " " . var_dump($newsearchkey) . "\n");
							break;
						}
						else{
							$newsearchkey .= " x";
							//print($searchkeyarray[$i + $j] . " " . var_dump($newsearchkey) . "\n");
						}
					}
					
					else if(is_numeric($searchkeyarray[$i + $j]))
					{
						if ($j == 0) {
							$newsearchkey = substr($searchkey, 0, $i + 1);
							//print(var_dump(substr($searchkey, 0, $i + 1)));
						}
						else{
							$newsearchkey .= " " . $searchkeyarray[$i + $j];
							//print($searchkeyarray[$i + $j] . " " . var_dump($newsearchkey) . "\n");
						}
					}
					
					else if($searchkeyarray[$i + $j] != " "){
						$newsearchkey .= substr($searchkey, $i + $j - 1);
							//print($searchkeyarray[$i + $j] . " " . var_dump($newsearchkey) . "\n");
						$i =  $i + $j;
						break;
					}
				}
			}
		}
		print("\n\n\n".$newsearchkey." ");
		?>
		</div>
        
        <div class="content">

            <?php
            $connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
            if (!$connection){
                die("No connection to the lego database could be established.");
            }
            $result = mysqli_query($connection, "SELECT DISTINCT * FROM parts WHERE partname LIKE '$searchkey%' ORDER BY length(CatID), CatID, partname ASC LIMIT 300");
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
                print("</tr>\n");
            }
			print("</table>");
            ?>
        </div>
        
        <div class="footer">
            <p>This is the footer</p>
        </div>
    </body>
</html>