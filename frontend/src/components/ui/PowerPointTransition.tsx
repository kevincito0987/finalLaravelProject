import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

interface PowerPointTransitionProps {
  children: React.ReactNode;
  type?: 'dissolve' | 'honeycomb' | 'shimmer' | 'wipe' | 'zoom' | 'flip' | 'cube' | 'spiral' | 'reveal' | 'glitch' | 'liquid' | 'morph' | 'elastic' | 'bounce' | 'fold';
  trigger?: boolean;
  delay?: number;
  duration?: number;
  className?: string;
  intensity?: number;
}

export default function PowerPointTransition({ 
  children, 
  type = 'dissolve',
  trigger = true,
  delay = 0,
  duration = 1000,
  className = '',
  intensity = 1
}: PowerPointTransitionProps) {
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    if (trigger) {
      const timer = setTimeout(() => {
        setIsVisible(true);
      }, delay);
      return () => clearTimeout(timer);
    }
  }, [trigger, delay]);

  // Framer Motion variants para diferentes tipos de transición
  const variants = {
    dissolve: {
      hidden: { 
        opacity: 0, 
        scale: 0.8 * intensity, 
        rotate: -10 * intensity,
        filter: 'blur(20px) brightness(1.2)'
      },
      visible: { 
        opacity: 1, 
        scale: 1, 
        rotate: 0,
        filter: 'blur(0px) brightness(1)',
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94],
          staggerChildren: 0.1
        }
      }
    },
    honeycomb: {
      hidden: { 
        opacity: 0,
        scale: 0.3 * intensity,
        rotateY: 90 * intensity,
        rotateX: 45 * intensity,
        clipPath: 'polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%)'
      },
      visible: { 
        opacity: 1,
        scale: 1,
        rotateY: 0,
        rotateX: 0,
        clipPath: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    shimmer: {
      hidden: { 
        opacity: 0,
        x: -100 * intensity,
        skewX: -15 * intensity,
        filter: 'contrast(0.8) brightness(1.2)'
      },
      visible: { 
        opacity: 1,
        x: 0,
        skewX: 0,
        filter: 'contrast(1) brightness(1)',
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    wipe: {
      hidden: { 
        opacity: 0,
        clipPath: 'inset(0 100% 0 0)'
      },
      visible: { 
        opacity: 1,
        clipPath: 'inset(0 0% 0 0)',
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    zoom: {
      hidden: { 
        opacity: 0,
        scale: 0.1 * intensity,
        rotate: 180 * intensity,
        filter: 'blur(15px)'
      },
      visible: { 
        opacity: 1,
        scale: 1,
        rotate: 0,
        filter: 'blur(0px)',
        transition: {
          duration: duration / 1000,
          ease: [0.34, 1.56, 0.64, 1]
        }
      }
    },
    flip: {
      hidden: { 
        opacity: 0,
        rotateY: 90 * intensity,
        perspective: 1000,
        scale: 0.9
      },
      visible: { 
        opacity: 1,
        rotateY: 0,
        perspective: 1000,
        scale: 1,
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    cube: {
      hidden: { 
        opacity: 0,
        rotateY: 90 * intensity,
        rotateX: 45 * intensity,
        scale: 0.8,
        perspective: 1000,
        z: -300 * intensity
      },
      visible: { 
        opacity: 1,
        rotateY: 0,
        rotateX: 0,
        scale: 1,
        perspective: 1000,
        z: 0,
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    spiral: {
      hidden: { 
        opacity: 0,
        scale: 0.3 * intensity,
        rotate: 720 * intensity,
        x: -200 * intensity,
        filter: 'hue-rotate(90deg)'
      },
      visible: { 
        opacity: 1,
        scale: 1,
        rotate: 0,
        x: 0,
        filter: 'hue-rotate(0deg)',
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    reveal: {
      hidden: { 
        opacity: 0,
        clipPath: 'circle(0% at 50% 50%)'
      },
      visible: { 
        opacity: 1,
        clipPath: 'circle(100% at 50% 50%)',
        transition: {
          duration: duration / 1000,
          ease: [0.34, 1.56, 0.64, 1]
        }
      }
    },
    glitch: {
      hidden: { 
        opacity: 0,
        x: 0,
        filter: 'blur(10px) contrast(2) hue-rotate(90deg)'
      },
      visible: { 
        opacity: 1,
        x: [10 * intensity, -10 * intensity, 5 * intensity, -5 * intensity, 0],
        filter: 'blur(0px) contrast(1) hue-rotate(0deg)',
        transition: {
          duration: duration / 1000,
          ease: "easeOut",
          x: {
            duration: duration / 1000,
            times: [0, 0.2, 0.4, 0.6, 1],
            ease: "easeOut"
          }
        }
      }
    },
    liquid: {
      hidden: {
        opacity: 0,
        borderRadius: '50%',
        scale: 0.5 * intensity,
        filter: 'blur(15px)'
      },
      visible: {
        opacity: 1,
        borderRadius: '0%',
        scale: 1,
        filter: 'blur(0px)',
        transition: {
          duration: duration / 1000,
          ease: "easeOut"
        }
      }
    },
    morph: {
      hidden: {
        opacity: 0,
        borderRadius: '0%',
        clipPath: 'polygon(0% 0%, 100% 0%, 100% 0%, 0% 0%)'
      },
      visible: {
        opacity: 1,
        borderRadius: '0%',
        clipPath: 'polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%)',
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    elastic: {
      hidden: {
        opacity: 0,
        scaleX: 2 * intensity,
        scaleY: 0.5 * intensity
      },
      visible: {
        opacity: 1,
        scaleX: [2 * intensity, 0.8 * intensity, 1.2 * intensity, 0.9 * intensity, 1],
        scaleY: [0.5 * intensity, 1.2 * intensity, 0.8 * intensity, 1.1 * intensity, 1],
        transition: {
          duration: duration / 1000,
          times: [0, 0.3, 0.6, 0.8, 1],
          ease: "easeOut"
        }
      }
    },
    bounce: {
      hidden: {
        opacity: 0,
        y: -100 * intensity,
        scale: 0.8
      },
      visible: {
        opacity: 1,
        y: [0, -30 * intensity, -15 * intensity, -5 * intensity, 0],
        scale: [0.8, 1.1 * intensity, 0.9 * intensity, 1.05 * intensity, 1],
        transition: {
          duration: duration / 1000,
          times: [0, 0.4, 0.6, 0.8, 1],
          ease: "easeOut"
        }
      }
    },
    fold: {
      hidden: {
        opacity: 0,
        rotateX: 90 * intensity,
        transformOrigin: 'top',
        perspective: 1000
      },
      visible: {
        opacity: 1,
        rotateX: 0,
        transition: {
          duration: duration / 1000,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    }
  };

  return (
    <AnimatePresence>
      {isVisible && (
        <motion.div
          className={className}
          initial="hidden"
          animate="visible"
          exit="hidden"
          variants={variants[type]}
        >
          {children}
        </motion.div>
      )}
    </AnimatePresence>
  );
}

// Hook personalizado para animaciones escalonadas
export function useStaggeredAnimation(itemCount: number, delay: number = 100) {
  const [visibleItems, setVisibleItems] = useState<number[]>([]);

  useEffect(() => {
    const timers: NodeJS.Timeout[] = [];
    
    for (let i = 0; i < itemCount; i++) {
      const timer = setTimeout(() => {
        setVisibleItems(prev => [...prev, i]);
      }, i * delay);
      timers.push(timer);
    }

    return () => {
      timers.forEach(timer => clearTimeout(timer));
    };
  }, [itemCount, delay]);

  return visibleItems;
}

// Componente para animaciones de entrada de elementos individuales
export function PowerPointItem({ 
  children, 
  index, 
  type = 'dissolve',
  delay = 100,
  intensity = 1
}: {
  children: React.ReactNode;
  index: number;
  type?: string;
  delay?: number;
  intensity?: number;
}) {
  return (
    <PowerPointTransition
      type={type as any}
      delay={index * delay}
      duration={800}
      intensity={intensity}
    >
      {children}
    </PowerPointTransition>
  );
}