<?php

    require_once 'common-top.php';

    echo '<div class="card">';

    echo '<h2>Removing All Products from Order</h2>';

    $_SESSION['cart'] = [];
    
    // Head back to the order form
    header( 'location: form-order.php' );

    require_once 'common-bottom.php';

?>

