"use strict";

(function($) {
    function aiocontactUs(element, options) {
        this._initialized = false;
        this.settings = null;
        this.options = $.extend({}, aiocontactUs.Defaults, options);
        this.$element = $(element);
        this.init();
        this.x = 0;
        this.y = 0;
        this._interval;
        this._menuOpened = false;
        this._callbackOpened = false;
        this.countdown = null;
    }
    aiocontactUs.Defaults = {
        align: 'right',
        mode: 'regular',
        countdown: 0,
        drag: false,
        buttonText: contactText,
        buttonSize: 'large',
        menuSize: 'normal',
        buttonIcon: '<svg width="20" height="20" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Canvas" transform="translate(-825 -308)"><g id="Vector"><use xlink:href="#path0_fill0123" transform="translate(825 308)" fill="#FFFFFF"/></g></g><defs><path id="path0_fill0123" d="M 19 4L 17 4L 17 13L 4 13L 4 15C 4 15.55 4.45 16 5 16L 16 16L 20 20L 20 5C 20 4.45 19.55 4 19 4ZM 15 10L 15 1C 15 0.45 14.55 0 14 0L 1 0C 0.45 0 0 0.45 0 1L 0 15L 4 11L 14 11C 14.55 11 15 10.55 15 10Z"/></defs></svg>',
        items: [],
        iconsAnimationSpeed: 800,
        theme: '#000000',
        closeIcon: '<svg width="12" height="13" viewBox="0 0 14 14" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Canvas" transform="translate(-4087 108)"><g id="Vector"><use xlink:href="#path0_fill" transform="translate(4087 -108)" fill="currentColor"></use></g></g><defs><path id="path0_fill" d="M 14 1.41L 12.59 0L 7 5.59L 1.41 0L 0 1.41L 5.59 7L 0 12.59L 1.41 14L 7 8.41L 12.59 14L 14 12.59L 8.41 7L 14 1.41Z"></path></defs></svg>',
    };
    aiocontactUs.prototype.init = function() {
        if (this._initialized) {
            return false;
        }
        this.destroy();
        this.settings = $.extend({}, this.options);
        this.$element.addClass('aiocontactus-widget').addClass('aiocontactus-message');
        if (this.settings.align === 'left') {
            this.$element.addClass('left');
        } else {
            this.$element.addClass('right');
        }
        if (this.settings.items.length) {
            this.$element.append('<!--noindex-->');
            this._initCallbackBlock();
            if (this.settings.mode == 'regular') {
                this._initMessengersBlock();
            }
            this._initMessageButton();
            this._initPrompt();
            this._initEvents();
            this.startAnimation();
            this.$element.append('<!--/noindex-->');
            this.$element.addClass('active');
        } else {
            console.info('jquery.contactus:no items');
        }
        this._initialized = true;
        this.$element.trigger('aiocontactus.init');
    };
    aiocontactUs.prototype.destroy = function() {
        if (!this._initialized) {
            return false;
        }
        this.$element.html('');
        this._initialized = false;
        this.$element.trigger('aiocontactus.destroy');
    };
    aiocontactUs.prototype._initCallbackBlock = function() {
        var $container = $('<div>', {
            class: 'callback-countdown-block',
            style: this._colorStyle()
        });
        var $close = $('<div>', {
            class: 'callback-countdown-block-close'
        });
        $close.append(this.settings.closeIcon);

        this.$element.append($container);
    };
    aiocontactUs.prototype._initMessengersBlock = function() {
        var $container = $('<div>', {
            class: 'messengers-block'
        });
        if (this.settings.menuSize === 'normal' || this.settings.menuSize === 'large') {
            $container.addClass('lg');
        }
        if (this.settings.menuSize === 'small') {
            $container.addClass('sm');
        }
        this._appendMessengerIcons($container);
        this.$element.append($container);
    };
    aiocontactUs.prototype._appendMessengerIcons = function($container) {
        $.each(this.settings.items, function(i) {

            var $item = $('<a>', {
                class: 'messenger' + (this.class ? this.class : ''),
                id: (this.id ? this.id : null),
                rel: 'nofollow noopener',
                href: this.href,
                style: this.style,
                target: (this.target ? this.target : '_blank')
            });
            if (this.onClick) {
                var $this = this;
                $item.on('click', function(e) {
                    $this.onClick(e);
                });
            }

            var $icon = $('<span>', {
                style: (this.color ? ('background-color:' + this.color) : null)
            });
            $icon.append(this.icon);
            $item.append($icon);
            $item.append('<p>' + this.title + '</p>');
            $container.append($item);
        });
    };
    aiocontactUs.prototype._initMessageButton = function() {
        var $this = this;
        var $container = $('<div>', {
            class: 'aiocontactus-message-button',
            style: this._backgroundStyle()
        });
        if (this.settings.buttonSize === 'large') {
            this.$element.addClass('lg');
        }
        if (this.settings.buttonSize === 'huge') {
            this.$element.addClass('hg');
        }
        if (this.settings.buttonSize === 'medium') {
            this.$element.addClass('md');
        }
        if (this.settings.buttonSize === 'small') {
            this.$element.addClass('sm');
        }
        var $static = $('<div>', {
            class: 'static'
        });

        $static.append(this.settings.buttonIcon);
        if (this.settings.buttonText !== false) {
            $static.append('<p>' + this.settings.buttonText + '</p>');
        } else {
            $container.addClass('no-text');
        }

        var $callBackState = $('<div>', {
            class: 'callback-state',
            style: $this._colorStyle()
        });

        $callBackState.append(this.settings.callbackStateIcon);

        var $icons = $('<div>', {
            class: 'icons hide'
        });

        var $iconsLine = $('<div>', {
            class: 'icons-line'
        });

        $.each(this.settings.items, function(i) {
            var $icon = $('<span>', {
                style: $this._colorStyle()
            });
            $icon.append(this.icon);
            $iconsLine.append($icon);
        });

        $icons.append($iconsLine);


        var $close = $('<div>', {
            class: 'aiocontactus-close'
        });

        $close.append(this.settings.closeIcon);

        var $pulsation = $('<div>', {
            class: 'pulsation',
            style: $this._backgroundStyle()
        });

        var $pulsation2 = $('<div>', {
            class: 'pulsation',
            style: $this._backgroundStyle()
        });

        $container.append($static).append($callBackState).append($icons).append($close).append($pulsation).append($pulsation2);

        this.$element.append($container);
    };

    aiocontactUs.prototype._initPrompt = function() {
        var $container = $('<div>', {
            class: 'aiocontactus-prompt'
        });
        var $close = $('<div>', {
            class: 'aiocontactus-prompt-close',
            style: this._colorStyle()
        });
        $close.append(this.settings.closeIcon);

        var $inner = $('<div>', {
            class: 'aiocontactus-prompt-inner',
        });

        $container.append($close).append($inner);

        this.$element.append($container);
    };

    aiocontactUs.prototype._initEvents = function() {
        var $el = this.$element;
        var $this = this;
        $el.find('.aiocontactus-message-button').on('mousedown', function(e) {
            $this.x = e.pageX;
            $this.y = e.pageY;
        }).on('mouseup', function(e) {
            if (e.pageX === $this.x && e.pageY === $this.y) {
                if ($this.settings.mode == 'regular') {
                    $this.toggleMenu();
                } else {
                    $this.openCallbackPopup();
                }
                e.preventDefault();
            }
        });
        if (this.settings.drag) {
            $el.draggable();
            $el.get(0).addEventListener('touchmove', function(event) {
                var touch = event.targetTouches[0];
                // Place element where the finger is
                $el.get(0).style.left = touch.pageX - 25 + 'px';
                $el.get(0).style.top = touch.pageY - 25 + 'px';
                event.preventDefault();
            }, false);
        }
        $(document).on('click', function(e) {
            $this.closeMenu();
        });
        $el.on('click', function(e) {
            e.stopPropagation();
        });
        $el.find('.call-back').on('click', function() {
            $this.openCallbackPopup();
        });
        $el.find('.callback-countdown-block-close').on('click', function() {
            if ($this.countdown != null) {
                clearInterval($this.countdown);
                $this.countdown = null;
            }
            $this.closeCallbackPopup();
        });
        $el.find('.aiocontactus-prompt-close').on('click', function() {
            $this.hidePrompt();
        });
        $el.find('form').on('submit', function(event) {
            event.preventDefault();
            $el.find('.callback-countdown-block-phone').addClass('ar-loading');
            if ($this.settings.reCaptcha) {
                grecaptcha.execute($this.settings.reCaptchaKey, {
                    action: $this.settings.reCaptchaAction
                }).then(function(token) {
                    $el.find('.ar-g-token').val(token);
                    $this.sendCallbackRequest();
                });
            } else {
                $this.sendCallbackRequest();
            }
        });
        setTimeout(function() {
            $this._processHash();
        }, 500);
        $(window).on('hashchange', function(event) {
            $this._processHash();
        });
    };
    aiocontactUs.prototype._processHash = function() {
            var hash = window.location.hash;
            var $this = this;
            switch (hash) {
                case '#callback-form':
                case 'callback-form':
                    $this.openCallbackPopup();
                    break;
                case '#callback-form-close':
                case 'callback-form-close':
                    $this.closeCallbackPopup();
                    break;
                case '#contactus-menu':
                case 'contactus-menu':
                    $this.openMenu();
                    break;
                case '#contactus-menu-close':
                case 'contactus-menu-close':
                    $this.closeMenu();
                    break;
                case '#contactus-hide':
                case 'contactus-hide':
                    $this.hide();
                    break;
                case '#contactus-show':
                case 'contactus-show':
                    $this.show();
                    break;
            }
        },
        aiocontactUs.prototype._callBackCountDownMethod = function() {
            var secs = this.settings.countdown;
            var $el = this.$element;
            var $this = this;
            var ms = 60;
            $el.find('.callback-countdown-block-phone, .callback-countdown-block-timer').toggleClass('display-flex');
            this.countdown = setInterval(function() {
                ms = ms - 1;
                var fsecs = secs;
                var fms = ms;
                if (secs < 10) {
                    fsecs = "0" + secs;
                }
                if (ms < 10) {
                    fms = "0" + ms;
                }
                var format = fsecs + ":" + fms;
                $el.find('.callback-countdown-block-timer_timer').html(format);
                if (ms === 0 && secs === 0) {
                    clearInterval($this.countdown);
                    $this.countdown = null;
                    $el.find('.callback-countdown-block-sorry, .callback-countdown-block-timer').toggleClass('display-flex');
                }
                if (ms === 0) {
                    ms = 60;
                    secs = secs - 1;
                }
            }, 20);
        };
    aiocontactUs.prototype.sendCallbackRequest = function() {
        var $this = this;
        var $el = $this.$element;
        this.$element.trigger('aiocontactus.beforeSendCallbackRequest');
        $.ajax({
            url: $this.settings.ajaxUrl,
            type: "POST",
            dataType: 'json',
            data: $el.find('form').serialize(),
            success: function(data) {
                if ($this.settings.countdown) {
                    $this._callBackCountDownMethod();
                }
                $el.find('.callback-countdown-block-phone').removeClass('ar-loading');
                if (data.success) {
                    if (!$this.settings.countdown) {
                        $el.find('.callback-countdown-block-sorry, .callback-countdown-block-phone').toggleClass('display-flex');
                    }
                } else {
                    if (data.errors) {
                        var errors = data.errors.join("\n\r");
                        alert(errors);
                    } else {
                        alert($this.settings.errorMessage);
                    }
                }
                $this.$element.trigger('aiocontactus.successCallbackRequest', data);
            },
            error: function() {
                $el.find('.callback-countdown-block-phone').removeClass('ar-loading');
                alert($this.settings.errorMessage);
                $this.$element.trigger('aiocontactus.errorCallbackRequest');
            }
        });
    };
    aiocontactUs.prototype.show = function() {
        this.$element.addClass('active');
        this.$element.trigger('aiocontactus.show');
    };
    aiocontactUs.prototype.hide = function() {
        this.$element.removeClass('active');
        this.$element.trigger('aiocontactus.hide');
    };
    aiocontactUs.prototype.openMenu = function() {
        if (this.settings.mode == 'callback') {
            console.log('Widget in callback mode');
            return false;
        }
        var $el = this.$element;
        if (!$el.find('.messengers-block').hasClass('show-messageners-block')) {
            this.stopAnimation();
            $el.find('.messengers-block, .aiocontactus-close').addClass('show-messageners-block');
            $el.find('.icons, .static').addClass('hide');
            $el.find('.pulsation').addClass('stop');
            this._menuOpened = true;
            this.$element.trigger('aiocontactus.openMenu');
        }
    };
    aiocontactUs.prototype.closeMenu = function() {
        if (this.settings.mode == 'callback') {
            console.log('Widget in callback mode');
            return false;
        }
        var $el = this.$element;
        if ($el.find('.messengers-block').hasClass('show-messageners-block')) {
            $el.find('.messengers-block, .aiocontactus-close').removeClass('show-messageners-block');
            $el.find('.icons, .static').removeClass('hide');
            $el.find('.pulsation').removeClass('stop');
            this.startAnimation();
            this._menuOpened = false;
            this.$element.trigger('aiocontactus.closeMenu');
        }
    };
    aiocontactUs.prototype.toggleMenu = function() {
        var $el = this.$element;
        this.hidePrompt();
        if ($el.find('.callback-countdown-block').hasClass('display-flex')) {
            return false;
        }
        if (!$el.find('.messengers-block').hasClass('show-messageners-block')) {
            this.openMenu();
        } else {
            this.closeMenu();
        }
        this.$element.trigger('aiocontactus.toggleMenu');
    };
    aiocontactUs.prototype.openCallbackPopup = function() {
        var $el = this.$element;
        $el.addClass('opened');
        this.closeMenu();
        this.stopAnimation();
        $el.find('.icons, .static').addClass('hide');
        $el.find('.pulsation').addClass('stop');
        $el.find('.callback-countdown-block').addClass('display-flex');
        $el.find('.callback-countdown-block-phone').addClass('display-flex');
        $el.find('.callback-state').addClass('display-flex');
        this._callbackOpened = true;
        this.$element.trigger('aiocontactus.openCallbackPopup');
    };
    aiocontactUs.prototype.closeCallbackPopup = function() {
        var $el = this.$element;
        $el.removeClass('opened');
        $el.find('.messengers-block').removeClass('show-messageners-block');
        $el.find('.aiocontactus-close').removeClass('show-messageners-block');
        $el.find('.icons, .static').removeClass('hide');
        $el.find('.pulsation').removeClass('stop');
        $el.find('.callback-countdown-block, .callback-countdown-block-phone, .callback-countdown-block-sorry, .callback-countdown-block-timer').removeClass('display-flex');
        $el.find('.callback-state').removeClass('display-flex');
        this.startAnimation();
        this._callbackOpened = false;
        this.$element.trigger('aiocontactus.closeCallbackPopup');
    };
    aiocontactUs.prototype.startAnimation = function() {
        var $el = this.$element;
        var $container = $el.find('.icons-line');
        var $static = $el.find('.static');
        var width = $el.find('.icons-line>span:first-child').width();
        var offset = width + 40;
        if (this.settings.buttonSize === 'huge') {
            var xOffset = 2;
            var yOffset = 0;
        }
        if (this.settings.buttonSize === 'large') {
            var xOffset = 2;
            var yOffset = 0;
        }
        if (this.settings.buttonSize === 'medium') {
            var xOffset = 4;
            var yOffset = -2;
        }
        if (this.settings.buttonSize === 'small') {
            var xOffset = 4;
            var yOffset = -2;
        }
        var iconsCount = $el.find('.icons-line>span').length;
        var step = 0;
        this.stopAnimation();
        if (this.settings.iconsAnimationSpeed === 0) {
            return false;
        }
        this._interval = setInterval(function() {
            if (step === 0) {
                $container.parent().removeClass('hide');
                $static.addClass('hide');
            }
            var x = offset * step;
            var translate = 'translate(' + (-(x + xOffset)) + 'px, ' + yOffset + 'px)';
            $container.css({
                "-webkit-transform": translate,
                "-ms-transform": translate,
                "transform": translate
            });
            step++;
            if (step > iconsCount) {
                if (step > iconsCount + 1) {
                    step = 0;
                }
                $container.parent().addClass('hide');
                $static.removeClass('hide');
                var translate = 'translate(' + (-xOffset) + 'px, ' + yOffset + 'px)';
                $container.css({
                    "-webkit-transform": translate,
                    "-ms-transform": translate,
                    "transform": translate
                });
            }
        }, this.settings.iconsAnimationSpeed);
    };
    aiocontactUs.prototype.stopAnimation = function() {
        clearInterval(this._interval);
        var $el = this.$element;
        var $container = $el.find('.icons-line');
        var $static = $el.find('.static');
        $container.parent().addClass('hide');
        $static.removeClass('hide');
        var translate = 'translate(' + (-2) + 'px, 0px)';
        $container.css({
            "-webkit-transform": translate,
            "-ms-transform": translate,
            "transform": translate
        });
    };
    aiocontactUs.prototype.showPrompt = function(data) {
        var $promptContainer = this.$element.find('.aiocontactus-prompt');
        if (data && data.content) {
            $promptContainer.find('.aiocontactus-prompt-inner').html(data.content);
        }
        $promptContainer.addClass('active');
        this.$element.trigger('aiocontactus.showPrompt');
    };
    aiocontactUs.prototype.hidePrompt = function() {
        var $promptContainer = this.$element.find('.aiocontactus-prompt');
        $promptContainer.removeClass('active');
        this.$element.trigger('aiocontactus.hidePrompt');
    };
    aiocontactUs.prototype.showPromptTyping = function() {
        var $promptContainer = this.$element.find('.aiocontactus-prompt');
        $promptContainer.find('.aiocontactus-prompt-inner').html('');
        this._insertPromptTyping();
        this.showPrompt({});
        this.$element.trigger('aiocontactus.showPromptTyping');
    };
    aiocontactUs.prototype._insertPromptTyping = function() {
        var $promptContainer = this.$element.find('.aiocontactus-prompt-inner');
        var $typing = $('<div>', {
            class: 'aiocontactus-prompt-typing'
        });
        var $item = $('<div>');
        $typing.append($item);
        $typing.append($item.clone());
        $typing.append($item.clone());
        $promptContainer.append($typing);
    };
    aiocontactUs.prototype.hidePromptTyping = function() {
        var $promptContainer = this.$element.find('.aiocontactus-prompt');
        $promptContainer.removeClass('active');
        this.$element.trigger('aiocontactus.hidePromptTyping');
    };
    aiocontactUs.prototype._backgroundStyle = function() {
        return 'background-color: ' + this.settings.theme;
    };
    aiocontactUs.prototype._colorStyle = function() {
        return 'color: ' + this.settings.theme;
    };
    $.fn.contactUs = function(option) {
        var args = Array.prototype.slice.call(arguments, 1);
        return this.each(function() {
            var $this = $(this),
                data = $this.data('ar.contactus');

            if (!data) {
                data = new aiocontactUs(this, typeof option == 'object' && option);
                $this.data('ar.contactus', data);
            }

            if (typeof option == 'string' && option.charAt(0) !== '_') {
                data[option].apply(data, args);
            }
        });
    };
    $.fn.contactUs.Constructor = aiocontactUs;
}(jQuery));