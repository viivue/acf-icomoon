class AcfIcomoonDom{
    constructor(app){
        this.app = app;
        this.id = uniqueId('icomoon-');
        this.app.setAttribute('data-icomoon-app', this.id);

        // get icons from json
        const iconsEl = this.app.querySelector('[data-icomoon-icons]');
        this.icons = JSON.parse(iconsEl.getAttribute('data-icomoon-icons'));
        iconsEl.remove();

        // create app
        this.createApp(this.id, [...this.icons], id => {
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

            console.log(`[ACF-Icomoon] Vue app created => ${id}`);
        });
    }

    createApp(id, icons, callback){
        Vue.createApp({
            data(){
                return {id, icons}
            },
            methods: {},
            created(){
                setTimeout(() => {
                    callback(this.id);
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
                            <vii-icomoon-item v-for="icon in icons" :icon="icon" />
                        </ul>
                        </div>
                    
                    </div>
                </div>
                `
            }
        ).component('vii-icomoon-item', {
            props: {
                icon: {
                    type: Object,
                    required: true
                },
            },
            template: `
            <li><button>
            <i v-html="icon.svg"></i>
            <label>{{icon.name}}</label>
            </button></li>
            `
        }).mount(this.app);
    }
}