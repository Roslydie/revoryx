<template>
  <div class="lang-switcher" role="group" aria-label="Language selector">
    <button class="lang-switcher__button" type="button" aria-label="Choose language" @click="isOpen = !isOpen">
      <span>{{ currentLanguage.flag }}</span>
      <span>{{ currentLanguage.code.toUpperCase() }}</span>
      <i class="fa fa-angle-down" aria-hidden="true"></i>
    </button>
    <div v-if="isOpen" class="lang-switcher__options">
      <button
        v-for="language in availableLangs"
        :key="language.code"
        class="lang-switcher__option"
        type="button"
        :class="{ active: language.code === currentLang }"
        @click="switchLang(language.code)"
      >
        <span>{{ language.flag }}</span>
        <span>{{ language.name }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { setLocale, availableLangs as langs } from '../../plugins/i18n';

const { locale } = useI18n();
const isOpen = ref(false);
const availableLangs = ref(langs.map((language) => ({
  ...language,
  flag: language.code === 'fr' ? '🇫🇷' : '🇺🇸',
})));

const currentLang = computed(() => locale.value);
const currentLanguage = computed(() => availableLangs.value.find(
  (language) => language.code === currentLang.value,
) ?? availableLangs.value[0]);

const switchLang = (lang) => {
  setLocale(lang);
  locale.value = lang;
  localStorage.setItem('lang', lang);
  isOpen.value = false;
};

onMounted(() => {
  const saved = localStorage.getItem('lang');
  if (saved && availableLangs.value.some((language) => language.code === saved)) {
    locale.value = saved;
  }
});
</script>

<style>
.lang-switcher { position: relative; }
.lang-switcher button { font: inherit; }
.lang-switcher__button, .lang-switcher__option { display: flex; align-items: center; border: 0; cursor: pointer; }
.lang-switcher__button { gap: 7px; min-width: 68px; padding: 9px 10px; border: 1px solid #dce5f1; border-radius: 3px; color: #232323; background: #fff; font-size: 12px; font-weight: 700; }
.lang-switcher__button i { margin-left: auto; color: #0c5adb; }
.lang-switcher__options { position: absolute; top: calc(100% + 8px); right: 0; z-index: 40; min-width: 130px; padding: 6px; border: 1px solid #dce5f1; border-radius: 3px; background: #fff; box-shadow: 0 8px 24px rgba(0, 0, 0, .12); }
.lang-switcher__option { gap: 9px; width: 100%; padding: 8px 9px; color: #555; background: transparent; text-align: left; }
.lang-switcher__option:hover, .lang-switcher__option.active { color: #0c5adb; background: #f2f6fc; }
.techno_nav_manu.sticky .lang-switcher__button { border-color: rgba(255, 255, 255, .35); color: #fff; background: transparent; }
@media (max-width: 767px) { .lang-switcher__options { right: auto; left: 0; } }
</style>