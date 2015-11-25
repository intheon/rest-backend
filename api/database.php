<?php


class database
{
	public function connectToDB()
	{
		$dbh = new medoo([
			"database_type" => "mysql",
			"database_name" => "api-test",
			"server" => "localhost",
			"username" => "root",
			"password" => "",
			"charset" => "utf8",
			"option" => [
				PDO::ATTR_CASE => PDO::CASE_NATURAL
			]
		]);

		return $dbh;
	}
}



?>