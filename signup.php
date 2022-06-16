<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
	// Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is being used.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } elseif(!preg_match("#[0-9]+#",trim($_POST["password"]))){
        $password_err = "Password must include at least one number!";
    } elseif(!preg_match("#[a-z]+#",trim($_POST["password"]))){
        $password_err = "Password must have atleast a lower case letter!";
    } elseif(!preg_match("#[A-Z]+#",trim($_POST["password"]))){
        $password_err = "Password must have atleast a upper case letter!";
	} else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($email_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username,email, password) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email,$param_password);
            
            // Set parameters
            $param_username = $username;
			$param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
    <title>Sign Up</title>
<style>
body {font-family: Arial, Helvetica, sans-serif;
  background-color: #DFF;
  }
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
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
.button2 {background-color: #008CBA;} /* Blue */

button:hover {
  opacity: 0.8;
}
.imgcontainer {
  text-align: center;
  margin: auto;
  width: 90%;
  max-width: 1000px;
}

img.login {
  width: 30%;
  border-radius: 50%;
}

.container {
 background-color:#f1f1f1;
  padding: 4px;
  margin: auto;
  width: 80%;
  max-width: 1000px;
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
<div class="imgcontainer">
  <img src="https://i.ibb.co/Y8c0jbG/Collab-Art-logo-Website-ready3.png" alt="Collab-Art-logo-Website-ready" style="width:60%" >
</div>
<div class="container">
	<div class="wrapper">
		<h2>Sign Up</h2>
		<p>Please fill this form to create an account.</p>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
					<label>Username</label>
					<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
					<span class="help-block"><?php echo $username_err; ?></span>
				</div>
				<div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
					<label>Email</label>
					<input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
					<span class="help-block"><?php echo $email_err; ?></span>
				</div>  			
				<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
					<label>Password</label>
					<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
					<span class="help-block"><?php echo $password_err; ?></span>
				</div>
				<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
					<label>Confirm Password</label>
					<input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
					<span class="help-block"><?php echo $confirm_password_err; ?></span>
				</div>
				<div class="form-group">
					<button type="submit" value="Submit">Submit</button>
					
					<button type="reset" value="Reset">Reset</button>
				</div>
				<p>Already have an account? <a href="login.php">Login here</a>.</p>
			</form>
	</div>
</div>

</body>
</html>