var $ = jQuery.noConflict();

$.oauthpopup = function(options)
{
	if (!options || !options.path) {
		return;
	}
	var left = (screen.width/2)-(304);
	var top = (screen.height/2)-(314);
	options = $.extend({
		windowName: 'ConnectWithOAuth'
	  , windowOptions: 'location=0,status=0,width=608,height=314,scrollbars=no, resizable=no,top='+top+',left='+left
	  , callback: function(){ window.location.reload(); }
	}, options);

	var oauthWindow   = window.open(options.path, options.windowName, options.windowOptions);
	var oauthInterval = window.setInterval(function(){
		if (oauthWindow.closed) {
			window.clearInterval(oauthInterval);
			options.callback();
		}
	}, 1000);
};  

function after_login(message){
	if(typeof message !== 'undefined')
		$("td.music-wrapper").css('padding-top', '60px').html(message);

	$(".login-wrapper").fadeOut('fast', function(){			
		$(".content").fadeIn('fast');
	});   
}

function show_music(set){
	var list = '';
	var placeholder = $("li#placeholder").html();
	$("li#placeholder").remove();
	$.each(set.response, function(i,ii){
		var track = document.createElement('li');
		$(track).addClass('track');	
		$(placeholder).appendTo(track);

  		if((ii.artist.length) > 35)
			ii.artist = ii.artist.substr(0, 35) + '&hellip;';  

 		if((ii.title.length + ii.artist.length) > 55)
			ii.title = ii.title.substr(0, 55 - ii.artist.length) + '&hellip;'; 

		$(track).find(".artist").html(ii.artist);
		$(track).find(".title").html(ii.title);
		$(track).find(".manage a").attr("href", '#download-' + ii.aid); 
		$(track).appendTo("ul#playlist");
	})
	
    return after_login();
}

function start_preloader(el){
	$(el).fadeOut('fast', function(){
		$("#preloader").fadeIn('fast');
	});
}

function show_button(){
	$("#preloader").fadeOut('fast', function(){
		$("#login").fadeIn('fast');
	});
}

function vk_get(){
	$.ajax({
		type: 'POST', url: '/get', data: "",
		error: function(){
			return show_error('Невозможно получить список аудиозаписей');
		},
		success: function(data){
			if(data.error)
				return show_button();

			if(data.success.response.length == 0)
				return after_login('Аудиозаписи не найдены');
			
			show_music(data.success);
		}
	});        
}

function download_single(aid, el){
	$.ajax({
		type: 'POST', url: '/download', data: {aid: aid.replace(/#download-/, '')},
		error: function(){
			return show_error('Невозможно загрузить аудиозапись');
		},
		success: function(data){
			if(data.error)
				return show_error(data.error); 

    		el.removeClass('loading').addClass('success');
		
			var link = document.createElement('a');
			link.href = data.success;
			document.body.appendChild(link);
			link.click();    
		}
	});      
}


function show_error(text) {
	var popup = jQuery('#popup'),
		text = popup.text(text),
		timeout = popup.attr('data-timed-id'),
		height = popup.outerHeight();

	if (!text)
		return;

	if (popup.is(':visible')) {
		clearTimeout(timeout);
		popup.stop(true, true).animate({ top: -height }, 150);
	}

	popup.stop(true, true).animate({ top: 0 }, 150, function () {

		var timer = setTimeout(function () {
			popup.animate({ top: -height }, 150, function () {
			});
		}, 3500);

		popup.attr('data-timed-id', timer);

	});
}

$(document).ready(function(){
    vk_get();

	$("#playlist").on('click', '.download', function(){
		if($(this).is('.loading, .success'))
			return false;
		$(this).addClass('loading');

		download_single($(this).attr('href'), $(this));

		return false;
	});

	$("#login").on('click', function(){
		start_preloader('#login');
		$.oauthpopup({
			path: '/login',
			callback: function(){
				location.reload();
			}
		});     	
	});
	
});
