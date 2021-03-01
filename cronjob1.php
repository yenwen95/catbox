<?php

    //delete files that are expired in the recycle bin
    
    require 'config/db.php';
    $set = '1';
    $date = date_create();
    $delete = date_format($date,'Y-m-d');
    $query = $con->prepare("SELECT filepath from Files WHERE is_insiderecyclebin = ? && exptime = ?");
    $query->execute([$set, $delete]);

   while( $row = $query->fetch()){
       unlink($row['filepath']);
   }

    $query2 = $con->prepare("DELETE FROM Files WHERE is_insiderecyclebin = ? && exptime = ?");
    $query2->execute([$set, $delete]);


?>