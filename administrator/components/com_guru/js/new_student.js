function showUsername(){
	 var div = document.getElementById("user_name");
	 div.style.display = "block";
	 document.adminForm.action.value="new_existing_student";
	 return true;
}

function hideUsername(){
	 var div = document.getElementById("user_name");
	 div.style.display = "none";
	 document.adminForm.action.value="new_student";
	 return true;
}