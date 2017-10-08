function foo(data) {
 	console.log(data);
}

(function() {

var script = document.createElement('script');
script.src = '//ipinfo.io/?callback=foo'
//script.src = 'https://geoip.nekudo.com/api/?callback=foo';

document.getElementsByTagName('head')[0].appendChild(script);

})();
