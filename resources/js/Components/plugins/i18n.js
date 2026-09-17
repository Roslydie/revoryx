import { createI18n } from "vue-i18n";
import detectBrowserLanguage from "detect-browser-language";

// All available languages
export const availableLangs = [
    { code: 'en', name: 'English' },
    { code: 'fr', name: 'Français' },
    // Add more languages here as needed
];

// Get all translations
const messages = {};

// Use Vite's import.meta.glob for dynamic imports
const modules = import.meta.glob('./locales/*.json', { eager: true });

// Load all language files
for (const lang of availableLangs) {
    const path = `./locales/${lang.code}.json`;
    if (modules[path]) {
        messages[lang.code] = modules[path].default;
    } else {
        console.warn(`Language file not found for ${lang.code}`);
    }
}

// Determine initial locale: localStorage -> browser detection -> default
const availableCodes = availableLangs.map(l => l.code);
const savedLocale = localStorage.getItem('lang');

let detected = null;
try {
    const raw = detectBrowserLanguage();
    if (typeof raw === 'string' && raw.length) {
        const normalized = raw.toLowerCase().split('-')[0];
        detected = availableCodes.includes(normalized) ? normalized : null;
    }
} catch (e) {
    detected = null;
}

const initialLocale = (savedLocale && availableCodes.includes(savedLocale))
    ? savedLocale
    : (detected || availableCodes[0]);

const i18n = createI18n({
    legacy: false,
    locale: initialLocale,
    globalInjection: true,
    messages,
});

export const setLocale = (locale) => {
    if (i18n.global.locale.value !== locale) {
        i18n.global.locale.value = locale;
        // Save locale to localStorage
        localStorage.setItem('lang', locale);
    }
};

export const getCurrentLocale = () => {
    return i18n.global.locale.value;
};

export default i18n;