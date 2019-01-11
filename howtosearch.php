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

 
        <!-- Sökruta -->
        <div class="searchdiv">
        <form action="Homepage_V2.php" method="get" >
       
        <input class="searchbar" type="text" name="searchkey" placeholder="Search for a lego part using name or PartID" size="40">
		<input type="hidden" name="pagenumber" value="1">
		<button class ="button" type="submit"> Search </button> 
       
        </form>
        </div>
		
		 
      <div class= "content">
        
            <h1>hellohello how to serach??</h1>
			<p>This database is dedicated to search for any piece of Lego in existence. It is possible to search for any piece and then see which set it belongs to, and also every variation of it.</p>
            <!-- contetn content div closing tag  -->
        </div>
    
    

    <div class = "footer">
        <p>This is the footer</p>
        	<p>Contact</p>
<p>Email: questions@liu.se</p>
<p>Phone: 013 28 10 00</p>
        <!-- Footer content div closing tag  -->
    </div>
            
<!-- wrapper content div closing tag  -->
 </div>
    
</body>


</html>