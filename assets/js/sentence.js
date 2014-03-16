$(document).ready(function() {
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
										return '<span href="#" class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></span><span href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></span>';
									} else {
										return '<span href="#" class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-up"></span></span><span href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></span>';
									}
								}
							})
						).addClass("annotation-vote");
				annoContainer.append(anno);
			});
		});
	});

	$(".sentence-text").on("json", "a.sentence-lemma[data-id!='0']", function(e) {

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
											return ' <span class="thumbs-up" data-src="'+form+'" data-target="' + item["id_lemma"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></span> ';
										} else {
											return ' <span class="thumbs-up" data-src="'+form+'" data-target="' + item["id_lemma"] + '">0 <span class="glyphicon glyphicon-thumbs-up"></span></span> ';
										}
									}
								})
							).append(
								$("<div/>", {
									"class" : "nav-3",
									html : function() {
										if( vote <= 0 ) {
											return ' <span class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></span> ';
										} else {
											return ' <span class="thumbs-down" data-src="'+form+'" data-target="' + item["id_lemma"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></span> ';
										}
									}
								})
						);
						
						if(typeof item["annotations"] !== "undefined" && item["annotations"].length > 0) {
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
														return '<span class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-up"></span></span><span href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-down"></span></span>';
													} else {
														return '<span href="#" class="annotations-thumbs-up" data-target="' + annData["id_annotation"] + '">0 <span class="glyphicon glyphicon-thumbs-up"></span></span><span href="#" class="annotations-thumbs-down" data-target="' + annData["id_annotation"] + '">' + vote + ' <span class="glyphicon glyphicon-thumbs-down"></span></span>';
													}
												}
											})
										).addClass("annotation-vote");
								annoContainer.find(".sidebar-category-content").append(anno);
							});	
						
							$("#lemma-sidebar").find(".annotations-containers").append(annoContainer);

						}

						$("#lemma-sidebar").find(".append-in").append(lemma);
						$("#lemma-sidebar").show();
					}); //End each
				}
			});
		} else {
			$("#lemma-sidebar").show();
		}
	});

	$("#sidebar").on("click", ".new-annotation .submit", function(e) {
		e.preventDefault();
	});



	$("#lemma-sidebar").on("click", ".newlemma .submit", function(e) {
		e.preventDefault();
	});




	$("#lemma-sidebar").on("click", ".thumbs-down, .thumbs-up", function(e) {
		e.preventDefault();
	});

	$("#sidebar").on("click", ".annotations-thumbs-down, .annotations-thumbs-up", function(e) {
		e.preventDefault();
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