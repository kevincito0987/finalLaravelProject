import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { GoogleOAuthProvider } from '@react-oauth/google';
import App from './App.tsx';
import './index.css';
import { setupFetchInterceptor, setupGlobalErrorHandlers } from './infrastructure/utils/errorHandling';
import { I18nextProvider } from 'react-i18next';
import i18n from './i18n.ts';

// Set up error handling and API request interception
setupFetchInterceptor();
setupGlobalErrorHandlers();

// Get Google OAuth client ID from environment variable
const GOOGLE_CLIENT_ID = import.meta.env.VITE_GOOGLE_CLIENT_ID || '';

// Clean up problematic data on start
try {
  const keysToRemove = [];
  for (let i = 0; i < localStorage.length; i++) {
    const key = localStorage.key(i);
    if (key && (
      key.startsWith('habita_') || 
      key.includes('chat') || 
      key.includes('message') || 
      key.includes('analytics') || 
      key.includes('bolt.new') ||
      key.includes('api')
    )) {
      keysToRemove.push(key);
    }
  }
  
  keysToRemove.forEach(key => {
    try {
      localStorage.removeItem(key);
    } catch (e) {
      // Ignore individual errors
    }
  });
} catch (error) {
  console.error('Error cleaning localStorage on start:', error);
}

// Add viewport meta tag for smartwatch support
const updateViewport = () => {
  const viewport = document.querySelector('meta[name="viewport"]');
  if (viewport) {
    // Enhanced viewport settings for better smartwatch support
    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover');
  }
};

// Call once on load
updateViewport();

// Always wrap with GoogleOAuthProvider, even if client ID is empty
// The individual components will handle the conditional display logic
createRoot(document.getElementById('root')!).render(
  <StrictMode>
    <GoogleOAuthProvider clientId={GOOGLE_CLIENT_ID}>
      <I18nextProvider i18n={i18n}>
        <App />
      </I18nextProvider>
    </GoogleOAuthProvider>
  </StrictMode>
);
