import React, { useState, useEffect } from 'react';
import { Routes, Route } from 'react-router-dom';
import { motion, AnimatePresence } from 'framer-motion';
import Sidebar from './dashboard/Sidebar';
import Header from './dashboard/Header';
import Overview from './dashboard/Overview';
import CheckIn from './dashboard/CheckIn';
import Activities from './dashboard/Activities';
import Progress from './dashboard/Progress';
import Community from './dashboard/Community';
import VoiceInterface from './dashboard/VoiceInterface';
import Emergency from './dashboard/Emergency';
import Billing from './dashboard/Billing';
import Profile from './dashboard/Profile';
import RouteTransition from './ui/RouteTransition';
import { useTheme } from '../contexts/ThemeContext';
import { Globe } from 'lucide-react';

export default function Dashboard() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [currentLanguage, setCurrentLanguage] = useState('es');
  const { theme } = useTheme();
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

  const toggleLanguage = () => {
    setCurrentLanguage(prev => {
      const nextLang = prev === 'es' ? 'en' : prev === 'en' ? 'fr' : prev === 'fr' ? 'pt' : 'es';
      return nextLang;
    });
  };

  return (
    <div className={`h-screen flex overflow-hidden transition-all duration-500 relative ${
      theme === 'light' 
        ? 'bg-gradient-to-br from-blue-100 via-blue-200 to-purple-200' 
        : 'bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900'
    }`}>
      {/* Background Images for Dashboard */}
      <div className="absolute inset-0 z-0">
        {theme === 'light' ? (
          <>
            {/* ✅ SOLO UNA IMAGEN: Paisaje nocturno sereno */}
            <div 
              className="absolute inset-0 w-full h-full bg-cover bg-center opacity-8"
              style={{
                backgroundImage: `url('https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=1920&h=1080&fit=crop&crop=center')`
              }}
            />
          </>
        ) : (
          <>
            {/* ✅ SOLO UNA IMAGEN: Paisaje nocturno para modo oscuro */}
            <div 
              className="absolute inset-0 w-full h-full bg-cover bg-center opacity-6"
              style={{
                backgroundImage: `url('https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=1920&h=1080&fit=crop&crop=center')`
              }}
            />
          </>
        )}
        
        {/* Gradient overlay for readability */}
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-gradient-to-br from-blue-100/85 via-blue-200/80 to-purple-200/85'
            : 'bg-gradient-to-br from-gray-900/90 via-gray-800/85 to-gray-900/90'
        }`} />
      </div>

      {/* Language Selector - Responsive positioning */}
      <div className={`absolute ${isSmartwatch ? 'top-1 right-1' : 'top-4 right-20'} z-50`}>
        <motion.button
          onClick={toggleLanguage}
          className={`flex items-center space-x-1 ${
            isSmartwatch ? 'px-1.5 py-1 text-xxs' : 'px-3 py-1.5 text-xs'
          } rounded-full font-medium transition-all duration-300 ${
            theme === 'light'
              ? 'bg-white/80 text-slate-700 hover:bg-white shadow-sm'
              : 'bg-gray-800/80 text-gray-300 hover:bg-gray-700'
          }`}
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
        >
          <Globe className={`${isSmartwatch ? 'w-2 h-2' : 'w-3 h-3'} mr-1`} />
          <span>{currentLanguage.toUpperCase()}</span>
        </motion.button>
      </div>

      <div className="relative z-10 flex w-full">
        {/* Responsive Sidebar */}
        <Sidebar sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} currentLanguage={currentLanguage} />
        
        <div className="flex-1 overflow-hidden flex flex-col">
          {/* Responsive Header */}
          <Header setSidebarOpen={setSidebarOpen} currentLanguage={currentLanguage} />
          
          {/* Responsive Main Content */}
          <main className={`flex-1 overflow-y-auto ${isSmartwatch ? 'p-1' : 'p-2 sm:p-4 md:p-6'}`}>
            {/* Transiciones específicas para rutas del dashboard */}
            <RouteTransition>
              <Routes>
                <Route path="/" element={<Overview currentLanguage={currentLanguage} />} />
                <Route path="/checkin" element={<CheckIn currentLanguage={currentLanguage} />} />
                <Route path="/activities" element={<Activities currentLanguage={currentLanguage} />} />
                <Route path="/progress" element={<Progress currentLanguage={currentLanguage} />} />
                <Route path="/community" element={<Community currentLanguage={currentLanguage} />} />
                <Route path="/voice" element={<VoiceInterface currentLanguage={currentLanguage} />} />
                <Route path="/emergency" element={<Emergency currentLanguage={currentLanguage} />} />
                <Route path="/billing" element={<Billing currentLanguage={currentLanguage} />} />
                <Route path="/profile" element={<Profile currentLanguage={currentLanguage} />} />
              </Routes>
            </RouteTransition>
          </main>
        </div>
      </div>
    </div>
  );
}