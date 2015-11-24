<!DOCTYPE html>
<html>
<head>
	<title>oi</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="messages">

	<h3>hello, world.</h3>
	<h4>this is the front end which consumes the api</h4>

</div>

<div class="api-builder">
	<select id="httpMethod">
		<option value="POST">POST</option>
		<option value="GET">GET</option>
		<option value="PUT">PUT</option>
		<option value="DELETE">DEL</option>
	</select>
	<div class="rootUrl">/api/</div>
	<input type="text" value="user" id="endpoint">
	<input type="button" value="perform ajax" id="doAjax">
</div>

<div class="output"></div>


<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="app.js"></script>

</body>
</html>