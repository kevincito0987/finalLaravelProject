import React from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, Play, Mic } from 'lucide-react';
import { motion } from 'framer-motion';
import { useTheme } from '../../context/ThemeContext';
import HabitaLogo from '../ui/HabitaLogo';

export default function Hero() {
  const { theme } = useTheme();

  return (
    <section className={`pt-16 sm:pt-20 pb-8 sm:pb-16 transition-all duration-500 relative overflow-hidden ${
      theme === 'light'
        ? 'bg-gradient-to-br from-blue-100 via-blue-200 to-purple-200' 
        : 'bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900' 
    }`}>
      {/* Background landscape image */}
      <div className="absolute inset-0 z-0">
        <motion.div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-20"
          style={{
            backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
          }}
          animate={{ 
            scale: [1, 1.05, 1],
            opacity: [0.20, 0.25, 0.20]
          }}
          transition={{ duration: 8, repeat: Infinity }}
        />
        
        {/* Enhanced floating particles */}
        <div className="absolute inset-0 overflow-hidden">
          {[...Array(50)].map((_, i) => (
            <motion.div
              key={i}
              className={`absolute rounded-full ${
                theme === 'light' 
                  ? 'bg-white/40' 
                  : 'bg-white/20'
              }`}
              style={{
                width: `${Math.random() * 4 + 1}px`,
                height: `${Math.random() * 4 + 1}px`
              }}
              initial={{
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                scale: Math.random() * 0.5 + 0.5
              }}
              animate={{
                y: [null, Math.random() * -200 - 50],
                x: [null, Math.random() * 150 - 75],
                opacity: [0, 0.8, 0],
                scale: [0, Math.random() * 2 + 0.5, 0],
                rotate: [0, 360]
              }}
              transition={{
                duration: Math.random() * 8 + 4,
                repeat: Infinity,
                delay: Math.random() * 5,
                ease: "easeOut"
              }}
            />
          ))}
        </div>
        
        {/* Enhanced gradient overlay for better text readability */}
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-gradient-to-br from-blue-100/90 via-blue-200/85 to-purple-200/90'
            : 'bg-gradient-to-br from-gray-900/95 via-gray-800/90 to-gray-900/95'
        }`} />
      </div>

      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
          <div className="text-center lg:text-left md:max-w-2xl md:mx-auto lg:col-span-6">
            <div className="mb-2 sm:mb-4 animate-fade-in-up" style={{ animationDelay: '0.2s' }}>
              <span className={`inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium transition-all duration-300 hover:scale-105 ${
                theme === 'light'
                  ? 'bg-white/90 text-emerald-800 backdrop-blur-sm shadow-sm hover:shadow-md'
                  : 'bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 hover:bg-emerald-200 dark:hover:bg-emerald-800'
              }`}>
                🌱 Tu compañero de bienestar diario
              </span>
            </div>
            <h1 className={`text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold animate-fade-in-up leading-tight ${
              theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
            }`} style={{ animationDelay: '0.4s' }}>
              Tu espacio digital de
              <span className="text-emerald-600 dark:text-emerald-400 animate-pulse block sm:inline"> bienestar emocional</span>
              <span className="block">y productividad personal</span>
            </h1>
            <p className={`mt-3 sm:mt-6 mx-auto text-sm sm:text-base lg:text-lg xl:text-xl md:mt-8 md:max-w-3xl lg:mx-0 animate-fade-in-up leading-relaxed ${
              theme === 'light' ? 'text-slate-700' : 'text-gray-600 dark:text-gray-300'
            }`} style={{ animationDelay: '0.6s' }}>
              Habita combina <strong>voz e inteligencia artificial</strong> para ayudarte a gestionar tus emociones, 
              cultivar hábitos de autocuidado y mantenerte motivado cada día. 
              <span className="text-emerald-700 dark:text-emerald-300 font-medium">No es terapia</span> — 
              es tu herramienta de bienestar personal.
            </p>
            
            <div className="mt-4 sm:mt-8 mx-auto sm:max-w-lg sm:text-center lg:text-left lg:mx-0 animate-fade-in-up" style={{ animationDelay: '0.8s' }}>
              <div className="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:gap-4 justify-center lg:justify-start">
                <motion.div
                  whileHover={{ scale: 1.05, y: -5 }}
                  whileTap={{ scale: 0.95 }}
                >
                  <Link
                    to="/register"
                    className="inline-flex items-center justify-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border border-transparent text-sm sm:text-base font-medium rounded-xl text-white bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl group"
                  >
                    <Mic className="mr-2 h-4 w-4 sm:h-5 sm:w-5 group-hover:animate-bounce" />
                    <span className="text-xs sm:text-sm lg:text-base">Comenzar mi bienestar</span>
                    <ArrowRight className="ml-2 h-3 w-3 sm:h-4 sm:w-4 group-hover:translate-x-1 transition-transform" />
                  </Link>
                </motion.div>
                <motion.button 
                  className={`inline-flex items-center justify-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border-2 text-sm sm:text-base font-medium rounded-xl transition-all duration-300 group ${
                    theme === 'light'
                      ? 'border-emerald-600 text-emerald-700 bg-white/80 hover:bg-white/90 backdrop-blur-sm shadow-sm hover:shadow-md'
                      : 'border-emerald-600 dark:border-emerald-400 text-emerald-700 dark:text-emerald-300 bg-transparent hover:bg-emerald-50 dark:hover:bg-emerald-900/20'
                  }`}
                  whileHover={{ scale: 1.05, y: -5 }}
                  whileTap={{ scale: 0.95 }}
                >
                  <Play className="mr-2 h-3 w-3 sm:h-4 sm:w-4 group-hover:animate-pulse" />
                  <span className="text-xs sm:text-sm lg:text-base">Ver cómo funciona</span>
                </motion.button>
              </div>
            </div>
            
            <div className="mt-4 sm:mt-8 flex flex-col space-y-2 sm:space-y-0 sm:flex-row items-center justify-center lg:justify-start sm:space-x-4 lg:space-x-8 animate-fade-in-up text-xs sm:text-sm" style={{ animationDelay: '1s' }}>
              <div className="flex items-center group hover:scale-105 transition-transform duration-300">
                <div className="w-4 h-4 sm:w-5 sm:h-5 mr-2 animate-spin" style={{ animationDuration: '3s' }}>
                  <HabitaLogo variant="icon" size="sm" />
                </div>
                <span className={`${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                }`}>Más de 10,000 usuarios activos</span>
              </div>
              <div className="flex items-center group hover:scale-105 transition-transform duration-300">
                <span className={`${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                }`}>⭐ 4.9/5 en bienestar diario</span>
              </div>
              <div className="flex items-center group hover:scale-105 transition-transform duration-300">
                <span className={`${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                }`}>🔒 100% privado y seguro</span>
              </div>
            </div>

            {/* Disclaimer */}
            <div className={`mt-4 sm:mt-8 p-3 sm:p-4 rounded-lg border transition-all duration-300 hover:shadow-md animate-fade-in-up mx-auto lg:mx-0 max-w-md ${
              theme === 'light'
                ? 'bg-white/80 border-blue-200 backdrop-blur-sm shadow-sm'
                : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800'
            }`} style={{ animationDelay: '1.2s' }}>
              <p className={`text-xs sm:text-sm leading-relaxed ${
                theme === 'light' ? 'text-slate-700' : 'text-blue-800 dark:text-blue-200'
              }`}>
                <strong>Importante:</strong> Habita no es una herramienta médica ni reemplaza la atención profesional en salud mental. 
                Si necesitas ayuda clínica, consulta con un psicólogo certificado.
              </p>
            </div>
          </div>
          
          {/* Mockup section */}
          <div className="mt-8 sm:mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center animate-fade-in-left" style={{ animationDelay: '0.6s' }}>
            <div className="relative mx-auto w-full rounded-2xl shadow-2xl lg:max-w-md transform hover:scale-105 transition-all duration-500">
              <div className={`rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl border transition-all duration-300 hover:shadow-2xl ${
                theme === 'light'
                  ? 'bg-white/90 border-white/60 backdrop-blur-sm'
                  : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700'
              }`}>
                <div className="text-center">
                  <motion.div 
                    className={`mx-auto h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 rounded-full flex items-center justify-center mb-4 sm:mb-6 ${
                      theme === 'light'
                        ? 'bg-gradient-to-br from-emerald-100 to-blue-100'
                        : 'bg-gradient-to-br from-emerald-100 to-blue-100 dark:from-emerald-900 dark:to-blue-900'
                    }`}
                    animate={{ 
                      y: [0, -10, 0],
                      scale: [1, 1.1, 1],
                      boxShadow: [
                        "0 0 0 rgba(16, 185, 129, 0)",
                        "0 0 20px rgba(16, 185, 129, 0.5)",
                        "0 0 0 rgba(16, 185, 129, 0)"
                      ]
                    }}
                    transition={{ 
                      duration: 2, 
                      repeat: Infinity,
                      repeatType: "loop"
                    }}
                  >
                    <Mic className="h-6 w-6 sm:h-7 sm:w-7 lg:h-8 lg:w-8 text-emerald-600 dark:text-emerald-400" />
                  </motion.div>
                  <h3 className={`text-lg sm:text-xl font-semibold mb-2 sm:mb-3 ${
                    theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
                  }`}>
                    Check-in Emocional Diario
                  </h3>
                  <p className={`text-xs sm:text-sm mb-4 sm:mb-6 ${
                    theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
                  }`}>
                    "Hola, soy tu compañero de bienestar. ¿Cómo te sientes hoy?"
                  </p>
                  
                  {/* Mood Selection */}
                  <div className="grid grid-cols-5 gap-1 sm:gap-2 mb-4 sm:mb-6">
                    {[
                      { emoji: '😢', label: 'Triste' },
                      { emoji: '😕', label: 'Bajo' },
                      { emoji: '😐', label: 'Neutral' },
                      { emoji: '😊', label: 'Bien' },
                      { emoji: '😄', label: 'Genial' }
                    ].map((mood, index) => (
                      <motion.button
                        key={index}
                        className={`p-2 sm:p-3 rounded-xl transition-all duration-300 ${
                          index === 3 
                            ? theme === 'light'
                              ? 'bg-emerald-100 scale-110 shadow-md'
                              : 'bg-emerald-100 dark:bg-emerald-900 scale-110 shadow-md'
                            : theme === 'light'
                            ? 'hover:bg-white/80'
                            : 'hover:bg-gray-100 dark:hover:bg-gray-700'
                        }`}
                        whileHover={{ scale: 1.1, rotate: index % 2 === 0 ? 5 : -5 }}
                        whileTap={{ scale: 0.9 }}
                        animate={index === 3 ? {
                          scale: [1.1, 1.15, 1.1],
                          boxShadow: [
                            "0 4px 6px -1px rgba(0, 0, 0, 0.1)",
                            "0 10px 15px -3px rgba(16, 185, 129, 0.3)",
                            "0 4px 6px -1px rgba(0, 0, 0, 0.1)"
                          ]
                        } : {}}
                        transition={{ duration: 2, repeat: Infinity, repeatType: "reverse" }}
                      >
                        <span className="text-xl sm:text-2xl">{mood.emoji}</span>
                      </motion.button>
                    ))}
                  </div>
                  
                  {/* Voice Interaction */}
                  <motion.div 
                    className={`rounded-xl p-3 sm:p-4 mb-3 sm:mb-4 ${
                      theme === 'light'
                        ? 'bg-gradient-to-r from-emerald-50 to-blue-50'
                        : 'bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-emerald-900/20 dark:to-blue-900/20'
                    }`}
                    animate={{
                      boxShadow: [
                        "0 0 0 rgba(16, 185, 129, 0)",
                        "0 0 15px rgba(16, 185, 129, 0.3)",
                        "0 0 0 rgba(16, 185, 129, 0)"
                      ]
                    }}
                    transition={{ duration: 3, repeat: Infinity }}
                  >
                    <div className="flex items-center justify-center space-x-1 sm:space-x-2 mb-2">
                      <motion.div 
                        className="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-emerald-500 rounded-full"
                        animate={{ 
                          height: [6, 12, 6],
                          opacity: [0.7, 1, 0.7]
                        }}
                        transition={{ duration: 1, repeat: Infinity }}
                      />
                      <motion.div 
                        className="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-blue-500 rounded-full"
                        animate={{ 
                          height: [6, 16, 6],
                          opacity: [0.7, 1, 0.7]
                        }}
                        transition={{ duration: 1, repeat: Infinity, delay: 0.2 }}
                      />
                      <motion.div 
                        className="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-purple-500 rounded-full"
                        animate={{ 
                          height: [6, 10, 6],
                          opacity: [0.7, 1, 0.7]
                        }}
                        transition={{ duration: 1, repeat: Infinity, delay: 0.4 }}
                      />
                    </div>
                    <p className={`text-xs ${
                      theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                    }`}>
                      Interacción por voz activada
                    </p>
                  </motion.div>
                  
                  <div className={`flex items-center justify-between text-xs ${
                    theme === 'light' ? 'text-slate-500' : 'text-gray-500 dark:text-gray-400'
                  }`}>
                    <motion.span 
                      animate={{ 
                        scale: [1, 1.1, 1],
                        color: [
                          theme === 'light' ? '#64748b' : '#9ca3af',
                          theme === 'light' ? '#ef4444' : '#f87171',
                          theme === 'light' ? '#64748b' : '#9ca3af'
                        ]
                      }}
                      transition={{ duration: 2, repeat: Infinity }}
                    >
                      🔥 Racha: 7 días
                    </motion.span>
                    <motion.span 
                      animate={{ 
                        y: [0, -3, 0],
                        color: [
                          theme === 'light' ? '#64748b' : '#9ca3af',
                          theme === 'light' ? '#10b981' : '#34d399',
                          theme === 'light' ? '#64748b' : '#9ca3af'
                        ]
                      }}
                      transition={{ duration: 2, repeat: Infinity, delay: 1 }}
                    >
                      🎯 Meta diaria: ✅
                    </motion.span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Custom CSS for animations */}
      <style jsx>{`
        @keyframes fade-in-up {
          from {
            opacity: 0;
            transform: translateY(30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        
        @keyframes fade-in-left {
          from {
            opacity: 0;
            transform: translateX(30px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }
        
        .animate-fade-in-up {
          animation: fade-in-up 0.8s ease-out forwards;
          opacity: 0;
        }
        
        .animate-fade-in-left {
          animation: fade-in-left 0.8s ease-out forwards;
          opacity: 0;
        }
      `}</style>
    </section>
  );
}