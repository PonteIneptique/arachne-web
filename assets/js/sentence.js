$(document).ready(function() {

	$('a.sentence-lemma').
	on("click", function(e) { 
		e.preventDefault(); 
	}).
	popover({
		html: true,
		placement : "bottom",
		content : function() {

			popover = $("#popover");
			$(this).trigger("json");
			return popover.html();
		}
	});

	$(".sentence-text").on("json", "a.sentence-lemma", function(e) {

		//Attributes
		that = $(this)
		form = that.attr("data-id");
		sentence = $(".sentence-text").attr("data-id");
		url = "/API/sentence/" + sentence + "/form/" + form;

		var json = $.getJSON(url, function(data) {
			if(typeof data !== "undefined" && data != null) {
				$.each(data, function(i, item) {
					vote = parseInt(item["votes"]);
					if (i === 0) {
						cl = " active ";
					} else {
						cl = "";
					}
					lemma = $( "<ul/>", {
						"class": "nav nav-pills nav-ehri nav-ehri-grey nav-justified" + cl
					}).append(
						$("<li/>").append(
							$("<div />", {
								"class" : "row"
							}).append(
								$("<div/>", {
									"class" : "col-xs-6",
									html : "<a class='lemma' data-id='" + item["id_lemma"] + "'>" + item["text_lemma"] + "</a>"
								})
							).append(
								$("<div/>", {
									"class" : "col-xs-3",
									html : function() {
										if( vote > 0 ) {
											return ' <a href="#" class="thumbs-up" data-src="'+form+'" data-target="' + item["id_lemma"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></a> ';
										} else {
											return ' <a href="#" class="thumbs-up" data-src="'+form+'" data-target="' + item["id_lemma"] + '">0 <span class="glyphicon glyphicon-thumbs-up"></span></a> ';
										}
									}
								})
							).append(
								$("<div/>", {
									"class" : "col-xs-3",
									html : function() {
										if( vote <= 0 ) {
											return ' <a href="#" class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></a> ';
										} else {
											return ' <a href="#" class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></a> ';
										}
									}
								})
							)
						)
					);
					

					annoContainer = $("<div />", {
						"class" :"popover-category"
					}).append($("<ul />", {
						"class" : "nav nav-pills nav-ehri nav-ehri-black nav-justified"
					}).html('<li><a class="popover-category-title" href="#">Annotation for ' + item["text_lemma"] + '</a></li>'));
					$ul = $("<ul />", {
						"class" : "nav nav-pills nav-ehri nav-ehri-grey nav-justified popover-category-form"
					});
					$.each(item["annotations"], function(i, annData) {
						anno = $("<li/>").append(
								$("<div />", {
									"class" : "row"
								}).append(
									$("<div/>", {
										"class" : "col-xs-8",
										html : annData["text_type"] + " : " + annData["text_value"]
									})
								).append(
									$("<div/>", {
										"class" : "col-xs-4",
										html : function() {
											if( vote > 0 ) {
												return '<a href="#" class="thumbs-up" data-src="'+form+'" data-target="' + item["id_lemma"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></a><a href="#" class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></a>';
											} else {
												return '<a href="#" class="thumbs-up" data-src="'+form+'" data-target="' + item["id_lemma"] + '">0 <span class="glyphicon glyphicon-thumbs-up"></span></a><a href="#" class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></a>';
											}
										}
									})
								)
							);
						$ul.append(anno);
					});
					that.next(".popover").find(".append-in").append(lemma);
					that.next(".popover").find(".annotations-containers").append(annoContainer.append($ul))
				}); //End each
			}
		});
	})

	$(".sentence-text").on("click", ".popover-category-title", function(e) {
	console.log($(this).parent())
		e.preventDefault();
		$(".popover-category-form").hide();
		$(this).parent().parent().parent().find(".popover-category-form").show();
	});

	$(".sentence-text").on("click", ".popover-title", function(e) {
		$(".popover-category-form").hide();
		$(".popover-category-lemmas").show();
	});

	$(".sentence-text").on("click", ".newlemma .submit", function() {
		parent = $(this).parents(".newlemma");

		lemma = parent.find("input[type='text']").val();
		sentence = $(".sentence-text").attr("data-id");
		form = parent.parents(".popover").children(".popover-title").text();

		$.post("/API/sentence/lemma", {
			"lemma" : lemma,
			"form" : form,
			"sentence" : sentence
		}, function(data) {
			if(typeof data["status"] !== "undefined" && data["status"] == "success") {
				a = parent.parents(".popover").prev("a");
				a.attr("data-id", data["results"]["form"]);
				a.removeClass("neutral").addClass("red");
				a.popover("hide");
				a.trigger("click");
			}
		});
	});

	$(".sentence-text").on("click", ".thumbs-down, .thumbs-up", function(e) {
		e.preventDefault();
		that = $(this);
		if(that.hasClass("thumbs-up")) { val = 1; } else { val = -1; }

		sentence = $(".sentence-text").attr("data-id");
		lemma = that.attr("data-target");
		form = that.attr("data-src");
		$.post("/API/forms/vote", {
			"sentence" : sentence,
			"form" : form,
			"lemma" : lemma,
			"value" : val
		}, function(data) {
			if(typeof data["status"] !== "undefined" && data["status"] == "success") {
				a = that.parents(".popover").prev("a");
				a.popover("hide");
				a.trigger("click");
			}
		});
	});

});