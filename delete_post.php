<?php

    define('TITLE', 'Delete Post');
    include('templates/header.html');

    print '<h2>Delete Your Post</h2>';

    //First, check if the user that is logged in is the one who posted or the admin
    include('connect/mysqli_connect.php');

    if(isset($_GET['id']) && is_numeric($_GET['id']) && ($_GET['id'] > 0)) {
        $query = "SELECT quote, source, favorite FROM quotes WHERE id={$_GET['id']}";

        if($result = mysqli_query($dbc, $query)) {
            //retrieve the information
            $row = mysqli_fetch_array($result);

            //Make the form
            print '<form action="delete_quote.php" method="post">
            <p>Are you sure you want to delete this quote?</p>
            <div><blockquote>' . $row['quote'] . '</blockquote>- ' . $row['source'];


            if ($row['favorite'] == 1) {
                print ' <strong>Favorite!</strong>';
            }

            print '</div><br>
            <input type="hidden" name="id" value="' . $_GET['id'] . '">
            <p><input type="submit" name="submit" value="Delete This Quote!"></p>
            </form>';
        } else {
            //couldn't get the information
            print '<p class="error">Could not retrieve the quotation because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' .$query . '</p>';
        }
    } elseif (isset($_POST['id']) && is_numeric($_POST['id']) && ($_POST['id'] > 0)) {
        $query = "DELETE FROM quotes WHERE id={$_POST['id']} LIMIT 1";

        $result = mysqli_query($dbc, $query);

        if (mysqli_affected_rows($dbc) == 1) {
            print '<p>The quote entry has been deleted.</p>';
        } else {
            print '<p class="error">Could not delete the quotation because:<br>' . mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
        }
    } else {
        //No ID set
        print '<p class="error">This page has been accessed in error.</p>';
    }

    mysqli_close($dbc);
    include('templates/footer.html');
?>
