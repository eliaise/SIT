<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login-default.php");
    exit;
}
//sanitize input
function sanitize_input($data)
{ 
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//get files in path
$path    = './uploads';
$AllFiles = array_diff(scandir($path), array('..', '.'));
?>


<!DOCTYPE html>
<html lang="en">
<head>
 	<title>Ductus Drive</title>
 	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
</head>
<body> 


     

<div class="container my-4">
<div class="row">
    <div class="col-12 col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="d-grid"> <a href="./home.php" class="btn btn-danger">+ Add File (BROKEN)</a>
				</div>
				<h5 class="my-3">My Drive</h5>
				<div class="fm-menu">
					<div class="list-group list-group-flush"> <a href="./home.php" class="list-group-item py-1"><i class="bx bx-folder me-2"></i><span>All Files</span></a>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="mb-0 text-primary font-weight-bold"><span class="float-end text-secondary">Welcome </span>
                <?php 
			        echo "<b>" . htmlspecialchars($_SESSION["username"]) . "</b><br>";?>      
                </h5>
                <br>
				<p>
   		            <a href="logout.php" class="btn btn-danger btn-sm">Sign Out</a>
  	            </p>
				<div class="mt-3"></div>
                
				<div class="d-flex align-items-center">
					<div class="fm-file-box bg-light-warning text-warning mr-3"><i class='bx bxs-error bx-sm'></i>
					</div>
					<div class="flex-grow-1 ms-2">
						<h6 class="mb-0">Notice</h6>
						<p class="mb-0 text-secondary">Our Add File button is broken but uploading of file is still possible.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-9">
		<div class="card">
			<div class="card-body">
				<div class="fm-search">
					<div class="mb-0">
                        <h1 class="display-4">Ductus Carry File Sharing</h1>
					</div>
				</div>
				<!--end row-->
				<div class="d-flex align-items-center">
					<div>
						<h5 class="mb-0">All Files</h5>
					</div>
				</div>
				<div class="table-responsive mt-3">
					<table class="table table-striped table-hover table-sm mb-0">
						<thead>
							<tr>
								<th>Name
								</th>

							</tr>
						</thead>
						<tbody>
                            <!--For loop to generate file entries in /uploads-->
                            <?php 
                                foreach($AllFiles as $SingleFile){
                                    echo '<tr>
                                    <td>
                                    <div class="d-flex align-items-center"><div><i class="bx bxs-file me-2 font-24 text-primary"></i>
                                    </div>
                                    <div class="font-weight-bold text-primary">';
                                    echo "<a href=uploads/" . $SingleFile . " download>". $SingleFile . "</a>";
                                    echo "
                                    </div>
                                    </div>
                                    <td>
                                    </tr>";
                                }
                
                            ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</body>
</html>

