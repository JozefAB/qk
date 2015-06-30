function update_cart(course_id, price, all_ids, simbol){
	if(price < 0){
		price = 0.00;	
	}
	document.getElementById("cart_item_price"+course_id).innerHTML = simbol+" "+parseFloat(price).toFixed(2);
	document.getElementById("cart_item_total"+course_id).innerHTML = simbol+" "+parseFloat(price).toFixed(2);
	setMaxTotal(simbol, all_ids);
}

function removeCourse(course_id, all_ids, action, cart_empty, link_redirect, click_here, simbol){
	var id = "row_" + course_id;
	var table = document.getElementById("g_table_cart");
	var row = document.getElementById(id);	
	table.deleteRow(row.rowIndex);
	 var req = new Request.HTML({
			method: 'get',
			url: "index.php?option=com_guru&controller=guruBuy&task=deletefromsession&course_id="+course_id+"&action="+action,
			data: { 'do' : '1' },
			onComplete: function(response){		
			}
		}).send();
	
	setMaxTotal(simbol, all_ids, cart_empty, link_redirect, click_here, simbol);
}
//start bootstrap
function update_cartb(course_id, price, all_ids, simbol){
	if(price < 0){
		price = 0.00;	
	}
	
	
	total_id = "cart_item_totalb"+course_id;
	document.getElementById(total_id).innerHTML = simbol+" "+parseFloat(price).toFixed(2);
	document.getElementById("max_totalb1").innerHTML = simbol+" "+parseFloat(price).toFixed(2);
	document.getElementById("max_totalb").innerHTML = simbol+" "+parseFloat(price).toFixed(2);

	setMaxTotalB(simbol, all_ids);
}
function removeCourseB(course_id, all_ids, action, cart_empty, link_redirect, click_here, simbol){
	var id = "row1_" + course_id;
	var table = document.getElementById("divuniq");
	var row = document.getElementById(id);	
	table.removeChild(row);
	var url = document.location.toString() ; //url
	var e_url = '' ; //edited url
	var p = 0 ; //position
	var p2 = 0 ;//position 2
	p = url.indexOf("//") ;
	e_url = url.substring(p+2) ;
	p2 = e_url.indexOf("/") ;
	var root_url = url.substring(0,p+p2+3);
	
	var req = new Request.HTML({
		method: 'get',
		url: root_url+"index.php?option=com_guru&controller=guruBuy&task=deletefromsession&course_id="+course_id+"&action="+action,
		data: { 'do' : '1' },
		onComplete: function(response){	
		}
	}).send();
	
	setMaxTotalB(simbol, all_ids, cart_empty, link_redirect, click_here, simbol);
}

function trim(str) {
	return str.replace(/^\s+|\s+$/g,"");
}

function setMaxTotalB(simbol, all_ids, cart_empty, link_redirect, click_here){
	var ids_array = new Array();
	ids_array = all_ids.split(',');
	 
	max_total = 0.0;
	nr_courses_after_delete = 0;
	
	
	for(var i=0; i<ids_array.length; i++){
		if(document.getElementById("cart_item_totalb"+ids_array[i])){
			element_id = "cart_item_totalb"+ids_array[i];
			element = document.getElementById(element_id);
			div_content = element.innerHTML + "";
			div_content = trim(div_content);
			
			div_content = div_content.replace(simbol, "");
			div_content = div_content.replace(" ", "");
			div_content = parseFloat(div_content);
			
			max_total += div_content;
			
			nr_courses_after_delete ++;
		}
	}
		
	if(nr_courses_after_delete == 0){
		document.getElementById("guru_cart1").innerHTML = cart_empty+", <a href=\""+link_redirect+"\">"+click_here+"</a>";
	}
	else{
		document.getElementById("max_totalb").innerHTML = simbol+" " + max_total.toFixed(2);
		document.getElementById("max_totalb1").innerHTML = simbol+" " + max_total.toFixed(2);
	}
}
//end bootstrap
function setMaxTotal(simbol, all_ids, cart_empty, link_redirect, click_here){
	var ids_array = new Array();
	ids_array = all_ids.split(',');
	
	max_total = 0.0;
	nr_courses_after_delete = 0;
	for(var i=0; i<ids_array.length; i++){
		if(document.getElementById("cart_item_total"+ids_array[i])){
			value = document.getElementById("cart_item_total"+ids_array[i]).innerHTML + "";
			value = value.replace(simbol, "");
			value = value.replace(" ", "");
			value = parseFloat(value);
			
			max_total += value;
			nr_courses_after_delete ++;
		}
	}
	if(nr_courses_after_delete == 0){
		document.getElementById("guru_cart").innerHTML = cart_empty+", <a href=\""+link_redirect+"\">"+click_here+"</a>";
	}
	else{
		document.getElementById("max_total").innerHTML = simbol+" " + max_total.toFixed(2);
	}
}