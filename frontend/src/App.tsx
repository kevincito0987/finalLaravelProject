import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider } from './context/ThemeContext';
import { AuthProvider, useAuth } from './context/AuthContext';
import RouteTransition from './components/ui/RouteTransition';
import ErrorBoundary from './components/ui/ErrorBoundary';
import LandingPage from './components/LandingPage';
import Dashboard from './components/Dashboard';
import Login from './components/auth/Login';
import Register from './components/auth/Register';
import SplashScreen from './components/SplashScreen';
import ResponsiveWrapper from './components/ui/ResponsiveWrapper';
import { motion } from 'framer-motion';

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
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-purple-100 dark:bg-gradient-to-br dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-all duration-500 relative overflow-hidden">
              {/* Animated background elements for dark mode */}
              {document.documentElement.classList.contains('dark') && (
                <div className="absolute inset-0 z-0 overflow-hidden">
                  {/* Floating particles */}
                  {[...Array(30)].map((_, i) => (
                    <motion.div
                      key={i}
                      className="absolute w-1 h-1 bg-blue-500/10 rounded-full"
                      animate={{
                        x: [Math.random() * 100, Math.random() * 100 + 50],
                        y: [Math.random() * 100, Math.random() * 100 - 50],
                        opacity: [0, 0.5, 0],
                        scale: [0, 1, 0]
                      }}
                      transition={{
                        duration: Math.random() * 5 + 3,
                        repeat: Infinity,
                        delay: Math.random() * 2
                      }}
                      style={{
                        left: `${Math.random() * 100}%`,
                        top: `${Math.random() * 100}%`
                      }}
                    />
                  ))}
                  
                  {/* Gradient overlay */}
                  <motion.div
                    className="absolute inset-0 bg-gradient-to-br from-blue-900/5 to-purple-900/5"
                    animate={{
                      background: [
                        'radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.05), transparent 70%)',
                        'radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.05), transparent 70%)'
                      ]
                    }}
                    transition={{ duration: 10, repeat: Infinity, ease: "easeInOut" }}
                  />
                </div>
              )}
              
              <AppRoutes />
            </div>
          </Router>
      </ThemeProvider>
    </AuthProvider>
  );
}

export default App;