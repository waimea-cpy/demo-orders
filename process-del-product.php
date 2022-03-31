<?php

    require_once 'common-top.php';

    echo '<div class="card">';

    echo '<h2>Removing Product from Order</h2>';

    // Check that all data fields exist
    if( !isset( $_POST['prodid'] )  || empty( $_POST['prodid'] ) )  showErrorAndDie( 'Missing product ID' );
    
    //Get order ID and product ID
    $prodID = $_POST['prodid'];
    
    // Find the product in the cart
    $found = -1;

    for( $i = 0; $i < count( $_SESSION['cart'] ); $i++ ) {
        if( $_SESSION['cart'][$i]['id'] == $prodID ) {
            // Found it, so keep track of index
            $found = $i;
            break;
        }
    }

    // If we found it...
    if( $found >= 0 ) {
        // delete from the cart
        array_splice( $_SESSION['cart'], $found, 1 );
    }    

    // Head back to the order form
    header( 'location: form-order.php' );

    require_once 'common-bottom.php';

?>

