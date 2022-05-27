(function ($) {
    $.Redactor.prototype.filemanager = function () {
        return {
            init: function () {
                if (!this.opts.fileManagerJson) return;

                this.modal.addCallback('file', this.filemanager.load);
            },
            load: function () {
                var $modal = this.modal.getModal();

                this.modal.createTabber($modal);
                this.modal.addTab(1, this.lang.get('upload'), 'active');
                this.modal.addTab(2, this.lang.get('choose'));

                if (this.opts.fileDelete) {
                    this.modal.addTab(3, this.lang.get('_delete'));
                }

                $('#redactor-modal-file-upload-box').addClass('redactor-tab redactor-tab1');

                var $box2 = $('<div id="redactor-file-manager-box" style="overflow: auto; height: 300px;" class="redactor-tab redactor-tab2">').hide();
                $modal.append($box2);


                $.ajax({
                    dataType: "json",
                    cache: false,
                    url: this.opts.fileManagerJson,
                    success: $.proxy(function (data) {
                        var ul = $('<ul id="redactor-modal-list">');
                        $.each(data, $.proxy(function (key, val) {
                            var a = $('<a href="#" title="' + val.title + '" rel="' + val.link + '" class="redactor-file-manager-link">' + val.title + ' <span style="font-size: 11px; color: #888;">' + val.name + '</span> <span style="position: absolute; right: 10px; font-size: 11px; color: #888;">(' + val.size + ')</span></a>');
                            var li = $('<li />');

                            a.on('click', $.proxy(this.filemanager.insert, this));

                            li.append(a);
                            ul.append(li);

                        }, this));

                        $('#redactor-file-manager-box').append(ul);
                    }, this)
                });

                if (this.opts.fileDelete) {
                    var $box3 = $('<div id="redactor-file-manager-delete-box" style="overflow: auto; height: 300px;" class="redactor-tab redactor-tab3">').hide();
                    $modal.append($box3);

                    $.ajax({
                        dataType: "json",
                        cache: false,
                        url: this.opts.fileManagerJson,
                        success: $.proxy(function (data) {
                            var ul = $('<ul id="redactor-modal-list">');
                            $.each(data, $.proxy(function (key, val) {
                                var id = val.title;
                                if (typeof val.id !== 'undefined') id = val.id;

                                var a = $('<a href="#" data-id="' + id + '" title="' + val.title + '" rel="' + val.link + '" class="redactor-file-manager-link">' + val.title + ' <span style="font-size: 11px; color: #888;">' + val.name + '</span> <span style="position: absolute; right: 10px; font-size: 11px; color: #888;">(' + val.size + ')</span></a>');
                                var li = $('<li />');

                                a.on('click', $.proxy(this.filemanager.delete, this));

                                li.append(a);
                                ul.append(li);

                            }, this));

                            $('#redactor-file-manager-delete-box').append(ul);
                        }, this)
                    });
                }
            },
            insert: function (e) {
                e.preventDefault();

                var $target = $(e.target).closest('.redactor-file-manager-link');

                this.file.insert('<a href="' + $target.attr('rel') + '">' + $target.attr('title') + '</a>');
            },
            delete: function (e) {
                var $target = $(e.target).closest('.redactor-file-manager-link');

                $.ajax({
                    type: "delete",
                    dataType: "json",
                    cache: false,
                    url: this.opts.fileDelete,
                    data: {"fileName": $target.data('id')},
                    success: function (data) {
                        $('#redactor-modal-list li a[rel="' + data['url'] + '"]').remove();
                    },
                    error: function (xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            }
        };
    };
})(jQuery);
