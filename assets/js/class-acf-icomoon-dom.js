class AcfIcomoonDom{
    constructor(app){
        this.app = app;
        this.id = uniqueId('icomoon-');
        this.icons = JSON.parse(this.app.querySelector('[data-icomoon-icons]').getAttribute('data-icomoon-icons'));
        console.log(this.icons)
        const icons = [...this.icons];
        Vue.createApp({
            data(){
                return {
                    icons: icons
                }
            },
            methods: {},
            created(){
                console.log('[ACF-Icomoon] Vue app created.');
            }
        }).component('vii-icomoon-popup', {
                props: {
                    icons: {
                        type: Array,
                        required: true
                    }
                },
                template: `
                <div class="vii-icomoon__popup">
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
            <li><button v-html="icon.svg"></button></li>
            `
        }).mount(this.app);
    }
}