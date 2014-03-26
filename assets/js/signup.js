$(document).ready(function () {
	$(".signup-form").on("submit", function(e) {
		var $checkup = $("#checkPolicies");

		if($checkup.is(':checked')) {
			return true;
		} else {
			$(".checkPolicies").css("color", "red");
			e.preventDefault();
			return false;
		}
	});
});