$(document).click(function(e){
	if ($(e.target).attr('id') != 'new-options'){
		if(eval(document.getElementById("button-options"))){
			document.getElementById("button-options").style.display = "none";
		}
	}
})