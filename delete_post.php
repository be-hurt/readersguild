<?php

    define('TITLE', 'Delete Post');
    include('templates/header.html');

    print '<h2>Delete Your Post</h2>';

    //First, check if the user that is logged in is the one who posted or the admin
    include('connect/mysqli_connect.php');

    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
        $query = "SELECT * FROM posts WHERE post_id={$_GET['id']} LIMIT 1";

        if($result = mysqli_query($dbc, $query)) {
            //retrieve the information
            $row = mysqli_fetch_array($result);

            if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
            } else {
                    $username = 'guest';
            }

            //Make the form
            if ($username == $row['post_user']) {
                //Make the form
                print '<form action="delete_post.php" method="post">
                <p>Are you sure you want to delete this post?</p>
                <div><blockquote>' . $row['post_txt'] . '</blockquote></div>
                <br>
                <input type="hidden" name="id" value="' . $_GET['id'] . '">
                <input type="hidden" name="thread_id" value="' . $_GET['thread_id'] . '">
                <input type="hidden" name="thread_title" value="' . $_GET['title'] . '">
                <p><input type="submit" name="submit" value="Delete Post"></p>
                </form>';
            } else {
                print 'You are not authorized to view this page.';
            }
        } else {
            //couldn't get the information
            print '<p class="error">Could not retrieve the quotation because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' .$query . '</p>';
        }
    } elseif (isset($_POST['id']) && is_numeric($_POST['id']) && ($_POST['id'] > 0)) {
        $query = "DELETE FROM posts WHERE post_id={$_POST['id']} LIMIT 1";

        $result = mysqli_query($dbc, $query);

        $thread = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['thread_id'])));
        $title = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['thread_title'])));

        if (mysqli_affected_rows($dbc) == 1) {
            print '<p>Your post has been deleted.</p>
            <br><a class="btn btn-default" href="view_thread.php?id='
            . $thread . '&title=' . $title . '" role="button">Back to Thread</a>';
        } else {
            print '<p class="error">Could not delete the post because:<br>' . mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
        }
    } else {
        //No ID set
        print '<p class="error">This page has been accessed in error.</p>';
    }

    mysqli_close($dbc);
    include('templates/footer.html');
?>
