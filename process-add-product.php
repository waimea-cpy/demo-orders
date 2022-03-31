<?php

    require_once 'common-top.php';

    echo '<div class="card">';

    echo '<h2>Adding Product to Order</h2>';

    // Check that all data fields exist
    if( !isset( $_POST['prodid'] )   || empty( $_POST['prodid'] ) )   showErrorAndDie( 'Missing product ID' );
    if( !isset( $_POST['quantity'] ) || empty( $_POST['quantity'] ) ) showErrorAndDie( 'Missing quantity' );
    
    $prodID = $_POST['prodid'];
    $quantity = $_POST['quantity'];

    // See if the product is already in the cart
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
        // ...just update the qty
        $_SESSION['cart'][$found]['quantity'] += $quantity;
    }
    else {
        // ...add a new item
        $_SESSION['cart'][] = [
            'id'       => $prodID,
            'quantity' => $quantity
        ];
    }

    // Head back to the new order form
    header( 'location: form-order.php' );

    require_once 'common-bottom.php';

?>

