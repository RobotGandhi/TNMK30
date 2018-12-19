<!DOCTYPE html>

<html>

    
<head>
    
     <meta charset="UTF-8"/>
    <link rel="stylesheet" href="proj.css" type="text/css"/>
	<title>Search result</title>
    
</head>
    
<body background="lego-duplo-2304-lego-duplo-large-green-building-plate-2.jpg">
        
 <div class="wrapper">
        
     
    
        <div class ="header">
       
        <h1>This is the header</h1>
		
		
		
        </div>
		
         <?php include("menu.txt");?>
		 
		 <?php include("searchform.txt");?>
		
      <div class= "content">
	 <?php
	if(!isset($_GET['part']) || empty($_GET['part'])){
		die("No PartID found!");
	}
	else
	{
	$part = $_GET['part'];
	$prefix = "http://www.itn.liu.se/~stegu76/img.bricklink.com/PL/";
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
            if (!$connection){
                die("No connection to the lego database could be established.");
            }
			
            $result = mysqli_query($connection, "SELECT DISTINCT parts.PartID, parts.partname, images.ItemTypeID, images.ItemID, images.has_largegif, images.has_largejpg FROM parts, images WHERE parts.PartID = '$part' AND images.ItemID=parts.PartID AND images.ItemTypeID='P'");
			if($result->num_rows == 0){
				$result = mysqli_query($connection, "SELECT DISTINCT PartID, partname FROM parts WHERE PartID = '$part'");
			}
            print("<table>\n<tr>");
	        print("<th>Image</th> <th>PartID</th> <th>Partname</th> ");
            print("</tr>\n");
            while($row = mysqli_fetch_array($result))
			{
            $PartID = $row['PartID'];
			$Partname = $row['partname'];
			$Imagesource = $prefix . $PartID;
			if($row['has_largejpg'])
			{
				$Imagesource .= ".jpg";
				$Imageexists = true;
			}
			else if($row['has_largegif'])
			{
				$Imagesource .= ".gif";
				$Imageexists = true;
			}
			else{
				$Imageexists = false;
			}
			print("<tr>");
			if ($Imageexists)
				print("<td> <img src=\"$Imagesource\"></td>");
			else
				print("<td>No image avaliable!</td>");
			print("<td>$PartID</td>");
			print("<td>$Partname</td>");
			print("</tr>");
			}
			print("</table>");
	}
	 ?>
            <!-- content content div closing tag  -->
        </div>
		
		<div class= "content">
		<h3> related parts </h3>
		</div>
    
    

    <div class = "footer">
        <p>This is the footer</p>
        
        <!-- Footer content div closing tag  -->
    </div>
            
<!-- wrapper content div closing tag  -->
 </div>
    
</body>


</html>