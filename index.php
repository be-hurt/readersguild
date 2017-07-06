<?php
    define('TITLE', 'The Reader\'s Guild');
    include ('templates/header.html');
    include ('connect/mysqli_connect.php');

    $query = 'SELECT topic_id, name, description FROM topics ORDER BY name';

    print '<div class="row">
        <div class="col-md-6 my-border">';
        if ($result = mysqli_query($dbc, $query)) {

            while ($row = mysqli_fetch_array($result)) {
                print "<div class=\"topic\"><h3><a href=\"view_topic.php?id={$row['topic_id']}&name={$row['name']}\">{$row['name']}</a></h3><p>{$row['description']}</p></div><hr>";
            }

        } else {
            //Query didn't run
            print '<p class="error">Could not retrieve the data because:<br>' . mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
        }
    print '</div>
    <div class="col-md-6">
    <p>Here\'s some text for a test</p>';

    //get the last updated topic and date from each post
    print '</div></div>';
    mysqli_close($dbc);

    include('templates/footer.html');
?>
