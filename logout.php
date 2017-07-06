<?php

    define('TITLE', 'Logout');
    include('templates/header.html');

    $_SESSION = [];
    session_destroy();

    print '<p>You are now logged out.</p>';

    include('templates/footer.html');
?>
