<?php

    require_once 'common-top.php';

    echo '<h2>Our Services</h2>';

    echo '<div class="card-list">';

    // Let's get all of the current products...
    $sql = 'SELECT name, description, cost
            FROM products
            ORDER BY name ASC';

    $products = getRecords( $sql );

    foreach( $products as $product ) {
        echo '<div class="card">';

        echo   '<h2>'.$product['name'].'</h2>';

        echo   '<p>'.nl2br( $product['description'] ).'</p>';
        
        echo   '<p>$'.$product['cost'].' per session</p>';

        echo '</div>';
    }

    echo  '</div>';

    require_once 'common-bottom.php';

?>

