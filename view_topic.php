<?php
    //default name to avoid error message
    if (isset($_GET['name'])) {
        $name= ($_GET['name']);
    } else {
        $name="oops";
    }

    //default id to avoid error message
    if (isset($_GET['id'])) {
        $id= ($_GET['id']);
    } else {
        $id= 0;
    }

    define('TITLE', "$name"); //pass in topic name from index.php to display here
    include('templates/header.html');

    print '<h2>' . $name . '</h2>';

    include('connect/mysqli_connect.php');

    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0) && isset($_GET['name'])) {
        $query = "SELECT thread_id, title, user, date_posted FROM threads WHERE topic_id={$_GET['id']}";
        $result = mysqli_query($dbc, $query);

        if($result && $result->num_rows != 0) {
            //retrieve the information
            while ($row = mysqli_fetch_array($result)) {
                print "<div><h3><a href=\"view_thread.php?id={$row['thread_id']}&title={$row['title']}\">{$row['title']}</a></h3><p>Posted by: {$row['user']}</p><p>Date: {$row['date_posted']}</p></div><hr>";
            }

            print '<p><a href="create_thread.php?id=' . $id . '">Create a new thread</a></p>';

        } else {
            //Topic is empty
            print '<p>Looks like no one has posted here yet.</p>
            <p><a href="create_thread.php?id=' . $id . '">Create a new thread</a></p>';
        }
    } else {
        //No ID set
        print '<p class="error">This page has been accessed in error.</p>';
    }


    mysqli_close($dbc);
    include('templates/footer.html');
?>
