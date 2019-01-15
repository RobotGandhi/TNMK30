<!DOCTYPE html>

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
			<p>Step 1: Search for the type of part you're looking for by typing in either its name or its PartID into the searchbar. <br> Then press either the "Search" button with your mouse or press the "Enter" button on your keyboard<br>
			<br>
			Step 2: Choose the type of part you're looking by clicking the partname with your mouse.<br>
			Step 3: Choose the color of the part you have chosen. You can filter the colors by typing any available color in the filter bar and either pressing the "Filter" button with your mouse or the "Enter" button on your keyboard.<br>
			You can navigate back to the type of part you have chosen by using the breadcrumb menu that appears at the upper part of your screen. You can navigate to the homepage at any time by pressing the "Home" button in either the breadcrumb menu, the header menu or by pressing the red lego brick at the top of your screen. 
			</p>
            <!-- content content div closing tag  -->
        </div>
    
    

      <?php include("footer.txt");?>
            
<!-- wrapper content div closing tag  -->
 </div>
    
</body>


</html>