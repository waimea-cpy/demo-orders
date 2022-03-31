<?php

    require_once 'common-top.php';

    echo '<div class="card">';
    echo '<h2>Submiting Order...</h2>';

    $cart = $_SESSION['cart'];

    // Check that all data fields exist
    if( count( $cart ) == 0 ) showErrorAndDie( 'Cart is empty!' );
    
    // Add the order, and get it's new ID
    $sql = 'INSERT INTO orders (user) 
            VALUES (?)';
    
    $orderID = modifyRecords( $sql, 'i', [$_SESSION['userID']] );

    // Then add all of the products
    $sql = 'INSERT INTO contains (order_id, product_id, quantity) 
            VALUES (?, ?, ?)';

    foreach( $cart as $item ) {
        modifyRecords( $sql, 'iii', [$orderID, $item['id'], $item['quantity']] );
    }

    // Clear out the cart
    $_SESSION['cart'] = [];

    // If we get here, all went well!
    showStatus( 'Success!' );
    echo '</div>';

    // Head back to order list
    addRedirect( 1000, 'list-orders.php' );

    require_once 'common-bottom.php';
?>    