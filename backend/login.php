<?php 
    require_once 'db.php';

    date_default_timezone_set("Asia/Singapore");
    $timestamp = date('y-m-d H:i:s',time());
 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // login
        if((isset($data['Username']) && !empty($data['Username'])) && (isset($data['Password']) && !empty($data['Password']))) {
            
            $Username = $data['Username'];
            $Password = $data['Password'];
            
            // 连接数据库
            $conn = mysqli_connect(HOST, USER, PASS, DB) or die('Unable to Connect...');
            $select_sql = "SELECT * FROM user WHERE Username = '$Username' and Password ='$Password'";
            $select_result = mysqli_query($conn, $select_sql);
            $result_found = mysqli_num_rows($select_result);
        
            if($result_found >= 1) {
                $one_select_result = mysqli_fetch_assoc($select_result);
                $Id = $one_select_result['Id'];
                $Name = $one_select_result['Name'];
                $Longitude = $one_select_result['Longitude'];
                $Latitude = $one_select_result['Latitude'];
                
                $arr = array('code' => 1,
                             'id' => $Id,
                             'username' => $Username,
                             'name' => $Name,
                             'longitude' => $Longitude,
                             'latitude' => $Latitude,
                             'message' => 'success'
                            );
                $json = json_encode($arr);
                echo $json;
            } else {
                $arr = array('code' => 2, 'message' => 'login failed');
                $json = json_encode($arr);
                echo $json;
            }
            
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