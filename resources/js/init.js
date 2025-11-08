document.addEventListener('alpine:init', () => {
  Alpine.data('tinymce', (options = {}, callbacks = {}) => ({
    options: options,
    callbacks: callbacks,

    async init() {
      await this.$nextTick()

      const editor = new tinymce.Editor(
        this.$el.getAttribute('id'),
        this.config(Alpine.store('darkMode').on),
        tinymce.EditorManager
      )

      editor.on('focusout', () => tinymce.activeEditor.save())
      editor.render()

      window.addEventListener('darkMode:toggle', () => tinymce.remove(editor))
    },

    fileManager: (callback, value, meta) => {
      const x =
        window.innerWidth ||
        document.documentElement.clientWidth ||
        document.getElementsByTagName('body')[0].clientWidth
      const y =
        window.innerHeight ||
        document.documentElement.clientHeight ||
        document.getElementsByTagName('body')[0].clientHeight
      console.log(meta)
      const cmsURL =
        (options.path_absolute ?? '/') +
        (options.file_manager ?? '') +
        '?editor=' +
        meta.fieldname +
        (meta.filetype === 'image' ? '&type=Images' : '&type=Files')

      tinymce.activeEditor.windowManager.openUrl({
        url: cmsURL,
        title: 'File Manager',
        width: x * 0.8,
        height: y * 0.8,
        resizable: 'yes',
        close_previous: 'no',
        onMessage: (api, message) => callback(message.content),
      })
    },

    config(darkMode) {
      for (const key in this.callbacks) {
        this.callbacks[key] = new Function('return ' + this.callbacks[key])()
      }

      return Object.assign(
        {
          path_absolute: '/',
          file_manager: '',
          relative_urls: false,
          branding: false,
          height: 50,
          skin: darkMode ? 'oxide-dark' : 'oxide',
          content_css: darkMode ? 'dark' : 'default',
          content_style: `[data-mce-placeholder]::before {color: var(--ms-cm-base-text) !important; opacity: .5;}`,
          file_picker_callback: this.options.file_manager ? this.fileManager : null,
        },
        this.options,
        this.callbacks
      )
    }
  }))
})

