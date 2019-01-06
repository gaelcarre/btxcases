var moduleGestionActu;

if(!moduleGestionActu) {
	moduleGestionActu = {};
}


moduleGestionActu = (function() {

	var init = function() {
		$(".new_item .btn-success").hide();
		$(".new_item .edit").hide();
		$("#new_item_news").hide();
		$(".new_item .btn-primary").click(function(){
			var parent = $(this).parents(".new_item");
			parent.find(".read").hide();
			parent.find(".edit").show();
			parent.find(".btn-primary").hide();
			parent.find(".btn-success").show();
		});
		$(".new_item .btn-danger").click(function(){
			var parent = $(this).parents(".new_item");
			if(confirm("T'es sur ?")){
				parent.hide();
				supprimer(parent.data("id"));
			}
			
		});
		$(".new_item .btn-success").click(function(){
			var parent = $(this).parents(".new_item");
			newcontent = parent.find("textarea").val();
			parent.find(".read").show();
			parent.find(".edit").hide();
			parent.find(".btn-primary").show();
			parent.find(".btn-success").hide();
			parent.find(".read p:first").html(newcontent);
			modifier(parent.data("id"), newcontent);
		});
		$("#ajouterNews").click(function(){
			if(!$(this).hasClass("disabled"))
			{
				$("#ajouterNews").addClass("disabled");
				$("#new_item_news").show();
			}
			
		});
	};

	var supprimer = function(id) {
		$.ajax({
			url: "admin/deleteNews?ajaxRequest=1",
			method: "POST",
			data: {'idToDelete':id},
			success: function(res){
				console.log(res);
			}

		});
	};

	var modifier = function(id, content) {
		$.ajax({
			url: "admin/editNews?ajaxRequest=1",
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
	moduleGestionActu.init();
});