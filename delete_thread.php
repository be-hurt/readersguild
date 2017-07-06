<?php

    define('TITLE', 'Delete Thread');
    include('templates/header.html');

    print '<h2>Delete Thread</h2>';

    //First, check if the user that is logged in is the one who posted or the admin
    include('connect/mysqli_connect.php');

    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
        $query = "SELECT * FROM threads WHERE thread_id={$_GET['id']} LIMIT 1";

        if($result = mysqli_query($dbc, $query)) {
            //retrieve the information
            $row = mysqli_fetch_array($result);

            //check if the user is the same as the one that made the thread, or is an admin
            if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
            } else {
                    $username = 'guest';
            }

            if ($username == $row['user']) {
                //Make the form
                print '<form action="delete_thread.php" method="post">
                <p>Are you sure you want to delete this thread and all associated posts?</p>
                <div><blockquote>' . $row['thread_post'] . '</blockquote></div>
                <br>
                <input type="hidden" name="id" value="' . $_GET['id'] . '">
                <p><input type="submit" name="submit" value="Delete Thread"></p>
                </form>';
            } else {
                print 'You are not authorized to view this page.';
            }
        } else {
            //couldn't get the information
            print '<p class="error">Could not retrieve the quotation because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' .$query . '</p>';
        }
    } elseif (isset($_POST['id']) && is_numeric($_POST['id']) && ($_POST['id'] > 0)) {
        //Delete thread
        $query = "DELETE FROM threads WHERE thread_id={$_POST['id']} LIMIT 1";

        //Make sure to delete the associated posts: not just the thread
        $post_query = "DELETE FROM posts WHERE thread_id={$_POST['id']}";

        $posts_result = mysqli_query($dbc, $post_query);
        if (mysqli_affected_rows($dbc)) {
            print '<p>All posts within the thread were deleted.</p>';
        } else {
            print '<p class="error">Could not delete the thread\'s posts because:<br>' . mysqli_error($dbc) . '</p><p>The query being run was: ' . $post_query . '</p>';
        }

        $result = mysqli_query($dbc, $query);

        if (mysqli_affected_rows($dbc) == 1) {
            print '<p>Your thread has been deleted.</p>';
        } else {
            print '<p class="error">Could not delete the thread because:<br>' . mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
        }

    } else {
        //No ID set
        print '<p class="error">This page has been accessed in error.</p>';
    }

    mysqli_close($dbc);
    include('templates/footer.html');
?>
