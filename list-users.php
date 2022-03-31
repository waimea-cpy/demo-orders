<?php

    require_once 'common-top.php';

    // Can only view users if an admin
    if( !$admin ) header( 'location: index.php' );

    if( !isset( $_GET['id'] ) || empty( $_GET['id'] ) ) {            
        // If no filter in place, show all
        echo '<h2>All Users</h2>';
        $filter = '';
        $types = null;
        $data = null;
    }
    else {
        // Filter for a specific user
        echo '<h2>User Info</h2>';
        $filter = 'WHERE id = ?';
        $types = 'i';
        $data = [$_GET['id']];
    }

    echo '<div class="card-list">';

    // Let's get all of the current users...
    $sql = 'SELECT id, username, forename, surname, email, phone, admin
            FROM users
            '.$filter.'
            ORDER BY surname ASC';

    $users = getRecords( $sql, $types, $data );

    foreach( $users as $user ) {
        echo '<div class="card user '.($user['admin'] == 1 ? 'admin' : '').'">';

        echo   '<h2>'.$user['forename'].' '.$user['surname'].'</h2>';

        echo   '<label>Username</label>';
        echo   '<p>'.$user['username'].'</p>';
        
        echo   '<label>Email</label>';
        echo   '<p>'.$user['email'].'</p>';
        
        echo   '<label>Phone</label>';
        echo   '<p>'.$user['phone'].'&nbsp;</p>';
        
        // Only show orders for non-admins
        if( $user['admin'] == 0 ) {
            echo   '<p><a href="list-orders.php?filter=user&userid='.$user['id'].'">View Orders</a></p>';
        }
        else {
            echo '<p>ADMIN</p>';
        }
        
        echo '</div>';
    }

    echo  '</div>';

    require_once 'common-bottom.php';

?>

