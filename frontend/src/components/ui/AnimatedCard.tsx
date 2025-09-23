import React from 'react';
import { motion } from 'framer-motion';
import FluidCard from './FluidCard';
import MagneticElement from './MagneticElement';

interface AnimatedCardProps {
  children: React.ReactNode;
  className?: string;
  hoverScale?: number;
  hoverRotate?: number;
  delay?: number;
  type?: 'slide' | 'zoom' | 'flip' | 'bounce' | 'glow' | 'spiral' | 'dissolve' | 'fluid' | 'magnetic' | 'elastic' | 'morph';
  intensity?: number;
  glowColor?: string;
}

export default function AnimatedCard({
  children,
  className = '',
  hoverScale = 1.05,
  hoverRotate = 0,
  delay = 0,
  type = 'slide',
  intensity = 1,
  glowColor = '#10b981'
}: AnimatedCardProps) {
  
  // Si es tipo fluid, usar el FluidCard
  if (type === 'fluid') {
    return (
      <FluidCard className={className} intensity={intensity} glowColor={glowColor}>
        {children}
      </FluidCard>
    );
  }

  // Si es tipo magnetic, usar el MagneticElement
  if (type === 'magnetic') {
    return (
      <MagneticElement className={className} strength={intensity * 0.3}>
        {children}
      </MagneticElement>
    );
  }

  const cardVariants = {
    slide: {
      hidden: { 
        opacity: 0, 
        y: 60 * intensity, 
        scale: 0.9, 
        rotateX: 15 * intensity,
        filter: 'blur(10px)'
      },
      visible: { 
        opacity: 1, 
        y: 0, 
        scale: 1,
        rotateX: 0,
        filter: 'blur(0px)',
        transition: {
          duration: 0.8,
          delay,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    zoom: {
      hidden: { 
        opacity: 0,  
        scale: 0.3 * intensity, 
        rotate: 15 * intensity, 
        filter: 'blur(15px) brightness(1.5)'
      },
      visible: { 
        opacity: 1, 
        scale: 1, 
        rotate: 0,
        filter: 'blur(0px) brightness(1)',
        transition: {
          duration: 0.9,
          delay,
          ease: [0.34, 1.56, 0.64, 1]
        }
      }
    },
    flip: {
      hidden: { 
        opacity: 0, 
        rotateY: 90 * intensity, 
        perspective: 1000, 
        scale: 0.9,
        filter: 'blur(8px)'
      },
      visible: { 
        opacity: 1, 
        rotateY: 0,
        perspective: 1000,
        scale: 1,
        filter: 'blur(0px)',
        transition: {
          duration: 0.8,
          delay,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    bounce: {
      hidden: { 
        opacity: 0, 
        y: -100 * intensity, 
        scale: 0.5, 
        rotateZ: -5 * intensity,
        filter: 'blur(12px)'
      },
      visible: { 
        opacity: 1, 
        y: 0, 
        scale: 1,
        rotateZ: 0,
        filter: 'blur(0px)',
        transition: {
          duration: 1,
          delay,
          type: "spring",
          bounce: 0.5 * intensity,
          stiffness: 150
        }
      }
    },
    glow: {
      hidden: { 
        opacity: 0, 
        scale: 0.8, 
        filter: 'blur(20px) brightness(1.8) saturate(1.5)'
      },
      visible: { 
        opacity: 1, 
        scale: 1, 
        filter: 'blur(0px) brightness(1) saturate(1)',
        transition: {
          duration: 1.2,
          delay,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    spiral: {
      hidden: { 
        opacity: 0,
        scale: 0.3,
        rotate: 720 * intensity,
        x: -200 * intensity,
        filter: 'hue-rotate(90deg) blur(15px)'
      },
      visible: { 
        opacity: 1,
        scale: 1,
        rotate: 0,
        x: 0,
        filter: 'hue-rotate(0deg) blur(0px)',
        transition: {
          duration: 1.2,
          delay,
          ease: [0.25, 0.46, 0.45, 0.94]
        }
      }
    },
    dissolve: {
      hidden: { 
        opacity: 0, 
        scale: 0.8, 
        rotate: -10 * intensity,
        filter: 'blur(25px) contrast(0.8) saturate(1.5)'
      },
      visible: { 
        opacity: 1, 
        scale: 1, 
        rotate: 0,
        filter: 'blur(0px) contrast(1) saturate(1)',
        transition: {
          duration: 1.2,
          delay,
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
          duration: 1,
          delay,
          times: [0, 0.3, 0.6, 0.8, 1],
          ease: "easeOut"
        }
      }
    },
    morph: {
      hidden: {
        opacity: 0,
        borderRadius: '50%',
        scale: 0.5 * intensity
      },
      visible: {
        opacity: 1,
        borderRadius: '0%',
        scale: 1,
        transition: {
          duration: 0.8,
          delay,
          ease: [0.34, 1.56, 0.64, 1]
        }
      }
    }
  };

  return (
    <motion.div
      className={className}
      initial="hidden"
      animate="visible"
      variants={cardVariants[type]}
      whileHover={{ 
        scale: hoverScale, 
        rotate: hoverRotate,
        y: -5,
        boxShadow: `0 25px 50px -12px ${glowColor}40`,
        transition: { duration: 0.3, ease: [0.25, 0.46, 0.45, 0.94] }
      }}
      whileTap={{ 
        scale: 0.95, 
        rotate: hoverRotate / 2,
        transition: { duration: 0.1 }
      }}
    >
      {children}
    </motion.div>
  );
}

// Componente para efectos de partículas mejoradas
export function ParticleEffect({ 
  count = 20, 
  color = 'emerald',
  size = { min: 2, max: 8 },
  speed = { min: 3, max: 7 },
  opacity = { min: 0.3, max: 0.8 }
}: { 
  count?: number; 
  color?: string;
  size?: { min: number; max: number };
  speed?: { min: number; max: number };
  opacity?: { min: number; max: number };
}) {
  const particles = Array.from({ length: count }, (_, i) => i);
  const colorMap: Record<string, string> = {
    emerald: '#10b981',
    blue: '#3b82f6',
    purple: '#8b5cf6',
    red: '#ef4444',
    orange: '#f59e0b',
    pink: '#ec4899',
    cyan: '#06b6d4',
    yellow: '#eab308'
  };
  
  const particleColor = colorMap[color] || color;

  return (
    <div className="absolute inset-0 pointer-events-none overflow-hidden">
      {particles.map((particle) => (
        <motion.div
          key={particle}
          className="absolute rounded-full"
          style={{
            backgroundColor: particleColor,
            width: Math.random() * (size.max - size.min) + size.min,
            height: Math.random() * (size.max - size.min) + size.min,
            opacity: Math.random() * (opacity.max - opacity.min) + opacity.min,
            filter: `blur(${Math.random() * 2}px) brightness(${1 + Math.random() * 0.5})`
          }}
          initial={{
            x: Math.random() * window.innerWidth,
            y: window.innerHeight + 10
          }}
          animate={{
            y: -10,
            opacity: [opacity.min, opacity.max, 0],
            scale: [0, 1.5, 0],
            rotate: [0, 360],
            filter: [
              `blur(${Math.random() * 2}px) brightness(${1 + Math.random() * 0.5})`,
              `blur(0px) brightness(${1.5 + Math.random() * 0.5})`,
              `blur(${Math.random() * 3}px) brightness(${1 + Math.random() * 0.5})`
            ]
          }}
          transition={{
            duration: Math.random() * (speed.max - speed.min) + speed.min,
            repeat: Infinity,
            delay: Math.random() * 3,
            ease: "easeOut"
          }}
        />
      ))}
    </div>
  );
}

// Hook para animaciones de texto tipo máquina de escribir
export function useTypewriter(text: string, speed: number = 50) {
  const [displayText, setDisplayText] = React.useState('');
  const [currentIndex, setCurrentIndex] = React.useState(0);

  React.useEffect(() => {
    if (currentIndex < text.length) {
      const timeout = setTimeout(() => {
        setDisplayText(prev => prev + text[currentIndex]);
        setCurrentIndex(prev => prev + 1);
      }, speed);

      return () => clearTimeout(timeout);
    }
  }, [currentIndex, text, speed]);

  return displayText;
}