$(document).ready(function() {
	var IdLemma = $("article#lemma").attr("data-target"),
		w = $("#svg-container").width(), 
		h = 500,
		labelDistance = 0,
		svg = d3.select("#svg-container").append("svg:svg").attr("width", w).attr("height", h),
		color = d3.scale.category20(),
		force = d3.layout.force()
				.gravity(.05)
				.distance(100)
				.charge(-100)
				.size([w, h]);

	d3.json('/API/Sigma', function(error, graph) {
		force
			.nodes(graph.nodes)
			.links(graph.links)
			.start();

		var link = svg.selectAll(".link")
			.data(graph.links)
			.enter().append("line")
			.attr("class", "link")
			.style("stroke-width", function(d) { return Math.sqrt(d.value); });

		var node = svg.selectAll(".node")
			.data(graph.nodes)
			.enter().append("circle")
			.attr("class", "node")
			.attr("r", 5)
			.style("fill", function(d) { return color(d.weight); })
			.call(force.drag);

		node.append("title")
			.text(function(d) { return d.label; });

		var texts = svg.selectAll("text.label")
			.data(graph.nodes)
			.enter().append("text")
			.attr("class", "label")
			.attr("fill", "black")
			.text(function(d) {  return d.label;  });

		force.on("tick", function() {
			link.attr("x1", function(d) { return d.source.x; })
				.attr("y1", function(d) { return d.source.y; })
				.attr("x2", function(d) { return d.target.x; })
				.attr("y2", function(d) { return d.target.y; });

			node.attr("cx", function(d) { return d.x; })
				.attr("cy", function(d) { return d.y; });

			texts.attr("transform", function(d) {
				return "translate(" + d.x + "," + d.y + ")";
			});
		});
	});
});