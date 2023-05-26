<?php
class Connection{
	protected $conn ="";

	public function __construct()
	{
		$this->conn = new mysqli("localhost","root","","om_sai_electricals");
	}

	public function __destruct(){
		$this->conn->close();
	}
}

?>