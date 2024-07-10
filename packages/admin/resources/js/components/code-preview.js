import { codeToHtml } from 'shiki'

const CodePreview = ({ code, lang, themes }) => {
    return {
        code,
        lang,
        themes,
        previewCode: null,

        async init() {
            this.previewCode = await codeToHtml(this.code, {
                lang,
                themes: this.themes,
            })

            this.$el.innerHTML = this.previewCode
        },

        destroy() {
            this.previewCode = null
        },
    }
}

export default CodePreview
