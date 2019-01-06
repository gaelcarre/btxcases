var moduleGestionDesc;

if(!moduleGestionDesc) {
	moduleGestionDesc = {};
}


moduleGestionDesc = (function() {

	var init = function() {
		$(".edit").hide();
		$("i.icon-ok").hide();
		$("#new_item_desc").hide();
		$(".btnAddDesc").click(function(){
			var parent = $(this).parents("td");
			parent.find(".read").hide();
			parent.find(".edit").show();
		});
		$(".validSaveDesc").click(function(){
			text = $(this).parent().find("textarea");
			if(text.val() == "") {
				text.addClass("error");
				alert("le champ est vide !");
			}
			else {
				newcontent = text.val();
				sauvegarderNouvelleDescription($(this).data("id"), $(this).data("lng"),newcontent);
				$(this).parents("td").empty().html(newcontent);
			}
		});
		$("i.icon-remove").click(function(){
			parent = $(this).parents("tr");
			lng = parent.find("td:first").html();
			if(confirm("T'es sur ?")){
				supprimer($(this).data("id"));
				$(location).attr('href',"admin/styles");
			}
		});
		$("i.icon-pencil").click(function(){
			parent = $(this).parents("tr");
			parent.find(".read").hide();
			parent.find(".edit").show();
			$("i.icon-ok").show();
			$("i.icon-pencil").hide();
		});
		$("i.icon-ok").click(function(){
			parent = $(this).parents("tr");
			newcontent = parent.find("textarea");
			if(newcontent.val() == "") {
				newcontent.addClass("error");
				alert("le champ est vide !");
			}
			else {
				if(confirm("T'es sur ?")) {
					modifier($(this).data("id"), newcontent.val());
					$(location).attr('href',"admin/styles");
				}
			}
		});
		$("#ajouterDesc").click(function(){
			if(!$(this).hasClass("disabled"))
			{
				$("#ajouterDesc").addClass("disabled");
				$("#new_item_desc").show();
			}
			
		});
		
	};

	var supprimer = function(id) {
		$.ajax({
			url: "admin/deleteDesc?ajaxRequest=1",
			method: "POST",
			data: {'idToDelete':id},
			success: function(res){
				console.log(res);
			}

		});
	};

	var modifier = function(id, content) {
		$.ajax({
			url: "admin/editDescription?ajaxRequest=1",
			method: "POST",
			data: {'idToEdit':id, 'content': content},
			success: function(res){
				console.log(res);
			}

		});
	};

	var sauvegarderNouvelleDescription = function(idToEdit, lang, content) {
		$.ajax({
			url: "admin/addDesc?ajaxRequest=1",
			method: "POST",
			data: {'idToEdit':idToEdit, 'content': content, 'lng': lang},
			success: function(res){
				console.log(res);
			}

		});
	};



	return {
		init: init
	};
})();

$(document).ready(function() {
	moduleGestionDesc.init();
});