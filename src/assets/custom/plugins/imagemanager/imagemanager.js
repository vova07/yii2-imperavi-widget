(function ($) {
    $.Redactor.prototype.imagemanager = function () {
        return {
            init: function () {
                if (!this.opts.imageManagerJson) return;

                this.modal.addCallback('image', this.imagemanager.load);
            },
            load: function () {
                var $modal = this.modal.getModal();

                this.modal.createTabber($modal);
                this.modal.addTab(1, this.lang.get('upload'), 'active');
                this.modal.addTab(2, this.lang.get('choose'));

                if (this.opts.imageDelete) {
                    this.modal.addTab(3, this.lang.get('_delete'));
                }

                $('#redactor-modal-image-droparea').addClass('redactor-tab redactor-tab1');

                var $box2 = $('<div id="redactor-image-manager-box" style="overflow: auto; height: 300px;" class="redactor-tab redactor-tab2">').hide();
                $modal.append($box2);

                $.ajax({
                    dataType: "json",
                    cache: false,
                    url: this.opts.imageManagerJson,
                    success: $.proxy(function (data) {
                        $.each(data, $.proxy(function (key, val) {
                            // title
                            var thumbtitle = '';
                            if (typeof val.title !== 'undefined') thumbtitle = val.title;
                            var id = '';
                            if (typeof val.id !== 'undefined') id = val.id;

                            var img = $('<img src="' + val.thumb + '" rel="' + val.image + '" title="' + thumbtitle + '" data-id="' + id + '" style="width: 100px; height: 75px; cursor: pointer;" />');
                            $('#redactor-image-manager-box').append(img);
                            $(img).click($.proxy(this.imagemanager.insert, this));

                        }, this));
                    }, this)
                });

                if (this.opts.imageDelete) {
                    var $box3 = $('<div id="redactor-image-manager-delete-box" style="overflow: auto; height: 300px;" class="redactor-tab redactor-tab3">').hide();
                    $modal.append($box3);

                    $.ajax({
                        dataType: "json",
                        cache: false,
                        url: this.opts.imageManagerJson,
                        success: $.proxy(function (data) {
                            $.each(data, $.proxy(function (key, val) {
                                // title
                                var thumbtitle = '';
                                if (typeof val.title !== 'undefined') thumbtitle = val.title;
                                var id = '';
                                if (typeof val.id !== 'undefined') id = val.id;

                                var img = $('<img src="' + val.thumb + '" rel="' + val.image + '" title="' + thumbtitle + '" data-id="' + id + '" style="width: 100px; height: 75px; cursor: pointer;" />');
                                $('#redactor-image-manager-delete-box').append(img);
                                $(img).click($.proxy(this.imagemanager.delete, this));

                            }, this));
                        }, this)
                    });
                }
            },
            insert: function (e) {
                this.image.insert('<img src="' + $(e.target).attr('rel') + '" alt="' + $(e.target).attr('title') + '" data-id="' + $(e.target).data('id') + '">');
            },
            delete: function (e) {
                $.ajax({
                    type: "delete",
                    dataType: "json",
                    cache: false,
                    url: this.opts.imageDelete,
                    data: {"fileName": $(e.target).data('id')},
                    success: function (data) {
                        $('#redactor-image-manager-box img[src="' + data['url'] + '"]').remove();
                        $('#redactor-image-manager-delete-box img[src="' + data['url'] + '"]').remove();
                    },
                    error: function (xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        };
    };
})(jQuery);
