<?php

//database settings
$connect = mysqli_connect("localhost", "root", "sipl@1234", "db_mean");
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

function toDoData() {
    /* Get data */
    $connect = mysqli_connect("localhost", "root", "sipl@1234", "db_mean");
    $result = mysqli_query($connect, "select * from  pankaj_todo_list ORDER BY id DESC");
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
    return array();
}

$postData = json_decode(file_get_contents("php://input"));
$method = $postData->method;
if (!empty($method)) {
    switch ($method) {
        case 'add':
            $response = array('status' => false);
            $postData = json_decode(file_get_contents("php://input"));
            $itemName = $postData->item_name;
            $itemId = $postData->item_id;
            $itemDescription = $postData->item_description;
            if (!empty($itemName) && !empty($itemDescription)) {
                if (!empty($itemId)) {
                    /* Update */
                    $result = mysqli_query($connect, "UPDATE `db_mean`.`pankaj_todo_list` SET `item_name` = '$itemName', `item_description` = '$itemDescription' WHERE `pankaj_todo_list`.`id` = $itemId;");
                } else {
                    /* insert data */
                    $result = mysqli_query($connect, "INSERT INTO `db_mean`.`pankaj_todo_list` (`id`, `item_name`, `item_description`) VALUES (NULL, '$itemName', '$itemDescription');");
                }
                $data = toDoData();
                print json_encode($data);
            }
            break;

        case 'delete':
            $response = array('status' => false);
            $postData = json_decode(file_get_contents("php://input"));
            $itemId = $postData->item_id;
            if (!empty($itemId)) {
                $result = mysqli_query($connect, "DELETE FROM `db_mean`.`pankaj_todo_list` WHERE `pankaj_todo_list`.`id` = $itemId");
                $response['status'] = true;
            }
            print json_encode($response);
            break;

        case 'multiDelete':         
            $postData = json_decode(file_get_contents("php://input"));
            $itemIds = $postData->ids;          
            if (!empty($itemIds) && is_array($itemIds)) {
                 $itemIds = implode(',', $itemIds);
                 $query  = "DELETE FROM `db_mean`.`pankaj_todo_list` WHERE `pankaj_todo_list`.`id` IN ( $itemIds )";
                 $result = mysqli_query($connect, $query);
                
            }
            $allData = toDoData();
            print json_encode($allData);
            break;

        case 'list':
            $data = toDoData();
            print json_encode($data);
            break;

        case 'getitem':
            $postData = json_decode(file_get_contents("php://input"));
            $itemId = $postData->item_id;
            $result = mysqli_query($connect, "select * from  pankaj_todo_list WHERE id = $itemId");
            $data = array();
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
            }
            print json_encode($data);
            break;

        default:
            print json_encode(array('status' => false));
            break;
    }
} else {
    print json_encode(array('status' => false));
}
?>






