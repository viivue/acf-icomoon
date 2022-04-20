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
                        // open popup
                        this.popup.toggle();

                        // set focus on search field
                        const searchInput = document.querySelector(`[data-easy-popup-id="${id}"] input[data-icomoon-search]`);
                        setTimeout(() => searchInput.focus(), 100)
                    });
                });

                this.app.classList.remove('unmounted');

                if(dev) console.log(`[ACF-Icomoon] (${this.type}) Vue app created => ${id}`, selected);
            }
        });
    }

    getIconObject(icon_class){
        const iconObject = this.icons.filter(i => i.icon_class === icon_class)[0];
        return typeof iconObject !== 'undefined' ? iconObject : {};
    }

    createApp({_this, callback}){
        Vue.createApp({
            data(){
                return {
                    id: _this.id,
                    icons: [..._this.icons],
                    selected: _this.getIconObject(_this.app.getAttribute('data-icomoon-selected')),
                    isMounted: true,
                }
            },
            methods: {
                selectIcon(val){
                    const lastSelected = this.selected;
                    this.selected = _this.getIconObject(val);
                    _this.app.setAttribute('data-icomoon-selected', val);

                    EasyPopup.get(this.id).close();

                    // param group > update group label
                    if(_this.type === 'vc-field' || _this.type === 'vc-param-field'){
                        const groupItem = _this.app.closest('.vc_param');
                        if(groupItem){
                            const label = groupItem.querySelector('.vc_param-group-admin-labels');
                            if(label){
                                if(label.innerHTML){
                                    // replace
                                    label.innerHTML = label.innerHTML.replace(lastSelected.icon_class, val);
                                }else{
                                    // create
                                    const labelTitle = groupItem.querySelector('[data-param_type="icomoon_class"] .wpb_element_label').textContent;
                                    label.innerHTML = `<label>${labelTitle}</label>: ${val}`;
                                    label.classList.remove('vc_hidden-label');
                                }
                            }
                        }
                    }
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
                    },
                    selected: {
                        type: Object,
                        required: false
                    }
                },
                data(){
                    return {
                        searchQuery: ''
                    }
                },
                methods: {
                    isShow(name){
                        if(this.searchQuery === '') return true;
                        return name.includes(this.searchQuery);
                    },
                    searchLabel(name){
                        if(this.searchQuery === '') return name;
                        return name.replace(this.searchQuery, `<span class="label-search-highlight">${this.searchQuery}</span>`);
                    },
                    isHasMatchedSearch(){
                        if(this.searchQuery === '') return true;

                        const matchedIcons = this.icons.filter(icon => icon.icon_class.includes(this.searchQuery));
                        return matchedIcons.length > 0;
                    }
                },
                template: `
                <div class="vii-icomoon__popup" :data-icomoon-popup="id">
                    <div class="vii-icomoon__popup-inner">
                    
                        <div class="vii-icomoon__popup-head">
                            <div class="vii-icomoon__search">
                                <input data-icomoon-search v-model="searchQuery" type="search" placeholder="Search icon...">
                            </div>
                            <span class="vii-icomoon__count-text">count text</span>
                        </div>
                        
                        <div class="vii-icomoon__popup-body">
                        
                            <ul class="vii-icomoon__icons" v-if="isHasMatchedSearch()">
                                <li v-for="icon in icons" :class="{'search-hidden': !isShow(icon.icon_class)}">
                                    <button 
                                        :data-icomoon-select="icon.icon_class" 
                                        @click="$emit('selectIcon', icon.icon_class)"
                                        :class="{active: icon.icon_class === selected.icon_class}"
                                     >
                                        <i v-html="icon.svg"></i>
                                        <label v-html="searchLabel(icon.icon_class)"></label>
                                    </button>
                                </li>
                            </ul>
                            
                            <strong class="vii-icomoon__icons-empty" v-if="!isHasMatchedSearch()">Oops! No icons matched your search.</strong>
                            
                        </div>
                    
                    </div>
                </div>
                `
            }
        ).mount(this.app);
    }
}