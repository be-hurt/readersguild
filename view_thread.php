<?php

    $title= ($_GET['title']);
    $id= ($_GET['id']);
    define('TITLE', "$title"); //pass in topic name from index.php to display here
    include('templates/header.html');

    print '<h2>' . $title . '</h2>';

    include('connect/mysqli_connect.php');

    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
        //Get the thread information and the original post
        $query = "SELECT thread_id, title, user, date_posted, thread_post FROM threads WHERE thread_id={$_GET['id']}";

        //Get the posts that have a 'thread_id' that matches the current thread
        $post_query = "SELECT post_id, post_user, post_txt, date_posted FROM posts WHERE thread_id={$_GET['id']}";

        if($result = mysqli_query($dbc, $query)) {
            //retrieve the thread information
            while ($row = mysqli_fetch_array($result)) {
                print "<div><p>{$row['user']}</p><p>{$row['date_posted']}</p><p>{$row['thread_post']}</p></div>";

                if (isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                } else {
                    $username = 'guest';
                }

                if ($row['user'] == $username) {
                    print '<a class="btn btn-default" href="edit_thread.php?id='
                    . $row['thread_id'] . '" role="button">Edit</a><a class="btn btn-default" href="delete_thread.php?id=' . $row['thread_id'] . '" role="button">Delete</a>';
                }

                print '<hr>';
            }

        } else {
            //couldn't get the thread information
            print '<p class="error">Could not retrieve the thread because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' .$query . '</p>';
        }

        $result = mysqli_query($dbc, $post_query);

        if($result && $result->num_rows != 0) {
            //retrieve the posts/replies information
            while ($row = mysqli_fetch_array($result)) {
                print "<div><p>{$row['post_user']}</p><p>{$row['date_posted']}</p><p>{$row['post_txt']}</p></div>";

                if ($row['post_user'] == $username) {

                    print '<a class="btn btn-default" href="edit_post.php?id=' . $row['post_id'] .'" role="button">Edit</a><a class="btn btn-default" href="delete_post.php?id=' . $row['post_id'] . '&title=' . ($_GET['title']) . '&thread_id=' . ($_GET['id']) . '" role="button">Delete</a>';
                }
                print '<hr>';
            }
            print '<p><a href="reply.php?id=' . $id . '&title=' . $title . '">Post a reply</a></p>';
        }  else {
            //No replies yet
            print '<p>Looks like no one has replied here yet.</p><br>
            <p><a href="reply.php?id=' . $id . '&title=' . $title . '">Post a reply</a></p>';

        }

    } else {
        //No ID set
        print '<p class="error">This page has been accessed in error.</p>';
    }


    mysqli_close($dbc);
    include('templates/footer.html');
?>
