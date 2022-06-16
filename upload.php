<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Include config file
require_once "config.php";

$allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma");
$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$file_err=$textpost_err="";
$textpost="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty(trim($_POST["textpost"]))){
        $textpost_err = "Please enter a Update.";
    } else{
	$textpost = trim($_POST["textpost"]);
	if($_FILES["file"]["size"] != 0 && $_FILES['file']['error'] == 0){
	
		if (($_FILES["file"]["type"] == "video/mp4")
		|| ($_FILES["file"]["type"] == "audio/mp3")
		|| ($_FILES["file"]["type"] == "audio/wma")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		&& ($_FILES["file"]["size"] < 50000)
		&& in_array($extension, $allowedExts)){
		if(file_exists("upload/" . $_FILES["file"]["name"])){
		$file_err = $_FILES["file"]["name"] . " already exists. ";
		}else{
			move_uploaded_file($_FILES["file"]["tmp_name"],
			"uploads/" . $_FILES["file"]["name"]);
			$file_err = $_FILES["file"]["tmp_name"] . "Has been uploaded";
			$file = $_FILES["file"]["tmp_name"]
		}
	}
}
$userid = $_SESSION["id"];
if(empty($file_err) && empty($textpost_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (file, post, userid) VALUES ( ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_file, $param_textpost, $param_userid);
            
            // Set parameters
            $param_textpost = $textpost;
			$param_file = $file;
            $param_userid = $userid
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
    body {font-family: Arial, Helvetica, sans-serif;
  }
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 85%;
  padding: 1px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #41B3A3;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 85%;
}

button:hover {
  opacity: 0.8;
}

.container {
 background-color:#f1f1f1;
  padding: 4px;
  width: 250px;
  margin: auto;
  
}


hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}
 


.clearfix::after {
  content: "";
  clear: both;
  display: table;
    </style>
</head>
<body>
<div class="container">
	<div class="wrapper">
    
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div class="form-group <?php echo (!empty($textpost_err)) ? 'has-error' : ''; ?>">
        <label>Post</label>
        <input type="textarea" rows="50" cols="200" style="width:200px; height:50px;"  maxlength="250" name="textpost" class="form-control" value="<?php echo $textpost; ?>">
        <span class="help-block"><?php echo $textpost_err; ?></span>
    </div>
	<div class="form-group <?php echo (!empty($file_err)) ? 'has-error' : ''; ?>">
		<label for="file"><span>Filename:</span></label>
		<input type="file" name="file" id="file" /> 
		<span class="help-block"><?php echo $file_err; ?></span>
	</div>
	<button type="submit" name="submit" value="Submit">Submit</button>
	</form>
 
    </div>
</div>

</body>
</html>
