<?php
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['name']) && isset($_POST['password']) && isset($_POST['username']) && isset($_POST['age']) && isset($_POST['gender']) && isset($_POST['weight']) && isset($_POST['height']) && isset($_POST['password']) && isset($_POST['trainer_id']) && isset($_POST['program_id'])) {
 
    // receiving the post params
    $name = $_POST['name'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $trainer_id = $_POST['trainer_id'];
    $program_id = $_POST['program_id'];
 
 
 
 
    // check if user is already existed with the same email
    if ($db->isUserExisted($username)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $username;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($name,$username,$password,$age,$gender,$weight,$height,$trainer_id,$program_id) ;
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["user"]["name"] = $user["name"];
            $response["user"]["username"] = $user["username"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, username or password) is missing!";
    echo json_encode($response);
}
?>