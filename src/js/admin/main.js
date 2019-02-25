/** Class to handle Custom CTA image insert */
class MediaUploader {
  /** Class constructor */
  constructor (mediaUploader) {
    this.mediaUploader = mediaUploader
  }

  /** get selet field ot display selected image */
  get targetField () {
    return document.querySelector('.custom-cta__content__img > #target')
  }

  /** Open WordPress Media Insert */
  openMediaUploaderImage () {
    /** Call wp object from window */
    const _wp = window.wp || null

    /** Return if wp doesn't exist */
    if (!_wp) return

    /** Setting up media uploads */
    this.mediaUploader = _wp.media({
      frame: 'post',
      state: 'insert',
      multiple: false
    })

    /** Set a event on insert image */
    this.mediaUploader.on('insert', () => {
      const json = this.mediaUploader
        .state()
        .get('selection')
        .first()
        .toJSON()

      /** Set data-id to target */
      this.targetField.dataset.id = json.id

      /** Fire selected image to target field */
      this.writeImageOnSelectField(json.url)
    })

    this.mediaUploader.open()
  }

  /** Clear target background */
  cleanTargetBackgroud () {
    this.writeImageOnSelectField()
  }

  /** Set a image to target div */
  writeImageOnSelectField (url) {
    /** Looking for target on DOM */
    const el = this.targetField

    /** Return if target doesn't exists */
    if (!el) return

    /** Set url as default background-image of target */
    el.style.backgroundImage = `url(${url})`
  }

  /** Fire php publish */
  publish () {
    const _jQuery = window.jQuery
    const _ajaxUrl = window.ajaxurl

    if (!_jQuery || !_ajaxUrl) return

    const id = this.targetField.dataset.id

    const data = {
      action: 'publishCta',
      imgId: id
     }

    _jQuery.post(
      _ajaxUrl,
      data,
      res => (
        console.log(res)
      )
    )
  }
}

/** Instance class */
// eslint-disable-next-line no-unused-vars
const mediaUploader = new MediaUploader()
