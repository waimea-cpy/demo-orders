<?php

    require_once 'common-top.php';

    echo '<div class="card">';

    echo '<h2>Order</h2>';

    // Get the cart
    $cart = $_SESSION['cart'];

    //---------------------------------------------------------------------------
    // Have we started an order yet?
    if( count( $cart ) == 0 ) {
        // Nope
        echo '<p>Your order is currently empty...</p>';
    }
    else {
        // Yes, it's in-progress
        echo '<p>Your order has not been submitted and currently contains...</p>';

        // Show current state of the cart
        echo '<table>';
        echo  '<thead>';            

        echo   '<tr>';
        echo     '<th>Service</th>';
        echo     '<th class="number">Unit Cost</th>';
        echo     '<th class="number">Qty</th>';
        echo     '<th class="number">Total Cost</th>';
        echo     '<th></th>';
        echo   '</tr>';
        echo  '</thead>';

        echo  '<tbody>';

        $totalCost = 0;

        foreach( $cart as $item ) {
            // get the product info for the cart item
            $sql = 'SELECT id, name, cost
                    FROM products
                    WHERE id = ?';

            $products = getRecords( $sql, 'i', [$item['id']] );
            $product = $products[0];

            $itemTotal = $product['cost'] * $item['quantity'];

            echo '<tr>';
            echo   '<td>'.$product['name'].'</td>';

            echo   '<td class="number">$'.$product['cost'].'</td>';
            echo   '<td class="number">'.$item['quantity'].'</td>';
            echo   '<td class="number">$'.number_format( $itemTotal, 2 ).'</td>';

            echo   '<td>';
            echo     '<form class="inline" method="post" action="process-del-product.php" 
                            onsubmit="return confirm( \'Delete this item?\' );">';
            echo       '<input name="prodid" type="hidden" value="'.$item['id'].'">';
            echo       '<input type="submit" value="−">';
            echo     '</form>';
            echo   '</td>';

            echo '</tr>';

            $totalCost += $itemTotal;
        }

        echo  '</tbody>';

        echo  '<tfoot>';
        echo   '<tr>';
        echo     '<th colspan="3">Total</th>';

        echo     '<td class="number">$'.number_format( $totalCost, 2 ).'</td>';

        echo     '<td>';
        echo       '<form class="inline" method="post" action="process-clear-products.php" 
                          onsubmit="return confirm( \'Delete ALL items?\' );">';
        echo         '<input type="submit" value="−">';
        echo       '</form>';
        echo     '</td>';

        echo   '</tr>';
        echo  '</tfoot>';

        echo '</table>';
    }

    //---------------------------------------------------------------------------
    echo '<h3>Sevices Avaliable</h3>';

    // Let's get a list of services the user can select from
    $sql = 'SELECT id, name, cost
            FROM products';

    $products = getRecords( $sql );

    echo '<section id="product-list">';

    foreach( $products as $product ) {
        echo '<form class="inline" method="post" action="process-add-product.php">';
        echo   '<p class="product">'.$product['name'].'</p>';
        echo   '<p class="cost">$'.$product['cost'].'</p>';

        echo   '<input name="prodid" type="hidden" value="'.$product['id'].'">';

        echo   '<input name="quantity" type="number" min="1" value="1" required>';
        
        echo   '<input type="submit" value="Add">';
        echo '</form>';
    }

    echo '</section>';

    //---------------------------------------------------------------------------
    // If an order is in progress, we have the option to submit it
    if( count( $cart ) > 0 ) {

        echo '<form  method="POST" action="process-submit-order.php">';
        echo   '<input type="submit" value="Submit Order">';
        echo '</form>';
    }

    echo '</div>';

    require_once 'common-bottom.php';

?>

