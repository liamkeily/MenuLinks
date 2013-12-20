var linklist;
$(function(){
	
	$("#addlink").click(function(){
		showForm();
	});
	$(".linkmenu").change(function(){
		loadParents();
	});

	showForm();
});
function showForm(){
	if($("#addlink").attr('checked') == 'checked'){
		$(".linkform").show();
	}
	else
	{
		$(".linkform").hide();
	}
}
function loadParents(){
	var menu = $(".linkmenu").val();
	var links = linklist[menu];
	var linkselect = '<option value="">-- Top Level --</option>';

	for(key in links){
		linkselect += '<option value="' + key + '">' + links[key]+ '</option>';	
	}
		$(".linkparent").html(linkselect);
}

