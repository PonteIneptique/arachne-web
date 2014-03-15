$(document).ready(function() {
	$(".new-value-line").on("click", ".add-value", function(e) {
		e.preventDefault();
		that = $(this);
		target = that.attr("data-target");
		par = that.parent();
		par.empty();

		par.append(
			$("<form />", {
				"class" : "form-inline",
				"data-target" : target,
				"action" : "/API/annotations/type/" + target
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