<?php

    require_once 'common-top.php';

    echo '<div class="card">';
    echo '<h2>Completing Order...</h2>';

    // Check that all data fields exist
    if( !isset( $_POST['orderid'] ) || empty( $_POST['orderid'] ) ) showErrorAndDie( 'Missing order ID' );
    
    //Get order ID and product ID
    $orderID   = $_POST['orderid'];
    
    // Mark order as complete
    $sql = 'UPDATE orders 
            SET completed = 1
            WHERE id = ?';
    
    modifyRecords( $sql, 'i', [$orderID] );

    // If we get here, all went well!
    showStatus( 'Success!' );
    echo '</div>';

    // Head back to order list
    addRedirect( 1000, 'list-orders.php' );

    require_once 'common-bottom.php';
?>    