<?php

    require_once 'common-top.php';

    echo '<div class="card">';

    echo '<h2>Deleting Order</h2>';

    // Check that all data fields exist
    if( !isset( $_POST['orderid'] ) || empty( $_POST['orderid'] ) ) showErrorAndDie( 'Missing order ID' );
    
    //Get order ID and product ID
    $orderID   = $_POST['orderid'];
    
    // Remove the products from the order...
    $sql = 'DELETE FROM contains WHERE order_id = ?';
    modifyRecords( $sql, 'i', [$orderID] );

    // Remove the the order...
    $sql = 'DELETE FROM orders WHERE id = ?';
    modifyRecords( $sql, 'i', [$orderID] );

    // Head back to the order form
    header( 'location: list-orders.php' );

    require_once 'common-bottom.php';

?>

