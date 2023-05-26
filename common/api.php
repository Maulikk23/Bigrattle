<?php 

// require 'library.php';

// echo $obj->generateAPIKey();

if(AUTHENTICATE_API == TRUE)
{
    if( isset($_POST['API_KEY']) && $_POST['API_KEY'] != "")
    {
        $source = $_POST['source'] ?? "";
        $api_key = $_POST['API_KEY'];
        
        if($source == "web") { $api_key = base64_decode($api_key); }
        
        $app_api_key = APP_API_KEY;
        
        if($api_key == $app_api_key) { return true; }
        else {
            $data["response"] = 'n';
            $data['error'] = true;
            $data["message"] = APP_API_KEY_INVALID_TEXT;
            echo json_encode($data);
            exit();
        }
    }
    else{
        $data["response"] = 'n';
        $data['error'] = true;
        $data["message"] = APP_API_KEY_REQUIRED_TEXT;
        echo json_encode($data);
        exit();
    }
}

?>
