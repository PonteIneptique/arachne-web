$(document).ready(function() {
	$(".background-like .help").on("click", function(e) {
		e.preventDefault();
		$(".shadow-content.help").toggle();
	});
	if(typeof $.cookie('help-seen-sentence') === "undefined") {
		$(".shadow-content.help").show();
	}
	$(".shadow-content.help .close").on("click", function(e) {
		$.cookie('help-seen-sentence', true, { expires: 31 });
		e.preventDefault();
		$(this).parent().hide();
	});
	$('a.sentence-lemma').
	on("click", function(e) { 
		e.preventDefault();
		$(".sentence-lemma").attr("data-active", 0);
		$(this).attr("data-active", 1);
		$(this).trigger("json");
	});

	var $lemmaSearch = $("#lemmaSearch");
	var $lemmaSearchBH = new Bloodhound(
		{
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: 
			{
				url : "/API/lemma/?query=%QUERY",
				filter : function(parsedResponse) {
					var result = [];
					if(parsedResponse != null) {
						if(typeof parsedResponse.length !== "undefined" && parsedResponse.length !== 0) {
							for (var i=0; i<parsedResponse.length; i++) {
								//Need to check if item not already in the db
								result.push({
									name: parsedResponse[i]["lemma"],
									value: parsedResponse[i]["lemma"]
								});
							}
						}
					}
					return result;
				}
			}
		});
	$lemmaSearchBH.initialize();
	var $lemmaSearchTemplate = Handlebars.compile('{{name}}');
	$('#lemmaSearch').typeahead(
		null,
		{
			name: "lemmaSearch",
			source: $lemmaSearchBH.ttAdapter(),
			templates: {
				suggestion : $lemmaSearchTemplate
			}
		}
	);

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
		var showFP = true;
		//Attributes
		that = $(this)
		form = that.attr("data-id");
		sentence = $(".sentence-text").attr("data-id");
		url = "/API/sentence/" + sentence + "/form/" + form;
		$("#forms-container .sidebar-category-title").text(that.text());
		$("#polarity-forms").empty().parent().hide();

		$("#lemma-sidebar").find(".append-in").empty();
		$("#lemma-sidebar").find(".annotations-containers").empty();
		if(form != "0") {
			var json = $.getJSON(url, function(data) {
				if(typeof data !== "undefined" && data != null) {
					$.each(data["lemma"], function(i, item) {
						vote = parseInt(item["votes"]);
						id_lemma = parseInt(item["id_lemma"]);
						if(id_lemma === -1) { showFP = false;}
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
					

						//Polarity
						if(!that.hasClass("query-lemma")) {
							var $polModel = $("#polarity-model")
												.clone()
												.attr("id", "polarity-form-" + item["id_lemma"])
												.attr("data-target", item["id_lemma"]);

							$polModel.find(".lemma-append-text").text(item["text_lemma"]);
							$polModel.find("input[type='radio']").attr("name", "polarity-" + item["id_lemma"]);
							if(item["polarity"] !== null) {
								$polModel.find("input[value='" + item["polarity"] + "']").attr("checked", "checked");
							}

							//In polModel, check if there is an attribute for
							//TODO
							
							$("#polarity-forms").append($polModel).parent().show();

						}
						//End Polarity				


					}); //End each	lemma


					//Relationships
					if(!that.hasClass("query-lemma")) {
						$("#relationships-forms").empty();
						$.each(data["relationships"], function(i, item) {
							$relationship_form = $navstack.
								clone().
								append(
										$("<div />", {
											"class" : "nav-6",
											"html" : function() {
												return $("#lemma-annotation-source select.relationships option[value='" + item["value_relationship"] + "']").text();
											}
										})
									).append($("<div />", {
											"class" : "nav-6",
											"html" : function() {
												return $("#lemma-annotation-source select.lemma-query option[value='" + item["id_lemma"] + "']").text();
											}
										}
									));
							$("#relationships-forms").append($relationship_form);
						});
						var $relSelect = $("#lemma-annotation-source select.relationships").clone();
						var $relQuery = $("#lemma-annotation-source select.lemma-query").clone();

						$relationship_form = 
							$navstack.
								clone().
								addClass("new-relationship").
								append(
										$("<div />", {
											"class" : "nav-4"
										})
										.append(
											$relSelect
										)
									).append($("<div />", {
											"class" : "nav-4"
										})
										.append($("<div />", {
												"class" : "target-type"
											}).append(
												$relQuery
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
						
						$("#relationships-forms").append($relationship_form).parent().show();

					} else {
						$("#relationships-forms").empty().parent().hide();
					}
					//End relationships	

				}
				if(showFP === true) {
					$(".falsePositive").show();
				} else {
					$(".falsePositive").hide();
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

		lemma = parent.find("#lemmaSearch").val();
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

	$("#lemma-sidebar").on("click", ".falsePositive", function(e) {
		e.preventDefault();
		that = $(this);

		lemma = -1;
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
				a.trigger("click");
				//sideBarFalsePositive();
			}
		});
	});

	$("#lemma-sidebar").on("click", ".new-relationship .submit", function(e) {
		e.preventDefault();
		parent = $(this).parents(".new-relationship");

		sentence = $(".sentence-text").attr("data-id");
		lemma = parent.find(".lemma-query").val();
		value = parent.find(".relationships").val();
		form = $("a.sentence-lemma[data-active='1']").attr("data-id");

		$.post("/API/relationship", {
			"sentence" : sentence,
			"lemma" : lemma,
			"form" : form,
			"val" : value
		}, function(data) {
			if(typeof data["status"] !== "undefined" && data["status"] == "success") {
				a = $("a.sentence-lemma[data-active='1']");
				a.trigger("click");
			}
		});
	})

	$("#lemma-sidebar").on("click", ".polarity-form input[type='radio']", function(e) {
		//e.preventDefault();
		parent = $(this).parents(".polarity-form");

		lemma = parent.attr("data-target");
		value = parent.find("input[type='radio']:checked").val();

		$.post("/API/polarity", {
			"lemma" : lemma,
			"val" : value
		}, function(data) {
			if(typeof data["status"] !== "undefined" && data["status"] == "success") {
				a = $("a.sentence-lemma[data-active='1']");
				a.trigger("click");
			}
		});
	});;




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
		//$(".sidebar-category-content").hide();
		$(this).parents(".sidebar-category").find(".sidebar-category-content").toggle();
	});
});