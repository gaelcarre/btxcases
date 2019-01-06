var moduleGestionCDS;

if(!moduleGestionCDS) {
	moduleGestionCDS = {};
}


moduleGestionCDS = (function() {

	var init = function() {
		$('#myTab a').click(function (e) {
  			e.preventDefault();
  			$(this).tab('show');
			})
	};

	

	return {
		init: init
	};
})();

$(document).ready(function() {
	moduleGestionCDS.init();
});