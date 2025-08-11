type LocaleKey = 'en' | 'bn' | 'hi'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLocaleStore = defineStore('locale', () => {
  const locale = ref<LocaleKey>('en')

  if (process.client) {
    const saved = localStorage.getItem('locale') as LocaleKey | null
    if (saved && ['en','bn','hi'].includes(saved)) locale.value = saved
  }

  function setLocale(newLocale: LocaleKey) {
    locale.value = newLocale
    if (process.client) {
      localStorage.setItem('locale', newLocale)
    }
  }

  function setLocal(newLocale: LocaleKey) {
    locale.value = newLocale
  }

  function resetLocale() {
    setLocale('en')
  }

  const translations = {
    en: {
      name: 'english_name',
      recentlyPlay: 'Recently Play',
      gameCategory: 'Game Category',
      popularGame: 'Popular Game'
    },
    bn: {
      name: 'bangla_name',
      recentlyPlay: 'সম্প্রতি খেলা',
      gameCategory: 'গেম বিভাগ',
      popularGame: 'জনপ্রিয় গেম'
    },
    hi: {
      name: 'hindi_name',
      recentlyPlay: 'हाल ही में खेला',
      gameCategory: 'गेम श्रेणी',
      popularGame: 'लोकप्रिय खेल'
    }
  }

  function getTranslate(key: keyof typeof translations['en']) {
    const lang = locale.value as LocaleKey
    return translations[lang]?.[key] || translations['en'][key] || ''
  }

  return { locale, setLocale, setLocal, resetLocale, translations, getTranslate }
})
