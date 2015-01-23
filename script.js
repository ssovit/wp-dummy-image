(function($, _) {
    var l10n,
        media = wp.media;
    l10n = media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {} : _wpMediaViewsL10n;
    var originalC = wp.media.controller.Library;
    wp.media.controller.Library = originalC.extend({

    });
    wp.media.view.DummyImage = wp.media.View.extend({
        tagName: 'div',
        className: 'wppress-custom',
        template: media.template('wppress-custom'),
        events: {
            'click .close': 'hide'
        },
        initialize: function() {
            if (_.isUndefined(this.options.postId)) {
                this.options.postId = media.view.settings.post.id;
            }
        },
        show: function() {
            this.$el.removeClass('hidden');
        },
        hide: function() {
            this.$el.addClass('hidden');
        }
    });
    _.each(["Select", "Post"], function(handle) {
        var original = wp.media.view.MediaFrame[handle];
        wp.media.view.MediaFrame[handle] = original.extend({
            initialize: function() {
                original.prototype.initialize.apply(this, arguments);
                this.on('content:render:wppress_dummy_image', this.wppress_dummy_imageContent, this);
                this.on('content:activate:wppress_dummy_image', this.wppress_dummy_imageContentActivated, this);
                this.on('ready', this.wppress_dummy_imageContentActivated, this);
            },
            browseRouter: function(routerView) {
                routerView.set({
                    wppress_dummy_image: {
                        text: l10n.wppressInsertDummyImageTitle,
                        priority: 21
                    },
                });
                original.prototype.browseRouter.apply(this, arguments);
            },
            wppress_dummy_imageContent: function() {
                var view = new wp.media.view.DummyImage({
                    controller: this,
                    model: this.state().props
                });

                this.content.set(view);
            },
            wppress_dummy_imageContentActivated: function() {
                if (this.content.mode() != "wppress_dummy_image") {
                    return;
                }
                var _this = this,
                    state = _this.state(),
                    library = state.get('library'),
                    width = $('.wppress_dummy_image_wrap .dummy_width', _this.$el),
                    height = $('.wppress_dummy_image_wrap .dummy_height', _this.$el),
                    bg = $('.wppress_dummy_image_wrap .dummy_bg', _this.$el),
                    color = $('.wppress_dummy_image_wrap .dummy_color', _this.$el),
                    _data = _this.$el.data('wppress_state_data');
                $('.dummy_colorpicker', _this.$el).wpColorPicker();
                if (_.isObject(_data)) {
                    width.val(_data.width);
                    height.val(_data.height);
                    bg.wpColorPicker('color', _data.bg);
                    color.wpColorPicker('color', _data.color);
                }

                $('.wppress_dummy_image_wrap', _this.$el).on('click', '.dummy_upload', function(e) {
                    e.preventDefault();
                    var _width = width.val(),
                        _height = height.val(),
                        _bg = bg.val().replace('#', ""),
                        _color = color.val().replace('#', ""),
                        __this = $(this),
                        _params = {
                            width: _width,
                            height: _height,
                            bg: _bg,
                            color: _color,
                        };
                    _this.$el.data('wppress_state_data', _params);
                    _params.action = "upload_dummy_image";
                    library._requery(true);
                    __this.text('Creating Dummy Image..').attr('disabled', "disabled");
                    $.getJSON(ajaxurl, _params, function(data) {
                        if (data.result == "success") {
                            __this.text('Upload Dummy Image').removeAttr('disabled');
                            _this.content.mode("browse");
                        } else {
                            __this.text('Upload Dummy Image').removeAttr('disabled');
                        }
                    });
                });
                $('.wppress_dummy_image_wrap', _this.$el).on('click', '.dummy_sizes', function(e) {
                    e.preventDefault();
                    var _w = $(this).attr('data-width'),
                        _h = $(this).attr('data-height');
                    width.val(_w);
                    height.val(_h);
                });
            },
            trigger: function() {
                // just for debuggin purpose
                //console.log('Event Triggered:', arguments);
                original.prototype.trigger.apply(this, Array.prototype.slice.call(arguments));
            },
        });
    })





}(jQuery, _));
