<!DOCTYPE html>

<html>

    
<head>
    
     <meta charset="UTF-8"/>
	<title>How to Search</title>
	<meta name="description" content="A site where you can find your lego parts and see what set they're in!"/>
        <meta name="author" content="Viktor Carlsson, Uma Eriksson, Ruben Bromee, Jessie Chow, Alma Fernvik"/>
        <link rel="stylesheet" href="proj.css" type="text/css"/>
		<link href="https://fonts.googleapis.com/css?family=Bree+Serif" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
    
<body>
        
 <div class="wrapper">
        
     
    
<?php include("headermenu.txt");?>

 
        <!-- Sökruta -->
       <div class="searchdiv">
        <form class="formbitch" action="Homepage_V2.php" method="get" >
        <input class="searchbar" type="text" name="searchkey" placeholder="Search for a lego part using name or PartID">
		<input type="hidden" name="pagenumber" value="1">
		<button class ="button" type="submit"> Search </button> 
        </form>
        </div>
		
		 
      <div class= "content">
        
            <h1>How to search</h1>
			<p>This database is dedicated to search for any piece of Lego in this database. It is possible to search for any piece and then see which set it belongs to, and also every variation of it.</p>
            <!-- content content div closing tag  -->
        </div>
    
    

      <?php include("footer.txt");?>
            
<!-- wrapper content div closing tag  -->
 </div>
    
</body>


</html>