<?php

require "/vendor/autoload.php";										// loads our frameworks
require "/database.php";											// loads our database handling code

class apiHandler
{
	public function __construct()
	{
		$app = new \Slim\Slim();									// slim framework

		/*
		Defining all the routes below.
		See https://github.com/intheon/rest-backend#allowed-methods for a full list.
		*/

		$app->get("/", array($this, "readHomeRoute"));				// for the root
		$app->get("/user", array($this, "readAllUsers"));			// get all users
		$app->get("/widget", array($this, "readAllWidgets")); 		// get all widgets
		$app->get("/user/:id", array($this, "readOneUser"));		// get specific user
		$app->get("/widget/:id", array($this, "readOneWidget"));	// get specific widget
		$app->get("/state/:id", array($this, "readOneState"));		// get specific state
		$app->post("/user", array($this, "createUser"));			// create new user
		$app->post("/widget", array($this, "createWidget"));		// create new widget
		$app->post("/state", array($this, "createState"));			// create new state
		$app->put("/user/:id", array($this, "updateUser"));			// update user
		$app->put("/widget/:id", array($this, "updateWidget"));		// update widget
		$app->put("/state/:id", array($this, "updateState"));		// update state
		$app->delete("/user/:id", array($this, "deleteUser"));		// delete user
		$app->delete("/widget/:id", array($this, "deleteWidget"));	// delete widget
		$app->delete("/state/:id", array($this, "deleteState"));	// delete state

		$app->run();												// start this mofo
	}

	public function readHomeRoute()									// messages for the home screen
	{
		echo "root mofo";
	}

	public function readAllUsers()									// block of users
	{
		$database = new medoo([
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

		$data = $database->select("test","*");

		foreach ($data as $lineItem)
		{
			echo $lineItem["phrase"];
		}


		/*

		while ($row = $statement->fetch())
		{
			echo $row->phrase;
		}
		*/
	}

	public function readAllWidgets()								// block of widgets
	{
		echo "all widgets...";
	}

	public function readOneUser($id)								// users profile
	{
		echo "a specific user... " . $id;
	}

	public function readOneWidget($id)								// widget info
	{
		echo "a specific widget... " . $id;
	}

	public function readOneState($id)								// specific state details
	{
		echo "a specific state... " . $id;
	}

	public function createUser()									// create a new user
	{
		echo "user created";
	}

	public function createWidget()									// create new widget
	{
		echo "widget created";
	}

	public function createState()									// create new state
	{
		echo "state created";
	}

	public function updateUser()									// update existing user
	{
		echo "user updated";
	}

	public function updateWidget()									// update existing widget
	{
		echo "widget updated";
	}

	public function updateState()									// update existing state
	{
		echo "state updated";
	}

	public function deleteUser()									// delete existing user
	{
		echo "user deleted";
	}

	public function deleteWidget()									// delete existing widget
	{
		echo "widget deleted";
	}

	public function deleteState()									// delete existing state
	{
		echo "state deleted";
	}
}

$api = new apiHandler();


/*
abstract class databaseConfig
{
	protected $host = "localhost";
	protected $username = "root";
	protected $password = "";
	protected $database = "api-test";

	public function __construct(){
		// todo, use PDO
		$connect = mysqli_connect(
			$this->host, 
			$this->username, 
			$this->password, 
			$this->database
		);

	}
}

class dbConnect extends databaseConfig
{
	public function __construct($operation)
	{
		switch ($operation)
		{
			case "getAllUsers":
			echo var_dump($connect);


				$this->getAllUsers($this);

			break;
			default:
			break;
		}
	}

	public function getAllUsers()
	{

		$data = mysqli_query($c,"SELECT * FROM test");

		echo var_dump($data);
		/*
		$json = array();

		while($row = mysqli_fetch_assoc($data))
		{
			$json[] =  $row;
		}
		echo json_encode($json);

	}
}

*/
?>