<?php
    define('TITLE', 'Add a Topic');
    include('templates/header.html');
    print '<h2>Add a New Topic</h2>';

    if (!is_administrator()) {
        print '<h2>Access Denied!</h2><p class="error">You do not have permission to access this page.</p>';
        include('templates/footer.html');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_POST['name']) && !empty($_POST['description'])) {
            //get the database connection
            include('connect/mysqli_connect.php');

            //prepare the values for storing
            $name = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['name'])));
            $description = mysqli_real_escape_string($dbc, trim(strip_tags($_POST['description'])));

            $query = "INSERT INTO topics(name, description) VALUES('$name', '$description')";
            mysqli_query($dbc, $query);

            if (mysqli_affected_rows($dbc) == 1) {
                //print a success message
                print '<p>The topic has been created!</p>';
            } else {
                //failure
                print '<p class="error">Error.<br>Could not create the topic because:<br>' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $query . '</p>';
            }
        } else {
            //Failed to enter a quotation
            print '<p class="error">Please enter a topic name and description.</p>';
        }
    }
?>

<form action="add_topic.php" method="post">
    <p>
        <label>Topic Name:<br><input type="text" name="name" class="form-control"></label>
    </p>
    <p>
        <label>Topic Description:<br><textarea name="description" class="form-control" rows="5" cols="70"></textarea></label>
    </p>
    <p><input type="submit" name="submit" value="Add New Topic"></p>
</form>
<?php
    include('templates/footer.html');
?>
