import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import { motion, AnimatePresence } from 'framer-motion';
import { Loader, Sparkles, Zap, Hexagon, Layers, Aperture, Brain, Heart, Activity } from 'lucide-react';

interface EnhancedRouteTransitionProps {
  children: React.ReactNode;
}

export default function EnhancedRouteTransition({ children }: EnhancedRouteTransitionProps) {
  const location = useLocation();
  const [isTransitioning, setIsTransitioning] = useState(false);
  const [displayChildren, setDisplayChildren] = useState(children);
  const [transitionType, setTransitionType] = useState<string>('morphing');

  // Configuración de transiciones cinematográficas por ruta
  const getTransitionForRoute = (pathname: string) => {
    if (pathname === '/login') return { 
      type: 'liquid', 
      duration: 1200, 
      name: 'Iniciando sesión...', 
      icon: Sparkles,
      color: '#10b981'
    };
    if (pathname === '/register') return { 
      type: 'morphing', 
      duration: 1400, 
      name: 'Creando cuenta...', 
      icon: Layers,
      color: '#3b82f6'
    };
    if (pathname.startsWith('/dashboard')) {
      if (pathname === '/dashboard') return { 
        type: 'neural', 
        duration: 1000, 
        name: 'Cargando dashboard...', 
        icon: Brain,
        color: '#8b5cf6'
      };
      if (pathname.includes('checkin')) return { 
        type: 'pulse', 
        duration: 900, 
        name: 'Preparando check-in...', 
        icon: Heart,
        color: '#ef4444'
      };
      if (pathname.includes('activities')) return { 
        type: 'wave', 
        duration: 1000, 
        name: 'Cargando actividades...', 
        icon: Activity,
        color: '#f59e0b'
      };
      if (pathname.includes('progress')) return { 
        type: 'data', 
        duration: 1100, 
        name: 'Analizando progreso...', 
        icon: Zap,
        color: '#06b6d4'
      };
      if (pathname.includes('community')) return { 
        type: 'network', 
        duration: 1200, 
        name: 'Conectando comunidad...', 
        icon: Layers,
        color: '#8b5cf6'
      };
      if (pathname.includes('voice')) return { 
        type: 'voice', 
        duration: 800, 
        name: 'Activando IA...', 
        icon: Aperture,
        color: '#10b981'
      };
      if (pathname.includes('emergency')) return { 
        type: 'emergency', 
        duration: 700, 
        name: 'Accediendo a soporte...', 
        icon: Sparkles,
        color: '#ef4444'
      };
      if (pathname.includes('profile')) return { 
        type: 'profile', 
        duration: 600, 
        name: 'Cargando perfil...', 
        icon: Hexagon,
        color: '#8b5cf6'
      };
    }
    return { 
      type: 'morphing', 
      duration: 800, 
      name: 'Cargando...', 
      icon: Loader,
      color: '#10b981'
    };
  };

  useEffect(() => {
    const transition = getTransitionForRoute(location.pathname);
    
    setIsTransitioning(true);
    setTransitionType(transition.type);

    const timer = setTimeout(() => {
      setDisplayChildren(children);
      
      setTimeout(() => {
        setIsTransitioning(false);
      }, 400);
    }, transition.duration);

    return () => clearTimeout(timer);
  }, [location.pathname, children]);

  const currentTransition = getTransitionForRoute(location.pathname);
  const IconComponent = currentTransition.icon;

  // Componentes de loading cinematográficos
  const LoadingComponents = {
    liquid: () => (
      <div className="relative">
        <motion.div
          className="w-32 h-32 rounded-full relative overflow-hidden"
          style={{ backgroundColor: `${currentTransition.color}20` }}
        >
          <motion.div
            className="absolute inset-0 rounded-full"
            style={{ backgroundColor: currentTransition.color }}
            animate={{
              scale: [0, 1.2, 0],
              opacity: [0, 0.8, 0]
            }}
            transition={{
              duration: 2,
              repeat: Infinity,
              ease: "easeInOut"
            }}
          />
          <motion.div
            className="absolute inset-4 rounded-full bg-white/90 flex items-center justify-center"
            animate={{
              rotate: [0, 360],
              scale: [1, 1.1, 1]
            }}
            transition={{
              rotate: { duration: 3, repeat: Infinity, ease: "linear" },
              scale: { duration: 2, repeat: Infinity, ease: "easeInOut" }
            }}
          >
            <IconComponent className="w-12 h-12" style={{ color: currentTransition.color }} />
          </motion.div>
        </motion.div>
      </div>
    ),

    morphing: () => (
      <div className="relative">
        <motion.div
          className="w-32 h-32 flex items-center justify-center"
          style={{ backgroundColor: currentTransition.color }}
          animate={{
            borderRadius: ["20%", "50%", "20%"],
            rotate: [0, 180, 360],
            scale: [1, 1.2, 1]
          }}
          transition={{
            duration: 3,
            repeat: Infinity,
            ease: "easeInOut"
          }}
        >
          <IconComponent className="w-16 h-16 text-white" />
        </motion.div>
      </div>
    ),

    neural: () => (
      <div className="relative w-32 h-32">
        {/* Nodos de red neuronal */}
        {Array.from({ length: 8 }, (_, i) => (
          <motion.div
            key={i}
            className="absolute w-4 h-4 rounded-full"
            style={{
              backgroundColor: currentTransition.color,
              left: `${50 + 40 * Math.cos((i * Math.PI * 2) / 8)}%`,
              top: `${50 + 40 * Math.sin((i * Math.PI * 2) / 8)}%`
            }}
            animate={{
              scale: [1, 1.5, 1],
              opacity: [0.5, 1, 0.5]
            }}
            transition={{
              duration: 1.5,
              repeat: Infinity,
              delay: i * 0.2
            }}
          />
        ))}
        {/* Centro */}
        <motion.div
          className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-12 h-12 rounded-full flex items-center justify-center"
          style={{ backgroundColor: currentTransition.color }}
          animate={{
            scale: [1, 1.3, 1],
            rotate: [0, 360]
          }}
          transition={{
            scale: { duration: 2, repeat: Infinity },
            rotate: { duration: 4, repeat: Infinity, ease: "linear" }
          }}
        >
          <IconComponent className="w-6 h-6 text-white" />
        </motion.div>
      </div>
    ),

    pulse: () => (
      <div className="relative">
        <motion.div
          className="w-32 h-32 rounded-full flex items-center justify-center relative"
          style={{ backgroundColor: `${currentTransition.color}20` }}
        >
          {/* Ondas de pulso */}
          {[0, 1, 2].map(i => (
            <motion.div
              key={i}
              className="absolute inset-0 rounded-full border-4"
              style={{ borderColor: currentTransition.color }}
              animate={{
                scale: [1, 2, 3],
                opacity: [1, 0.5, 0]
              }}
              transition={{
                duration: 2,
                repeat: Infinity,
                delay: i * 0.6
              }}
            />
          ))}
          <IconComponent className="w-16 h-16 z-10" style={{ color: currentTransition.color }} />
        </motion.div>
      </div>
    ),

    wave: () => (
      <div className="flex items-center space-x-2">
        {Array.from({ length: 7 }, (_, i) => (
          <motion.div
            key={i}
            className="w-3 rounded-full"
            style={{ backgroundColor: currentTransition.color }}
            animate={{
              height: [20, 60, 20],
              opacity: [0.5, 1, 0.5]
            }}
            transition={{
              duration: 1,
              repeat: Infinity,
              delay: i * 0.1
            }}
          />
        ))}
      </div>
    ),

    data: () => (
      <div className="relative w-32 h-32">
        {/* Barras de datos */}
        {Array.from({ length: 5 }, (_, i) => (
          <motion.div
            key={i}
            className="absolute bottom-0 w-4 rounded-t"
            style={{
              backgroundColor: currentTransition.color,
              left: `${20 + i * 15}%`,
              height: `${30 + Math.random() * 40}%`
            }}
            animate={{
              height: [`${30 + Math.random() * 40}%`, `${60 + Math.random() * 40}%`, `${30 + Math.random() * 40}%`],
              opacity: [0.7, 1, 0.7]
            }}
            transition={{
              duration: 1.5,
              repeat: Infinity,
              delay: i * 0.2
            }}
          />
        ))}
        <div className="absolute top-4 left-1/2 transform -translate-x-1/2">
          <IconComponent className="w-8 h-8" style={{ color: currentTransition.color }} />
        </div>
      </div>
    ),

    network: () => (
      <div className="relative w-32 h-32">
        {/* Nodos de red */}
        {Array.from({ length: 6 }, (_, i) => (
          <motion.div
            key={i}
            className="absolute w-3 h-3 rounded-full"
            style={{
              backgroundColor: currentTransition.color,
              left: `${Math.random() * 80 + 10}%`,
              top: `${Math.random() * 80 + 10}%`
            }}
            animate={{
              scale: [1, 1.5, 1],
              opacity: [0.5, 1, 0.5]
            }}
            transition={{
              duration: 2,
              repeat: Infinity,
              delay: i * 0.3
            }}
          />
        ))}
        {/* Líneas conectoras animadas */}
        <svg className="absolute inset-0 w-full h-full">
          {Array.from({ length: 4 }, (_, i) => (
            <motion.line
              key={i}
              x1="20%"
              y1="20%"
              x2="80%"
              y2="80%"
              stroke={currentTransition.color}
              strokeWidth="2"
              initial={{ pathLength: 0, opacity: 0 }}
              animate={{ pathLength: 1, opacity: 0.6 }}
              transition={{
                duration: 1.5,
                repeat: Infinity,
                delay: i * 0.4
              }}
            />
          ))}
        </svg>
      </div>
    ),

    voice: () => (
      <div className="flex items-center space-x-1">
        {Array.from({ length: 5 }, (_, i) => (
          <motion.div
            key={i}
            className="w-2 rounded-full"
            style={{ backgroundColor: currentTransition.color }}
            animate={{
              height: [10, 40, 10],
              opacity: [0.5, 1, 0.5]
            }}
            transition={{
              duration: 0.8,
              repeat: Infinity,
              delay: i * 0.1
            }}
          />
        ))}
      </div>
    ),

    emergency: () => (
      <div className="relative">
        <motion.div
          className="w-32 h-32 rounded-full flex items-center justify-center"
          style={{ backgroundColor: `${currentTransition.color}20` }}
          animate={{
            boxShadow: [
              `0 0 0 0 ${currentTransition.color}40`,
              `0 0 0 20px ${currentTransition.color}00`,
              `0 0 0 0 ${currentTransition.color}40`
            ]
          }}
          transition={{
            duration: 1.5,
            repeat: Infinity
          }}
        >
          <motion.div
            animate={{
              scale: [1, 1.2, 1],
              rotate: [0, 10, -10, 0]
            }}
            transition={{
              duration: 1,
              repeat: Infinity
            }}
          >
            <IconComponent className="w-16 h-16" style={{ color: currentTransition.color }} />
          </motion.div>
        </motion.div>
      </div>
    ),

    profile: () => (
      <div className="relative">
        <motion.div
          className="w-32 h-32 flex items-center justify-center"
          style={{ backgroundColor: currentTransition.color }}
          animate={{
            clipPath: [
              'polygon(50% 0%, 0% 100%, 100% 100%)',
              'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
              'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)',
              'polygon(50% 0%, 0% 100%, 100% 100%)'
            ]
          }}
          transition={{
            duration: 3,
            repeat: Infinity,
            ease: "easeInOut"
          }}
        >
          <IconComponent className="w-16 h-16 text-white" />
        </motion.div>
      </div>
    )
  };

  const LoadingComponent = LoadingComponents[transitionType as keyof typeof LoadingComponents] || LoadingComponents.morphing;

  return (
    <div className="relative w-full h-full">
      {/* Overlay de transición cinematográfico */}
      <AnimatePresence>
        {isTransitioning && (
          <motion.div
            className="fixed inset-0 z-[9999] flex items-center justify-center"
            style={{
              background: `linear-gradient(135deg, ${currentTransition.color}15, ${currentTransition.color}25)`
            }}
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.3 }}
          >
            {/* Partículas de fondo dinámicas */}
            <div className="absolute inset-0 overflow-hidden">
              {Array.from({ length: 60 }, (_, i) => (
                <motion.div
                  key={i}
                  className="absolute rounded-full"
                  style={{
                    backgroundColor: `${currentTransition.color}40`,
                    width: `${Math.random() * 8 + 2}px`,
                    height: `${Math.random() * 8 + 2}px`,
                    left: `${Math.random() * 100}%`,
                    top: `${Math.random() * 100}%`
                  }}
                  animate={{
                    y: [0, -100, -200],
                    opacity: [0, 0.8, 0],
                    scale: [0, 1.5, 0],
                    rotate: [0, 360]
                  }}
                  transition={{
                    duration: Math.random() * 4 + 3,
                    repeat: Infinity,
                    delay: Math.random() * 3,
                    ease: "easeOut"
                  }}
                />
              ))}
            </div>

            {/* Contenido central */}
            <motion.div
              className="text-center space-y-8 p-12 rounded-3xl backdrop-blur-2xl border shadow-2xl relative z-10"
              style={{
                background: `${currentTransition.color}10`,
                borderColor: `${currentTransition.color}30`
              }}
              initial={{ scale: 0.8, opacity: 0, rotateY: 90 }}
              animate={{ scale: 1, opacity: 1, rotateY: 0 }}
              exit={{ scale: 0.8, opacity: 0, rotateY: -90 }}
              transition={{ duration: 0.5, ease: [0.25, 0.46, 0.45, 0.94] }}
            >
              {/* Componente de loading específico */}
              <motion.div
                initial={{ scale: 0 }}
                animate={{ scale: 1 }}
                transition={{ delay: 0.2, type: "spring", stiffness: 200 }}
              >
                <LoadingComponent />
              </motion.div>

              {/* Texto de carga */}
              <motion.div
                className="space-y-4"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.4 }}
              >
                <h3
                  className="text-3xl font-bold"
                  style={{ color: currentTransition.color }}
                >
                  {currentTransition.name}
                </h3>
                
                {/* Barra de progreso cinematográfica */}
                <div className="w-96 h-2 bg-white/20 rounded-full overflow-hidden backdrop-blur-sm">
                  <motion.div
                    className="h-full rounded-full"
                    style={{
                      background: `linear-gradient(90deg, ${currentTransition.color}, ${currentTransition.color}dd)`
                    }}
                    initial={{ width: '0%' }}
                    animate={{ width: '100%' }}
                    transition={{
                      duration: currentTransition.duration / 1000,
                      ease: [0.25, 0.46, 0.45, 0.94]
                    }}
                  />
                </div>

                {/* Mensaje motivacional */}
                <motion.p
                  className="text-lg opacity-80"
                  style={{ color: currentTransition.color }}
                  initial={{ opacity: 0 }}
                  animate={{ opacity: 0.8 }}
                  transition={{ delay: 0.6 }}
                >
                  Preparando tu experiencia de bienestar...
                </motion.p>
              </motion.div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Contenido principal con transición mejorada */}
      <motion.div
        className="w-full h-full"
        animate={{
          opacity: isTransitioning ? 0 : 1,
          scale: isTransitioning ? 0.95 : 1,
          filter: isTransitioning ? 'blur(10px)' : 'blur(0px)'
        }}
        transition={{
          duration: 0.5,
          ease: [0.25, 0.46, 0.45, 0.94]
        }}
      >
        {displayChildren}
      </motion.div>
    </div>
  );
}