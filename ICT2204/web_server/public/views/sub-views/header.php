<?php
    include_once __DIR__.'/../../helpers/mysql.php';
    include_once __DIR__.'/../../helpers/helper.php';

	$helper = new Helper();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ductus Carry</title>
    <script src="https://kit.fontawesome.com/fc5bc90864.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $helper->cssPath("site.css") ?>"/>
    <script src="../../js/slider.js" defer></script>
    <script src="../../js/ajax-search.js" defer></script>
</head>
<?php
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome, Guest<br />";
$content2 = "<li class='nav-item'>
			 <a class='nav-link' href='".$helper->pageUrl("register.php")."'>Sign Up</a></li>
                         <li><a class='nav-link'>|</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='".$helper->pageUrl("login.php")."'>Login</a></li>";

if(isset($_SESSION["customerName"])) { 
        $escapedname = htmlspecialchars($_SESSION["customerName"]);
	$content1 = "Welcome, <b>$escapedname</b>";
	$content2 = "<li class=' nav-item'>
				<a class='nav-link' href='".$helper->pageUrl("profile.php")."'>Update Profile</a></li>
                                <li><a class='nav-link'>|</a></li>
				<li class='nav-item'>
				<a class='nav-link' href='".$helper->processUrl("logout.php")."'>Logout</a></li>";
	if(isset($_SESSION["numCartItem"])) {
		$content1 .= ", $_SESSION[numCartItem] item(s) in the shopping cart.";
	}
}
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
	<div class="container">
		<!-- Dynamic Text Display -->
		<span class="navbar-text mr-auto"
		  style="color:white;">
		<?php echo $content1; ?>
		</span>

		<ul class="navbar-nav flex-row">
			<?php echo $content2; ?>
		</ul>
	</div>
</nav>        
 
<nav class="navbar navbar-expand-md navbar-light bg-light second-nav">
	<div class="container">
		<a class="navbar-brand" href="<?php echo $helper->pageIndex("home.php")?>"><img src="<?php echo $helper->imgPath('logo.png') ?>" width="70" height="70" alt="Ductus Carry"></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse"
				data-target="#collapsibleNavbar">
			<span class="navbar-toggler-icon"></span>
		</button>
		<!--Collapsible part of navbar-->
		<div class="collapse navbar-collapse" id="collapsibleNavbar">
			<ul class="navbar-nav ml-auto">
			<li class="nav-item">
					<a class="nav-link" href="<?php echo $helper->pageIndex("home.php")?>">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $helper->pageUrl("category.php")?>">Categories</a>
				</li>
                                <li class="nav-item">
					<a class="nav-link" href="<?php echo $helper->pageUrl("about.php")?>">About</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $helper->pageUrl("search.php")?>">Search</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo $helper->pageUrl("shoppingcart.php")?>">Cart</a>
				</li>
			</ul>
			
			<!--Right justified menu items-->
			<!-- <ul class="navbar-nav ml-auto">
				<?php echo $content2;?>
			</ul> -->
		</div>	
	</div>
</nav>

