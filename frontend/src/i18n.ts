import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

i18n
  .use(initReactI18next)
  .init({
    lng: 'es', // Por defecto a español
    fallbackLng: 'es',
    interpolation: {
      escapeValue: false
    }
  });

export default i18n;
