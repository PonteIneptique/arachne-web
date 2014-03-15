$(document).ready(function() {
	$("#annotations-list").on("submit", ".new-value", function(e) {
		e.preventDefault();
		form = $(this);
		url = form.attr("action");
		name = form.find('input[name="name"]').val();
		$.post(
			url, 
			{
				"name" : name
			}, 
			function(data) {
				if(typeof data["id"] !== "undefined") {
					par = form.parent();
					par.empty();

					par.append(
						$("<a />", {
							"class" : "add-value",
							"data-target" : target,
							"href" : "#",
							"html" : '<span class="glyphicon glyphicon-plus-sign"></span>'
						})
					);

					par.before(
						$("<li />", {
							"html" : name
						})
					);
				}
			}
		);
	});
	$("#new-type").on("submit", function(e) {
		e.preventDefault();
		that = $(this);
		url = that.attr("action");
		$.post(
			url, 
			{
				"type_name" : that.find('input[name="type_name"]').val(),
				"target" : that.find('select[name="target"]').val()
			}, 
			function(data) {
				if(typeof data["id"] !== "undefined") {
					$("#annotations-list tbody").prepend(
						$("<tr />").append(
							$("<td />", {
								"html" : that.find('select[name="target"]').val()
							})
						).append(
							$("<td />", {
								"html" : that.find('input[name="type_name"]').val()
							})
						).append(
							$("<td />").append(
								$("<ul />", {
									"class" : "list-unstyled list-value"
								}).append(
									$("<li />", {
										"class" : "new-value-line"
									}).append(
										$("<a />", {
											"class" : "add-value",
											"data-target" : data["id"],
											"href" : "#",
											"html" : '<span class="glyphicon glyphicon-plus-sign"></span>'
										})
									)
								)
							)
						)
					);
					that.find('input[name="type_name"]').val("");
				}
			}
		);
	});
	$("#annotations-list tbody").on("click", ".add-value", function(e) {
		e.preventDefault();
		that = $(this);
		target = that.attr("data-target");
		par = that.parent();
		par.empty();

		par.append(
			$("<form />", {
				"class" : "form-inline new-value",
				"data-target" : target,
				"action" : "/API/annotations/value/" + target
			}).append(
				$("<div />", {
					"class" : "form-group"
				}).append(
					$("<input />", {
						"class" : "form-control",
						"name" : "name",
						"type" : "text",
						"placeholder" : "Name of the value"
					})
				)
			).append(
				$("<input />", {
					"class" : "btn btn-default",
					"name" : "submit",
					"type" : "submit",
					"value" : "Add"
				})
			).append(
				$("<a />", {
					"class" : "del-value",
					"href" : "#",
					"html" : '<span class="glyphicon glyphicon-minus-sign"></span>'
				}).on("click", function(e) {
					e.preventDefault();

					that = $(this);
					form = that.parents("form");
					target = form.attr("data-target");

					par = form.parent();
					par.empty();

					par.append(
						$("<a />", {
							"class" : "add-value",
							"data-target" : target,
							"href" : "#",
							"html" : '<span class="glyphicon glyphicon-plus-sign"></span>'
						})
					);


				})
			)
		);

	});
});