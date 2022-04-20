const dev = false;
const appSelector = '[data-icomoon-app]:not([data-v-app])';

const initIcomoonSelect = e => {
    const app = e.$el.find(appSelector).get()[0];
    if(app){
        app.setAttribute('data-icomoon-init-html', app.outerHTML);
        new AcfIcomoonDom(app, 'acf');
    }
}

/**
 * ACF events
 */
// on load
acf.addAction('load_field/type=viivue_acf_icomoon', e => initIcomoonSelect(e));

// watch for new fields via ACF
acf.addAction('append_field/type=viivue_acf_icomoon', e => initIcomoonSelect(e));

/**
 * VC After Element Render
 * @param panel
 */
const jsComposerAfterElementRender = panel => {
    panel.querySelectorAll(appSelector).forEach(app => new AcfIcomoonDom(app, 'vc-field'));
}

/**
 * VC Param Group After Add
 * @param fields
 */
function vcAfterIcomoonFieldAdd(fields){
    const app = fields[0].querySelector(appSelector);
    if(app) new AcfIcomoonDom(app, 'vc-param-field')
}


/**
 * Watch for ACF repeater duplicate
 */
document.addEventListener('click', function(e){
    if(e.target.classList.contains('-duplicate')){
        const oldRow = e.target.closest('.acf-row');
        const app = oldRow.querySelector('[data-icomoon-app]');
        const oldRowId = oldRow.getAttribute('data-id');
        if(app){
            const newRow = oldRow.nextSibling;

            // load app html
            newRow.querySelector('[data-icomoon-app]').parentElement.innerHTML = app.getAttribute('data-icomoon-init-html');

            // clone value
            const newId = newRow.getAttribute('data-id');
            const newVal = app.getAttribute('data-icomoon-selected');
            const newRowApp = newRow.querySelector('[data-icomoon-app]');
            const newInput = newRowApp.querySelector('input[data-icomoon-input]');
            const newInputName = newInput.getAttribute('name').replace(oldRowId, newId);
            newRowApp.setAttribute('data-icomoon-selected', newVal);
            newInput.setAttribute('name', newInputName);
            newInput.value = newVal;

            // init app
            newRowApp.setAttribute('data-icomoon-init-html', newRowApp.outerHTML);
            new AcfIcomoonDom(newRowApp, 'acf-repeater-duplicate');
        }
    }
});