export default defineNuxtPlugin(() => {
  if (process.client) {
    ;(window as any).Tawk_API = (window as any).Tawk_API || {}
    ;(window as any).Tawk_LoadStart = new Date()

    const s1 = document.createElement('script')
    s1.async = true
    s1.src = 'https://embed.tawk.to/68b2e9b7109d7be2aa210de6/1j3te1udn'
    s1.charset = 'UTF-8'
    s1.setAttribute('crossorigin', '*')

    s1.onload = () => {
      if ((window as any).Tawk_API) {
        (window as any).Tawk_API.onLoad = function () {
          ;(window as any).Tawk_API.hideWidget() 
        }
      }
    }

    document.body.appendChild(s1)
  }
})