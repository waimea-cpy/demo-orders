<?php

    require_once 'common-top.php';

    if( !$loggedIn ) {
        // Not logged in so no access to orders
        header( 'location: index.php' );
    }

    // Assume we're going to view all orders
    $filter = '';
    $types = null;
    $data = null;

    if( !$admin ) {
        // Only shown own orders for a normal user
        echo '<h2>Your Orders</h2>';

        $filter = 'WHERE users.id = ?';
        $types = 'i';
        $data = [$_SESSION['userID']];
    }
    else {
        // It's an admin

        // Are we filtering?
        if( isset( $_GET['filter'] ) && !empty( $_GET['filter'] ) ) {            
            // Check the filter type
            if( $_GET['filter'] == 'open' ) {
                echo '<h2>Open Orders</h2>';
                $filter = 'WHERE orders.completed = 0';
            }
            elseif( $_GET['filter'] == 'closed' ) {
                echo '<h2>Closed Orders</h2>';
                $filter = 'WHERE orders.completed = 1';
            }
            elseif( $_GET['filter'] == 'user' ) {
                if( isset( $_GET['userid'] ) && !empty( $_GET['userid'] ) ) {            
                    echo '<h2>Orders for User</h2>';
                    $filter = 'WHERE users.id = ?';
                    $types = 'i';
                    $data = [$_GET['userid']];
                }
            }
        }

        if( $filter == '' ) {
            echo '<h2>All Orders</h2>';
        }
    }

    // Get the top-level posts (parent is NULL)
    $sql = 'SELECT orders.id AS oid,
                   orders.placed,
                   orders.completed,

                   users.id AS uid,
                   users.username,
                   users.forename,
                   users.surname 

            FROM orders
            JOIN users ON orders.user = users.id

            '.$filter.'

            ORDER BY orders.completed ASC, 
                     orders.placed ASC';

    $orders = getRecords( $sql, $types, $data );

    if( count( $orders ) == 0 ) showErrorAndDie( 'No orders match' );

    echo '<table id="order-list">';
    echo  '<thead>';
    echo   '<tr>';
    echo     '<th>Order #</th>';
    if( $admin ) echo     '<th>Customer</th>';
    echo     '<th>Date / Time</th>';
    echo     '<th class="centred">Complete?</th>';
    echo     '<th class="centred">Action</th>';
    echo    '</tr>';
    echo   '</thead>';

    echo   '<tbody>';

    foreach( $orders as $order ) {
        $date = new DateTime( $order['placed'] );
        $niceDate = $date->format( 'j M Y \a\t H:i' );

        echo '<tr class="order ';
          if( $order['completed'] == 1 ) echo 'completed';
        echo   '">';

        echo   '<td><a href="show-order.php?id='.$order['oid'].'">'.str_pad( $order['oid'], 5, '0', STR_PAD_LEFT ).'</a></td>';
        if( $admin ) echo   '<td><a href="list-users.php?id='.$order['uid'].'">'.$order['forename'].' '. $order['surname'].'</a></td>';

        echo   '<td>'.$niceDate.'</td>';

        echo   '<td class="centred">'.($order['completed'] == 1 ? 'Yes' : 'No').'</td>';

        // Can we cancel this order, i.e. not yet completed
        if( $order['completed'] == 0 ) {
            echo   '<td class="centred">';
            echo     '<form class="inline" method="post" action="process-del-order.php" onsubmit="return confirm( \'Cancel this order?\' );">';
            echo       '<input name="orderid" type="hidden" value="'.$order['oid'].'">';
            echo       '<input type="submit" value="Cancel">';
            echo     '</form>';
            echo   '</td>';
        }
        else {
            echo   '<td></td>';
        }

        echo '</tr>';
    }

    echo  '</tbody>';
    echo '</table>';

    require_once 'common-bottom.php';

?>

