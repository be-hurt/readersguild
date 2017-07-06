<?php
    define('TITLE', 'Create a Thread');
    include('templates/header.html');
    print '<h2>Create a New Thread</h2>';

    if (!is_logged_in()) {
        print '<h2>Access Denied!</h2><p class="error">You do not have permission to access this page.</p>';
        include('templates/footer.html');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['title']) && !empty($_POST['post'])) {
            //get the database connection
            include('connect/mysqli_connect.php');

            //prepare the values for storing
            $title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['title'])));
            $post = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['post'])));
            $user = mysqli_real_escape_string($dbc, trim(strip_tags($_SESSION['username'])));
            $topic = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['topic'])));

            $query = "INSERT INTO threads(title, user, topic_id, thread_post) VALUES('$title', '$user', $topic, '$post')";
            mysqli_query($dbc, $query);

            if (mysqli_affected_rows($dbc) == 1) {
                //print a success message
                print '<p>Your thread was successfully created!</p>';
            } else {
                //failure
                print '<p class="error">Error.<br>Could not create the thread because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
            }

            mysqli_close($dbc);
        } else {
            //Failed to enter a quotation
            print '<p class="error">Please enter a topic name and description.</p>';
        }
    } else {
        print '<form action="create_thread.php" method="post">
    <p>
        <label>Thread Title:<br><input type="text" name="title" class="form-control"></label>
    </p>
    <p>
        <label>Post:<br><textarea name="post" class="form-control" rows="5" cols="70"></textarea></label>
    </p>
    <input type="hidden" name="topic" id="hiddenField" value="'. $_GET['id'] . '" />
    <p><input type="submit" name="submit" value="Create Thread"></p>
    </form>';
    }

    include('templates/footer.html');
?>
