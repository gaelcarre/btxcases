
var moduleMain;
var moduleHeader;

if(!moduleMain) {
	moduleMain = {};
}

if(!moduleHeader) {
	moduleHeader = {};
}

moduleMain = (function() {

	var init = function() {
		moduleHeader.init();
	};

	return {
		init: init
	};
})();

moduleHeader = (function() {

	var init = function() {
		$("img#image_header").hide();
		var $source = $("img#image_header").attr("src");
		$("header").backstretch($source);
	};

	return {
		init: init
	};

})(); 

$(document).ready(function() {
	moduleMain.init();
});