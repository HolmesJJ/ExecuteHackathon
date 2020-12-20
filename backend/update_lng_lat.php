<?php 
    require_once 'db.php';

    date_default_timezone_set("Asia/Singapore");
    $timestamp = date('y-m-d H:i:s',time());
 

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $data = json_decode(file_get_contents('php://input'), true);

        // update_lng_lat
        if((isset($data['Id']) && !empty($data['Id'])) && (isset($data['Longitude']) && !empty($data['Longitude'])) && (isset($data['Latitude']) && !empty($data['Latitude']))) {
            
            $Id = $data['Id'];
            $Longitude = round($data['Longitude'], 3);
            $Latitude = round($data['Latitude'], 3);
            // 连接数据库
            $conn = mysqli_connect(HOST, USER, PASS, DB) or die('Unable to Connect...');
            $update_sql = "UPDATE user SET Longitude = '$Longitude', Latitude = '$Latitude' WHERE Id = $Id";
            $update_result = mysqli_query($conn, $update_sql);
            if($update_result) {
                $arr = array('code' => 1, 'message' => 'success');
                $json = json_encode($arr);
                echo $json;
            } else {
                $arr = array('code' => 2, 'message' => 'update longitude and latitude failed');
                $json = json_encode($arr);
                echo $json;
            }
            
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