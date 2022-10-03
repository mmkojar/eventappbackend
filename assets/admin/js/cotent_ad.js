
$(document).ready(function(){
		$.noConflict();
		$('#assignto').multiselect({
			columns: 1,
			placeholder: 'Select Users To Assign',
			search: true,
			selectAll: true
		});
		$("#images").hide();
		$("#images1").hide();
		$("#videos").hide();
		change();
	
});

function change(){
		var selected =  $("#content_type").val();
		
		if(selected == "image"){
			$("#videos").hide();
			$("#images").show();
			$("#images1").show();
		}else if(selected == "video"){
			$("#videos").show();
			$("#images").hide();
			$("#images1").hide();
		}
}