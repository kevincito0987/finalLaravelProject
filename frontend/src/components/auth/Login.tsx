import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { Eye, EyeOff, Loader, Globe } from 'lucide-react';
import { useAuth } from '../../context/AuthContext';
import { useTheme } from '../../context/ThemeContext';
import HabitaLogo from '../ui/HabitaLogo';
import { GoogleLogin } from '@react-oauth/google';
import { motion } from 'framer-motion';

export default function Login() {
  const [email, setEmail] = useState('demo@habita.app');
  const [password, setPassword] = useState('demo123');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState('');
  const [currentLanguage, setCurrentLanguage] = useState('es');
  
  const { login, handleGoogleLogin } = useAuth();
  const { theme } = useTheme();

  // Check if Google OAuth is configured properly
  const googleClientId = import.meta.env.VITE_GOOGLE_CLIENT_ID;
  const isGoogleConfigured = googleClientId && 
    googleClientId !== 'your_google_client_id_here.apps.googleusercontent.com' &&
    googleClientId.trim() !== '';

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setError('');

    try {
      await login(email, password);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Credenciales inválidas');
    } finally {
      setIsLoading(false);
    }
  };

  const handleGoogleLoginSuccess = async (credentialResponse: any) => {
    setIsLoading(true);
    setError('');

    try {
      await handleGoogleLogin(credentialResponse);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Error al iniciar sesión con Google');
    } finally {
      setIsLoading(false);
    }
  };

  const handleGoogleLoginError = () => {
    setError('Error al iniciar sesión con Google. Por favor intenta de nuevo.');
  };

  const toggleLanguage = () => {
    setCurrentLanguage(prev => {
      const nextLang = prev === 'es' ? 'en' : prev === 'en' ? 'fr' : prev === 'fr' ? 'pt' : 'es';
      return nextLang;
    });
  };

  const translations = {
    es: {
      welcomeBack: 'Bienvenido de vuelta',
      continueJourney: 'Continúa tu viaje de bienestar',
      email: 'Correo electrónico',
      password: 'Contraseña',
      rememberMe: 'Recordarme',
      forgotPassword: '¿Olvidaste tu contraseña?',
      login: 'Iniciar sesión',
      continueWithEmail: 'O continúa con email',
      newToHabita: '¿Nuevo en Habita?',
      createAccount: 'Crear una cuenta',
      loading: 'Cargando...',
      loginWithGoogle: 'Iniciar sesión con Google'
    },
    en: {
      welcomeBack: 'Welcome back',
      continueJourney: 'Continue your wellness journey',
      email: 'Email',
      password: 'Password',
      rememberMe: 'Remember me',
      forgotPassword: 'Forgot your password?',
      login: 'Login',
      continueWithEmail: 'Or continue with email',
      newToHabita: 'New to Habita?',
      createAccount: 'Create an account',
      loading: 'Loading...',
      loginWithGoogle: 'Sign in with Google'
    },
    fr: {
      welcomeBack: 'Bon retour',
      continueJourney: 'Continuez votre voyage de bien-être',
      email: 'Email',
      password: 'Mot de passe',
      rememberMe: 'Se souvenir de moi',
      forgotPassword: 'Mot de passe oublié?',
      login: 'Connexion',
      continueWithEmail: 'Ou continuez avec email',
      newToHabita: 'Nouveau sur Habita?',
      createAccount: 'Créer un compte',
      loading: 'Chargement...',
      loginWithGoogle: 'Se connecter avec Google'
    },
    pt: {
      welcomeBack: 'Bem-vindo de volta',
      continueJourney: 'Continue sua jornada de bem-estar',
      email: 'Email',
      password: 'Senha',
      rememberMe: 'Lembrar-me',
      forgotPassword: 'Esqueceu sua senha?',
      login: 'Entrar',
      continueWithEmail: 'Ou continue com email',
      newToHabita: 'Novo no Habita?',
      createAccount: 'Criar uma conta',
      loading: 'Carregando...',
      loginWithGoogle: 'Entrar com Google'
    }
  };

  const t = translations[currentLanguage as keyof typeof translations];

  return (
    <div className="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 transition-all duration-500 relative overflow-hidden">
      {/* Background Images - Full Screen Coverage */}
      <div className="absolute inset-0 z-0">
        {/* Night landscape background for both themes */}
        <motion.div 
          className="absolute inset-0 w-full h-full bg-cover bg-center"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=1920&h=1080&fit=crop&crop=center')`,
            opacity: theme === 'light' ? 0.15 : 0.25
          }}
          animate={{ 
            scale: [1, 1.05, 1],
            opacity: theme === 'light' ? [0.15, 0.18, 0.15] : [0.25, 0.30, 0.25]
          }}
          transition={{ duration: 20, repeat: Infinity, ease: "easeInOut" }}
        />
        
        {/* Additional subtle overlay image */}
        <motion.div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-8"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop&crop=center')`
          }}
          animate={{ 
            opacity: [0.08, 0.05, 0.08]
          }}
          transition={{ duration: 15, repeat: Infinity, ease: "easeInOut" }}
        />
        
        {/* Gradient overlay for readability - covers full screen */}
        <div className={`absolute inset-0 w-full h-full ${
          theme === 'light'
            ? 'bg-gradient-to-br from-blue-100/75 via-blue-200/70 to-purple-200/75'
            : 'bg-gradient-to-br from-emerald-50/85 to-blue-50/85 dark:from-gray-900/80 dark:to-gray-800/80'
        }`} />
      </div>

      {/* Language Selector */}
      <div className="absolute top-4 right-4 z-20">
        <motion.button
          onClick={toggleLanguage}
          className={`flex items-center space-x-1 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-300 ${
            theme === 'light'
              ? 'bg-white/80 text-slate-700 hover:bg-white shadow-sm'
              : 'bg-gray-800/80 text-gray-300 hover:bg-gray-700'
          }`}
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
        >
          <Globe className="w-3 h-3 mr-1" />
          <span>{currentLanguage.toUpperCase()}</span>
        </motion.button>
      </div>

      <div className="relative z-10 sm:mx-auto sm:w-full sm:max-w-md">
        <div className="flex justify-center">
          <HabitaLogo size="lg" animated />
        </div>
        <h2 className={`mt-6 text-center text-3xl font-bold ${
          theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
        }`}>
          {t.welcomeBack}
        </h2>
        <p className={`mt-2 text-center text-sm ${
          theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
        }`}>
          {t.continueJourney}
        </p>
      </div>

      <div className="relative z-10 mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div className={`py-8 px-4 shadow-xl sm:rounded-lg sm:px-10 transition-all duration-300 ${
          theme === 'light'
            ? 'bg-white/95 backdrop-blur-md border border-white/70 shadow-2xl'
            : 'bg-white dark:bg-gray-800/95 backdrop-blur-md border border-gray-100/50 dark:border-gray-700/50'
        }`}>
          {/* Google Sign-In Button - IMPROVED */}
          <motion.div 
            className="mb-6"
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.2 }}
          >
            <motion.button
              onClick={() => {
                // The GoogleLogin component will handle the click
                document.getElementById('google-login-button')?.click();
              }}
              className={`w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl border-2 font-medium transition-all duration-300 ${
                theme === 'light'
                  ? 'bg-white hover:bg-gray-50 text-gray-700 border-gray-300 hover:border-gray-400 shadow-sm hover:shadow'
                  : 'bg-white hover:bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white dark:border-gray-600'
              }`}
              whileHover={{ scale: 1.02 }}
              whileTap={{ scale: 0.98 }}
            >
              <svg width="24" height="24" viewBox="0 0 24 24">
                <path
                  d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                  fill="#4285F4"
                />
                <path
                  d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                  fill="#34A853"
                />
                <path
                  d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                  fill="#FBBC05"
                />
                <path
                  d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                  fill="#EA4335"
                />
                <path d="M1 1h22v22H1z" fill="none" />
              </svg>
              <span>{t.loginWithGoogle}</span>
            </motion.button>
            
            {/* Hidden Google Login button */}
            <div className="hidden">
              <GoogleLogin
                id="google-login-button"
                onSuccess={handleGoogleLoginSuccess}
                onError={handleGoogleLoginError}
                useOneTap
                theme={theme === 'dark' ? 'filled_black' : 'outline'}
                text="signin_with"
                shape="rectangular"
                locale={currentLanguage}
                logo_alignment="center"
                width="100%"
              />
            </div>
          </motion.div>

          <div className="relative mb-6">
            <div className="absolute inset-0 flex items-center">
              <div className={`w-full border-t ${
                theme === 'light' ? 'border-gray-300' : 'border-gray-300 dark:border-gray-600'
              }`} />
            </div>
            <div className="relative flex justify-center text-sm">
              <span className={`px-2 ${
                theme === 'light'
                  ? 'bg-white/95 text-slate-500'
                  : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400'
              }`}>
                {t.continueWithEmail}
              </span>
            </div>
          </div>

          <form className="space-y-6" onSubmit={handleSubmit}>
            {error && (
              <div className={`border px-4 py-3 rounded ${
                theme === 'light'
                  ? 'bg-red-50/95 border-red-200 text-red-700 backdrop-blur-sm'
                  : 'bg-red-50 dark:bg-red-900/50 border-red-200 dark:border-red-800 text-red-700 dark:text-red-400'
              }`}>
                {error}
              </div>
            )}
            
            <div>
              <label htmlFor="email" className={`block text-sm font-medium ${
                theme === 'light' ? 'text-slate-700' : 'text-gray-700 dark:text-gray-300'
              }`}>
                {t.email}
              </label>
              <div className="mt-1">
                <input
                  id="email"
                  name="email"
                  type="email"
                  autoComplete="email"
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className={`appearance-none block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm ${
                    theme === 'light'
                      ? 'border-gray-300 placeholder-gray-400 bg-white/95 text-slate-800 backdrop-blur-sm'
                      : 'border-gray-300 dark:border-gray-600 placeholder-gray-400 dark:placeholder-gray-500 dark:bg-gray-700 dark:text-white'
                  }`}
                  placeholder="Ingresa tu correo"
                />
              </div>
            </div>

            <div>
              <label htmlFor="password" className={`block text-sm font-medium ${
                theme === 'light' ? 'text-slate-700' : 'text-gray-700 dark:text-gray-300'
              }`}>
                {t.password}
              </label>
              <div className="mt-1 relative">
                <input
                  id="password"
                  name="password"
                  type={showPassword ? 'text' : 'password'}
                  autoComplete="current-password"
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className={`appearance-none block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm pr-10 ${
                    theme === 'light'
                      ? 'border-gray-300 placeholder-gray-400 bg-white/95 text-slate-800 backdrop-blur-sm'
                      : 'border-gray-300 dark:border-gray-600 placeholder-gray-400 dark:placeholder-gray-500 dark:bg-gray-700 dark:text-white'
                  }`}
                  placeholder="Ingresa tu contraseña"
                />
                <button
                  type="button"
                  className="absolute inset-y-0 right-0 pr-3 flex items-center"
                  onClick={() => setShowPassword(!showPassword)}
                >
                  {showPassword ? (
                    <EyeOff className="h-4 w-4 text-gray-400" />
                  ) : (
                    <Eye className="h-4 w-4 text-gray-400" />
                  )}
                </button>
              </div>
            </div>

            <div className="flex items-center justify-between">
              <div className="flex items-center">
                <input
                  id="remember-me"
                  name="remember-me"
                  type="checkbox"
                  className={`h-4 w-4 text-emerald-600 focus:ring-emerald-500 rounded ${
                    theme === 'light'
                      ? 'border-gray-300'
                      : 'border-gray-300 dark:border-gray-600'
                  }`}
                />
                <label htmlFor="remember-me" className={`ml-2 block text-sm ${
                  theme === 'light' ? 'text-slate-700' : 'text-gray-900 dark:text-gray-300'
                }`}>
                  {t.rememberMe}
                </label>
              </div>

              <div className="text-sm">
                <a href="#" className="font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-500">
                  {t.forgotPassword}
                </a>
              </div>
            </div>

            <div>
              <motion.button
                type="submit"
                disabled={isLoading}
                className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                whileHover={{ scale: 1.02 }}
                whileTap={{ scale: 0.98 }}
              >
                {isLoading ? (
                  <Loader className="w-4 h-4 animate-spin" />
                ) : (
                  t.login
                )}
              </motion.button>
            </div>
          </form>

          <div className="mt-6">
            <div className="relative">
              <div className="absolute inset-0 flex items-center">
                <div className={`w-full border-t ${
                  theme === 'light' ? 'border-gray-300' : 'border-gray-300 dark:border-gray-600'
                }`} />
              </div>
              <div className="relative flex justify-center text-sm">
                <span className={`px-2 ${
                  theme === 'light'
                    ? 'bg-white/95 text-slate-500'
                    : 'bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400'
                }`}>
                   {t.newToHabita}
                </span>
              </div>
            </div>

            <div className="mt-6">
              <Link
                to="/register"
                className={`w-full flex justify-center py-2 px-4 border rounded-md shadow-sm text-sm font-medium transition-colors ${
                  theme === 'light'
                    ? 'border-gray-300 text-slate-700 bg-white/95 hover:bg-white backdrop-blur-sm'
                    : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'
                }`}
              >
                {t.createAccount}
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}