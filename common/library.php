<?php
date_default_timezone_set('Asia/Kolkata');

require 'connection.php';

 class Helper extends Connection{

	public function select($fieldName, $tableName, $condition, $values = [])
	{
        if(count($values) > 0)
        {
            // for sql injection
            $query = "SELECT $fieldName FROM $tableName WHERE $condition";
            $stmt = $this->conn->prepare($query);

            $stmt->bind_param(str_repeat('s', count($values)), ...$values);
            $stmt->execute();
            $result = $stmt->get_result();
            // fetching result would go here, but will be covered later
            // $stmt->close();
            // for sql injection
        }
        else {
            $query = "SELECT $fieldName FROM $tableName WHERE $condition";
            $result = $this->conn->query($query) or die($this->conn->error);
        }
        
		// $result = $this->conn->query($quer y) or die($this->conn->error);

		if($result->num_rows == 0){

			return "No Data";
		}
		else{
			while($row = $result->fetch_array(MYSQLI_BOTH)){
				$data[] = $row;
			}
			return $data;
		}
	}
    
	public function insert($tableName, $fieldName, $value)
	{
	    $query = "INSERT INTO $tableName ($fieldName) VALUES ($value)";
        
		$this->conn->query($query) or die($this->conn->error);
		return $this->conn->insert_id;
	}
    
	public function execute($query)
	{
		$this->conn->query($query) or die($this->conn->error);
	}
	
	public function logout()
	{
	    if($this->isLoggedIn())
	    {
	        $token = hash('sha256', $_COOKIE['token']);
	        
	        setcookie("token", "", time() - 3600, "/");
            $this->execute("DELETE FROM login_tokens WHERE token='$token'");
            
            return true;
        }
        else{ return false; }
	}
	
	public function isLoggedIn()
	{
	    if(isset($_COOKIE['token']) && $_COOKIE['token'] != "")
	    {
        	$token = hash('sha256', $_COOKIE['token']);
            $is_logged_in = $this->select("*", "login_tokens", "token='$token'");
            
            if(is_array($is_logged_in)) { return true; }
            else{ return false; }
        }
        else{ return false; }
	}
    
	public function getUserID()
	{
	    if($this->isLoggedIn())
	    {
        	$token = hash('sha256', $_COOKIE['token']);
            $user_id = $this->select("user_id", "login_tokens", "token='$token'")[0]['user_id'];
            
            return $user_id;
	    }
	    else { return ""; }
	}
	
	public function authUserID()
	{
	    $token = $_COOKIE['token'] ?? "";
	    $user_id = $this->select("user_id", "login_tokens", "token=?", [$token]);
	    
        if(is_array($user_id)) { return $user_id[0][0]; }
        else { return "Not logged in"; }
	}
	
	public function generateAPIKey($length = 19)
	{
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
	
	public function verifyAPIKey($API_KEY)
	{
	    $app_api_key = API_KEY ?? "";
	    if($app_api_key != "")
	    {
            if($API_KEY == $app_api_key) { return true; }
            else { return APP_API_KEY_INVALID_TEXT; }
	    }
	    else { return APP_API_KEY_REQUIRED_TEXT; }
	}
	
	public function sanitize($string)
	{
        //user this for names, age
        $string = trim($string);
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
	    $string = $this->conn->real_escape_string($string ); // escapes special characters
		return  $string;
	}
    
	public function html_encode($str)
	{
	    //when user user the editor
    	$string = htmlentities($str,ENT_QUOTES);
    	$string = trim($string);
    	return $string;
	}
	
	public function html_decode($str)
	{
    	$string = html_entity_decode($str,ENT_QUOTES);
    	$string = trim($string);
    	return  $string;
	}
    
    public function divider($number_of_digits) {
        $tens="1";
        
        if($number_of_digits>8)
            return 10000000;
        
        while(($number_of_digits-1)>0)
        {
            $tens.="0";
            $number_of_digits--;
        }
        return $tens;
    }
    
    public function convertAmount($num)
    {
        // $num = "7890000";
        $ext="";//thousand,lac, crore
        $number_of_digits = strlen($num); //this is call
        if($number_of_digits>=5)
        {
            if($number_of_digits%2!=0){
                $divider=$this->divider($number_of_digits-1);}
            else{
                $divider=$this->divider($number_of_digits);}
        }
        else{
            $divider=1;
        }
        
        
        $fraction=$num/$divider;
        $fraction=round($fraction,2);
        if($number_of_digits==5){
            $ext="K";}
        if($number_of_digits==6 || $number_of_digits==7){
            $ext="L";}
        if($number_of_digits==8 || $number_of_digits>=9){
            $ext="Cr";
        }
        return $fraction."".$ext;
    }
    
	public function dynamic_dropdown($data, $name, $type)
	{
		$select = '';
		$selected = '<select name="'. $name .'" id="' . $name .'" class="' . $name .'" style="width:100%;height:40px">';
		if(is_array($data)){
			$selected .= '<option value="">Select '.$type.'</option>';
			foreach ($data as $key => $value) {
				$selected .= '<option ' . $select . ' value="'.$value[0].'">'.$value[1].'</option>';
			}
		}
		else{
			$selected .= '<option value="">Nothing Found</option>';
		}
		$selected .= '</select>';
        
		return $selected;
	}
    
	public function dynamic_edit_dropdown($data, $name, $type, $id)
	{
		$selected = '<select name="'. $name .'" class="validate[required] form-control ' . $name .'" id="' . $name .'" style="width:100%;">';
		if(is_array($data)){
			$selected .= '<option value="">Select '.$type.'</option>';
			foreach ($data as $key => $value)
			{
				if($id) {
					$id==$value[0]?$select = 'selected="selected"': $select='';
				}
				$selected .= '<option ' . $select . ' value="'.$value[0].'">'.$value[1].'</option>';
			}
		}
		else{
			$selected .= '<option value="">Nothing Found</option>';
		}
		$selected .= '</select>';
		
		return $selected ;
	}
    
	public function createDir($path)
	{
		if (!file_exists($path)) {
    		mkdir($path, 0777, true);
		}
	}
    
	public function upload_file($file, $type, $max_size, $path)
	{
		if($file['name'] == ''){
			return 'File Name is Empty';
		}
		if($file['error'] == 1){
			return 'File Cannot Be Uploaded Try Again!';
		}
		if($file['size'] > $max_size){
			return 'File Size Exceeds ' . ($max_size/(1024*1024)) . 'MB';
		}
		if(!in_array($file['type'], $type)){
			return 'File Type . ' . $file['type'] . ' not allowed';
		}
        
		if(!is_dir($path)){
			mkdir($path, 0755);
		}
        
		//$final_name = $file['name'];
        
        $final_name =time().$file['name'];
		$source_path = $file['tmp_name'];
		$destination_path = $path.'/' . $final_name;
		move_uploaded_file($source_path, $destination_path);
		return array($final_name);
	}
    
    public function multiple_upload_file($file, $type, $max_size, $path)
    {
		$count = 0;
		foreach($file['name'] as $value)
		{
			if($file['name'][$count] == ''){
				return 'File Name is Empty';
			}
			if($file['error'][$count] == 1){
				return 'File Cannot Be Uploaded Try Again!';
			}
			if($file['size'][$count] > $max_size){
				return 'File Size Exceeds ' . ($max_size/(1024*1024)) . 'MB';
			}
			if(!in_array($file['type'][$count], $type)){
				return 'File Type . ' . $file['type'][$count] . 'not allowed';
			}
            
			if(!is_dir($path)){
				mkdir($path, 0755);
			}
            
			$final_name = time().$file['name'][$count];
			$source_path = $file['tmp_name'][$count];
			$destination_path = $path. $final_name;
			move_uploaded_file($source_path, $destination_path);
			$count++;
			$file_name[] = $final_name;
		}
		return $file_name;
	}
    
	public function thumnail_creation($imageName, $imagePath, $width, $ratio, $thumb_path,$height)
	{
		$pos = strrpos($imagePath, '.');
		$ext = strtolower(substr($imagePath, $pos+1));
		
		if($ext == 'jpg' || $ext == 'jpeg'){
			$original_image = imagecreatefromjpeg($imagePath);
		}
		else if($ext == 'png' ){
			$original_image = imagecreatefrompng($imagePath);
		}
		else if($ext == 'gif'){
			$original_image = imagecreatefromgif($imagePath);
		}
		else {
			return 'Only Jpg, gif and png images are allowed.';
		}
		
		$original_width = imagesx($original_image);
		$original_height = imagesy($original_image);
		if($original_height>0 && $original_width>0){
			//Thumbnamil Width Height Calculation
			if($ratio == 0){
				$thumb_width = $width;
				$thumb_height = round(($thumb_width*$original_height)/$original_width);
			}
			else if($ratio == 2){
				$thumb_width = $width;
				$thumb_height = $height;
			}
			else{
				$thumb_width = $width;
				$thumb_height = $width;
			}
		}
		else{
			return 'Image Height or Width can\'t be Zero';
		}
        
		if(!is_dir($thumb_path)){
			mkdir($thumb_path, '0755');
		}
        
		$thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);
		$color = imagecolorallocate($thumb_image, 224, 224, 222);
		imagefill($thumb_image, 0, 0, $color);
		imagecopyresampled($thumb_image, $original_image, 0, 0, 0, 0, $thumb_width, $thumb_height, $original_width, $original_height);
		$destination_path = $thumb_path . '/' . $imageName;
        
        
		if($ext == 'jpg' || $ext == 'jpeg'){
			imagejpeg($thumb_image,$destination_path);
		}
		else if($ext == 'png' ){
			imagepng($thumb_image,$destination_path);
		}
		else if($ext == 'gif'){
			imagegif($thumb_image,$destination_path);
		}
	}
    
    public function ConvertINWord($number)
    {
    	$number = $number;
       $no = round($number);
       $point = round($number - $no, 2) * 100;
       $hundred = null;
       $digits_1 = strlen($no);
       $i = 0;
       $str = array();
       $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
       $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    
       while ($i < $digits_1) {
         $divider = ($i == 2) ? 10 : 100;
         $number = floor($no % $divider);
         $no = floor($no / $divider);
         $i += ($divider == 10) ? 1 : 2;
         if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
         } else $str[] = null;
      }
    
      $str = array_reverse($str);
      $result = implode('', $str);
    
      $points = ($point) ?
        "." . $words[$point / 10] . " " .
              $words[$point = $point % 10] : 'Zero';
    
      return $result . "Rupees  " . $points . " Paise";
	}
    
	////////////////////
    
	public function barcode_dropdown($data, $name, $type){
			$select = '';
			$selected = '<select name="'. $name .'" id="' . $name .'" style="width:220px;">';
			if(is_array($data)){
				$selected .= '<option value="">Select '.$type.'</option>';
					foreach ($data as $key => $value) {
						$selected .= '<option ' . $select . ' value="'.$value[0].'-'.$value[1].'">'.$value[2].' '.$value[3].'</option>';
					}

			}
			else{
				$selected .= '<option value="">Nothing Found</option>';
			}
			$selected .= '</select>';

			return $selected;
	}
    
	//////////////////
    
	public function barcode_edit_dropdown($data, $name, $type, $id)
	{
		$selected = '<select name="'. $name .'" class="validate[required] form-control ' . $name .'" id="' . $name .'" style="width:220px;">';
		if(is_array($data)){
			$selected .= '<option value="">Select '.$type.'</option>';
				foreach ($data as $key => $value) {
					//print_r($value);
					if($id){
						$id==$value[0]."-".$value[1]?$select = 'selected="selected"': $select='';
					}
					$selected .= '<option ' . $select . ' value="'.$value[0].'-'.$value[1].'">'.$value[2].' '.$value[3].'</option>';
				}

		}
		else{
			$selected .= '<option value="">Nothing Found</option>';
		}
		$selected .= '</select>';
		return $selected ;
	}
    
	//FILTERS
	public function string_filter($data)
	{
        $data = stripslashes($data);
        $data = trim($data);
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        $data = $this->conn->real_escape_string($data);
        return $data;
    }
    
    public function int_filter($data)
    {
        $data = trim($data);
        if(filter_var($data, FILTER_VALIDATE_INT) === 0 || !filter_var($data, FILTER_VALIDATE_INT) === false) {
            return $data;
        }
    }
    
    public function email_filter($data)
    {
        $data = trim($data);
        if(filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return $data;
        }
    }
    
    public function password_filter($data)
    {
        $data = trim($data);
        //can contain only alphabets, numbers and special characters such as (.$%&@!*#)
        if(preg_match("/^[a-zA-Z0-9.$%&@!*#]*$/", $data)) {
            return $data;
        }
    }
	//FILTERS
    
	public function insert_ip()
	{
	    $curr_date=date('Y-m-d h:i:s');
        
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];//64.30.228.118
        $result  = array('country'=>'', 'city'=>'');
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        
        //$ip = getHostByName(getHostName());
        $query ="SELECT * FROM `user_ip` WHERE `ip`='".$ip."'";
        
		$result = $this->conn->query($query) or die($this->conn->error);
        
		if($result->num_rows == 0)
		{
            $query = "INSERT INTO user_ip (`ip`,`createdon`,`count`) VALUES ('$ip','$curr_date','1')";
            $this->conn->query($query) or die($this->conn->error);
		}
		else
		{
            $this->conn->query("UPDATE `user_ip` SET `count`=`count`+1,`createdon`='$curr_date' WHERE `ip`='".$ip."'") or die($this->conn->error);
            while($row = $result->fetch_array(MYSQLI_NUM)){
                $data[] = $row;
            }
            //return $data;
		}
        
		//return $this->conn->insert_id;
        
		//Delete privous one month data
		$previous_date = strtotime(date("Y-m-d", strtotime("-1 months")));
		$this->conn->query("DELETE FROM user_ip WHERE STR_TO_DATE(SUBSTR(createdon, 0, 25), '%a, %d %b %Y %H:%i:%S') < DATE_SUB($previous_date, INTERVAL 30 DAY)") or die($this->conn->error); //NOW()
    }
}

$obj= new Helper();

?>
