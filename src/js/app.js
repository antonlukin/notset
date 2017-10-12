var ip = {
	api: '//ipinfo.io/',

	back: function(data) {
		console.log(data);
//		document.querySelector(".info__code").innerHTML = JSON.stringify(data);

		var ul = document.createElement("ul")
		ul.className = 'info__code';

		for(var key in data) {
			var li = document.createElement('li');
			li.innerHTML = '<span>' + key + '</span>: <span> ' + data[key] + '</span>';

			ul.appendChild(li);
		}

		document.querySelector(".info").appendChild(ul);
	},

	init: function() {
		var script = document.createElement('script');

		var i = document.querySelector(".info__head").innerHTML;

		script.src = ip.api + i + '/?callback=ip.back';
		document.getElementsByTagName('head')[0].appendChild(script);
	}
}

ip.init();
