<?php

    require_once 'common-top.php';

    echo '<div class="card">';

    // Make sure that there is an ID passed via GET
    if( !isset( $_GET['id'] ) || empty( $_GET['id'] ) ) showErrorAndDie( "Missing order ID" );

    // Get the ID from the URL
    $orderID = $_GET['id'];

    // Let's get the current state of the order...
    $sql = 'SELECT placed, completed
            FROM orders
            WHERE id = ?';

    $orders = getRecords( $sql, 'i', [$orderID] );
    $order = $orders[0];

    // And get the products the order contains
    $sql = 'SELECT contains.quantity,

                   products.name,
                   products.cost,

                   (contains.quantity * products.cost) AS total
                    
            FROM contains
            JOIN products ON contains.product_id = products.id
            
            WHERE contains.order_id = ?';

    $orderProducts = getRecords( $sql, 'i', [$orderID] );

    // And show it
    echo '<h2>Order #'.str_pad( $orderID, 5, '0' , STR_PAD_LEFT ).'</h2>';

    $date = new DateTime( $order['placed'] );
    $niceDate = $date->format( 'd/m/Y H:i' );
    
    echo '<p>Submitted: '.$niceDate.'</p>';

    echo '<table>';
    echo  '<thead>';
    echo   '<tr>';
    echo     '<th>Service</th>';
    echo     '<th class="number">Unit Cost</th>';
    echo     '<th class="number">Qty</th>';
    echo     '<th class="number">Total Cost</th>';
    echo   '</tr>';
    echo  '</thead>';

    echo  '<tbody>';

    $totalCost = 0;

    foreach( $orderProducts as $product ) {
        echo '<tr>';
        echo   '<td>'.$product['name'].'</td>';
        echo   '<td class="number">$'.$product['cost'].'</td>';
        echo   '<td class="number">'.$product['quantity'].'</td>';
        echo   '<td class="number">$'.$product['total'].'</td>';
        echo '</tr>';

        $totalCost += $product['total'];
    }

    echo  '</tbody>';

    echo  '<tfoot>';
    echo   '<tr>';
    echo     '<th colspan="3">Total</th>';
    echo     '<td class="number">$'.number_format( $totalCost, 2 ).'</td>';
    echo   '</tr>';
    echo  '</tfoot>';

    echo '</table>';

    if( $order['completed'] == 1 ) {
        echo '<p>Order has been completed<p>';
    }
    else {
        echo '<p>Order has not been completed yet<p>';
    }

    if( $admin ) {
        echo '<form class="inline" method="post" action="process-complete-order.php">';
        echo   '<input name="orderid" type="hidden" value="'.$orderID.'">';
        echo   '<input type="submit" value="Mark Complete">';
        echo '</form>';
    }

    echo '</div>';

    echo '<a href="list-orders.php">Back to Order List</a>';

    require_once 'common-bottom.php';

?>

