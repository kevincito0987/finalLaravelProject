import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Menu, X, Sun, Moon, Globe } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useTheme } from '../../context/ThemeContext';
import HabitaLogo from '../ui/HabitaLogo';

interface NavigationProps {
  currentLanguage: string;
  setCurrentLanguage: (lang: string) => void;
}

export default function Navigation({ currentLanguage, setCurrentLanguage }: NavigationProps) {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const { theme, toggleTheme } = useTheme();

  // Detectar scroll para cambiar la apariencia del navbar
  useEffect(() => {
    const handleScroll = () => {
      const isScrolled = window.scrollY > 20;
      if (isScrolled !== scrolled) {
        setScrolled(isScrolled);
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, [scrolled]);

  const scrollToSection = (sectionId: string) => {
    const element = document.getElementById(sectionId);
    element?.scrollIntoView({ behavior: 'smooth' });
    setIsMenuOpen(false);
  };

  const toggleLanguage = () => {
    setCurrentLanguage(prev => {
      const nextLang = prev === 'es' ? 'en' : prev === 'en' ? 'fr' : prev === 'fr' ? 'pt' : 'es';
      return nextLang;
    });
  };

  const translations = {
    es: {
      features: 'Características',
      howItWorks: 'Cómo Funciona',
      team: 'Equipo',
      login: 'Iniciar Sesión',
      startFree: 'Comenzar Gratis'
    },
    en: {
      features: 'Features',
      howItWorks: 'How It Works',
      team: 'Team',
      login: 'Login',
      startFree: 'Start Free'
    },
    fr: {
      features: 'Fonctionnalités',
      howItWorks: 'Comment ça marche',
      team: 'Équipe',
      login: 'Connexion',
      startFree: 'Commencer'
    },
    pt: {
      features: 'Recursos',
      howItWorks: 'Como Funciona',
      team: 'Equipe',
      login: 'Entrar',
      startFree: 'Começar Grátis'
    }
  };

  const t = translations[currentLanguage as keyof typeof translations];

  return (
    <nav className={`fixed top-0 w-full backdrop-blur-xl border-b z-50 transition-all duration-500 ${
      scrolled 
        ? theme === 'light'
          ? 'bg-white/90 border-white/40 shadow-md'
          : 'bg-gray-900/95 border-gray-700/60 shadow-md'
        : theme === 'light'
        ? 'bg-white/80 border-white/30'
        : 'bg-gray-900/90 border-gray-700/50'
    }`}>
      <div className="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <div className="flex justify-between items-center h-14 sm:h-16">
          {/* Logo */}
          <div className="flex items-center animate-fade-in-right">
            <motion.div 
              className="flex-shrink-0 flex items-center"
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              <HabitaLogo size="md" animated />
            </motion.div>
          </div>

          {/* Desktop Navigation */}
          <div className="hidden md:block animate-fade-in-left">
            <div className="ml-10 flex items-center space-x-4 lg:space-x-6">
              <motion.button
                onClick={() => scrollToSection('features')}
                className={`px-2 lg:px-3 py-2 text-xs lg:text-sm font-medium transition-all duration-200 hover:scale-105 hover:-translate-y-1 ${
                  theme === 'light'
                    ? 'text-slate-700 hover:text-emerald-600'
                    : 'text-gray-300 hover:text-emerald-400'
                }`}
                whileHover={{ scale: 1.05, y: -2 }}
                whileTap={{ scale: 0.95 }}
              >
                {t.features}
              </motion.button>
              <motion.button
                onClick={() => scrollToSection('how-it-works')}
                className={`px-2 lg:px-3 py-2 text-xs lg:text-sm font-medium transition-all duration-200 hover:scale-105 hover:-translate-y-1 ${
                  theme === 'light'
                    ? 'text-slate-700 hover:text-emerald-600'
                    : 'text-gray-300 hover:text-emerald-400'
                }`}
                whileHover={{ scale: 1.05, y: -2 }}
                whileTap={{ scale: 0.95 }}
              >
                {t.howItWorks}
              </motion.button>
              <motion.button
                onClick={() => scrollToSection('team')}
                className={`px-2 lg:px-3 py-2 text-xs lg:text-sm font-medium transition-all duration-200 hover:scale-105 hover:-translate-y-1 ${
                  theme === 'light'
                    ? 'text-slate-700 hover:text-emerald-600'
                    : 'text-gray-300 hover:text-emerald-400'
                }`}
                whileHover={{ scale: 1.05, y: -2 }}
                whileTap={{ scale: 0.95 }}
              >
                {t.team}
              </motion.button>
              <motion.div
                whileHover={{ scale: 1.05, y: -2 }}
                whileTap={{ scale: 0.95 }}
              >
                <Link
                  to="/login"
                  className={`px-2 lg:px-3 py-2 text-xs lg:text-sm font-medium transition-all duration-200 ${
                    theme === 'light'
                      ? 'text-slate-700 hover:text-emerald-600'
                      : 'text-gray-300 hover:text-emerald-400'
                  }`}
                >
                  {t.login}
                </Link>
              </motion.div>
              <motion.div
                whileHover={{ scale: 1.05, y: -2 }}
                whileTap={{ scale: 0.95 }}
              >
                <Link
                  to="/register"
                  className="bg-gradient-to-r from-emerald-600 to-blue-600 text-white hover:from-emerald-700 hover:to-blue-700 px-3 lg:px-6 py-1.5 lg:py-2 rounded-xl text-xs lg:text-sm font-medium transition-all duration-200 shadow-lg hover:shadow-xl"
                >
                  {t.startFree}
                </Link>
              </motion.div>
              <motion.button
                onClick={toggleTheme}
                className={`p-1.5 lg:p-2 rounded-lg transition-all duration-200 ${
                  theme === 'light'
                    ? 'text-slate-700 hover:text-emerald-600 hover:bg-white/60'
                    : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800/60'
                }`}
                whileHover={{ scale: 1.1, rotate: 12 }}
                whileTap={{ scale: 0.9, rotate: 0 }}
              >
                {theme === 'light' ? 
                  <Moon size={16} className="animate-pulse" /> : 
                  <Sun size={16} className="animate-spin" style={{ animationDuration: '3s' }} />
                }
              </motion.button>
              <motion.button
                onClick={toggleLanguage}
                className={`p-1.5 lg:p-2 rounded-lg transition-all duration-200 ${
                  theme === 'light'
                    ? 'text-slate-700 hover:text-emerald-600 hover:bg-white/60'
                    : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800/60'
                }`}
                whileHover={{ scale: 1.1, rotate: -12 }}
                whileTap={{ scale: 0.9, rotate: 0 }}
              >
                <Globe size={16} />
              </motion.button>
            </div>
          </div>

          {/* Mobile menu button */}
          <div className="md:hidden flex items-center space-x-2 animate-fade-in-left">
            <motion.button
              onClick={toggleLanguage}
              className={`p-1.5 rounded-lg transition-all duration-200 ${
                theme === 'light'
                  ? 'text-slate-700 hover:text-emerald-600'
                  : 'text-gray-300 hover:text-emerald-400'
              }`}
              whileHover={{ scale: 1.1, rotate: -12 }}
              whileTap={{ scale: 0.9, rotate: 0 }}
            >
              <Globe size={16} />
            </motion.button>
            <motion.button
              onClick={toggleTheme}
              className={`p-1.5 rounded-lg transition-all duration-200 ${
                theme === 'light'
                  ? 'text-slate-700 hover:text-emerald-600'
                  : 'text-gray-300 hover:text-emerald-400'
              }`}
              whileHover={{ scale: 1.1, rotate: 12 }}
              whileTap={{ scale: 0.9, rotate: 0 }}
            >
              {theme === 'light' ? 
                <Moon size={16} className="animate-pulse" /> : 
                <Sun size={16} className="animate-spin" style={{ animationDuration: '3s' }} />
              }
            </motion.button>
            <motion.button
              onClick={() => setIsMenuOpen(!isMenuOpen)}
              className={`p-1.5 rounded-lg transition-all duration-200 ${
                theme === 'light'
                  ? 'text-slate-700 hover:text-emerald-600'
                  : 'text-gray-300 hover:text-emerald-400'
              }`}
              whileHover={{ scale: 1.1, rotate: isMenuOpen ? -90 : 0 }}
              whileTap={{ scale: 0.9 }}
              animate={{ rotate: isMenuOpen ? 90 : 0 }}
              transition={{ duration: 0.3 }}
            >
              {isMenuOpen ? <X size={20} /> : <Menu size={20} />}
            </motion.button>
          </div>
        </div>
      </div>

      {/* Mobile Navigation Menu */}
      <AnimatePresence>
        {isMenuOpen && (
          <motion.div 
            className="md:hidden"
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: 'auto' }}
            exit={{ opacity: 0, height: 0 }}
            transition={{ duration: 0.3, ease: "easeInOut" }}
          >
            <div className={`px-2 pt-2 pb-3 space-y-1 sm:px-3 border-b shadow-lg backdrop-blur-xl ${
              theme === 'light'
                ? 'bg-white/90 border-white/40'
                : 'bg-gray-900/95 border-gray-700/50'
            }`}>
              {[
                { label: t.features, action: () => scrollToSection('features') },
                { label: t.howItWorks, action: () => scrollToSection('how-it-works') },
                { label: t.team, action: () => scrollToSection('team') }
              ].map((item, index) => (
                <motion.button
                  key={item.label}
                  onClick={item.action}
                  className={`block px-3 py-2 text-xs font-medium rounded-lg transition-all w-full text-left hover:scale-105 hover:translate-x-2 animate-fade-in-up ${
                    theme === 'light'
                      ? 'text-slate-700 hover:text-emerald-600 hover:bg-white/60'
                      : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800/60'
                  }`}
                  style={{ animationDelay: `${index * 0.1}s` }}
                  whileHover={{ x: 10, scale: 1.02 }}
                  whileTap={{ scale: 0.98 }}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: index * 0.1 }}
                >
                  {item.label}
                </motion.button>
              ))}
              <motion.div
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.3 }}
                whileHover={{ x: 10, scale: 1.02 }}
                whileTap={{ scale: 0.98 }}
              >
                <Link
                  to="/login"
                  className={`block px-3 py-2 text-xs font-medium rounded-lg transition-all hover:scale-105 hover:translate-x-2 w-full text-left ${
                    theme === 'light'
                      ? 'text-slate-700 hover:text-emerald-600 hover:bg-white/60'
                      : 'text-gray-300 hover:text-emerald-400 hover:bg-gray-800/60'
                  }`}
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t.login}
                </Link>
              </motion.div>
              <motion.div
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.4 }}
                whileHover={{ x: 10, scale: 1.02 }}
                whileTap={{ scale: 0.98 }}
              >
                <Link
                  to="/register"
                  className="block px-3 py-2 text-xs font-medium bg-gradient-to-r from-emerald-600 to-blue-600 text-white hover:from-emerald-700 hover:to-blue-700 rounded-lg transition-all duration-200 text-center transform hover:scale-105 w-full"
                  onClick={() => setIsMenuOpen(false)}
                >
                  {t.startFree}
                </Link>
              </motion.div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Custom CSS for animations */}
      <style jsx>{`
        @keyframes slide-down {
          from {
            opacity: 0;
            transform: translateY(-20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        
        @keyframes fade-in-right {
          from {
            opacity: 0;
            transform: translateX(-20px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }
        
        @keyframes fade-in-left {
          from {
            opacity: 0;
            transform: translateX(20px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }
        
        @keyframes fade-in-up {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        
        .animate-slide-down {
          animation: slide-down 0.3s ease-out;
        }
        
        .animate-fade-in-right {
          animation: fade-in-right 0.8s ease-out;
        }
        
        .animate-fade-in-left {
          animation: fade-in-left 0.8s ease-out;
        }
        
        .animate-fade-in-up {
          animation: fade-in-up 0.6s ease-out forwards;
          opacity: 0;
        }
      `}</style>
    </nav>
  );
}