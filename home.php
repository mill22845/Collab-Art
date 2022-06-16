<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}
require_once "config.php";
// Include config file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma");
  $errors = array();
  $textpost_err = "";
  $textpost = "";
  if (empty(trim($_POST["textpost"]))) {
    $textpost_err = "Please enter a Update.";
  } else {
    $textpost = trim($_POST["textpost"]);
    //Validate file
    if (isset($_FILES['uploadfile']) && $_FILES['uploadfile']['size'] > 0) {
      $file_name = $_FILES['uploadfile']['name'];
      $file_size = $_FILES['uploadfile']['size'];
      $file_tmp = $_FILES['uploadfile']['tmp_name'];
      $file_type = $_FILES['uploadfile']['type'];
      $file_ext = strtolower(end(explode('.', $_FILES['uploadfile']['name'])));

      if (in_array($file_ext, $allowedExts) === false) {
        $errors[] = "extension not allowed.";
      }
      if ($file_size > 5097152) {
        $errors[] = 'File size must be excately 5 MB';
      }
      if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "uploads/" . $file_name);
        $_file = $file_name;
      } else {
        print_r($errors);
      }
    }
    $userid = $_SESSION["id"];
    if (empty($errors) && empty($textpost_err)) {
      // Prepare an insert statement
      $sql = "INSERT INTO posts (file, post, userid) VALUES ( ?, ?, ?)";
      if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $param_file, $param_textpost, $param_userid);
        // Set parameters
        $param_textpost = $textpost;
        $param_file = $_file;
        $param_userid = $userid;
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
        } else {
          echo "Something went wrong no upload. Please try again later." . mysqli_stmt_error($stmt);
        }
        // Close statement
        mysqli_stmt_close($stmt);
      }
    }
    // Close connection
    mysqli_close($link);
    header("location: home.php");
    exit;
  }
}
//place holder sql
$sql = "SELECT * FROM posts WHERE userid = ? ORDER BY uploaddate DESC";
if ($stmt = mysqli_prepare($link, $sql)) {
  mysqli_stmt_bind_param($stmt, "s", $param_userid);
  $param_userid = $_SESSION["id"];
  if (mysqli_stmt_execute($stmt)) {
    $images =  mysqli_stmt_get_result($stmt);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    html {
      min-width: 550px;
      position: relative;
    }
    * {
      box-sizing: border-box;
    }
    /* Body Style */
    body {
      background-color: #DFF;
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
    }
    /* Header */
    .header {
      text-align: mi;
      background: #41b3A3;
      color: white;
    }
    /* Sticky navbar */
    .navbar {
      overflow: hidden;
      background-color: #e8a87c;
      position: relative;
      position: -webkit-sticky;
      top: 0;
    }
    /* Style the navigation bar links */
    .navbar a {
      float: left;
      display: block;
      color: white;
      text-align: center;
      padding: 14px 20px;
      text-decoration: none;
    }
    /* Right-aligned link */
    .navbar a.right {
      float: right;
    }
 /* Change color on hover */
    .navbar a:hover {
      background-color: #e27D60;
      color: black;
    }
    /* Nav bar links */
    .navbar a.active {
      background-color: #edb996;
      color: white;
    }
    #friendList a {
      position: fixed;
      z-index: -1;
      left: 0px;
      transition: 0.3s;
      padding: 15px;
      width: 100px;
      height: 500px;
      text-decoration: none;
      font-size: 20px;
      color: white;
      border-radius: 0 5px 5px 0;
    }
    #friend {
      top: 300px;
      background-color: #41B3a3;
    }
    .card {
      position: relative;
      margin-top: 10px;
      margin-bottom: 10px;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      transition: 0.3s;
      width: 20%;
      border-radius: 5px;
      background: white;
    }
    .card:hover {
      box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }
    img {
      border-radius: 5px 5px 0 0;
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
    textarea {
      resize: vertical;
    }
    .container {
      background-color: #f1f1f1;
      padding: 4px;
      width: 200px;
      margin: auto;
    }
   /* Footer */
.footer {
 position: relative;
  left: 0;
  bottom: 0;
  width: 100%;  padding: 10px;
  text-align: center;
  background: #e8a87c;
}

  </style>
</head>
<body>
  <div class="header">
    <img src="https://i.ibb.co/Y8c0jbG/Collab-Art-logo-Website-ready3.png" alt="Collab-Art-logo-Website-ready" style="width:10%">
  </div>
  <div class="navbar">
  <a href="https://potter3.uwmsois.com/CollabArt/home.php" class="active">Home</a>
  <a href="https://potter3.uwmsois.com/CollabArt/profile_page.php">Profile</a>
  <a href="#">Friends</a>
  <a href="#">Job Post</a>
  <a href="https://potter3.uwmsois.com/CollabArt/login.php?logout"class="right">Logout</a>
  <a href="#" class="right">Inbox</a>
  </div>
  <div id="friendList" class="sideBar">
    <a href="#" id="friend">Friends</a>
    <div class="card" style="margin-left:auto;margin-right:auto;">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="container">
        <div class="wrapper">    
            <div class="form-group <?php echo (!empty($textpost_err)) ? 'has-error' : ''; ?>">
              <label>Post</label>
              <textarea type="textarea" rows="50" cols="200" style="width:190px; height:50px;" maxlength="250" name="textpost" class="form-control" value="<?php echo $textpost; ?>"></textarea>
              <span class="help-block"><?php echo $textpost_err; ?></span>
              <p><?php echo $file_err; ?></p>
            </div>
            <div class="form-group <?php echo (!empty($file_err)) ? 'has-error' : ''; ?>">
              <label for="file"><span>Filename:</span></label>
              <input type="file" name="uploadfile" id="fileToUpload" />
            </div>
            <button type="submit" name="submit" value="Submit">Submit</button>
        </div>
      </div>
      </form>
    </div>
    <?php foreach ($images as $image) : ?>
      <?php echo "<div class='card' style='margin-left:auto;margin-right:auto;'>"; ?>
      <?php if (file_exists("uploads/" . $image['file']) && isset($image['file'])) : ?>
        <?php
        $ext = pathinfo($image['file'], PATHINFO_EXTENSION);
        if ($ext == "mp3" || $ext == "mp4" || $ext == "wmv") {
          echo "<div class='flowplayer' data-swf='flowplayer.swf' data-ratio='0.4167'>";
          echo "<source type='video/webm' src='uploads/" . $image['file'] . "' alt='Userpost' style='width:100%'>";
          echo "<source type='video/mp4' src='uploads/" . $image['file'] . "' alt='Userpost' style='width:100%'>";
          echo "</div>";
        } else {
          echo "<img src='uploads/" . $image['file'] . "' alt='Userpost' style='width:100%'>";
        }
        ?>
      <?php endif; ?>
      <?php echo "<div class='container'>"; ?>
      <?php echo "<h4><b>" . $_SESSION["username"] . "</b></h4>"; ?>
      <?php echo "<p>" . $image['post'] . "</p>"; ?>
      <?php echo "</div>"; ?>
      <?php echo "</div>"; ?>
    <?php endforeach; ?>
    <div class="footer">
      <h2>&copy; Copyright 2020 CollabArt</footer>
      </h2>
    </div>
</body>
</html>