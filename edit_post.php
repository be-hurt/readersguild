<?php

    define('TITLE', 'Edit Post');
    include('templates/header.html');

    print '<h2>Edit Your Post</h2>';

    include('connect/mysqli_connect.php');

    //NOTE: Also need to be able to update the topic's initial post.

    //Check if the user is an admin or the person who made the post
    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
        $query = "SELECT post_user, post_txt FROM posts WHERE post_id={$_GET['id']}";

        if($result = mysqli_query($dbc, $query)) {
            //retrieve the information
            $row = mysqli_fetch_array($result);

            if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                } else {
                    $username = 'guest';
                }

            if ($username == $row['post_user']) {
                //Make the form
                print '<form action="edit_post.php" method="post">
                <p><label>Post:<br><textarea name="post" class="form-control" rows="5" cols="70">' . htmlentities($row['post_txt']) . '</textarea></label></p>
                <input type="hidden" name="id" value="' . $_GET['id'] . '">
                <p><input type="submit" name="submit" value="Update"></p>
                </form>';
            } else {
                print 'You are not authorized to view this page.';
            }
        } else {
            //couldn't get the information
            print '<p class="error">Could not retrieve the post because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' .$query . '</p>';
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
            $query = "UPDATE posts SET post_txt='$post' WHERE post_id={$_POST['id']}";

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
