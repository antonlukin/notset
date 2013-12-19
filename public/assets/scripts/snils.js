$(function(){
	function removeSpaces(s) {
		var spaceRe = /[\s-]+/g;
		return s.replace(spaceRe, "");
	}

	$("#test").submit(function(){
		$("#res").html('');
		$("#res").slideUp(200);
		var snils = removeSpaces($("#sval").val());
		if(snils.length != 11){
			$("#res").slideDown(200, function(){$(this).html('<div style="text-align:center;color:#e88;">Неверный формат</div>')});
			return false;
		}

		$.post("?test", {num:snils}, function(data){
			$("#res").slideDown(200, function(){$(this).html(data)});
		});
		return false;
	});
	$("#find").live('click', function(){
		$("#test").trigger('submit');
		return false;
	});
});           
