(function($, _) {
    var l10n,
        media = wp.media;
    l10n = media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {} : _wpMediaViewsL10n;
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
    var original = wp.media.view.MediaFrame.Select;
    wp.media.view.MediaFrame.Select = original.extend({
        initialize: function() {
            original.prototype.initialize.apply(this, arguments);
            this.on('content:render:wppress_dummy_image', this.wppress_dummy_imageContent, this);
        },
        browseRouter: function(routerView) {
            routerView.set({
                upload: {
                    text: l10n.uploadFilesTitle,
                    priority: 20
                },
                browse: {
                    text: l10n.mediaLibraryTitle,
                    priority: 40
                },
                wppress_dummy_image: {
                    text: l10n.wppressInsertDummyImageTitle,
                    priority: 90
                },
            });
        },
        wppress_dummy_imageContent: function() {
            var view = new wp.media.view.DummyImage({
                    controller: this,
                    model: this.state().props
                }),
                _this = this,
                state = _this.state(),
                library = state.get('library');
            var width,
                height,
                bg,
                color;
            _this.content.set(view);
            setTimeout(function() {
                /* I dont' know why i am doing this.. but timing out gave me proper variables for later use. */
                width = $('.wprpess_dummy_image_wrap .dummy_width', _this.$el);
                height = $('.wprpess_dummy_image_wrap .dummy_height', _this.$el);
                bg = $('.wprpess_dummy_image_wrap .dummy_bg', _this.$el);
                color = $('.wprpess_dummy_image_wrap .dummy_color', _this.$el);
                $('.dummy_colorpicker', _this.$el).wpColorPicker();
            }, 500);
            this.$el.on('click', '.wprpess_dummy_image_wrap .dummy_upload', function(e) {
                var _width = width.val(),
                    _height = height.val(),
                    _bg = bg.val().replace('#',""),
                    _color = color.val().replace('#',""),
                    _params = {
                        action: "upload_dummy_image",
                        width: _width,
                        height: _height,
                        bg:_bg,
                        color:_color,
                    };
                var __this = $(this);
                __this.text('Creating Dummy Image..').attr('disabled', "disabled");
                library._requery(true);
                $.getJSON(ajaxurl, _params, function(data) {
                    if (data.result == "success") {
                        __this.text('Upload Dummy Image').removeAttr('disabled');
                        _this.content.mode("browse");
                    } else {
                        __this.text('Upload Dummy Image').removeAttr('disabled');
                    }
                });
            })
        },
    });
}(jQuery, _));
