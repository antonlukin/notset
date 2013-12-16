var $ = jQuery.noConflict();

function error(text) {
	var popup = jQuery('#popup'),
		text = popup.text(text),
		height = popup.outerHeight();

	if (!text)
		return;

	if (popup.is(':visible')) 			
		popup.stop(true, true).animate({ top: -height }, 150);

	popup.stop(true, true).animate({ top: 0 }, 150, function () {
		setTimeout(function () {
			popup.animate({ top: -height }, 150);
		}, 3500);
	});
}

function status(action, id){
 	$.ajax({
		type: 'POST', url: '/' + action, data:{item:id},
		error: function(){
			return error('Не удалось связаться с сервером');
		},
		success: function(data){

			if(data.error)
				return error(data.error); 

			return true;
		}
	});                  
}

function init(){ 
	var hash = document.location.hash.replace('#', ''),
		manage = (hash === 'manager');
		page = manage ? 'manager' : 'normal';


  	$.ajax({
		type: 'POST', url: '/' + page,
		error: function(){
			return error('Не удалось связаться с сервером');
		},
		success: function(data){

			if(data.error)
				return error(data.error); 

			if(!data.success)
				return false;
			
			var response = $.parseJSON(data.success);
			$.each(response, function(i, v){
				var item = $(document.createElement('li'))
				$('<h2>' + v.name + '</h2>').appendTo(item);
 				$('<h3>' + v.title + '</h3>').appendTo(item); 

  				item.data('id', v.id); 
				item.data('start', v.start);
 				item.data('cron', v.cron); 

				if(v.done)
					item.prependTo("#done");
				else
					item.prependTo("#todo"); 
			});

			if($("#done > li").length > 0)
				$("#done-title").show();

			$("#reminder").addClass(page);
		}
	}); 
}

$(window).on('hashchange', function(){

	$("#reminder").fadeOut(function(){
		$("#preloader").fadeIn(function(){

			window.location.reload();

		});
	});   

});


$(window).load(function(){

	$.when(init()).then(function(){

		$("#preloader").fadeOut(function(){
			$("#reminder").fadeIn();
		});

	});
});

$(document).ready(function(){
	
	$(document.body).on('click', '#reminder.normal #todo li', function(){
		var id = $(this).data('id');
		
		$(this).fadeOut( function(){				
			if($("#done > li").length < 1)
				$("#done-title").show();

			status('close', id);
				
			$(this).appendTo("#done").fadeIn();
		});				
	});
	
	$(document.body).on('click', '#reminder.normal #done li', function(){
		var id = $(this).data('id');
		
		$(this).fadeOut( function(){		
			$(this).appendTo("#todo").fadeIn();
			
 			status('open', id); 

			if($("#done > li").length < 1)
				$("#done-title").hide();
		});				
	});
	
}); 
