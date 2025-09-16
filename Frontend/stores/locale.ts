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
      popularGame: 'Popular Game',
      gameProvider:'Provider',
      userName:'Username',
      currency:'Currency',
      phoneNumber:'Phone number',
      referCode:'Refer Code',
      email:'Email',
      password:'Password',
      optional:'optional',
      confirmPassword:'Confirm Password',
    },
    bn: {
      name: 'bangla_name',
      recentlyPlay: 'সম্প্রতি খেলা',
      gameCategory: 'গেম বিভাগ',
      popularGame: 'জনপ্রিয় গেম',
      gameProvider: 'প্রোভাইডার',
      userName:'ইউজারনেম',
      currency: 'মুদ্রা',
      phoneNumber: 'ফোন নম্বর',
      referCode: 'রেফার কোড',
      email: 'ইমেইল',
      password: 'পাসওয়ার্ড',
      optional:'ঐচ্ছিক',
      confirmPassword:'পাসওয়ার্ড নিশ্চিত করুন',
    },
    hi: {
      name: 'hindi_name',
      recentlyPlay: 'हाल ही में खेला',
      gameCategory: 'गेम श्रेणी',
      popularGame: 'लोकप्रिय खेल',
      gameProvider: 'प्रदाता',
      userName:'यूज़रनेम',
      currencycurrency: 'मुद्रा',
      phoneNumber: 'फ़ोन नंबर',
      referCode: 'रेफ़र कोड',
      email: 'ईमेल',
      password: 'पासवर्ड',
      optional:'वैकल्पिक',
      confirmPassword:'पासवर्ड की पुष्टि करें',
    }
  }

  function getTranslate(key: keyof typeof translations['en']) {
    const lang = locale.value as LocaleKey
    return translations[lang]?.[key] || translations['en'][key] || ''
  }

  return { locale, setLocale, setLocal, resetLocale, translations, getTranslate }
})
