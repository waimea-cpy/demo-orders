<?php
    require_once 'common-session.php';
    require_once 'common-functions.php';

    // Which script is running?
    $script = basename( $_SERVER['SCRIPT_NAME'] );

    // Check if login data exists and use is logged in
    if( isset( $_SESSION['loggedIn'] ) && $_SESSION['loggedIn'] == true ) {
        $loggedIn = true;

        // And are they an admin?
        if( isset( $_SESSION['admin'] ) && $_SESSION['admin'] == true ) {
            $admin = true;
        }
        else {
            $admin = false;
        }
    }
    else {
        $loggedIn = false;
        $admin = false;

        // Check against a whitelist of allowed scripts
        if( $script != 'index.php' &&
            $script != 'form-login.php' &&
            $script != 'process-login.php' &&
            $script != 'form-new-user.php' &&
            $script != 'process-new-user.php' &&
            $script != 'list-products.php' ) {

            // Not an allowed script, so stick to home page
            header( 'location: index.php' );
        }
    }

?>
    

<!doctype html>

<html>
    
<head>
    <title>The Cat Spa</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="favicon.svg" type="image/svg+xml">

    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header id="main-header">

        <h1><a href="index.php">
            <img src="images/cat.svg" alt="header icon">
            The Cat Spa
        </a></h1>

        <div id="user-info">
<?php
    if( $loggedIn ) {
        echo 'Hello, '.$_SESSION['forename'].' '.$_SESSION['surname'];

        if( $admin ) {
            echo ' (admin)';
        }
    }
    else {
        echo 'Welcome, guest';
    }
?>
        </div>

        <nav id="main-nav">
            
            <label for="toggle">
                <img src="images/menu.svg">
            </label>

            <input id="toggle" type="checkbox">

            <ul>
                <label for="toggle">
                    <img src="images/close.svg">
                </label>

<?php

    if( $loggedIn ) {
        if( $admin ) {
            echo '<li><a href="list-orders.php">All Orders</a></li>';
            echo '<li><a href="list-orders.php?filter=open">Open Orders</a></li>';
            echo '<li><a href="list-orders.php?filter=closed">Closed Orders</a></li>';
            echo '<li><a href="list-users.php">Users</a></li>';
        }
        else {
            echo '<li><a href="list-products.php">Our Services</a></li>';
            echo '<li><a href="list-orders.php">Your Orders</a></li>';
            echo '<li><a href="form-order.php">New Order</a></li>';
        }

        echo '<li><a href="process-logout.php">Logout</a></li>';
    }
    else {    
        echo '<li><a href="list-products.php">Our Services</a></li>';
        echo '<li><a href="form-login.php">Login</a></li>';
        echo '<li><a href="form-new-user.php">Create Account</a></li>';
    }

?>
            </ul>
        </nav>

    </header>

    <main>

