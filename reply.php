<?php
    define('TITLE', 'Reply');
    include('templates/header.html');

    if (!is_logged_in()) {
        print '<h2>Access Denied!</h2><p class="error">You do not have permission to access this page. Please <a href="login.php">Login</a> or <a href="register.php">register</a></p>';
        include('templates/footer.html');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['post']) && !empty($_POST['thread'])) {

            //get the database connection
            include('connect/mysqli_connect.php');

            //prepare the values for storing
            $user = mysqli_real_escape_string($dbc, trim(strip_tags($_SESSION['username'])));
            $post = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['post'])));
            $thread = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['thread'])));

            $query = "INSERT INTO posts(post_user, post_txt, thread_id) VALUES('$user', '$post', $thread)";
            mysqli_query($dbc, $query);

            if (mysqli_affected_rows($dbc) == 1) {
                //print a success message
                print '<p>Your post was successful. Click <a href="view">here</a> to go back to the main topics.</a></p>';
            } else {
                //failure
                print '<p class="error">Error.<br>Unable to submit your post because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
            }
        } else {
            //Failed to enter a quotation
            print '<p class="error">Please enter a topic name and description.</p>';
        }
    } else {
        $title= ($_GET['title']);
        $id= ($_GET['id']);
        print '<h2>Reply To ' . $title . '</h2>';

        include('connect/mysqli_connect.php');

        //Display the thread and a form to reply to it
        $query = "SELECT user, date_posted, thread_post FROM threads WHERE thread_id = '$id'";

        if($result = mysqli_query($dbc, $query)) {
            //retrieve the information
            while ($row = mysqli_fetch_array($result)) {
                print "<div><p>{$row['user']}</p><p>{$row['date_posted']}</p><p>{$row['thread_post']}</p></div>";
            }
        } else {
            //couldn't get the information
            print '<p class="error">Could not retrieve the thread because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' .$query . '</p>';
        }

        print '<form class="reply" action="reply.php" method="post">
        <p>
            <label>Post:<br><textarea name="post" rows="5" cols="60"></textarea></label>
        </p>
        <input type="hidden" name="thread" id="hiddenField" value="'. $_GET['id'] . '" />
        <p><input type="submit" name="submit" value="Submit"></p>
        </form>';
    }

    mysqli_close($dbc);
    include('templates/footer.html');
?>
