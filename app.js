$(document).ready(function(){

	var rootUrl = "http://localhost/rest-backend/api/"

	$("#doAjax").click(function(){

		var endpoint = $("#endpoint").val();
		var httpMethod = $("#httpMethod").val();

		$.ajax({
			type: httpMethod,
			url: rootUrl + endpoint,
			success: function(response)
			{
				$(".output").html("<div class='output-content'><h4>Server Response:</h4>\
					<div class='response'>"+ response +"</div></div>");
			},
			error: function(response)
			{
				if (response.status == 404)
				{
					$(".output").html("<div class='output-content'><h4>Server Response:</h4>\
					<div class='response'>Disallowed Method</div></div>");
				}
			}
		});

	});
});