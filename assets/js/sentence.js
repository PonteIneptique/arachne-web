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
										return ' <a href="#" class="thumbs-up">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></a> ';
									} else {
										return ' <a href="#" class="thumbs-up">0 <span class="glyphicon glyphicon-thumbs-up"></span></a> ';
									}
								}
							})
						).append(
							$("<div/>", {
								"class" : "col-xs-3",
								html : function() {
									if( vote <= 0 ) {
										return ' <a href="#" class="thumbs-down">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></a> ';
									} else {
										return ' <a href="#" class="thumbs-down">0 <span class="glyphicon glyphicon-thumbs-down"></span></a> ';
									}
								}
							})
						)
					)
				);
				console.log(item["text_lemma"])
				console.log(that.find(".append-in"));
				that.next(".popover").find(".append-in").append(lemma);
			}); //End each
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

});