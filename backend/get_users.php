<?php 
    require_once 'db.php';

    date_default_timezone_set("Asia/Singapore");
    $timestamp = date('y-m-d H:i:s',time());
 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $data = json_decode(file_get_contents('php://input'), true);

        // get_users
        if(isset($data['Id']) && !empty($data['Id'])) {
            
            $Id = $data['Id'];
            // 连接数据库
            $conn = mysqli_connect(HOST, USER, PASS, DB) or die('Unable to Connect...');
            $select_sql = "SELECT * FROM user WHERE Id != $Id";
            $select_result = mysqli_query($conn, $select_sql);
            $arr_result = array();
            while ($one_select_result = mysqli_fetch_assoc($select_result)) {
                $one_arr_result = array(
                    "id" => $one_select_result['Id'],
                    "username" => $one_select_result['Username'],
                    "name" => $one_select_result['Name'],
                    "firebase_token" => $one_select_result['FirebaseToken'],
                    "longitude" => $one_select_result['Longitude'],
                    "latitude" => $one_select_result['Latitude']
                );
                array_push($arr_result, $one_arr_result);
            }
            $json = json_encode($arr_result);
            echo $json;
            
            // 释放结果集
            mysqli_free_result($select_result);
            // 关闭数据库连接
            mysqli_close($conn);
        }
        // 找不到请求
        else {
            $arr = array('code' => -1, 'message' => 'api error');
            $json = json_encode($arr);
            echo $json;
        }
    } else {
        $arr = array('code' => -2, 'message' => 'system error');
        $json = json_encode($arr);
        echo $json;
    }
?>