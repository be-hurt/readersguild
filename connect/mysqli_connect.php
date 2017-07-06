<?php

    //This script connects to the database and establishes the character set for communications.

    //connect:
    $dbc = mysqli_connect('localhost', 'rgadmin', 'reader', 'readersguild');
    //set the character set:
    mysqli_set_charset($dbc, 'utf8');

?>
