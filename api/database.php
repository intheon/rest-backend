<?php


class database
{
	public function __construct()
	{
		// handles the database stuff for us
		$host = "localhost";
		$username = "root";
		$password = "";
		$database = "api-test";

		try
		{
			// create a handle to connect to mysql database
			$databaseHandle = new PDO("mysql:host=$host; dbname=$database", $username, $password);
			$databaseHandle->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch (PDOException $exception)
		{
			echo $exception->getMessage();
		}
	}
}



?>