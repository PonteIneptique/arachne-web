$(document).ready(function() {

	$(".dial").knob();

	$('a.sentence-lemma').
	on("click", function(e) { 
		e.preventDefault();
		$(".sentence-lemma").attr("data-active", 0);
		$(this).attr("data-active", 1);
		$(this).trigger("json");
	});

	$("#sentence-sidebar").on("json", function() {
		that = $(this);
		annoContainer = that.find(".annotations-container");
		annoContainer.empty();

		$navstack = $("<div />", {
			"class" : "nav-stack nav-stack-grey"
		});

		var json = $.getJSON("/API/annotations/sentence/" + $(".sentence-text").attr("data-id"), function(data) {
			$.each(data, function(i, annData) {
				anno = $navstack.clone().append(
							$("<div/>", {
								"class" : "nav-8",
								html : annData["text_type"] + " : " + annData["text_value"]
							})
						).append(
							$("<div/>", {
								"class" : "nav-4",
								html : function() {
									vote = annData["votes"];
									if( vote > 0 ) {
										return '<a href="#" class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></a><a href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></a>';
									} else {
										return '<a href="#" class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-up"></span></a><a href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></a>';
									}
								}
							})
						).addClass("annotation-vote");
				annoContainer.append(anno);
			});
		});
	});

	$(".sentence-text").on("json", "a.sentence-lemma", function(e) {

		//Attributes
		that = $(this)
		form = that.attr("data-id");
		sentence = $(".sentence-text").attr("data-id");
		url = "/API/sentence/" + sentence + "/form/" + form;
		$("#forms-container .sidebar-category-title").text(that.text());


		$("#lemma-sidebar").find(".append-in").empty();
		$("#lemma-sidebar").find(".annotations-containers").empty();
		if(form != "0") {
			var json = $.getJSON(url, function(data) {
				if(typeof data !== "undefined" && data != null) {
					$.each(data, function(i, item) {
						vote = parseInt(item["votes"]);
						if (i === 0) {
							cl = " active ";
						} else {
							cl = "";
						}
						lemma = $( "<div/>", {
							"class": "nav-stack nav-stack-grey" + cl
						}).append(
							$("<div/>", {
									"class" : "nav-6",
									html : "<a class='lemma' data-id='" + item["id_lemma"] + "'>" + item["text_lemma"] + "</a>"
								})
							).append(
								$("<div/>", {
									"class" : "nav-3",
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
									"class" : "nav-3",
									html : function() {
										if( vote <= 0 ) {
											return ' <a href="#" class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></a> ';
										} else {
											return ' <a href="#" class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></a> ';
										}
									}
								})
						);
						

							annoContainer = $("<div />", {
								"class" :"sidebar-category"
							}).append($("<a />", {
								"class" : "nav-stack nav-stack-black full sidebar-category-title",
								"href" : "#",
								"text" : 'Annotation for ' + item["text_lemma"]
								})
							);
							
							annoContainer.append($("<div />", { "class" : "sidebar-category-content" }));
							$navstack = $("<div />", {
								"class" : "nav-stack nav-stack-grey"
							});


							if(typeof item["annotations"] !== "undefined" && item["annotations"].length > 0) {
								$.each(item["annotations"], function(i, annData) {
									anno = $navstack.clone().append(
												$("<div/>", {
													"class" : "nav-8",
													html : annData["text_type"] + " : " + annData["text_value"]
												})
											).append(
												$("<div/>", {
													"class" : "nav-4",
													html : function() {
														vote = annData["votes"];
														if( vote > 0 ) {
															return '<a href="#" class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></a><a href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></a>';
														} else {
															return '<a href="#" class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-up"></span></a><a href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></a>';
														}
													}
												})
											).addClass("annotation-vote");
									annoContainer.find(".sidebar-category-content").append(anno);
								});	
							}

							/*Adding new annotation form*/
							$annotation_form = 
								$navstack.
									clone().
									addClass("new-annotation").
									attr("data-target", item["id_lemma"]).
									attr("data-table", "lemma").
									append(
											$("<div />", {
												"class" : "nav-4"
											})
											.append(
												$("#lemma-annotation-source select.types").clone()
											)
										).append($("<div />", {
												"class" : "nav-4"
											})
											.append($("<div />", {
													"class" : "target-type"
												})
													.append(
															$("#lemma-annotation-source .value[data-target='" + $("#lemma-annotation-source select.types").val() + "']")
															.clone()
														)
											)
										).append($("<div />", {
												"class" : "nav-4"
											})
											.append($("<button />", {
													"class" : "nav-stack-input submit",
													html : '<span class="glyphicon glyphicon-plus-sign"></span>'
												})
											)
										);
							annoContainer.find(".sidebar-category-content").append($annotation_form);
							$("#lemma-sidebar").find(".annotations-containers").append(annoContainer);

						$("#lemma-sidebar").find(".append-in").append(lemma);
						$("#lemma-sidebar").show();
					}); //End each
				}
			});
		} else {
			$("#lemma-sidebar").show();
		}
	})

	$("#sidebar").on("change", ".types", function(e) {
		e.preventDefault();
		that = $(this);
		parent = that.parents(".new-annotation");
		table = parent.attr("data-table");

		target = parent.find(".target-type");
		target.children().remove();
		console.log($("#" + table + "-annotation-source .value[data-target='" + that.val() + "']"));
		target.append(
			$("#" + table + "-annotation-source .value[data-target='" + that.val() + "']").clone()
		);
	});


	$("#sidebar").on("click", ".new-annotation .submit", function(e) {
		e.preventDefault();
		that = $(this);
		form = that.parents(".new-annotation");
		table = form.attr("data-table");

		$.post("/API/annotations/" + table + "/" + form.attr("data-target"), {
			"type" : form.find(".types").val(),
			"value" : form.find(".value").val()
		}, function(data) {
			if(typeof data["status"] === "undefined" || data["status"] == "error") {
				//Do something ?
			} else {
				if(table == "lemma") {
					$("a.sentence-lemma[data-active='1']").trigger("click");
				} else {
					$("#sentence-sidebar").trigger("json");
				}
			}
		});

	});



	$("#lemma-sidebar").on("click", ".newlemma .submit", function(e) {
		e.preventDefault();
		parent = $(this).parents(".newlemma");

		lemma = parent.find("input[type='text']").val();
		sentence = $(".sentence-text").attr("data-id");
		form = $("a.sentence-lemma[data-active='1']").text();

		$.post("/API/sentence/lemma", {
			"lemma" : lemma,
			"form" : form,
			"sentence" : sentence
		}, function(data) {
			if(typeof data["status"] !== "undefined" && data["status"] == "success") {
				a = $("a.sentence-lemma[data-active='1']");
				a.attr("data-id", data["results"]["form"]);
				a.removeClass("neutral").addClass("red");
				a.trigger("click");
			}
		});
	});




	$("#lemma-sidebar").on("click", ".thumbs-down, .thumbs-up", function(e) {
		e.preventDefault();
		that = $(this);
		if(that.hasClass("thumbs-up")) { val = 1; } else { val = -1; }

		sentence = $(".sentence-text").attr("data-id");
		lemma = that.attr("data-target");
		form = that.attr("data-src");
		$.post("/API/vote/forms", {
			"sentence" : sentence,
			"form" : form,
			"lemma" : lemma,
			"value" : val
		}, function(data) {
			if(typeof data["status"] !== "undefined" && data["status"] == "success") {
				a = $("a.sentence-lemma[data-active='1']");
				a.trigger("click");
			}
		});
	});

	$("#sidebar").on("click", ".annotations-thumbs-down, .annotations-thumbs-up", function(e) {
		e.preventDefault();

		that = $(this);
		if(that.hasClass("annotations-thumbs-up")) { val = 1; } else { val = -1; }
		target = that.attr("data-target");
		$.post("/API/vote/annotations/"+ target, {
			"vote" : val
		}, function(data) {
			if(typeof data["status"] !== "undefined" && data["status"] == "success") {
				if(that.parents("#sentence-sidebar").length == 1) {
					$("#sentence-sidebar").trigger("json");
				} else {
					a = $("a.sentence-lemma[data-active='1']");
					a.trigger("click");
				}
			}
		});
	});

	/*

		Toggles

	*/
	$("#sidebar").on("click", ".sidebar-category-title", function(e) {
		e.preventDefault();
		$(".sidebar-category-content").hide();
		$(this).parents(".sidebar-category").find(".sidebar-category-content").show();
	});
});