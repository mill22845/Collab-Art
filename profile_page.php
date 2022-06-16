<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}
require_once "config.php";
$sql = "SELECT * FROM prof WHERE userid = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
  // Bind variables to the prepared statement as parameters
  mysqli_stmt_bind_param($stmt, "s", $param_id);

  // Set parameters
  $param_id = $_SESSION["id"];

  // Attempt to execute the prepared statement
  if (mysqli_stmt_execute($stmt)) {
    /* store result */
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
      header("location: edit_profile.php");
      exit;
    }
    mysqli_stmt_close($stmt);
  }
}
// Prepare an SELECT statement
$sql = "SELECT * FROM prof WHERE userid = ?";

if ($stmt = mysqli_prepare($link, $sql)) {

  mysqli_stmt_bind_param($stmt, "s", $param_username);


  $param_username = $_SESSION["id"];

  if (mysqli_stmt_execute($stmt)) {

    $prof =  mysqli_stmt_get_result($stmt);
  }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  header("location: edit_profile.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Profile Page</title>
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

    /* Column container */
    .row {
      display: -ms-flexbox;
      /* IE10 */
      display: flex;
      -ms-flex-wrap: wrap;
      /* IE10 */
      flex-wrap: wrap;
    }

    /* Create two unequal columns that sits next to each other */
    /* Right sidebar */
    .side {
      float: right;
      -ms-flex: 10%;
      /* IE10 */
      flex: 10%;
      background-color: #f1f1f1;
      padding: 20px;
    }

    .container {
      background-color: #f1f1f1;
      padding: 4px;
      margin: auto;
      width: 80%;
      max-width: 1000px;
    }

    /* Name column */
    .name {
      -ms-flex: 70%;
      /* IE10 */
      flex: 70%;
      background-color: white;
      padding: 20px;
    }

    /* Description column */
    .description {
      -ms-flex: 70%;
      /* IE10 */
      flex: 70%;
      background-color: white;
      padding: 20px;
    }

    /* Job Post column */
    .Job Post {
      -ms-flex: 70%;
      /* IE10 */
      flex: 70%;
      background-color: white;
      padding: 20px;
    }

    /* Job History column */
    .Job History {
      -ms-flex: 70%;
      /* IE10 */
      flex: 70%;
      background-color: white;
      padding: 20px;
    }

    /* Work column */
    .Work {
      -ms-flex: 70%;
      /* IE10 */
      flex: 70%;
      background-color: white;
      padding: 20px;
    }

    button {
      background-color: #41B3A3;
      color: white;
      padding: 14px 20px;
      margin: auto;
      border: none;
      cursor: pointer;
      width: 20%;
    }

    .button2 {
      background-color: #008CBA;
    }

    /* Blue */
    button:hover {
      opacity: 0.8;
    }

    /* Footer */
    .footer {
      position: relative;
      left: 0;
      bottom: 0;
      width: 100%;
      padding: 10px;
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
    <a href="https://potter3.uwmsois.com/CollabArt/profile_page.php" class="active">Profile</a>
    <a href="#">Friends</a>
    <a href="#">Job Post</a>
    <a href="https://potter3.uwmsois.com/CollabArt/login.php?logout" class="right">Logout</a>
    <a href="#" class="right">Inbox</a>
  </div>

  <!-- Friend list code -->

  <div class="row">
    <div class="side">
      <h2>Friend List</h2>
    </div>
    <div class="container">
      <div class="wrapper">
        <?php foreach ($prof as $profile) : ?>
          <div class="name">
            <?php echo "<h4>" . $profile['firstname'] . " " . $profile['lastname'] . "</h4>" ?>
            <!-- Add Message Button to the right of name collumn -->
            <div class="description">
              <h2>Description</h2>
              <?php echo "<p>" . $profile['descr'] . "</p>" ?>

              <div class="Job History">
                <h2>Job History</h2>
                <?php echo "<p>" . $profile['jobhistory'] . "</p>" ?>
                <div class="Work.">
                  <h2>Work</h2>
                  <?php echo "<p>" . $profile['work'] . "</p>" ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <button input type="submit" name="submit" value="Edit">Edit</button>
          </form>

          <div class="footer">
            <h2>&copy; Copyright 2020 CollabArt</h2>
          </div>
</body>

</html>