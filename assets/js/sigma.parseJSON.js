sigma.publicPrototype.parseJSON = function(jsonPath, colors, callback) {
	var sigmaInstance = this;
	coef = 3;
	jQuery.getJSON(jsonPath, function(data) {
		for (i=0; i<data.nodes.length; i++){
			var nodeNode = data.nodes[i];
			var id = nodeNode.id;
			var label = nodeNode.name;
			var color = colors[nodeNode.group];
			var xX = 4.5*Math.random();
			var yY = 4.5*Math.random();
			var size = parseInt(Math.sqrt(parseInt(nodeNode.weight)))*coef;
			var clean_node = {id:id, label:label, color:color, 'x':xX, 'y':yY, attributes:[{attr:"group", val:nodeNode.group}], size:size};
			for (j=0; j < nodeNode.attributes.length; j++){
				var raw_attribute = nodeNode.attributes[j];
				var attr = raw_attribute.for;
				var val = raw_attribute.value;
				clean_node.attributes.push({attr:attr, val:val});
			}
			sigmaInstance.addNode(id, clean_node);
		};
		for(i=0; i<data.links.length; i++){
			var edgeNode = data.links[i];
			var edge = {
				id: i,
				sourceID: parseFloat(edgeNode.source),
				targetID: parseFloat(edgeNode.target),
				label: Math.random(),
				weight: parseInt(edgeNode.value)*20,
				displaySize: parseInt(edgeNode.value)*2,
				size: parseInt(edgeNode.value),
				attributes: [],
				color : edgeNode.color,
				true_color : edgeNode.color
			};
			if (edgeNode.attributes) {
				for (j=0; j < edgeNode.attributes.length; j++){
					var raw_attribute = edgeNode.attributes[j];
					var attr = raw_attribute.for;
					var val = raw_attribute.value;
					edge.attributes.push({attr:attr, val:val});
				}
			}
			sigmaInstance.addEdge(i, edge.sourceID, edge.targetID, edge.weight, edge);
		};
		if (callback) callback.call(this);
	});
};
