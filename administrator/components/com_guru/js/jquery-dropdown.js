function alertNotification(){
	document.getElementById("guru-not-content").className = "open";
}

$(document).click(function(e){
	if ($(e.target).attr('id') != 'guru-dropdown-toggle' && $(e.target).attr('id') != 'icon-bell' && $(e.target).attr('id') != 'badge-important' && $(e.target).attr('id') != 'new-options-button'){
		if(eval(document.getElementById("guru-not-content"))){
			document.getElementById("guru-not-content").className = "";
		}
		
		if(eval(document.getElementById("button-options"))){
			document.getElementById("button-options").style.display = "none";
		}
	}
})