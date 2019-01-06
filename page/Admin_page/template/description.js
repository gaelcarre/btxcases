var moduleGestionDescription;

if(!moduleGestionDescription) {
	moduleGestionDescription = {};
}


moduleGestionDescription = (function() {

	var init = function() {
		$(".edit").hide();
		$("i.icon-ok").hide();
		$("i.icon-pencil").click(function(){
			var parent = $(this).parents(".item-description");
			parent.find(".read").hide();
			parent.find(".edit").show();
			parent.find("i.icon-ok").show();
			parent.find("i.icon-pencil").hide();
		});
		$("i.icon-ok").click(function(){
			var parent = $(this).parents(".item-description");
			newcontent = parent.find("textarea");
			if(newcontent.val() == "") {
				newcontent.addClass("error");
				alert("le champ est vide !");
			}
			else {
				if(confirm("T'es sur ?")) {
					modifier($(this).data("id"), newcontent.val());
					//$(location).attr('href',"admin/description");
				}
			}
		});
	};

	var modifier = function(id, content) {
		$.ajax({
			url: "admin/editIndexDesc?ajaxRequest=1",
			method: "POST",
			data: {'idToEdit':id, 'content': content},
			success: function(res){
				console.log(res);
			}

		});
	}

	return {
		init: init
	};
})();

$(document).ready(function() {
	moduleGestionDescription.init();
});