import React, { useState } from 'react';
import Navigation from './landing/Navigation';
import Hero from './landing/Hero';
import Features from './landing/Features';
import HowItWorks from './landing/HowItWorks';
import Team from './landing/Team';
import Benefits from './landing/Benefits';
import CallToAction from './landing/CallToAction';
import Footer from './landing/Footer';
import { Globe } from 'lucide-react';
import { motion } from 'framer-motion';
import { useTheme } from '../context/ThemeContext';

export default function LandingPage() {
  const [currentLanguage, setCurrentLanguage] = useState('es');
  const { theme } = useTheme();

  const toggleLanguage = () => {
    setCurrentLanguage(prev => {
      const nextLang = prev === 'es' ? 'en' : prev === 'en' ? 'fr' : prev === 'fr' ? 'pt' : 'es';
      return nextLang;
    });
  };

  return (
    <div className="min-h-screen relative">
      {/* Animated background elements for light mode */}
      {theme === 'light' && (
        <div className="fixed inset-0 z-0 pointer-events-none overflow-hidden">
          {/* Floating gradient blobs */}
          <motion.div
            className="absolute w-96 h-96 rounded-full bg-blue-200/30 blur-3xl"
            animate={{
              x: [0, 100, 0],
              y: [0, 50, 0],
              scale: [1, 1.2, 1]
            }}
            transition={{ duration: 20, repeat: Infinity, ease: "easeInOut" }}
            style={{ top: '10%', left: '5%' }}
          />
          <motion.div
            className="absolute w-96 h-96 rounded-full bg-purple-200/30 blur-3xl"
            animate={{
              x: [0, -70, 0],
              y: [0, 100, 0],
              scale: [1, 1.3, 1]
            }}
            transition={{ duration: 25, repeat: Infinity, ease: "easeInOut" }}
            style={{ top: '40%', right: '10%' }}
          />
          <motion.div
            className="absolute w-80 h-80 rounded-full bg-emerald-200/20 blur-3xl"
            animate={{
              x: [0, 50, 0],
              y: [0, -70, 0],
              scale: [1, 1.1, 1]
            }}
            transition={{ duration: 18, repeat: Infinity, ease: "easeInOut" }}
            style={{ bottom: '15%', left: '20%' }}
          />
        </div>
      )}
      
      {/* Animated background elements for dark mode */}
      {theme === 'dark' && (
        <div className="fixed inset-0 z-0 pointer-events-none overflow-hidden">
          {/* Floating stars/particles */}
          {[...Array(100)].map((_, i) => (
            <motion.div
              key={i}
              className="absolute w-1 h-1 bg-white rounded-full"
              animate={{
                opacity: [0, Math.random() * 0.7 + 0.3, 0],
                scale: [0, Math.random() * 0.5 + 0.5, 0]
              }}
              transition={{
                duration: Math.random() * 3 + 2,
                repeat: Infinity,
                delay: Math.random() * 5
              }}
              style={{
                left: `${Math.random() * 100}%`,
                top: `${Math.random() * 100}%`
              }}
            />
          ))}
          
          {/* Subtle gradient blobs */}
          <motion.div
            className="absolute w-96 h-96 rounded-full bg-blue-900/10 blur-3xl"
            animate={{
              x: [0, 100, 0],
              y: [0, 50, 0],
              scale: [1, 1.2, 1]
            }}
            transition={{ duration: 20, repeat: Infinity, ease: "easeInOut" }}
            style={{ top: '10%', left: '5%' }}
          />
          <motion.div
            className="absolute w-96 h-96 rounded-full bg-purple-900/10 blur-3xl"
            animate={{
              x: [0, -70, 0],
              y: [0, 100, 0],
              scale: [1, 1.3, 1]
            }}
            transition={{ duration: 25, repeat: Infinity, ease: "easeInOut" }}
            style={{ top: '40%', right: '10%' }}
          />
        </div>
      )}
      
      <Navigation/>
      <Hero />
      <Features />
      <HowItWorks />
      <Benefits />
      <Team/>
      <CallToAction />
      <Footer />
      
      {/* Demo Mode Banner */}
      <div className="fixed bottom-4 right-4 z-50">
        <div className="bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg">
          <div className="flex items-center space-x-2">
            <span className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            <span className="text-sm font-medium">
              {currentLanguage === 'es' ? 'Modo Demo Activo' : 
               currentLanguage === 'fr' ? 'Mode Démo Actif' :
               currentLanguage === 'pt' ? 'Modo Demo Ativo' :
               'Demo Mode Active'}
            </span>
          </div>
        </div>
      </div>

      {/* Language Selector - Fixed position */}
      <div className="fixed top-20 right-4 z-50">
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
    </div>
  );
}