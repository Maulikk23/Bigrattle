<?php
require('../common/library.php');
require('../common/constant.php');

// $_POST['source'] = "web";
// $_POST['API_KEY'] = base64_encode(APP_API_KEY);
require('../common/api.php');

// $_POST['username'] = '9888888888';
// $_POST['password'] = '123456';

if(
    isset($_POST['email_id']) && $_POST['email_id']!="" && 
    isset($_POST['password']) && $_POST['password']!="" 
)
{
    $source = $_POST['source'] ?? "";
    if($source == "web") {
        session_start();
    }
    
    $user_email = $_POST['email_id'];
	$password = $_POST['password'];
	
    $today = CURRENTTIME;

    $userData = $obj->select("*", "users", "user_email=?", [$user_email]);
    
    if(is_array($userData))
    {
        $hashed_password = $obj->select("user_password", "users", "user_email=?", [$user_email])[0][0];
        
        if ($password == $hashed_password)
        {

            $user_id = $userData[0]['users_id'];

            if($source == "web") 
            {
                // Generate token for authentication
                $token = openssl_random_pseudo_bytes(50);
                $token = bin2hex($token);
                $db_token = hash('sha256', $token);
                
                setcookie("token", $token, time() + (86400 * 180), "/");
                setcookie("user_id", $user_id, time() + (86400 * 180), "/");
                
                $obj->insert("login_tokens", "token, user_id, created_on", "'$db_token','$user_id','$today'");
            }


            $data['response'] = "y";
            $data['error'] = false;
            $data['user_id'] = $userData[0]['users_id'];
            $data['message'] = "Login successful";
            echo json_encode($data);
        }
        else
        {
            $data['response'] = "n";
            $data['error'] = true;
            $data['message'] = "Password does not match";
            echo json_encode($data);
        }
    }
    else
    {
        $data['response'] = "n";
        $data['error'] = true;
        $data['message'] = "Mobile number is not registered";
        echo json_encode($data);
    }
}
else
{
    $data['response'] = "n";
    $data['error'] = true;
    $data['message'] = "All field required";
    echo json_encode($data);
}
?>
