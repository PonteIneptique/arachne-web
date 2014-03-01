	/* finds the intersection of 
	 * two arrays in a simple fashion.  
	 *
	 * PARAMS
	 *  a - first array, must already be sorted
	 *  b - second array, must already be sorted
	 *
	 * NOTES
	 *
	 *  Should have O(n) operations, where n is 
	 *    n = MIN(a.length(), b.length())
	 */
function intersect_safe(array1, array2) {
	var result = [];
	var a = array1.slice(0);
	var b = array2.slice(0);
	var aLast = a.length - 1;
	var bLast = b.length - 1;
	while (aLast >= 0 && bLast >= 0) {
		if (a[aLast] > b[bLast]) {
			a.pop();
			aLast--;
		} else if (a[aLast] < b[bLast]) {
			b.pop();
			bLast--;
		} else	{
			result.push(a.pop());
			b.pop();
			aLast--;
			bLast--;
		}
	}
	return result;
};
function init() {
	
    /*
     * Canvas
     */ 
	
	filters = {
		item : false,
		lemma : false,
		metadata: {}
	}
	cleanFilters = {
		item : false,
		lemma : false,
		metadata: {}
	}
	var getAttr = function(node) {
		var attr = node.attr.attributes;
		var attributes = {}
		for(z=0; z<attr.length; z++) {
			var att = attr[z];
			attributes[att["attr"]] = att["val"];
		}
		return attributes;
	}
	var applyFilters = function() {
		var neighbors = {};
		var availableNodes = {};
		var filteredAlready = false;
		
		//We make visible everything
		sigInst.iterEdges(function(e){
			e.hidden = 0;
		}).iterNodes(function(n){
			n.hidden = 0;
		});
		
		//We make first the subgraph of a selected item
		if(filters.item != false) {
			var filteredAlready = true;
			var nodes = filters.item;
			console.log(nodes);
			sigInst.iterEdges(function(e){
				if(nodes.indexOf(e.source)>=0 || nodes.indexOf(e.target)>=0){
					neighbors[e.source] = 1;
					neighbors[e.target] = 1;
				}
			}).iterNodes(function(n){
				if(!neighbors[n.id]){
					n.hidden = 1;
				}else{
					n.hidden = 0;
					availableNodes[n.id] = true;
				}
			});
		}
		
		//Then we apply lemma filter
		if(filters.lemma === true) {
			sigInst.iterNodes(function(n){
				var attr = getAttr(n);
				if(attr.group == 1) {
					if(filteredAlready && filters.item[0] == n.id) {
						n.hidden = 0;
					} else {
						n.hidden = 1;
						availableNodes[n.id] = true;
					}
				} else {
					if(filteredAlready && availableNodes[n.id]) {
						n.hidden = 0;
						availableNodes[n.id] = true;
					} else if (filteredAlready && !availableNodes[n.id]){
						n.hidden = 1;
					} else {
						n.hidden = 0;
						availableNodes[n.id] = true;
					}
				}
			});
			var filteredAlready = true;
		}
		
		//Metadata filter
		jQuery.each(filters.metadata, function(t, m) {
			//For each metadata type selected, we set stuff hidden
			if(m.length > 0) {
				//We check that the array of label selected is not empty
				sigInst.iterNodes(function(n){
					var attr = getAttr(n);
					if(filteredAlready) {
						if(availableNodes[n.id]) {
							if(attr[t]) {
								if(intersect_safe(attr[t], m).length > 0) {
									n.hidden = 0;
									availableNodes[n.id] = true;
								} else {
									n.hidden = 1;
								}
							} else {
								n.hidden = 1;
							}
						}
					} else {
						//Filter apply only on group 2 (Lemma)
						if(attr.group == 2) {
							if(attr[t]) {
								if(intersect_safe(attr[t], m).length > 0) {
									n.hidden = 0;
									availableNodes[n.id] = true;
								} else {
									n.hidden = 1;
								}
							} else {
								n.hidden = 1;
							}
						}
					}
				});
				filteredAlready = true;
			}
		});
		var filteredAlready = true;
		sigInst.draw(2,2,2);
	}
	var color = {
		1 : "rgb(44, 62, 80)",
		2 : "rgb(211, 84, 0)"
	}
	// Instanciate sigma.js and customize it :
	var sigInst = sigma.init(document.getElementById('svg-container')).drawingProperties({
		defaultLabelColor: '#000000',
		defaultLabelSize: 14,
		defaultLabelBGColor: '#fff',
		defaultLabelHoverColor: '#000',
		labelThreshold: 1,
		defaultEdgeType: 'curve',
		edgeColor: "target"
	}).graphProperties({
		minNodeSize: 1,
		maxNodeSize: 30
	}).mouseProperties({
		maxRatio: 10
	});;
	
	sigInst.parseJSON("./API/sigma", color);
	// Start the ForceAtlas2 algorithm
	// (requires "sigma.forceatlas2.js" to be included)
	sigInst.startFruchtermanReingold();
	 
	var isRunning = true;
	
	document.getElementById('stop-layout').addEventListener('click',function(){
		if(isRunning){
			isRunning = false;
			sigInst.stopFruchtermanReingold();
			document.getElementById('stop-layout').childNodes[0].nodeValue = 'Start Layout';
		}else{
			isRunning = true;
			sigInst.startFruchtermanReingold();
			document.getElementById('stop-layout').childNodes[0].nodeValue = 'Stop Layout';
		}
	},true);
	
	document.getElementById('item-layout').addEventListener('click',function(){
		if(isRunning){
			isRunning = false;
			sigInst.stopFruchtermanReingold();
			document.getElementById('item-layout').childNodes[0].nodeValue = 'Scale to node';
		}else{
			isRunning = true;
			sigInst.startFruchtermanReingold();
			document.getElementById('item-layout').childNodes[0].nodeValue = 'End scaling';
		}
	},true);
	
	document.getElementById('rescale-graph').addEventListener('click',function(){
		sigInst.position(0,0,1).draw();
	},true);
	
	sigInst.bind("downnodes", function(event) {
		filters.item = event.content;
		applyFilters();
		
		//Get node
		sigInst.iterNodes(function(n){
			node = n;
		},[event.content[0]]); 
		
		attributes = getAttr(node);
		
		if(attributes.group == 2) { //Lemma
			var url = "lemme";
		} else {
			var url = "author";
		}
		jQuery.getJSON("./API/" + url + "/"+attributes.uid+"/sentence", function(data) {
			var html = "<dl>";
			$.each(data, function( index, value ) {
				html += "<dt>" + value.author + ", " + value.book + " <a target=\"_blank\" href=\"" + value.url + "\"><span class=\"glyphicon glyphicon-globe\"></span><span class=\"text-hide\">Source</span></a></dt>";
				html += "<dd> " + value.sentence + "</dd>";
				//alert( index + ": " + value );
			});
			html += "</dl>";
			$("#attributepane").show();
			$("#attributepane h1").text(node.label);
			$("#data-show").html(html);
		});
	});
	$("li[data-toggle=metadata]").on("click", function() {
		var m = $(this).attr("data-metadata");
		var t = $(this).parent().parent().attr("data-typemetadata");
		
		if(filters.metadata[t]) {
			if (filters.metadata[t].indexOf(m) >= 0) {
				filters.metadata[t].splice(filters.metadata[t].indexOf(m), 1);
				$(this).removeClass("active");
			} else {
				filters.metadata[t].push(m);
				$(this).addClass("active");
			}
		} else {
			filters.metadata[t] = [m];
			$(this).addClass("active");
		}
		
		applyFilters();
	});
	$("#attributepane .close").on("click", function() { $("#attributepane").hide(); });
	$(".show-lemma").on("click", function() { 
		status = parseInt($(this).attr("data-status"));
		if (status == 1) {
			status = 0;
			filters.lemma = false;
			applyFilters();
		} else {
			filters.lemma = true;
			status = 1;		
			applyFilters();			
		}
		$(this).attr("data-status", status);
	});
	$(".clean-focus, #attributepane .close").on("click", function() {
		filters.item = false;
		applyFilters();
	});
	$(".clean-filters").on("click", function() {
		filters = cleanFilters;
		applyFilters();
		
		$("li.active").each(function() {
			$(this).removeClass("active");
		});
	});
	$("#identitypane .close").on("click", function() {
		$("#identitypane").hide();
	});
	
	$("#identitypane .close").on("click", function() {
		$("#identitypane").hide();
	});
	$("#identitypaneopen").on("click", function() {
		$("#identitypane").show();
	});
}
if (document.addEventListener) {
	document.addEventListener('DOMContentLoaded', init, false);
} else {
	window.onload = init;
}
$(document).ready(function() {
	$('#stop-layout').popover('show');
	$('#stop-layout').on('click', function() {
		$(this).popover('hide');
	});
});
