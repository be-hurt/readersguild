<?php
    define('TITLE', 'The Reader\'s Guild');
    include ('templates/header.html');
    include ('connect/mysqli_connect.php');

    $query = 'SELECT topic_id, name, description FROM topics ORDER BY name';

    print '
    <div class="container-fluid">';
            if ($result = mysqli_query($dbc, $query)) {

                while ($row = mysqli_fetch_array($result)) {

                    $thread_query = "SELECT COUNT(*) FROM threads WHERE topic_id={$row['topic_id']}";
                    $thread_result = mysqli_query($dbc, $thread_query);
                    $thread_row = mysqli_fetch_array($thread_result);

                    print "<div class=\"row\">
                        <div class=\"col-md-3\">
                            <p class=\"title\">
                                <a href=\"view_topic.php?id={$row['topic_id']}&name={$row['name']}\">{$row['name']}
                                </a>
                            </p>
                        </div>
                        <div class=\"col-md-6\">
                            <p>{$row['description']}</p>
                        </div>
                        <div class=\"col-md-3\">
                            <p>Total Threads: {$thread_row['COUNT(*)']}</p>
                        </div>
                    </div>
                    <hr>";
                }

            } else {
                //Query didn't run
                print '<p class="error">Could not retrieve the data because:<br>' . mysqli_error($dbc) . '</p><p>The query being run was: ' . $query . '</p>';
            }

    print '</div>';
    //Add the latest post here when not under the effects of alcohol

    //get the last updated topic and date from each post
    print '</div></div>';
    mysqli_close($dbc);

    include('templates/footer.html');
?>
