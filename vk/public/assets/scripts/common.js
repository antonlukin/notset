var $ = jQuery.noConflict();
var page = false;

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
		$("td.music").css('padding-top', '60px').html(message);

	$(".authenticate").fadeOut('fast', function(){
		$(".content").fadeIn('fast');
	});
}

function show_music(set){
	var list = '';
	var placeholder = $("li#placeholder").html();
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

function preloader(show){
    show = (typeof show === 'undefined') ? true : show;

	if(!show)
		return $("#preloader").fadeOut('fast', function(){ 
			$(".authenticate").hide();
		}); 

	$(".authenticate").show();

	$("#login").fadeOut('fast', function(){
		$("#preloader").fadeIn('fast');
	}); 
}

function show_button(){
	$(".content").hide();
 	$(".authenticate").show(); 

	$("#preloader").fadeOut('fast', function(){
		$("#login").fadeIn('fast');
	});
}

function vk_more(offset){
	if(!page)
		return;

	var current = page;
	page = false;

	offset = (typeof offset === 'undefined') ? "offset=0" : "offset=" + offset;    

	$.ajax({
		type: 'POST', url: '/get', data: offset, timeout: 5000,    
		error: function(){
			return show_error('Возникли проблемы с доступом к серверу');  
		},
		success: function(data){
			if(data.error)
	 			return show_error(data.error); 

			if(data.success.response.length == 0)
				return after_login();
		
			show_music(data.success);
			page = current + 1;
		}
	});         
}

function vk_get(){
	$.ajax({
		type: 'POST', url: '/get', data: 0, timeout: 5000,
		error: function(){
			return show_error('Возникли проблемы с доступом к серверу'); 
		},
		success: function(data){
			if(data.error)
				return show_button();

			if(data.success.response.length == 0)
				return after_login('Аудиозаписи не найдены');
			
			page = 1;
			show_music(data.success);
		}
	});        
}

function download_single(aid, el){
	$.ajax({
		type: 'POST', url: '/download', data: {aid: aid.replace(/#download-/, '')}, cache: false,
		error: function(){
			el.removeClass('loading');
			return show_error('Возникли проблемы с доступом к серверу');
		},
		success: function(data){
			if(data.error)
				return show_error(data.error); 

    		el.removeClass('loading').addClass('success');
					
			var link = document.createElement('iframe');
			link.src = data.success; 
			document.body.appendChild(link);
		}
	});      
}

function correct_number(number, titles) {
	cases = [2, 0, 1, 1, 1, 2];
	return number + " " + titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];       
}

function show_promo(){
	if($.cookie("promo"))
		return;

	$("#vk_like").html(VK.Share.button({url: "http://vk.notset.ru/"},{type: "button", text: "Поделиться"}));
	$("#promo").fadeIn('slow');
}

function show_count(){
	$.ajax({
		type: 'POST', url: '/count', data: 0, timeout:5000,
		error: function(){
			return false;
		},
		success: function(data){
			show_promo();

			if(!data.success)
				return false;

			$("#files-number").html(correct_number(data.success, ['файл', 'файла', 'файлов']));
            if((data.success % 10 === 1) && (data.success % 100 !== 11))
				$("#files-ending").remove();

			$("p.footer-promo").fadeIn();
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

$(window).scroll(function() {
	if(!page)
		return; 

	if($(window).scrollTop() == $(document).height() - $(window).height())
		$(".authenticate").addClass('more').fadeIn('fast', function(){
	  		vk_more(page);
		});
});

$(document).ready(function(){
    vk_get();

	$("#playlist").on('click', '.download', function(){

		if($(this).is('.loading'))
			return false;

 		if($(this).is('.success'))
			$(this).removeClass('success');

		$(this).addClass('loading');

		download_single($(this).attr('href'), $(this));

		return false;
	});

	$("#login").on('click', function(){
		preloader(true);
		$.oauthpopup({
			path: '/login',
			callback: function(){
				location.reload();
			}
		});     	
	});

	$(document).on('click', '#promo-close', function(e){
		$.post("/promo", function(data) {
			console.log(data);
		});

		$.cookie("promo", "set", {expires: 100});
		$(".promo").fadeOut();
		return false;
	});

	
	
	show_count();
});
