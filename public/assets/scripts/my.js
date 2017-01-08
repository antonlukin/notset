var $ = jQuery.noConflict();
 
var reminder = $("#reminder"),
	preloader = $("#preloader");           

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

function auth(vals){
	//alert(vals);
}

function init(action, json){
	var response = $.parseJSON(json);
	$.each(response, function(i, v){
		var item = $(document.createElement('li'))
		var content =  '<h2>' + v.name + '</h2>' + '<h3>' + v.title + '</h3>';
			content += '<b>×</b>' + '<div>' + v.start + ' / ' + v.cron + '</div>';

		$(content).appendTo(item);

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

	if(action === 'manager')
		$("#todo .new").show();
	else
		$("#todo .new").hide(); 

	reminder.removeClass().addClass(action); 
}

function query(action, vals){
 	$.ajax({
		type: 'POST', url: '/' + action, data: vals, dataType:"json",
		error: function(){
			return error(warning.server); 
		},
		success: function(data){
			if(typeof data.error !== 'undefined')
				return error(data.error); 
	 
			if(typeof data.auth !== 'undefined')
				return auth(data.auth);       

			if(typeof data.success === 'undefined')
				return error(warning.action); 

			if(action === 'manager' || action === 'normal')
				return init(action, data.success);

			if(action === 'add')
				reminder.find("li.new input").val('');
		
			return true;
		}
	});
}


function board(act){

   	reminder.fadeOut(function(){
		reminder.find('ul li').not('.new').remove();
	 	preloader.fadeIn(function(){
            var page = (typeof act === 'undefined' || !act) ? 'normal' : 'manager'; 
			$.when(query(page)).then(function(){
				preloader.fadeOut(function(){   
					reminder.fadeIn();
				});
			}); 

		});
	}); 

}
  
$(window).load(function(){
	board(false);
});

$(document).ready(function(){
 
	$("header").on('click', 'p#options img', function(){
		var settings = reminder.prop('class') !== 'manager',
			src = $(this).prop('src'),
			icon = settings ? src.replace('cogs', 'refresh') :  src.replace('refresh', 'cogs');
			
		$(this).fadeOut(function(){
			$(this).prop('src', icon).fadeIn(700);
		});
		board(settings);

		return false;
	});     
	
	$(document.body).on('click', '#reminder.normal #todo li', function(){
		var id = $(this).data('id');
		
		$(this).fadeOut( function(){				
			if($("#done > li").length < 1)
				$("#done-title").show();

			query('close', {item:id});
				
			$(this).appendTo("#done").fadeIn();
		});				
	}); 

	$(document.body).on('click', '#reminder.normal #done li', function(){
		var id = $(this).data('id');
		
		$(this).fadeOut( function(){		
			$(this).appendTo("#todo").fadeIn();
			
 			query('open', {item:id}); 

			if($("#done > li").length < 1)
				$("#done-title").hide();
		});				
	});

	$(document.body).on('click', '#reminder.manager li b', function(){
		var li = $(this).closest('li');

		li.fadeOut(function(){
			query('delete', {item:li.data('id')});
		});
		
	});      

	$(document.body).on('keypress', '#reminder.manager li input', function(e){
		if(e.which !== 13)
			return;

		var li = $(this).closest('li'),
			item = new Object(),
			valid = true;

		li.find('input').each(function(i, v){
			$(v).removeClass('invalid');
            
			var v = $(v);
				n = v.prop('class').replace('item-', '');

			item[n] = v.val();

			if(!v.prop('pattern'))
				return;

			if(v.val().match(v.prop('pattern')))
				return;
		
			if(n == 'start' && v.val() == '')
				return;
				
			valid = false;
			v.addClass('invalid');
		});

		if(!valid)
			return false;

		$.when(query('add', item)).then(function(){  
 		   	board(true);
		});
		
	});       
	
}); 
