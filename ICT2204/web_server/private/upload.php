<?php
$tmpDir = "tmp/";
$uploadDir = "uploads/";
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login-default.php");
    exit;
}

function av_scan($name) {
	// do an "AV" scan
	global $tmpDir;
	global $uploadDir;
	
	// TODO: implement AV scanner
	sleep(3);
	
	return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	global $uploadsDir;
	global $tmpDir;
	$whitelist = ["png","jpeg","gif","zip","docx","xlsx","pptx","pdf"];
	$mime_mapping = [
		"application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "docx",
		"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "xlsx",
		"application/vnd.openxmlformats-officedocument.presentationml.presentation" => "pptx",
		"application/pdf" => "pdf",
		"application/zip" => "zip",
		"image/png" => "png",
		"image/gif" => "gif",
		"image/jpeg" => "jpeg",
	];
	$fileName = $_FILES["myFile"]["name"]; // PHP null byte injection has been fixed

	// START - valid file size check
	$fileSize = filesize($_FILES['myFile']['tmp_name']);
	if (!isset($_FILES["myFile"])) {
		die("There is no file to upload.");
	}

	if ($fileSize === 0) {
   		die("The file is empty.");
	}

	if ($fileSize > 5 * 1024 * 1024) { // 5 MB
		die("The file is too large.");
	}
	// END - valid file size check

	// START - check file type
	$fileType = mime_content_type($_FILES['myFile']['tmp_name']);
	if (!in_array($mime_mapping[$fileType], $whitelist)) {
		die("File type not in whitelist.");
	}
	// END - check file type

	// START - remove hostile scripts
	if ($mime_mapping[$fileType] === "png") {
		$png = imagecreatefrompng($_FILES['myFile']['tmp_name']);
		imagepng($png, $tmpDir . $fileName);
		imagedestroy($png);
		// erase_php_png($_FILES['myFile']['tmp_name'], $fileName);
	}
	else if ($mime_mapping[$fileType] === "gif") {
		$gif = imagecreatefromgif($_FILES['myFile']['tmp_name']);
		imagegif($gif, $tmpDir . $fileName);
		imagedestroy($gif);
		// erase_php_gif($_FILES['myFile']['tmp_name'], $fileName);
	}
	else if ($mime_mapping[$fileType] === "png") {
		$jpeg = imagecreatefromjpeg($_FILES['myFile']['tmp_name']);
		imagejpeg($jpeg, $tmpDir . $jpeg);
		imagedestroy($jpeg);
		
	}
	else {
		// non-image files
		// TODO: detect php scripts in these files
	}
	// END - remove hostile scripts
	
	// START - check for malware
	$malware = av_scan($fileName);
	if ($malware) {
		die("Malware detected in file.");
	}
	// END - check for malware

	// START - valid file extension check
	$fileArray = explode('.', strtolower($fileName));
        $extension = end($fileArray);
	if (!in_array($extension, $whitelist, true)) {
		// remove from tmp dir if the image was stored there for the AV scan
		unlink($tmpDir . $fileName);
		die("Extension is not in whitelist.");
	}
	// END - valid file extension check

	// START - check if file name exists
	if (file_exists($uploadDir . $fileName)) {
		// remove from tmp dir if the image was stored there for the AV scan
		unlink($tmpDir . $fileName);
		die("File with the same name already exists.");
	}
	// END - check if file name exists

	// START - save file
	if (file_exists($tmpDir . $fileName)) {
		// move the safe version from tmp to uploads
		rename($tmpDir . $fileName, $uploadDir . $fileName);
		chmod($uploadDir . $fileName, 0666); // so that the cron job can archive it later
	}
	else {
		$success = move_uploaded_file($_FILES["myFile"]["tmp_name"], $uploadDir . $fileName);
		if ($success) {
			echo '<h1 class="mt-4" style="text-align: center;">Upload successful</h1>';
			chmod($uploadDir . $fileName, 0666); // so that the cron job can archive it later
		}
		else {
			echo '<h1 class="mt-4" style="text-align: center;">Upload Failure</h1>';
		}
	}
	// END - save file
		
}
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
						<h6 class="mb-0">Reminder to Staff</h6>
						<p class="mb-0 text-secondary">Files will be regularly deleted</p>
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
                        <h1 class="display-4">Upload Files</h1>
					</div>
				</div>
				<!--end row-->
				<div class="d-flex align-items-center">
					<div>
						<h5 class="mb-0">Click "Browse" to select the file to upload and click "Upload" to start uploading!</h5>
						<br>
						<h6 class="mb-0">Files will be visible to other staff. Think before uploading</h6>
					</div>
				</div>
				<div class="table-responsive mt-3">
					<table class="table table-striped table-hover table-sm mb-0">
						<tbody>
						<tr>
								<td>
									<div class="d-flex align-items-center">
										<div class="font-weight-bold text-secondary">
											<form style="font-size: large;" method="post" action="upload.php" enctype="multipart/form-data">
												<input type="file" name="myFile" />
												<input type="submit" value="Upload" name="submit" />
											</form>
										</div>
									</div>
								</td>
								
							</tr>
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
