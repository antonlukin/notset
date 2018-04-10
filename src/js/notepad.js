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
     * Close tab on cross icon click
     */
    manage.addEventListener('click', function(e) {
        e.preventDefault();

        if(e.target.className === 'tab-close' && manage.querySelectorAll('.tab').length > 1) {
            return deleteTab(e.target.parentNode);
        }
    }, true);


    /**
     * Select tab on click
     */
    manage.addEventListener('click', function(e) {
        e.preventDefault();

        if(e.target.className === 'tab') {
            return selectTab(e.target);
        }
    }, true);


    /**
     * Create new tab on Alt+T shortcut
     */
    document.addEventListener('keydown', function(e) {
        if(e.altKey && e.code === 'KeyT') {
            e.preventDefault();

            return createTab();
        }
    }, true);


    /**
     * Close current tab on Alt+W shortcut
     */
    document.addEventListener('keydown', function(e) {
        if(e.altKey && e.code === 'KeyW') {
            e.preventDefault();

            let tab = manage.querySelector('.tab.active');

            if(manage.querySelectorAll('.tab').length > 1 && tab) {
                return deleteTab(tab);
            }
        }
    }, true);


    /**
     * Select tab using keybord shortcut
     */
    document.addEventListener('keydown', function(e) {
        if(e.altKey && (e.keyCode >= 49 && e.keyCode <= 57)) {
            e.preventDefault();

            let num = e.keyCode - 48;

            // get tab by num keyCode
            let tab = manage.querySelector('.tab:nth-child(' + num + ')');

            if(tab !== null) {
                return selectTab(tab);
            }
        }
    }, true);


    /**
     * Create tab function
     */
    let createTab = function() {
        // get clone element from last tab
        let clone = manage.querySelector('.tab:last-child').cloneNode(true);

        manage.appendChild(clone);

        return selectTab(clone);
    }


    /**
     * Change editor data on tab change
     */
    let selectTab = function(tab) {
        let tabs = manage.querySelectorAll('.tab');

        // loop over tabs
        for(let i = 0; i < tabs.length; ++i) {
            // add tab number
            tabs[i].querySelector('.tab-title').textContent = i + 1;

            // remove .active class if exists
            tabs[i].classList.remove('active');
        }

       return tab.classList.add('active');
    }


    /**
     * Close tab and delete data from local storage
     */
    let deleteTab = function(tab) {
        tab.parentNode.removeChild(tab);

        // we have to choose new tab while deleting current
        return selectTab(manage.querySelector('.tab:last-child'));
    }

    return editor.value = localStorage.getItem('editor');
})();
