(function($){
    /**
     * Icomoon Select
     */
    function viivue_icomoon_select(){
        const dev = false;
        if(dev) console.log('init viivue_icomoon_select')

        // update value for the given field
        function update($field, value = null){
            const $input = $field.find('.vii-icomoon__hidden-input input');
            const $resultSvg = $field.find(".vii-icomoon__custom-field-result .icon-svg");
            const $resultName = $field.find(".vii-icomoon__custom-field-result .icon-name");
            value = value === null ? $input.val() : value;

            let svg = '';
            if(value.length){
                // has value
                let $icon = $field.find('a[data-icomoon-value="' + value + '"]');
                $field.removeClass('empty');
                svg = $icon.html();
                $field.find('[data-icomoon-value].active').removeClass('active');
                $icon.addClass("active");
            }else{
                // empty
                $field.addClass('empty');
                svg = '--';
            }

            // save value
            if(dev) console.log('update value:', value)
            $resultName.html(value);
            $resultSvg.html(svg);
            $input.val(value);
        }

        // update on load
        $('.vii-icomoon').each(function(){
            update($(this));
        });

        // show/hide popup
        $(document).on('click', '[data-icomoon="popup-trigger"]', function(e){
            const $field = $(e.target).closest('.vii-icomoon');
            const $popup = $field.find('.vii-icomoon__popup');

            e.preventDefault();
            $field.toggleClass("popup-open");
            if(dev) console.log('show/hide popup')

            // check popup position top/bottom
            let offsetTop = $field.offset().top - $(window).scrollTop(),
                popupTop = offsetTop > ($popup.outerHeight() + 40);
            if(popupTop){
                $field.addClass("popup-top");
                $field.removeClass("popup-bottom");
            }else{
                $field.removeClass("popup-top");
                $field.addClass("popup-bottom");
            }

            // check popup position left/right
            let offsetRight = $(window).width() - ($field.offset().left + $field.width()),
                popupLeft = offsetRight > ($popup.width() + 40);
            if(popupLeft){
                $field.addClass("popup-left");
                $field.removeClass("popup-right");
            }else{
                $field.removeClass("popup-left");
                $field.addClass("popup-right");
            }
        });

        // select icon
        $(document).on('click', '[data-icomoon="select"]', function(e){
            const $field = $(e.target).closest('.vii-icomoon');
            const $this = $(e.target).closest('[data-icomoon="select"]');

            e.preventDefault();
            if(dev) console.log('select icon:', $this.attr("data-icomoon-value"))

            $field.removeClass("popup-open");
            update($field, $this.attr("data-icomoon-value"));
        });

        // remove value
        $(document).on('click', '[data-icomoon="remove-value"]', function(e){
            const $field = $(e.target).closest('.vii-icomoon');

            e.preventDefault();
            if(dev) console.log('remove icon')

            update($field, '');
        });


        // click outside
        $(document).click(function(e){
            const $field = $(e.target).closest('.vii-icomoon');
            if(!$field.length){
                $('.vii-icomoon').removeClass("popup-open");
            }
        });
    }

    // init
    acf.add_action('ready', viivue_icomoon_select);

})(jQuery);
