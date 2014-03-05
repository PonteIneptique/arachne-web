$(document).ready(function() {

        $('a.sentence-lemma').on("click", function(e) { e.preventDefault(); }).popover({
        	html: true,
        	placement : "bottom",
        	content : function() {
        		return $("#popover").html();
        	}
        });
        $(".sentence-text").on("click", ".popover-category-title", function(e) {
        	console.log($(this).parent())
        	e.preventDefault();
        	$(".popover-category-form").hide();
        	$(this).parent().parent().parent().find(".popover-category-form").show();
        });
        $(".sentence-text").on("click", ".popover-title", function(e) {
        	console.log("Here");
        	$(".popover-category-form").hide();
        	$(".popover-category-lemmas").show();
        });

});