(function(){
	var push = function() {
		console.log("It works!");
	}

	var init = document.querySelector('body').getAttribute('data-init');

	if(init.split(' ').indexOf('whois') > -1)
		return push();
})();
