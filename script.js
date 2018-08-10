(function($, _) {
    var l10n;
    var media = wp.media;
    l10n = media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {} : _wpMediaViewsL10n;
    /*    var originalC = media.controller.Library;
        media.controller.Library = originalC.extend({});
    */
    var DummyImage = media.View.extend({
        tagName: 'div',
        className: 'wppress-custom',
        template: media.template('wppress-custom'),
        regions: ['content', 'router'],
        events: {
            'click .close': 'hide',
            'click .dummy_upload': 'dummyUpload',
            'click .dummy_sizes': 'dummyChangeSize'
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
        },
        dummyChangeSize: function(e) {
            e.preventDefault();
            var width = $('.dummy_width', this.$el),
                height = $('.dummy_height', this.$el),
                _w = $(e.target)
                .attr('data-width'),
                _h = $(e.target)
                .attr('data-height');
            width.val(_w);
            height.val(_h);
        },
        dummyUpload: function(e) {
            e.preventDefault();
            var button = $(e.target);
            var controller = this.controller;
            var state = this.controller.state();
            var library = this.controller.state()
                .get('library');
            var selection = state.get('selection');
            /* Now Variables */
            var width = $('.dummy_width', this.$el)
                .val();
            var height = $('.dummy_height', this.$el)
                .val();
            var image_keyword = $('.dummy_keyword', this.$el)
                .val();
            var color = $('.dummy_color', this.$el)
                .val();
            var background = $('.dummy_bg', this.$el)
                .val();
            var _params = {
                width: width,
                height: height,
                bg: background.replace('#', ""),
                color: color.replace('#', ""),
                image_keyword: image_keyword,
                action: "upload_dummy_image",
            };
            library._requery(true);
            button.text('Creating Dummy Image..')
                .attr('disabled', "disabled");
            $.getJSON(ajaxurl, _params, function(data) {
                if (data.result == "success") {
                    button.text('Upload Dummy Image')
                        .removeAttr('disabled');
                    this._dummy_image_id = data.id;
                    var attach = media.attachment(data.id);
                    if (selection.multiple == "reset") {
                        selection.reset([], {
                            silent: true
                        });
                        selection.validateAll(state.frame._selection.attachments);
                    }
                    selection.add(attach);
                    controller.content.mode("browse");
                } else {
                    button.text('Upload Dummy Image')
                        .removeAttr('disabled');
                    alert("Something went wrong.. Please try again!");
                }
            });
        }
    });
    _.each(["Select", "Post"], function(handle) {
        var original = media.view.MediaFrame[handle];
        media.view.MediaFrame[handle] = original.extend({
            initialize: function() {
                original.prototype.initialize.apply(this, arguments);
            },
            bindHandlers: function() {
                original.prototype.bindHandlers.apply(this, arguments);
                this.on('content:render:wppress_dummy_image', this.wppress_dummy_imageContent, this);
                this.on('content:activate:wppress_dummy_image', this.wppress_dummy_imageContentActivated, this);
                this.on('content:deactivate:wppress_dummy_image', this.wppress_dummy_imageContentDeactivated, this);
            },
            browseRouter: function(routerView) {
                original.prototype.browseRouter.apply(this, arguments);
                routerView.set({
                    wppress_dummy_image: {
                        text: l10n.wppressInsertDummyImageTitle,
                        priority: 21
                    },
                });
            },
            wppress_dummy_imageContent: function() {
                var view = new DummyImage({
                    controller: this,
                    model: this.state()
                        .props
                });
                this.content.set(view);
            },
            wppress_dummy_imageContentActivated: function() {},
            wppress_dummy_imageContentDeactivated: function() {},
            trigger: function() {
                //console.log("Event: ", arguments);
                original.prototype.trigger.apply(this, Array.prototype.slice.call(arguments));
            },
        });
    });
}(jQuery, _));