import React, { useState, useEffect } from 'react';
// Se mantienen solo los íconos necesarios para el layout de ejemplo (Activity, Clock, Target, Trophy, Loader)
import { Activity, Clock, Target, Trophy, Loader } from 'lucide-react'; 
import { motion } from 'framer-motion';
import { useTheme } from '../../context/ThemeContext';
// Se eliminó: import { useHabits } from '../../context/HabitsContext';
import AnimatedCard, { useTypewriter, ParticleEffect } from '../ui/AnimatedCard';
import PowerPointTransition from '../ui/PowerPointTransition';

// Se mantiene la interfaz vacía por si necesitas añadir props en el futuro
export default function Activities() {
  // Se eliminaron todos los estados y hooks relacionados con hábitos (useHabits, loading, userHabits, etc.)
  const { theme } = useTheme();
  const [isSmartwatch, setIsSmartwatch] = useState(false);
  const [loading, setLoading] = useState(true); // Se usa un estado 'loading' dummy

  // Simulación de datos estáticos y lógica eliminada
  const completedToday = 2; 
  const totalActivities = 5;
  const totalTime = 30;

  // Detect if we're on a smartwatch-sized screen
  useEffect(() => {
    const checkSmartwatch = () => {
      setIsSmartwatch(window.innerWidth < 280);
    };
    
    checkSmartwatch();
    window.addEventListener('resize', checkSmartwatch);
    return () => window.removeEventListener('resize', checkSmartwatch);
  }, []);
  
  // Simulación de carga para que el estado 'loading' inicial sea visible por un momento
  useEffect(() => {
    const timer = setTimeout(() => {
        setLoading(false);
    }, 1500);
    return () => clearTimeout(timer);
  }, []);

  const titleText = "Actividades de Bienestar"; 
  const typedTitle = useTypewriter(titleText, 80);

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-96">
        <motion.div
          animate={{ 
            rotate: 360
          }}
          transition={{ 
            duration: 2, 
            repeat: Infinity,
            ease: "linear"
          }}
        >
          <Loader className="w-4 h-4 sm:w-8 sm:h-8 text-emerald-600 drop-shadow-lg" />
        </motion.div>
      </div>
    );
  }

  return (
    <div className="space-y-3 sm:space-y-6 relative">
      {/* Efecto de partículas de fondo */}
      <ParticleEffect count={15} color="emerald" />

      {/* Header con animación de máquina de escribir */}
      <PowerPointTransition type="spiral" duration={1500}>
        <motion.div 
          className="flex items-center justify-between"
          whileHover={{ scale: 1.01 }}
        >
          <div className="flex items-center">
            <motion.div
              animate={{ 
                rotate: [0, 360],
                scale: [1, 1.3, 1],
                filter: ["drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))", "drop-shadow(0 0 10px rgba(16, 185, 129, 0.8))", "drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))"]
              }}
              transition={{ 
                duration: 3, 
                repeat: Infinity,
                ease: "easeInOut"
              }}
            >
              <Activity className="w-3 h-3 sm:w-6 sm:h-6 text-emerald-600 dark:text-emerald-400 mr-1.5 sm:mr-3" />
            </motion.div>
            <motion.h2 
              className={`text-sm sm:text-2xl font-bold ${
                theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
              }`}
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.5 }}
            >
              {typedTitle}
              <motion.span
                animate={{ opacity: [1, 0] }}
                transition={{ duration: 0.8, repeat: Infinity, repeatType: "reverse" }}
              >
                |
              </motion.span>
            </motion.h2>
          </div>
          {/* Se eliminó el botón 'Agregar Hábito' */}
          <div className={`px-2 py-1 sm:px-4 sm:py-2 text-xxs sm:text-sm font-medium ${
            theme === 'light' ? 'text-gray-500' : 'text-gray-400'
          }`}>
              Panel de Actividades
          </div>
        </motion.div>
      </PowerPointTransition>

      {/* Stats con animaciones escalonadas tipo PowerPoint - Usando datos estáticos */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-6">
        {[
          {
            icon: Target,
            label: 'Completados Hoy', 
            value: `${completedToday}/${totalActivities}`, // Datos estáticos
            gradient: 'from-emerald-400 to-emerald-600',
            bgLight: 'bg-gradient-to-br from-emerald-50 to-emerald-100',
            borderLight: 'border-emerald-200',
            type: 'bounce' as const,
            delay: 0.2
          },
          {
            icon: Clock,
            label: 'Tiempo Invertido', 
            value: `${totalTime} min`, 
            gradient: 'from-blue-400 to-blue-600',
            bgLight: 'bg-gradient-to-br from-blue-50 to-blue-100',
            borderLight: 'border-blue-200',
            type: 'flip' as const,
            delay: 0.4
          },
          {
            icon: Trophy,
            label: 'Hábitos Activos', 
            value: totalActivities, // Datos estáticos
            gradient: 'from-purple-400 to-purple-600',
            bgLight: 'bg-gradient-to-br from-purple-50 to-purple-100',
            borderLight: 'border-purple-200',
            type: 'glow' as const,
            delay: 0.6
          }
        ].map((stat, index) => (
          <AnimatedCard
            key={index}
            delay={stat.delay}
            type={stat.type}
            hoverScale={1.08}
            hoverRotate={index % 2 === 0 ? 3 : -3}
            className={`rounded-lg sm:rounded-2xl p-2 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform relative overflow-hidden ${
              theme === 'light'
                ? `${stat.bgLight} border-2 ${stat.borderLight}`
                : 'bg-white dark:bg-gray-800 shadow-sm'
            }`}
          >
            {/* Efecto de brillo en hover */}
            <motion.div
              className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
              initial={{ x: '-100%' }}
              whileHover={{ x: '100%' }}
              transition={{ duration: 0.8 }}
            />
            
            <div className="flex items-center relative z-10">
              <motion.div 
                className={`p-1.5 sm:p-3 rounded-lg ${
                  theme === 'light' ? 'bg-white/80' : 'bg-gray-100 dark:bg-gray-700'
                }`}
                whileHover={{ 
                  scale: 1.2, 
                  rotate: 15,
                  boxShadow: "0 10px 25px -5px rgba(0, 0, 0, 0.1)"
                }}
                transition={{ type: "spring", stiffness: 300 }}
              >
                <stat.icon className="w-3 h-3 sm:w-6 sm:h-6 text-emerald-600 dark:text-emerald-400" />
              </motion.div>
              <div className="ml-2 sm:ml-4">
                <motion.p 
                  className={`text-xxs sm:text-sm font-medium ${
                    theme === 'light' ? 'text-gray-700' : 'text-gray-600 dark:text-gray-400'
                  }`}
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: stat.delay + 0.2 }}
                >
                  {stat.label}
                </motion.p>
                <motion.p 
                  className={`text-sm sm:text-2xl font-bold ${
                    theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
                  }`}
                  initial={{ opacity: 0, scale: 0.5 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: stat.delay + 0.4, type: "spring" }}
                >
                  {stat.value}
                </motion.p>
              </div>
            </div>
          </AnimatedCard>
        ))}
      </div>

      {/* Placeholder para la lista de hábitos o nuevo contenido */}
      <PowerPointTransition type="honeycomb" delay={800}>
        <motion.div 
          className={`rounded-lg sm:rounded-2xl p-3 sm:p-6 shadow-lg transition-all duration-300 relative overflow-hidden text-center ${
            theme === 'light'
              ? 'bg-gradient-to-br from-white to-blue-50 border-2 border-blue-200'
              : 'bg-white dark:bg-gray-800 shadow-sm'
          }`}
          whileHover={{ scale: 1.01 }}
        >
          <motion.h3 
            className={`text-xs sm:text-lg font-semibold mb-2 sm:mb-4 ${
              theme === 'light' ? 'text-blue-900' : 'text-gray-900 dark:text-white'
            }`}
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 1 }}
          >
            Sección de Contenido (Vacía)
          </motion.h3>
          <p className={`text-xxs sm:text-sm ${
                theme === 'light' ? 'text-blue-800' : 'text-gray-500 dark:text-gray-400'
              }`}>
                Esta sección está lista para que implementes tu propia lista de actividades o cualquier otro contenido.
          </p>
        </motion.div>
      </PowerPointTransition>
    </div>
  );
}