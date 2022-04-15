const dev = true;
const appSelector = '[data-icomoon-app]:not([data-v-app])';

const initIcomoonSelect = e => {
    const app = e.$el.find(appSelector).get()[0];
    if(app) new AcfIcomoonDom(app, 'acf');
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
    //if(dev) console.log(`[ACF-Icomoon] VC element rendered.`);

    panel.querySelectorAll(appSelector).forEach(app => {
        new AcfIcomoonDom(app, 'vc-field');
    });
}

/**
 * VC Param Group After Add
 * @param fields
 */
function vcAfterIcomoonFieldAdd(fields){
    //if(dev) console.log(`[ACF-Icomoon] VC param field added.`);

    const app = fields[0].querySelector(appSelector);
    if(app){
        new AcfIcomoonDom(app, 'vc-param-field');
    }
}