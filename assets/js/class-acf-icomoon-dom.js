class AcfIcomoonDom{
    constructor(app, type = 'acf'){
        this.app = app;
        this.type = type;
        this.id = uniqueId('icomoon-');
        this.app.setAttribute('data-icomoon-app', this.id);

        // get icons from json
        const iconsEl = this.app.querySelector('[data-icomoon-icons]');
        this.icons = JSON.parse(iconsEl.getAttribute('data-icomoon-icons'));
        iconsEl.remove();

        // input
        this.input = this.app.querySelector('input[data-icomoon-input]');

        // create app
        this.createApp({
            _this: this,
            callback: ({id, selected}) => {
                // init popup
                EasyPopup.init(`[data-icomoon-popup="${id}"]`, {
                    id: id,
                    outerClass: 'vii-icomoon-easy-popup',
                });

                this.popup = EasyPopup.get(id);
                this.triggers = this.app.querySelectorAll('[data-icomoon-popup-trigger]');
                this.triggers.forEach(el => {
                    el.addEventListener('click', () => {
                        this.popup.toggle();
                    });
                });

                this.app.classList.remove('unmounted');

                if(dev) console.log(`[ACF-Icomoon] (${this.type}) Vue app created => ${id}`, selected);
            }
        });
    }

    getIconObjectByName(name){
        const iconObject = this.icons.filter(i => i.name === name)[0];
        return typeof iconObject !== 'undefined' ? iconObject : {};
    }

    createApp({_this, callback}){
        Vue.createApp({
            data(){
                return {
                    id: _this.id,
                    icons: [..._this.icons],
                    selected: _this.getIconObjectByName(_this.app.getAttribute('data-icomoon-selected')),
                    isMounted: true,
                }
            },
            methods: {
                selectIcon(name){
                    this.selected = _this.getIconObjectByName(name);
                    _this.app.setAttribute('data-icomoon-selected', name);

                    EasyPopup.get(this.id).close();
                },
                clearSelection(){
                    this.selected = {};
                    _this.app.setAttribute('data-icomoon-selected', '');
                }
            },
            created(){
                setTimeout(() => {
                    callback({id: this.id, selected: this.selected});
                }, 10);
            }
        }).component('vii-icomoon-popup', {
                props: {
                    id: {
                        type: String,
                        required: true
                    },
                    icons: {
                        type: Array,
                        required: true
                    }
                },
                template: `
                <div class="vii-icomoon__popup" :data-icomoon-popup="id">
                    <div class="vii-icomoon__popup-inner">
                    
                        <div class="vii-icomoon__popup-head">
                            <div class="vii-icomoon__search"><input data-icomoon="search" type="search" placeholder="Search icon..."></div>
                            <span class="vii-icomoon__count-text">count text</span>
                        </div>
                        
                        <div class="vii-icomoon__popup-body">
                            <ul class="vii-icomoon__icons">
                                <li v-for="icon in icons">
                                    <button :data-icomoon-select="icon.name" @click="$emit('selectIcon', icon.name)">
                                        <i v-html="icon.svg"></i>
                                        <label>{{icon.name}}</label>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    
                    </div>
                </div>
                `
            }
        ).mount(this.app);
    }
}