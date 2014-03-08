$(document).ready(function() {

	var s = new sigma({
		container: document.getElementById('svg-container'),
		settings: {
			edgeColor: 'default',
			defaultEdgeColor: 'rgb(222,222,222)',
			labelThreshold : 1
		}
    });
	

	db = new sigma.plugins.neighborhoods();
	db.load('/API/Sigma', function() {
		function refreshGraph(centerNodeId) {
			// Empty the graph:
			s.graph.clear();

			// Read the graph:
			s.graph.read(db.neighborhood(centerNodeId));

			// Refresh the display:
			s.refresh();

	
		}	

		s.bind('doubleClickNode', function(event) {
			if (!event.data.node.center) {
				refreshGraph(event.data.node.id);
			}
		});
	});
	js = sigma.parsers.json('/API/Sigma', function(graph) {
		s.graph.read(graph);
		s.refresh();
	});


	s.bind('clickNode', function(event) {
		console.log(event.data.node.id);
	});
});