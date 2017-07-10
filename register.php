<?php
    define ('TITLE', 'Register');
    include ('templates/header.html');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!empty($_POST['username']) && !empty($_POST['password1'])) {
            if (($_POST['password1']) == ($_POST['password2'])) {
                //get the database connection
                include('connect/mysqli_connect.php');

                //prepare the values for storing
                $username = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['username'])));
                $password = mysqli_real_escape_string($dbc, sha1(trim(strip_tags($_POST['password1']))));

                // Create the admin value
                if (isset($_POST['is_admin'])) {
                    $admin = 1;
                } else {
                    $admin = 0;
                }

                //Check if the username already exists
                $query = "SELECT * FROM users WHERE username = '$username'";
                mysqli_query($dbc, $query);
                if (mysqli_affected_rows($dbc) == 0) {

                    $query = "INSERT INTO users (username, password, admin) VALUES ('$username', '$password', '$admin')";
                    mysqli_query($dbc, $query);

                    if (mysqli_affected_rows($dbc) == 1) {
                        //print a success message
                        print '<p>You have successfully been registered! Welcome to the guild!</p>';
                    } else {
                        //failure
                        print '<p class="error">Could not register because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
                    }
                } else {
                    //username already exists in the database
                    print '<p class="error">The username you entered already exists. Please enter a unique username.</p>';
                }
            } else {
                //failed to enter a matching pw and confirmation pw
                print '<p class="error">Your Password and Password Confirmation did not match. Please try again.</p>';
            }
        } else {
            //Failed to enter a username or password
            print '<p class="error">Please make sure you enter a username and password!</p>';
        }
    }
?>
<h2>Register</h2>
<form action="register.php" method="post">
    <div class="form-group">
        <label for="username" class="control-label">Username:</label>
        <div>
            <input type="text" class="form-control" name="username" placeholder="Username">
        </div>
    </div>
    <div class="form-group">
        <label for="password1" class="control-label">Password:</label>
        <div>
            <input type="password" class="form-control" name="password1" placeholder="Password">
        </div>
    </div>
    <div class="form-group">
        <label for="password2" class="control-label">Confirm Password:</label>
        <div>
            <input type="password" class="form-control" name="password2" placeholder="Confirm Password">
        </div>
    </div>
    <div class="form-group">
        <div>
            <input  class="btn btn-default" type="submit" name="submit" value="Sign Up">
        </div>
    </div>
</form>
<?php
    include('templates/footer.html');
?>
