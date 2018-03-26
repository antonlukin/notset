(function(){
    var editor = document.getElementById('editor');

	editor.addEventListener('keyup', function(e) {
		localStorage.setItem('editor', this.value);
	}, true);

	editor.addEventListener('keydown', function(e) {
		if(e.keyCode == 9 || e.which == 9) {
			e.preventDefault();

			return this.value = this.value + "\t";
		}
	}, true);

	return editor.value = localStorage.getItem('editor');
})();
