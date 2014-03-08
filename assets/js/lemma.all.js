$(document).ready(function() {
	$("#search").on("keyup", function() {
		var value = $(this).val();

		$("#lemmas tbody tr").each(function(index) {
			//if (index != 0) {

				$row = $(this);

				var id = $row.find("td:first").text();

				if (id.indexOf(value) != 0) {
					$(this).hide();
				}
				else {
					$(this).show();
				}
			//}
		});
	});
});