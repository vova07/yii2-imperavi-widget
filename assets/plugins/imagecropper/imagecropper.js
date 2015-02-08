/**
 * @link https://github.com/denar90/imperavi-image-cropper-plugin
 * @copyright Copyright (c) 2015 denar90
 * @license http://opensource.org/licenses/MIT MIT
 */
if (!RedactorPlugins) var RedactorPlugins = {};

(function($)
{
	RedactorPlugins.imagecropper = function()
	{
		return {
			cropped: false,
			modalWidth: 700,
			buttonCropText: 'Crop',
			buttonSaveText: 'Save without cropping',

			init: function() {
				var button = this.button.add('imagecropper', 'Cropper');
				this.button.setAwesome('imagecropper', 'fa-picture-o');
				this.button.addCallback(button, this.imagecropper.showModal);
			},
			showModal: function() {
				this.opts.imagecropper.modalWidth = this.opts.imagecropper.modalWidth || this.imagecropper.modalWidth;
				this.opts.imagecropper.buttonCropText = this.opts.imagecropper.buttonCropText || this.imagecropper.buttonCropText;
				this.opts.imagecropper.buttonSaveText = this.opts.imagecropper.buttonSaveText || this.imagecropper.buttonSaveText;

				this.modal.load('image', this.lang.get('image'), this.opts.imagecropper.modalWidth);
				this.upload.init('#redactor-modal-image-droparea', this.opts.imagecropper.imageUpload, this.imagecropper.crop);
				this.selection.save();
				this.modal.show();
			},
			crop: function(json, direct, e) {
				if (!this.imagecropper.cropped) {
					var self = this,
						template =
							'<form class="js-crop-form" style="margin:2rem">' +
								'<div class="img-container">' +
									'<a class="js-crop" style="margin:0.5rem" href="#">' +
										'<i class="fa fa-crop fa-lg"></i> ' + this.opts.imagecropper.buttonCropText  +
									'</a>' +
									'<a class="js-save" style="margin:0.5rem" href="#">' +
										'<i class="fa fa-floppy-o fa-lg"></i> ' + this.opts.imagecropper.buttonSaveText +
									'</a>' +
									'<img class="js-image" src="' + json.filelink + '">' +
									'<input class="js-crop-data" name="data" type="hidden">' +
									'<input class="js-crop-src" name="src" value="' + json.filelink + '" type="hidden">' +
								'</div>' +
							'</form>';

					self.imagecropper.cropped = true;

					this.upload.$droparea.find('.js-crop-form').remove();
					this.upload.$droparea.append(template);
					this.upload.$droparea.css('padding', '0');

					this.opts.imagecropper.options.built = function () {
						$(this).cropper("zoom", 0.5);
					};

					this.opts.imagecropper.options.done = function (data) {
						var cropData = [
							'{"x":' + data.x,
							'"y":' + data.y,
							'"height":' + data.height,
							'"width":' + data.width + "}"
						].join();
						$('.js-crop-data').val(cropData);
					};

					$('.js-image').cropper(this.opts.imagecropper.options);

					this.upload.$droparea.on('click', '.js-crop', function (e) {
						e.preventDefault();
						if (self.imagecropper.imagecropped()) {
							var formData = new FormData($('.js-crop-form')[0]);
							self.upload.sendData(formData);
						} else {
							self.imagecropper.insertImage(json);
						}
					});

					this.upload.$droparea.on('click', '.js-save', function (e) {
						e.preventDefault();
						self.imagecropper.insertImage(json);
					});
				} else {
					this.imagecropper.insertImage(json);
				}
			},
			imagecropped: function() {
				var cropData = JSON.parse($('.js-crop-data').val()),
					imageHeight = $('.js-image').height(),
					imageWidth = $('.js-image').width();

				if (cropData.height == imageHeight && cropData.width == imageWidth) {
					return false;
				} else {
					return true;
				}
			},
			insertImage: function(json) {
				var $img = $('<img>');
				this.imagecropper.cropped = false;
				$img.attr('src', json.filelink).attr('data-redactor-inserted-image', 'true');
				this.modal.close();
				this.insert.html(this.utils.getOuterHtml($img), false);
			}
		};
	};
})(jQuery);