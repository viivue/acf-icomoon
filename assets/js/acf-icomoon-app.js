const dev = true;
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
        const row = e.target.closest('.acf-row');
        const app = row.querySelector('[data-icomoon-app]');
        if(app){
            const newRow = row.nextSibling;

            // load app html
            newRow.querySelector('[data-icomoon-app]').parentElement.innerHTML = app.getAttribute('data-icomoon-init-html');

            // clone value
            const newRowApp = newRow.querySelector('[data-icomoon-app]');
            newRowApp.setAttribute('data-icomoon-selected', app.getAttribute('data-icomoon-selected'));

            // init app
            newRowApp.setAttribute('data-icomoon-init-html', newRowApp.outerHTML);
            new AcfIcomoonDom(newRowApp, 'acf-repeater-duplicate');
        }
    }
});