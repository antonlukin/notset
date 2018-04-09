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

            return createTab();
		}
	}, true);


    /**
     * Close current tab on Alt+W shortcut
     */
    editor.addEventListener('keydown', function(e) {
		if(e.altKey && e.code === 'KeyW') {
			e.preventDefault();

            let current = manage.querySelector('.tab.active');

            if(manage.querySelectorAll('.tab').length > 1 && current) {
                return closeTab(current);
            }
		}
	}, true);


    /**
     * Close tab on cross icon click
     */
    manage.addEventListener('click', function(e) {
        e.preventDefault();

        if(e.target.className === 'tab-close' && manage.querySelectorAll('.tab').length > 1) {
            return closeTab(e.target.parentNode);
        }
    }, true);


    /**
     * Create tab function
     */
    let createTab = function() {
        // get clone element from last tab
        let clone = manage.querySelector('.tab:last-child').cloneNode(true);

        // get clone title selector
        let title = clone.querySelector('.tab-title');

        // set new tab count
        let count = parseInt(title.textContent) + 1;

        title.textContent = count;

        clone.classList.add('active');

        return manage.appendChild(clone);
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
        return tab.parentNode.removeChild(tab);
    }

	return editor.value = localStorage.getItem('editor');
})();
