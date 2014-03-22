$(document).ready(function() {
	$(".sentence-stack").on("click", ".sentence-author", function() {
		$(this).next(".sentence-book").toggle(200);
	});
	$(".sentence-stack").on("click", ".sentence-book", function() {
		$(this).next(".sentence-list").toggle(200);
	});
	$('*[data-toggle="tooltip"]').tooltip();

	$("#quicksearch").on("keyup", function() {
		var value = $(this).val();
		if(value === "") {
			$(".sentence-stack").show();
			$(".sentence-list").hide();
			$(".sentence-book").hide();
		} else {
			$(".sentence-stack").each(function(index) {
				$row = $(this);

				var author = $row.find("h2").text();
				var book = $row.find("h3").text();

				if (author.indexOf(value) === -1 && book.indexOf(value) === -1) {
					$row.hide();
				} else {
					$row.show();
					$row.find(".sentence-list").show();
					$row.find(".sentence-book").show();
				}
			});
		}
	});
});