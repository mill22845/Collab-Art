<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}
require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Prepare an insert statement
  if (isset($_POST["firstname"])) {
    $fname = trim($_POST["firstname"]);
  } else {
    $fname = "";
  }
  if (isset($_POST["lastname"])) {
    $lname = trim($_POST["lastname"]);
  } else {
    $lastname = "";
  }
  if (isset($_POST["descr"])) {
    $desc = trim($_POST["descr"]);
  } else {
    $desc = "";
  }
  if (isset($_POST["jobhistory"])) {
    $job = trim($_POST["jobhistory"]);
  } else {
    $job = "";
  }
  if (isset($_POST["work"])) {
    $work = trim($_POST["work"]);
  } else {
    $work = "";
  }
  $userid = $_SESSION["id"];
  // Prepare a select statement
  $sql = "INSERT INTO prof (userid, firstname, lastname, descr, jobhistory, work) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE firstname = VALUES(firstname), lastname = VALUES(lastname), descr= VALUES(descr), jobhistory = VALUES(jobhistory), work = VALUES(work)";
  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssss", $param_userid, $param_fname, $param_lname, $param_desc, $param_job, $param_work);
    // Set parameters
    $param_fname = $fname;
    $param_lname = $lname;
    $param_desc = $desc;
    $param_job = $job;
    $param_work = $work;
    $param_userid = $userid;
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Redirect to login page
    } else {
      echo "Something went wrong. Please try again later.";
    }
    // Close statement
    mysqli_stmt_close($stmt);
    header("location: profile_page.php");
    exit;
  }
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

    .container {
     background-color:#f1f1f1;
      padding: 4px;
      width: 800px;
      margin: auto;
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
      padding: 10px;
    }

    /* Job History column */
    .Job History {
     -ms-flex: 70%;
      /* IE10 */
      flex: 70%;
      background-color: white;
      padding: 10px;
    }

    /* Work column */
    .Work {
  -ms-flex: 70%;
      /* IE10 */
      flex: 70%;
      background-color: white;
      padding: 10px;
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

   textarea {
    resize: vertical;
} 

 /* Footer */
.footer {
 position: relative;
  left: 0;
  width: 100%;  padding: 10px;
  text-align: center;
  background: #e8a87c;
}

  </style>
</head>

<body>
<div class="header">
  <img src="https://i.ibb.co/Y8c0jbG/Collab-Art-logo-Website-ready3.png" alt="Collab-Art-logo-Website-ready" style="width:10%" >
</div>
  <div class="navbar">
  <a href="https://potter3.uwmsois.com/CollabArt/home.php">Home</a>
  <a href="https://potter3.uwmsois.com/CollabArt/profile_page.php"class="active">Profile</a>
  <a href="#">Friends</a>
  <a href="#">Job Post</a>
  <a href="https://potter3.uwmsois.com/CollabArt/login.php?logout"class="right">Logout</a>
  <a href="#" class="right">Inbox</a>
  </div>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="container">
      <div class="wrapper">
	  
      <div class="name">
        <h2>First Name</h2>
        <input type="text" size="50" name="firstname" ;>
        <h2>Last Name</h2>
        <input type="text" size="50" name="lastname" ;>
        <!-- Add Message Button to the right of name collumn -->
        <div class="description">
          <h2>Description</h2>
          <textarea input type="textarea" rows="5" cols="80" maxlength="300" name="descr"></textarea>
          <div class="Job History">
            <h2>Job History</h2>
            <textarea input type="textarea" rows="5" cols="80" maxlength="300" name="jobhistory"></textarea>
            <div class="Work.">
              <h2>Work</h2>
              <textarea input type="textarea" rows="5" cols="80" maxlength="300" name="work"></textarea>
            </div>
          </div>
          <button input type="submit" name="submit" value="Save">Save</button>
  </form>
   </div>
</div>

  <div class="footer">
    <h2>&copy; Copyright 2020 CollabArt</h2>
  </div>
</body>

</html>