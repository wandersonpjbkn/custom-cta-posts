"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/** Class to handle Custom CTA image insert */
var MediaUploader =
/*#__PURE__*/
function () {
  /** Class constructor */
  function MediaUploader(mediaUploader) {
    _classCallCheck(this, MediaUploader);

    this.mediaUploader = mediaUploader;
  }
  /** Get select field ot display selected image */


  _createClass(MediaUploader, [{
    key: "openMediaUploaderImage",

    /** Open WordPress Media Insert */
    value: function openMediaUploaderImage() {
      var _this = this;

      /** Call wp object from window */
      var _wp = window.wp || null;
      /** Return if wp doesn't exist */


      if (!_wp) return;
      /** Setting up media uploads */

      this.mediaUploader = _wp.media({
        frame: 'post',
        state: 'insert',
        multiple: false
      });
      /** Set a event on insert image */

      this.mediaUploader.on('insert', function () {
        var json = _this.mediaUploader.state().get('selection').first().toJSON();
        /** Fire selected image to target field */


        _this.writeImageOnSelectField(json);
      });
      this.mediaUploader.open();
    }
    /** Clear target background */

  }, {
    key: "cleanTargetBackgroud",
    value: function cleanTargetBackgroud() {
      this.writeImageOnSelectField({
        id: 0,
        url: ''
      });
    }
    /** Set a image to target div */

  }, {
    key: "writeImageOnSelectField",
    value: function writeImageOnSelectField(data) {
      /** Looking for target on DOM */
      var el = this.targetImgField;
      /** Return if target doesn't exists */

      if (!el) return;
      /** Set img id as data-id of target */

      el.dataset.id = data.id;
      /** Set url as default background-image of target */

      var url = String(data.url).replace(/\\/g, '');
      el.style.backgroundImage = "url(".concat(url, ")");
      data.destiny ? this.targetTextField.value = data.destiny : this.targetTextField.value = '';
    }
    /** Fire php publish */

  }, {
    key: "publish",
    value: function publish() {
      var _jQuery = window.jQuery;
      var _ajaxUrl = window.ajaxurl;
      if (!_jQuery || !_ajaxUrl) return;
      var id = parseInt(this.targetImgField.dataset.id);
      var text = this.targetTextField.value;

      if (id === 0 || id === undefined) {
        window.confirm('Alert\nNenhuma imagem selecionada. Prosseguir mesmo assim?');
      }

      var data = {
        action: 'publishCta',
        imgId: id,
        pageDestiny: text
      };

      _jQuery.post(_ajaxUrl, data, function (response) {
        function messageOk() {
          window.alert('Info\nCTA publicado!');
          document.location.reload(true);
        }

        response = JSON.parse(response);
        response.warning !== undefined ? window.alert("Alerta\n".concat(response.warning)) : response['historic']['img_id'] === undefined || response['current']['img_id'] === undefined ? window.alert('Erro\nFalha solicitação. Contate o desenvolvedor') : messageOk();
        window.localStorage.setItem('ctaPostLog', JSON.stringify(response));
      });
    }
  }, {
    key: "targetImgField",
    get: function get() {
      return document.querySelector('.custom-cta__content__img > #target');
    }
    /** Get selet field to display selected image */

  }, {
    key: "targetTextField",
    get: function get() {
      return document.querySelector('.custom-cta__content__input > input');
    }
  }]);

  return MediaUploader;
}();
/** Instance class */
// eslint-disable-next-line no-unused-vars


var mediaUploader = new MediaUploader();
