const initIcomoonSelect = e => {
    const app = e.$el.find('[data-icomoon-app]').get()[0];
    if(app) new AcfIcomoonDom(app);
}

/**
 * ACF events
 */
// on load
acf.addAction('load_field/type=viivue_acf_icomoon', e => initIcomoonSelect(e));

// watch for new fields via ACF
acf.addAction('append_field/type=viivue_acf_icomoon', e => initIcomoonSelect(e));