<?php

require "/vendor/autoload.php";

$app = new \Slim\Slim();

/*
	Defining all the methods below.
	See https://github.com/intheon/rest-backend#allowed-methods for a full list.
*/

// root /api/
$app->get("/", function() use($app){
	$app->response->setStatus(200);
	echo "chamone";
});

// all users
$app->get("/user", function() use($app){
	$app->response->setStatus(200);
	echo "show me some users";
});

// a user
$app->get("/user/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "i will show you user " . $id;
});

// all widgets
$app->get("/widget", function() use($app){
	$app->response->setStatus(200);
	echo "show all widgets";
});

// a widget
$app->get("/widget/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "i will show you widget " . $id;
});
   
// a state
$app->get("/state/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "i will show you state " . $id;
});

// create a user
$app->post("/user", function() use($app){
	$app->response->setStatus(200);
	echo "user created";
});

// create a widget
$app->post("/widget", function() use($app){
	$app->response->setStatus(200);
	echo "widget created";
});

// create a state
$app->post("/state", function() use($app){
	$app->response->setStatus(200);
	echo "state created";
});

// update a user
$app->put("/user/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "user " . $id . " updated";
});

// update a widget
$app->put("/widget/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "widget " . $id . " updated";
});

// update a state
$app->put("/state/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "state " . $id . " updated";
});

// update a user
$app->delete("/user/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "user " . $id . " deleted";
});

// update a widget
$app->delete("/widget/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "widget " . $id . " deleted";
});

// update a state
$app->delete("/state/:id", function($id) use($app){
	$app->response->setStatus(200);
	echo "state " . $id . " deleted";
});


$app->run();


?>