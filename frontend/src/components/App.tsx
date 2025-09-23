import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider } from '../context/ThemeContext';
import { AuthProvider, useAuth } from '../context/AuthContext';
import RouteTransition from './ui/RouteTransition';
import ErrorBoundary from './ui/ErrorBoundary';
import LandingPage from './LandingPage';
import Dashboard from './Dashboard';
import Login from './auth/Login';
import Register from './auth/Register';
import SplashScreen from './SplashScreen';
import ResponsiveWrapper from './ui/ResponsiveWrapper';

function AppRoutes() {
  const { user, loading } = useAuth();
  const [showSplash, setShowSplash] = useState(true);
  const [isSmartwatch, setIsSmartwatch] = useState(false);

  useEffect(() => {
    // Detect if we're on a smartwatch-sized screen
    const checkSmartwatch = () => {
      setIsSmartwatch(window.innerWidth < 280);
    };
    
    checkSmartwatch();
    window.addEventListener('resize', checkSmartwatch);
    return () => window.removeEventListener('resize', checkSmartwatch);
  }, []);

  useEffect(() => {
    // Solo mostrar el splash screen cuando el usuario está autenticado
    // y solo por 10 segundos (tiempo suficiente para las animaciones)
    if (user && showSplash) {
      const timer = setTimeout(() => {
        setShowSplash(false);
      }, isSmartwatch ? 5000 : 10000); // Tiempo más corto para smartwatches
      
      return () => clearTimeout(timer);
    }
  }, [user, showSplash, isSmartwatch]);

  if (loading) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-blue-100 via-blue-200 to-purple-200 dark:bg-gradient-to-br dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center transition-all duration-500">
        <div className="text-center space-y-4">
          <div className="relative w-16 h-16 mx-auto">
            <div className="absolute inset-0 border-4 border-emerald-200 rounded-full animate-spin"></div>
            <div className="absolute inset-2 border-4 border-emerald-600 rounded-full animate-spin" style={{ animationDirection: 'reverse' }}></div>
          </div>
          <p className="text-emerald-600 font-medium animate-pulse">Iniciando Habita...</p>
        </div>
      </div>
    );
  }

  // Mostrar el splash screen si el usuario está autenticado y showSplash es true
  if (user && showSplash) {
    return <SplashScreen onComplete={() => setShowSplash(false)} userName={user.profile?.name} />;
  }

  return (
    <ErrorBoundary>
      <ResponsiveWrapper>
        <RouteTransition>
          <Routes>
            <Route path="/" element={user ? <Navigate to="/dashboard" /> : <LandingPage />} />
            <Route path="/login" element={user ? <Navigate to="/dashboard" /> : <Login />} />
            <Route path="/register" element={user ? <Navigate to="/dashboard" /> : <Register />} />
            <Route path="/dashboard/*" element={user ? <Dashboard /> : <Navigate to="/login" />} />
          </Routes>
        </RouteTransition>
      </ResponsiveWrapper>
    </ErrorBoundary>
  );
}

function App() {
  return (
    <AuthProvider>
      <ThemeProvider>
          <Router>
            <div className="min-h-screen bg-white dark:bg-gray-900 transition-all duration-500">
              <AppRoutes />
            </div>
          </Router>
      </ThemeProvider>
    </AuthProvider>
  );
}

export default App;