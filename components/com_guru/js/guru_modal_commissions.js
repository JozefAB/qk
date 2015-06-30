var modalWindow = {
	parent:"body",
	windowId:null,
	content:null,
	width:null,
	height:null,
	left:null,
	right:null,
	top:null,
	bigmodal:null,
	close:function()
	{
		jQuery(".modal-window").remove();
		jQuery(".modal-overlay").remove();
	},
	open:function()
	{
		modal_style = "";
		
		if(this.width != null && this.width != 0){
			screen_width = window.innerWidth;
			screen_height = window.innerHeight;
			modal_style = 'style="height:'+(screen_height-100)+'px; width:90%; margin-left:-45%; top:6% padding:10px;"';
		}
		else{
			modal_style = 'style="left:'+this.left+'%; right:'+this.right+'%;"';
		}
		
		var modal = "";
		modal += "<div class=\"modal-overlay\"></div>";
		modal += "<div id=\"" + this.windowId + "\" "+modal_style+" class=\"modal-window modal p_modal \">";
		modal += this.content;
		modal += "</div>";
		jQuery(this.parent).append(modal);


		jQuery(".modal-window").append("<a class=\"close-window\" id=\"close-window\"></a>");
		jQuery(".close-window").click(function(){modalWindow.close();});
		jQuery(".modal-overlay").click(function(){modalWindow.close();});
	}
};

var openMyModal = function(width, height, source){
	modalWindow.windowId = "myModal";
	iframe_style = "";
	if(width != 0 && height != 0){
		screen_width = window.innerWidth;
		screen_height = window.innerHeight;
		
		modalWindow.width = width;
		modalWindow.height = height;
		
		iframe_style = 'style="width:100%; height:'+(screen_height-100)+'px;"';
	}
	else{
		modalWindow.width = 0;
		modalWindow.height = 0;
		iframe_style = 'style=""';
	}
	
	modalWindow.content = "<iframe "+iframe_style+" id='g_preview' class='pub_modal_frame' src='" + source + "'>content</iframe>";
	modalWindow.open();
};