import React from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, BookOpen, Volume2 } from 'lucide-react'; // Cambié Play por Volume2 y Mic por BookOpen
import { motion } from 'framer-motion';
import { useTheme } from '../../context/ThemeContext';
import CommTechLogo from '../ui/CommTechLogo'; // Asumiendo un nuevo logo para ComunicaTech

export default function Hero()  {
  const { theme } = useTheme();

  return (
    <section className={`pt-16 sm:pt-20 pb-8 sm:pb-16 transition-all duration-500 relative overflow-hidden ${
      theme === 'light'
        ? 'bg-gradient-to-br from-blue-100 via-blue-200 to-purple-200' 
        : 'bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900' 
    }`}>
      {/* El fondo animado se mantiene similar pero con colores que evocan tranquilidad y concentración */}
      <div className="absolute inset-0 z-0">
        <motion.div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-20"
          style={{
            // Cambiado a una imagen que evoque aprendizaje, comunicación o comunidad
            backgroundImage: `url('https://images.pexels.com/photos/3769138/pexels-photo-3769138.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
          }}
          animate={{ 
            scale: [1, 1.05, 1],
            opacity: [0.20, 0.25, 0.20]
          }}
          transition={{ duration: 8, repeat: Infinity }}
        />
        
        {/* Partículas y Overlay para legibilidad se mantienen igual */}
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
                  ? 'bg-white/90 text-indigo-800 backdrop-blur-sm shadow-sm hover:shadow-md'
                  : 'bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-800'
              }`}>
                🗣️ Comunicación alternativa y aprendizaje inclusivo
              </span>
            </div>
            <h1 className={`text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold animate-fade-in-up leading-tight ${
              theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
            }`} style={{ animationDelay: '0.4s' }}>
              El puente digital para
              <span className="text-indigo-600 dark:text-indigo-400 animate-pulse block sm:inline"> romper el silencio</span>
              <span className="block">y conectar con el mundo</span>
            </h1>
            <p className={`mt-3 sm:mt-6 mx-auto text-sm sm:text-base lg:text-lg xl:text-xl md:mt-8 md:max-w-3xl lg:mx-0 animate-fade-in-up leading-relaxed ${
              theme === 'light' ? 'text-slate-700' : 'text-gray-600 dark:text-gray-300'
            }`} style={{ animationDelay: '0.6s' }}>
              ComunicaTech es una plataforma web inclusiva para TEA, afasia y disartria. Usa tarjetas visuales interactivas con audio multilingüe y lecciones personalizadas para fomentar la autonomía y el aprendizaje.
              <span className="text-indigo-700 dark:text-indigo-300 font-medium block">
                 Diseñada para usuarios, terapeutas y cuidadores.
              </span>
            </p>
            
            <div className="mt-4 sm:mt-8 mx-auto sm:max-w-lg sm:text-center lg:text-left lg:mx-0 animate-fade-in-up" style={{ animationDelay: '0.8s' }}>
              <div className="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:gap-4 justify-center lg:justify-start">
                <motion.div
                  whileHover={{ scale: 1.05, y: -5 }}
                  whileTap={{ scale: 0.95 }}
                >
                  <Link
                    to="/register"
                    className="inline-flex items-center justify-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border border-transparent text-sm sm:text-base font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl group"
                  >
                    <BookOpen className="mr-2 h-4 w-4 sm:h-5 sm:w-5 group-hover:animate-bounce" />
                    <span className="text-xs sm:text-sm lg:text-base">Explorar lecciones y tarjetas</span>
                    <ArrowRight className="ml-2 h-3 w-3 sm:h-4 sm:w-4 group-hover:translate-x-1 transition-transform" />
                  </Link>
                </motion.div>
                <motion.button 
                  className={`inline-flex items-center justify-center px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border-2 text-sm sm:text-base font-medium rounded-xl transition-all duration-300 group ${
                    theme === 'light'
                      ? 'border-indigo-600 text-indigo-700 bg-white/80 hover:bg-white/90 backdrop-blur-sm shadow-sm hover:shadow-md'
                      : 'border-indigo-600 dark:border-indigo-400 text-indigo-700 dark:text-indigo-300 bg-transparent hover:bg-indigo-50 dark:hover:bg-indigo-900/20'
                  }`}
                  whileHover={{ scale: 1.05, y: -5 }}
                  whileTap={{ scale: 0.95 }}
                >
                  <Volume2 className="mr-2 h-3 w-3 sm:h-4 sm:w-4 group-hover:animate-pulse" />
                  <span className="text-xs sm:text-sm lg:text-base">Ver demo de tarjetas</span>
                </motion.button>
              </div>
            </div>
            
            <div className="mt-4 sm:mt-8 flex flex-col space-y-2 sm:space-y-0 sm:flex-row items-center justify-center lg:justify-start sm:space-x-4 lg:space-x-8 animate-fade-in-up text-xs sm:text-sm" style={{ animationDelay: '1s' }}>
              <div className="flex items-center group hover:scale-105 transition-transform duration-300">
                <div className="w-4 h-4 sm:w-5 sm:h-5 mr-2 animate-spin" style={{ animationDuration: '3s' }}>
                  <CommTechLogo variant="icon" size="sm" /> 
                </div>
                <span className={`${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                }`}>✅ Accesibilidad y estándares web</span>
              </div>
              <div className="flex items-center group hover:scale-105 transition-transform duration-300">
                <span className={`${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                }`}>🗣️ Audio y frases en múltiples idiomas</span>
              </div>
              <div className="flex items-center group hover:scale-105 transition-transform duration-300">
                <span className={`${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                }`}>📊 Seguimiento de progreso y lecciones</span>
              </div>
            </div>

            {/* Disclaimer actualizado para el contexto de comunicación */}
            <div className={`mt-4 sm:mt-8 p-3 sm:p-4 rounded-lg border transition-all duration-300 hover:shadow-md animate-fade-in-up mx-auto lg:mx-0 max-w-md ${
              theme === 'light'
                ? 'bg-white/80 border-blue-200 backdrop-blur-sm shadow-sm'
                : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800'
            }`} style={{ animationDelay: '1.2s' }}>
              <p className={`text-xs sm:text-sm leading-relaxed ${
                theme === 'light' ? 'text-slate-700' : 'text-blue-800 dark:text-blue-200'
              }`}>
                <strong>Nota:</strong> Esta es una herramienta de apoyo a la comunicación y el aprendizaje, no reemplaza la **terapia del lenguaje** o la intervención profesional.
              </p>
            </div>
          </div>
          
          {/* Sección de Mockup: Tarjeta de Comunicación Interactiva */}
          <div className="mt-8 sm:mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center animate-fade-in-left" style={{ animationDelay: '0.6s' }}>
            <div className="relative mx-auto w-full rounded-2xl shadow-2xl lg:max-w-md transform hover:scale-105 transition-all duration-500">
              <div className={`rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl border transition-all duration-300 hover:shadow-2xl ${
                theme === 'light'
                  ? 'bg-white/90 border-white/60 backdrop-blur-sm'
                  : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700'
              }`}>
                <div className="text-center">
                  <motion.div 
                    className={`mx-auto h-24 w-24 sm:h-32 sm:w-32 lg:h-40 lg:w-40 rounded-xl flex items-center justify-center mb-4 sm:mb-6 ${
                      theme === 'light'
                        ? 'bg-gradient-to-br from-indigo-100 to-blue-100'
                        : 'bg-gradient-to-br from-indigo-900/80 to-blue-900/80'
                    }`}
                    animate={{ 
                      scale: [1, 1.05, 1],
                      boxShadow: [
                        "0 0 0 rgba(99, 102, 241, 0)",
                        "0 0 30px rgba(99, 102, 241, 0.5)",
                        "0 0 0 rgba(99, 102, 241, 0)"
                      ]
                    }}
                    transition={{ 
                      duration: 3, 
                      repeat: Infinity,
                      repeatType: "loop"
                    }}
                  >
                    <span className="text-5xl sm:text-6xl" role="img" aria-label="Manzana">🍎</span>
                  </motion.div>
                  <h3 className={`text-xl sm:text-2xl font-bold mb-2 sm:mb-3 ${
                    theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
                  }`}>
                    "Quiero una manzana"
                  </h3>
                  <p className={`text-sm sm:text-base mb-4 sm:mb-6 ${
                    theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
                  }`}>
                    Frase clave y traducción (Español/Inglés)
                  </p>
                  
                  {/* Botones de Interacción (Visual/Auditivo/Táctil) */}
                  <div className="flex justify-center gap-4 mb-4 sm:mb-6">
                    <motion.button
                      className="p-3 rounded-full bg-indigo-500 text-white shadow-lg hover:bg-indigo-600 transition-colors"
                      whileHover={{ scale: 1.1 }}
                      whileTap={{ scale: 0.9 }}
                    >
                      <Volume2 className="h-5 w-5" />
                    </motion.button>
                    <motion.button
                      className="p-3 rounded-full bg-purple-500 text-white shadow-lg hover:bg-purple-600 transition-colors"
                      whileHover={{ scale: 1.1 }}
                      whileTap={{ scale: 0.9 }}
                    >
                      <BookOpen className="h-5 w-5" />
                    </motion.button>
                    <motion.button
                      className="p-3 rounded-full bg-emerald-500 text-white shadow-lg hover:bg-emerald-600 transition-colors"
                      whileHover={{ scale: 1.1 }}
                      whileTap={{ scale: 0.9 }}
                    >
                      <span role="img" aria-label="Mano">🖐️</span>
                    </motion.button>
                  </div>

                  {/* Simulación de Registro de Uso / RFID */}
                  <div className={`rounded-xl p-3 sm:p-4 mb-3 sm:mb-4 ${
                    theme === 'light'
                      ? 'bg-gradient-to-r from-indigo-50 to-blue-50'
                      : 'bg-gradient-to-r from-indigo-900/20 to-blue-900/20'
                  }`}>
                    <div className="flex items-center justify-center space-x-2">
                      <motion.div 
                        className="w-2 h-2 bg-indigo-500 rounded-full"
                        animate={{ 
                          scale: [1, 1.5, 1],
                          opacity: [0.7, 1, 0.7]
                        }}
                        transition={{ duration: 1.5, repeat: Infinity }}
                      />
                      <p className={`text-xs font-medium ${
                        theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                      }`}>
                        Tarjeta escaneada/tocada: Audio reproducido
                      </p>
                    </div>
                  </div>
                  
                  <div className={`flex items-center justify-between text-xs ${
                    theme === 'light' ? 'text-slate-500' : 'text-gray-500 dark:text-gray-400'
                  }`}>
                    <motion.span 
                      animate={{ 
                        scale: [1, 1.05, 1],
                        color: [
                          theme === 'light' ? '#64748b' : '#9ca3af',
                          theme === 'light' ? '#6366f1' : '#a5b4fc',
                          theme === 'light' ? '#64748b' : '#9ca3af'
                        ]
                      }}
                      transition={{ duration: 2, repeat: Infinity }}
                    >
                      🏷️ Código: UUID-45678
                    </motion.span>
                    <motion.span 
                      animate={{ 
                        y: [0, -3, 0]
                      }}
                      transition={{ duration: 2, repeat: Infinity, delay: 1 }}
                    >
                      📈 Lección N° 5 completada
                    </motion.span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Custom CSS for animations (se mantiene el CSS inyectado al final) */}
      <style>{`
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
};