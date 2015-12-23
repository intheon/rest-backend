<?php

require "/vendor/autoload.php";															// loads our frameworks
require "/database.php";																// loads our database handling code
require "/tokenAuth.php";																// middleware to intercept the token

class apiHandler
{
	public function __construct()
	{
		$app = new \Slim\Slim();														// slim framework
		$app->add(new tokenAuth());
		/*
		Defining all the routes below.
		See https://github.com/intheon/rest-backend#allowed-methods for a full list.
		*/

		$app->get("/", array($this, "readHomeRoute"));									// for the root
		$app->get("/user", array($this, "readAllUsers"));								// get all users
		$app->get("/widget", array($this, "readAllWidgets")); 							// get all widgets
		$app->get("/user/:id", array($this, "readOneUser"));							// get specific user
		$app->get("/widget/:id", array($this, "readOneWidget"));						// get specific widget
		$app->get("/state/:id", array($this, "readOneState"));							// get specific state
		$app->post("/login/:username/:password", array($this, "loginUser"));			// login user
		$app->post("/user/register", array($this, "registerUser"));						// register new user
		$app->post("/widget", array($this, "createWidget"));							// create new widget
		$app->post("/state", array($this, "createState"));								// create new state
		$app->put("/user/:id", array($this, "updateUser"));								// update user
		$app->put("/widget/:id", array($this, "updateWidget"));							// update widget
		$app->put("/state/:id", array($this, "updateState"));							// update state
		$app->delete("/user/:id", array($this, "deleteUser"));							// delete user
		$app->delete("/widget/:id", array($this, "deleteWidget"));						// delete widget
		$app->delete("/state/:id", array($this, "deleteState"));						// delete state

		$app->run();																	// start this mofo
	}

	public function readHomeRoute()														// messages for the home screen
	{
		echo "do the funky chicken";
	}

	public function readAllUsers()														// block of users
	{
		// this needs restricting to be admin only, or removing!
		/*
		$db = new database();
		$data = $db->connectToDB()->select("user","*");
		echo json_encode($data);
		*/
	}

	public function readAllWidgets()													// get block of widgets
	{
		$db = new database();
		$data = $db->connectToDB()->select("widgets",["w_id", "w_name", "w_codeName", "w_pathToCode", "w_desc"]);
		echo json_encode($data);
	}

	public function readOneUser($id)													// get users profile
	{
		$states = $this->getUsersStates($id);
		
		if (!$states)
		{
			echo "nowidgets";
		}
		else
		{
			// all the users states in an array
			$states = $this->getUsersStates($id);

			// retreve all users widgets individually, and store them in an array
			$widgets = array();
			foreach ($states as $individual) $widgets[] = $this->readOneWidget($individual["widgetId"]);

			//now, we merge the two!
			$merged = $this->mergeStateAndWidgetData($states, $widgets);

			echo json_encode($merged);

		}

	}

	public function readOneWidget($widgetId)													// get widget info
	{
		$db = new database();
		$data = $db->connectToDB()->select("widgets", 
			[
				"w_id",
				"w_name",
				"w_codeName",
				"w_pathToCode"
			],[
				"w_Id" => $widgetId
			]);

		return $data;
	}

	public function readOneState($stateId)												// get specific state details
	{
		$db = new database();
		$data = $db->connectToDB()->select("states", 
			[
				"widgetId",
				"widgetData"
			],[
				"stateId" => $stateId
			]);

		return $data[0];
	}

	private function getUsersPrimary($username)
	{
		$db = new database();
		$data = $db->connectToDB()->select("auth",[
				"id"
			],[
				"username" => $username
			]);

		return array_shift($data);
	}

	private function getUsersStates($id)
	{
		$usersPrimaryKey = $this->getUsersPrimary($id);
		$usersPrimaryKey = array_shift($usersPrimaryKey);

		$db = new database();

		$data = $db->connectToDB()->select("states",[
				"stateId",
				"widgetId",
				"userId",
				"widgetData"
			],[
				"userId" => $usersPrimaryKey
			]);

		if (empty($data)) return false;
		else return $data;
	}


	public function registerUser()														// register a new user
	{
		if (isset($_POST["payload"]))
		{
			$payload = $_POST["payload"];

			$doesUserExist = $this->checkUsernameExists($payload["usr"]);

			if (!$doesUserExist)
			{
				$hashed = $this->hashPassword($payload["pwd"]);
				$token = bin2hex(openssl_random_pseudo_bytes(16));
				$token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

				$db = new database();
				$response = $db->connectToDB()->insert("auth", [
					"username" => $payload["usr"],
					"password" => $hashed,
					"name" => $payload["nm"],
					"email" => $payload["email"],
					"token" => $token,
					"token_expiry" => $token_expiry
				]);

				$this->loginUser($payload["usr"], $payload["pwd"]);

			}
			else
			{
				$this->responseBuilder("message", "nonexistent");
			}

			/*
			$db = new database();

			$data = $db->connectToDB()->insert("auth", "username", [
					"username" => $username,

				]);

			if (count($data) === 0) return false;
			else if (count($data) === 1) return true;
			*/
		}
	}

	public function createWidget()														// create new widget
	{
		echo "widget created";
	}

	public function createState()														// create new state
	{
		if (isset($_POST["payload"]))
		{
			$payload = $_POST["payload"];
			$userId = $payload["userId"];
			$widgetId = $payload["widgetId"];

			$db = new database();
			$action = $db->connectToDB()->insert("states", [
				"userId" => $userId,
				"widgetId" => $widgetId
				]);
		}
	}

	public function loginUser($username, $password)										// log user in
	{
		$doesUserExist = $this->checkUsernameExists($username);

		if ($doesUserExist)
		{
			$isPasswordCorrect = $this->checkPassword($username, $password);
			if ($isPasswordCorrect) $this->generateAuthToken($username);
			else if ($isPasswordCorrect == false) $this->responseBuilder("message", "incorrectpwd");
		}
		else $this->responseBuilder("message", "nonexistent");
	}

	public function updateUser()														// update existing user
	{
		echo "user updated";
	}

	public function updateWidget()														// update existing widget
	{
		echo "widget updated";
	}

	public function updateState($stateId)												// update existing state
	{
		echo var_dump(file_get_contents("php://input"));
	

			/*
		}
		$db = new database();

		$data = $db->connectToDB()->update("states", [
				"widgetData" => $credentials["token"],
				"token_expiry" => $credentials["tokenExpiration"]
			],[
				"username" => $username
			]);
			*/
	}

	public function deleteUser()														// delete existing user
	{
		echo "user deleted";
	}

	public function deleteWidget()														// delete existing widget
	{
		echo "widget deleted";
	}

	public function deleteState()														// delete existing state
	{
		echo "state deleted";
	}

	private function hashPassword($plaintext_password)									// helper functions for log in and auth stuff
	{
		return password_hash($plaintext_password,PASSWORD_DEFAULT);
	}

	private function checkUsernameExists($username)
	{
		$db = new database();
		$data = $db->connectToDB()->select("auth", "username", ["username" => $username]);

		if (count($data) === 0) return false;
		else if (count($data) === 1) return true;
	}

	private function checkPassword($username, $password)
	{
		$db = new database();
		$data = $db->connectToDB()->select("auth", "password", ["username" => $username]);

		foreach ($data as $row)
			if (!password_verify($password,$row)) return false;
			else if (password_verify($password,$row)) return true;
	}

	private function generateAuthToken($username)
	{
		/*
		see https://www.webstreaming.com.ar/articles/php-slim-token-authentication/
		*/ 
		$credentials["username"] = $username;
		$credentials["token"] = bin2hex(openssl_random_pseudo_bytes(16));
		$credentials["tokenExpiration"] = date('Y-m-d H:i:s', strtotime('+1 hour'));
		$credentials["userId"] = $this->getUsersPrimary($username);
		$credentials["userId"] = array_shift($credentials["userId"]);

		$db = new database();
		$data = $db->connectToDB()->update("auth", [
				"token" => $credentials["token"],
				"token_expiry" => $credentials["tokenExpiration"]
			],[
				"username" => $username
			]);

		echo $this->responseBuilder("token", json_encode($credentials));
	}

	private function responseBuilder($messageType, $messageBody)							// a helper function to build a json response to the client
	{
		$response["messageType"] = $messageType;
		$response["messageBody"] = $messageBody; 
		echo json_encode($response);
	}

	private function mergeStateAndWidgetData($state, $widget)
	{
		/*
			Currently, the vars look like this:

			$state
			------
			Array
			(
			    [0] => Array
			        (
			            [stateId] => 10
			            [widgetId] => 4
			            [userId] => 8
			            [widgetData] => "blah"
			        )

			    [1] => Array
			        (
			            [stateId] => 11
			            [widgetId] => 3
			            [userId] => 8
			            [widgetData] => "blah"
			        )

			)
			-------------------------------------------------------------
			$widget
			---------------
			Array
			(
			    [0] => Array
			        (
			            [0] => Array
			                (
			                    [w_id] => 4
			                    [w_name] => Gmail Plugin
			                    [w_codeName] => gmail
			                    [w_pathToCode] => /Gmail
			                )

			        )

			    [1] => Array
			        (
			            [0] => Array
			                (
			                    [w_id] => 3
			                    [w_name] => News Feeds
			                    [w_codeName] => newsfeeds
			                    [w_pathToCode] => /News
			                )

			        )
			)
			-----------------------------------------------------------------

			I'm getting them to look like this:

			Array
			(
			    [0] => Array
			        (
			            [widgetId] => 1
			            [widgetData] => this is some json of all my todos
			            [w_name] => Todo
			            [w_codeName] => todo
			            [w_pathToCode] => /widgets/Todo
			        )

			    [1] => Array
			        (
			            [widgetId] => 2
			            [widgetData] => this is my money calendar stuff
			            [w_name] => Calendar
			            [w_codeName] => calendar
			            [w_pathToCode] => /widgets/Calendar
			        )
			)
			-------------------------------------------------------------

			*/

		$raw = array();

		foreach ($widget as $individualWidget)
		{
			$widgetId = $individualWidget[0]["w_id"];

			foreach ($state as $individualState)
			{
				if ($widgetId == $individualState["widgetId"])
				{
					$temp = array();

					$temp["widgetId"] = $widgetId;
					$temp["stateId"] = $individualState["stateId"];
					$temp["widgetName"] = $individualWidget[0]["w_name"];
					$temp["widgetPath"] = $individualWidget[0]["w_pathToCode"];
					$temp["widgetCodeName"] = $individualWidget[0]["w_codeName"];
					$temp["widgetData"] = $individualState["widgetData"];

					$raw[] = $temp;
				}
			}
		}

		return $raw;

	}

	private function turnStringToArray($string)
	{
		$string = ltrim($string, "[");
		$string = rtrim($string, "]");
		$string = explode(",", $string);
		return $string;
	}
}

$api = new apiHandler();

?>