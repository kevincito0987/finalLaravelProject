import React, { useRef, useState, useEffect } from 'react';
import { Menu, Bell, Sun, Moon, LogOut } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useAuth } from '../../context/AuthContext';
import { useTheme } from '../../context/ThemeContext';
import NotificationsDropdown from '../ui/NotificationsDropdown';

interface HeaderProps {
  setSidebarOpen: (open: boolean) => void;
  // Se eliminó currentLanguage
}

export default function Header({ setSidebarOpen }: HeaderProps) {
  const { user, logout } = useAuth();
  const { theme, toggleTheme } = useTheme();
  const [notificationsOpen, setNotificationsOpen] = useState(false);
  const notificationButtonRef = useRef<HTMLButtonElement>(null);
  const [isSmartwatch, setIsSmartwatch] = useState(false);

  // Detect if we're on a smartwatch-sized screen
  useEffect(() => {
    const checkSmartwatch = () => {
      setIsSmartwatch(window.innerWidth < 280);
    };
    
    checkSmartwatch();
    window.addEventListener('resize', checkSmartwatch);
    return () => window.removeEventListener('resize', checkSmartwatch);
  }, []);

  // Notificaciones no leídas se inicializa en 0 (cero)
  const unreadNotifications = 0; 

  // Se eliminó el objeto translations y la variable t

  return (
    <header className={`backdrop-blur-xl border-b transition-all duration-500 animate-slide-down ${
      theme === 'light'
        ? 'bg-white/70 border-white/30'
        : 'bg-gray-800/80 border-gray-700/50'
    }`}>
      <div className="px-2 sm:px-3 md:px-6 py-2 sm:py-3 md:py-4">
        <div className="flex items-center justify-between">
          <div className="flex items-center">
            <motion.button
              onClick={() => setSidebarOpen(true)}
              className={`lg:hidden p-1 sm:p-2 md:p-2.5 rounded-lg sm:rounded-xl transition-all duration-200 ${
                theme === 'light'
                  ? 'text-slate-600 hover:text-slate-800 hover:bg-white/60'
                  : 'text-gray-400 hover:text-gray-300 hover:bg-gray-700/80'
              }`}
              whileHover={{ scale: 1.1, rotate: 3 }}
              whileTap={{ scale: 0.9 }}
            >
              <Menu className="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" />
            </motion.button>
            <div className="ml-2 sm:ml-3 lg:ml-0 animate-fade-in-right">
              <h1 className={`text-xs sm:text-sm md:text-base lg:text-lg xl:text-xl font-semibold transition-colors duration-300 ${
                theme === 'light' ? 'text-slate-800' : 'text-white'
              }`}>
                {/* Texto hardcodeado */}
                Bienvenido de vuelta, {user?.profile?.name || user?.username}
              </h1>
              <p className={`text-xxs sm:text-xs md:text-sm mt-0.5 ${
                theme === 'light' ? 'text-slate-600' : 'text-gray-400'
              }`}>
                {/* Texto hardcodeado */}
                ¿Cómo te sientes hoy?
              </p>
            </div>
          </div>

          <div className="flex items-center space-x-1 sm:space-x-2 md:space-x-3 animate-fade-in-left">
            {/* Notifications Button */}
            {!isSmartwatch && (
              <div className="relative">
                <motion.button 
                  ref={notificationButtonRef}
                  onClick={() => setNotificationsOpen(!notificationsOpen)}
                  className={`p-1 sm:p-2 md:p-2.5 rounded-lg sm:rounded-xl relative transition-all duration-200 ${
                    theme === 'light'
                      ? 'text-slate-600 hover:text-slate-800 hover:bg-white/60'
                      : 'text-gray-400 hover:text-gray-300 hover:bg-gray-700/80'
                  }`}
                  whileHover={{ scale: 1.1, rotate: 3 }}
                  whileTap={{ scale: 0.9 }}
                >
                  <Bell className="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" />
                  {unreadNotifications > 0 && (
                    <motion.span 
                      className="absolute -top-1 -right-1 w-2 h-2 sm:w-4 sm:h-4 md:w-5 md:h-5 bg-gradient-to-r from-red-400 to-red-500 text-white text-xxs sm:text-xs rounded-full flex items-center justify-center font-semibold shadow-lg"
                      initial={{ scale: 0 }}
                      animate={{ scale: 1 }}
                      transition={{ 
                        type: "spring",
                        stiffness: 500,
                        damping: 15
                      }}
                    >
                      {unreadNotifications > 9 ? '9+' : unreadNotifications}
                    </motion.span>
                  )}
                </motion.button>
                
                <AnimatePresence>
                  {notificationsOpen && (
                    <NotificationsDropdown 
                      isOpen={notificationsOpen}
                      onClose={() => setNotificationsOpen(false)}
                      triggerRef={notificationButtonRef}
                    />
                  )}
                </AnimatePresence>
              </div>
            )}
            
            <motion.button
              onClick={toggleTheme}
              className={`p-1 sm:p-2 md:p-2.5 rounded-lg sm:rounded-xl transition-all duration-200 ${
                theme === 'light'
                  ? 'text-slate-600 hover:text-slate-800 hover:bg-white/60'
                  : 'text-gray-400 hover:text-gray-300 hover:bg-gray-700/80'
              }`}
              whileHover={{ scale: 1.1, rotate: 12 }}
              whileTap={{ scale: 0.9 }}
            >
              {theme === 'light' ? 
                <Moon className="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 animate-pulse" /> : 
                <Sun className="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 animate-spin" style={{ animationDuration: '3s' }} />
              }
            </motion.button>

            <div className="flex items-center space-x-1 sm:space-x-2 md:space-x-3">
              <div className="relative group">
                <motion.img
                  src={user?.avatar}
                  alt={user?.profile?.name || user?.username}
                  className={`w-5 h-5 sm:w-7 sm:h-7 md:w-9 md:h-9 rounded-lg sm:rounded-xl md:rounded-2xl shadow-sm transition-all duration-300 ${
                    theme === 'light'
                      ? 'ring-2 ring-white/50'
                      : 'ring-2 ring-emerald-500/30'
                  }`}
                  whileHover={{ scale: 1.1, rotate: 3 }}
                />
                <motion.div 
                  className={`absolute -bottom-0.5 -right-0.5 w-1.5 h-1.5 sm:w-2 sm:h-2 md:w-3 md:h-3 bg-green-400 rounded-full ring-1 sm:ring-2 ${
                    theme === 'light' ? 'ring-white' : 'ring-gray-800'
                  }`}
                  animate={{ 
                    scale: [1, 1.2, 1],
                    opacity: [0.7, 1, 0.7]
                  }}
                  transition={{ 
                    duration: 2, 
                    repeat: Infinity,
                    repeatType: "reverse"
                  }}
                />
                
                {/* Tooltip - Hide on smartwatch */}
                {!isSmartwatch && (
                  <div className="absolute bottom-full right-0 mb-2 px-2 py-1 bg-gray-800 text-white text-xxs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                    {/* Texto hardcodeado */}
                    En línea
                  </div>
                )}
              </div>
              <motion.button
                onClick={logout}
                className={`p-1 sm:p-2 md:p-2.5 rounded-lg sm:rounded-xl transition-all duration-200 group ${
                  theme === 'light'
                    ? 'text-slate-600 hover:text-slate-800 hover:bg-white/60'
                    : 'text-gray-400 hover:text-gray-300 hover:bg-gray-700/80'
                }`}
                title="Cerrar sesión" // Texto hardcodeado
                whileHover={{ scale: 1.1, rotate: -3 }}
                whileTap={{ scale: 0.9 }}
              >
                <LogOut className="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 group-hover:animate-pulse" />
              </motion.button>
            </div>
          </div>
        </div>
      </div>

      {/* Custom CSS for animations (se mantiene sin cambios) */}
      <style>{`
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
        
        .animate-slide-down {
          animation: slide-down 0.6s ease-out;
        }
        
        .animate-fade-in-right {
          animation: fade-in-right 0.8s ease-out;
        }
        
        .animate-fade-in-left {
          animation: fade-in-left 0.8s ease-out;
        }
      `}</style>
    </header>
  );
}