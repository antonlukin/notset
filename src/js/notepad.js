(function(){
    let editor = document.getElementById('editor');
    let manage = document.getElementById('manage');


    /**
     * Save current editor value to local storage
     */
	editor.addEventListener('keyup', function(e) {
		localStorage.setItem('editor', this.value);
	}, true);


    /**
     * Add indetion on tab in editor
     */
	editor.addEventListener('keydown', function(e) {
		if(e.code === 'Tab') {
			e.preventDefault();

			return this.value = this.value + "\t";
		}
	}, true);


    /**
     * Create new tab on Alt+T shortcut
     */
    editor.addEventListener('keydown', function(e) {
		if(e.altKey && e.code === 'KeyT') {
			e.preventDefault();

		    alert(1)	;
		}
	}, true);


    /**
     * Close current tab on Alt+W shortcut
     */
    editor.addEventListener('keydown', function(e) {
		if(e.altKey && e.code === 'KeyW') {
			e.preventDefault();

		    alert(1);
		}
	}, true);


    /**
     * Close tab on cross icon click
     */
    manage.querySelector('.tab-close').addEventListener('click', function(e) {
        return closeTab(this.parentNode);
    }, true);


    /**
     * Create tab function
     */
    let createTab = function() {

    }


    /**
     * Change editor data on tab change
     */
    let changeTab = function() {

    }


    /**
     * Close tab and delete data from local storage
     */
    let closeTab = function(tab) {

    }

	return editor.value = localStorage.getItem('editor');
})();
