$(document).ready(function() {
	$(".sentence-stack").on("click", ".sentence-author", function() {
		$(this).next(".sentence-book").toggle("slow");
	});
	$(".sentence-stack").on("click", ".sentence-book", function() {
		$(this).next(".sentence-list").toggle(300);
	});
	$('*[data-toggle="tooltip"]').tooltip();

});