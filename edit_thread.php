<?php

    define('TITLE', 'Edit Thread');
    include('templates/header.html');

    print '<h2>Edit Thread</h2>';

    include('connect/mysqli_connect.php');

    //NOTE: Also need to be able to update the topic's initial post.

    //Check if the user is an admin or the person who made the post
    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
        $query = "SELECT thread_post FROM threads WHERE thread_id={$_GET['id']}";

        if($result = mysqli_query($dbc, $query)) {
            //retrieve the information
            $row = mysqli_fetch_array($result);

            //Make the form
            print '<form action="edit_thread.php" method="post">
            <p><label>Post:<br><textarea name="post" class="form-control" rows="5" cols="70">' . htmlentities($row['thread_post']) . '</textarea></label></p>
            <input type="hidden" name="id" value="' . $_GET['id'] . '">
            <p><input type="submit" name="submit" value="Update"></p>
            </form>';
        } else {
            //couldn't get the information
            print '<p class="error">Could not retrieve the thread because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' .$query . '</p>';
        }
    } elseif (isset($_POST['id']) && is_numeric($_POST['id']) && ($_POST['id'] > 0)) {
        $problem = false;
        if (!empty($_POST['post'])) {
            //prepare the post to be stored/edited
            $post = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['post'])));
        } else {
            print '<p class="error">Please submit a post that isn\'t empty.</p>';
            $problem = "true";
        }

        if (!$problem) {
            $query = "UPDATE threads SET thread_post='$post' WHERE thread_id={$_POST['id']}";

            if($result = mysqli_query($dbc, $query)) {
                print '<p>Your post has been updated.</p>';
            } else {
                //There was a problem updating the quote
                print '<p class="error">Could not update your post because:<br>' . mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
            }
        }
    } else {
        //No ID set
        print '<p class="error">This page has been accessed in error.</p>';
    }

    mysqli_close($dbc);
    include('templates/footer.html');
?>
