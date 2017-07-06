<?php
    $loggedin = false;
    $error = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            include('connect/mysqli_connect.php');

            $username = ($_POST['username']);
            $password = sha1(trim(strip_tags($_POST['password'])));

            $query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
            $result = mysqli_query($dbc, $query);

            //check if the username and password exist in the database
            if ($row = mysqli_fetch_array($result)) {

                //if the password is correct, begin the session
                if ($row['password'] == $password) {
                    session_start();
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['is_admin'] = $row['admin']; //get the admin value from the users table and assign it here. Used to check if the user is an admin
                    $loggedin = true;
                } else {
                     $error = 'Incorrect password. Please try again.';
                }
            } else {
                //Incorrect email/password
                $error = 'Your username or password was incorrect. Please try again.';
            }
        } else {
            //forgot a field
            $error = 'Please make sure you enter both a username and a password!';
        }
    }

    define('TITLE', 'Login');
    include('templates/header.html');

    if($error) {
        print '<p class="error">' . $error . '</p>';
    }

    if ($loggedin) {
        print '<p>You are now logged in!</p>';
        print '<p>Welcome back, ' . $_SESSION['username'] . '. Click <a href="index.php">here</a> to go back to the home page.</p>';
    } else {
        print '<h2>Login Form</h2>
        <div class="register">
            <p>Not yet a member? <a class="btn btn-default" href="register.php" role="button">Sign Up!</a></p>
        </div>
        <form class="form-horizontal" action="login.php" method="post">
            <div class="form-group">
                <label for="username" class="col-sm-2 control-label">Username:</label>
                <div class="col-xs-3">
                    <input type="text" class="form-control" name="username" placeholder="Username">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password:</label>
                <div class="col-xs-3">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" class="btn btn-default" name="submit" value="Log In!">
                </div>
            </div>
        </form>';
    }

    include('templates/footer.html');
?>
