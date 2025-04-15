<?php
    $data = file_get_contents('php://input');
    file_put_contents('received_data.json', $data);
    echo "Data received!";
?>