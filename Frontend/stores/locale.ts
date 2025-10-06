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
      image:'Image',
      updateProfile:'Update Profile',
      nameInput:'Name',
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
      optional:'Optional',
      confirmPassword:'Confirm Password',
      deposite:'Deposit',
      withdrow:'Withdraw',
      selectBalance:'Select Balance',
      enterAmount:'Enter Amount',
      enterAmountText:'Minimum Amount : BDT 100, Maximum Amount : BDT 25,000',
      selectPaymentChannel:'Select Payment Channel',
      selectBonus:'Select Bonus',
      submit:'Submit',
      viewAll:'View all',
    },
    bn: {
      image:'ইমেজ',
      updateProfile: 'প্রোফাইল আপডেট করুন',
      nameInput: 'নাম',
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
      deposite:'ডিপোজিট',
      withdrow:'উইথড্র',
      selectBalance:'ব্যালেন্স নির্বাচন করুন',
      enterAmount:'পরিমাণ লিখুন',
      enterAmountText:'ন্যূনতম পরিমাণ : BDT 100, সর্বোচ্চ পরিমাণ : BDT 25,000',
      selectPaymentChannel:'পেমেন্ট চ্যানেল নির্বাচন করুন',
      selectBonus:'বোনাস নির্বাচন করুন',
      submit:'সাবমিট',
      viewAll: 'সব দেখুন',
    },
    hi: {
      image:'छवि',
      updateProfile: 'प्रोफ़ाइल अपडेट करें',
      nameInput: 'नाम',
      name: 'hindi_name',
      recentlyPlay: 'हाल ही में खेला',
      gameCategory: 'गेम श्रेणी',
      popularGame: 'लोकप्रिय खेल',
      gameProvider: 'प्रदाता',
      userName:'यूज़रनेम',
      currency: 'मुद्रा',
      phoneNumber: 'फ़ोन नंबर',
      referCode: 'रेफ़र कोड',
      email: 'ईमेल',
      password: 'पासवर्ड',
      optional:'वैकल्पिक',
      confirmPassword:'पासवर्ड की पुष्टि करें',
      deposite:'जमा करें',
      withdrow:'निकासी',
      selectBalance:'बैलेंस चुनें',
      enterAmount:'राशि दर्ज करें',
      enterAmountText:'न्यूनतम राशि : BDT 100, अधिकतम राशि : BDT 25,000',
      selectPaymentChannel:'भुगतान चैनल चुनें',
      selectBonus:'बोनस चुनें',
      submit:'सबमिट',
      viewAll: 'सभी देखें',
    }
  }


  function getTranslate(key: keyof typeof translations['en']) {
    const lang = locale.value as LocaleKey
    return translations[lang]?.[key] || translations['en'][key] || ''
  }

  return { locale, setLocale, setLocal, resetLocale, translations, getTranslate }
})
