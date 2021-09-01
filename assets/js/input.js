(function($){
    /**
     * Icomoon Select v3.1
     */
    function fw_icomoon_select(){
        const dev = false;
        if(dev) console.log('init fw_icomoon_select')

        // update value for the given field
        function update($field, value = null){
            const $input = $field.find('.fw-icomoon-select__input input');
            const $resultSvg = $field.find(".fw-icomoon-select__field-result .icon-svg");
            const $resultName = $field.find(".fw-icomoon-select__field-result .icon-name");
            value = value === null ? $input.val() : value;

            let svg = '';
            if(value.length){
                // has value
                let $icon = $field.find('a[data-fw-value="' + value + '"]');
                $field.removeClass('empty');
                svg = $icon.html();
                $field.find('[data-fw-value].active').removeClass('active');
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
        $('.fw-icomoon-select').each(function(){
            update($(this));
        });

        // show/hide popup
        $(document).on('click', '[data-fw="popup-trigger"]', function(e){
            const $field = $(e.target).closest('.fw-icomoon-select');
            const $popup = $field.find('.fw-icomoon-select__popup');

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
        $(document).on('click', '[data-fw="select"]', function(e){
            const $field = $(e.target).closest('.fw-icomoon-select');
            const $this = $(e.target).closest('[data-fw="select"]');

            e.preventDefault();
            if(dev) console.log('select icon:', $this.attr("data-fw-value"))

            $field.removeClass("popup-open");
            update($field, $this.attr("data-fw-value"));
        });

        // remove value
        $(document).on('click', '[data-fw="remove-value"]', function(e){
            const $field = $(e.target).closest('.fw-icomoon-select');

            e.preventDefault();
            if(dev) console.log('remove icon')

            update($field, '');
        });


        // click outside
        $(document).click(function(e){
            const $field = $(e.target).closest('.fw-icomoon-select');
            if(!$field.length){
                $('.fw-icomoon-select').removeClass("popup-open");
            }
        });
    }

    // init
    acf.add_action('ready', fw_icomoon_select);

})(jQuery);
