/** Class to handle Custom CTA image insert */
class MediaUploader {
  /** Class constructor */
  constructor (mediaUploader) {
    this.mediaUploader = mediaUploader
  }

  /** Get select field ot display selected image */
  get targetImgField () {
    return document.querySelector('.custom-cta__content__img > #target')
  }

  /** Get selet field to display selected image */
  get targetTextField () {
    return document.querySelector('.custom-cta__content__input > input')
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

      /** Fire selected image to target field */
      this.writeImageOnSelectField(json)
    })

    this.mediaUploader.open()
  }

  /** Clear target background */
  cleanTargetBackgroud () {
    this.writeImageOnSelectField({
      id: 0,
      url: ''
    })
  }

  /** Set a image to target div */
  writeImageOnSelectField (data) {
    /** Looking for target on DOM */
    const el = this.targetImgField

    /** Return if target doesn't exists */
    if (!el) return

    /** Set img id as data-id of target */
    el.dataset.id = data.id

    /** Set url as default background-image of target */
    let url = String(data.url).replace(/\\/g, '')
    el.style.backgroundImage = `url(${url})`

    data.destiny
      ? (this.targetTextField.value = data.destiny)
      : (this.targetTextField.value = '')
  }

  /** Fire php publish */
  publish () {
    const _jQuery = window.jQuery
    const _ajaxUrl = window.ajaxurl

    if (!_jQuery || !_ajaxUrl) return

    const id = parseInt(this.targetImgField.dataset.id)
    const text = this.targetTextField.value

    if (id === 0 || id === undefined) {
      window.confirm('Alert\nNenhuma imagem selecionada. Prosseguir mesmo assim?')
    }

    const data = {
      action: 'publishCta',
      imgId: id,
      pageDestiny: text
    }

    _jQuery.post(
      _ajaxUrl,
      data,
      response => {
        function messageOk () {
          window.alert('Info\nCTA publicado!')
          document.location.reload(true)
        }

        response = JSON.parse(response)

        response.warning !== undefined
          ? window.alert(`Alerta\n${response.warning}`)
          : (response['historic']['img_id'] === undefined || response['current']['img_id'] === undefined)
            ? window.alert('Erro\nFalha solicitação. Contate o desenvolvedor')
            : messageOk()

        window.localStorage.setItem('ctaPostLog', JSON.stringify(response))
      }
    )
  }
}

/** Instance class */
// eslint-disable-next-line no-unused-vars
const mediaUploader = new MediaUploader()
