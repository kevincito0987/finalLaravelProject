import React, { useRef } from 'react';
import { motion, useMotionValue, useSpring, useTransform } from 'framer-motion';
import { useGesture } from '@use-gesture/react';

interface FluidCardProps {
  children: React.ReactNode;
  className?: string;
  intensity?: number;
  glowColor?: string;
}

export default function FluidCard({ 
  children, 
  className = '',
  intensity = 1,
  glowColor = '#10b981'
}: FluidCardProps) {
  const ref = useRef<HTMLDivElement>(null);
  
  // Motion values for mouse tracking
  const x = useMotionValue(0);
  const y = useMotionValue(0);
  
  // Spring animations for smooth movement
  const mouseX = useSpring(x, { stiffness: 500, damping: 100 });
  const mouseY = useSpring(y, { stiffness: 500, damping: 100 });
  
  // Transform values for 3D effects
  const rotateX = useTransform(mouseY, [-0.5, 0.5], [15 * intensity, -15 * intensity]);
  const rotateY = useTransform(mouseX, [-0.5, 0.5], [-15 * intensity, 15 * intensity]);
  const scale = useTransform(mouseX, [-0.5, 0.5], [1, 1.05]);

  // Gesture handling
  const bind = useGesture({
    onMove: ({ xy, currentTarget }) => {
      if (!currentTarget) return;
      const rect = (currentTarget as HTMLElement).getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;
      
      x.set((xy[0] - centerX) / rect.width);
      y.set((xy[1] - centerY) / rect.height);
    },
    onHover: ({ hovering }) => {
      if (!hovering) {
        x.set(0);
        y.set(0);
      }
    }
  });

  return (
    <motion.div
      ref={ref}
      className={`relative overflow-hidden rounded-2xl backdrop-blur-sm ${className}`}
      style={{
        rotateX,
        rotateY,
        scale,
        transformStyle: 'preserve-3d',
        perspective: 1000
      }}
      initial={{ opacity: 0, y: 50 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.8, ease: [0.25, 0.46, 0.45, 0.94] }}
      whileHover={{ 
        boxShadow: `0 25px 50px -12px ${glowColor}40`,
        transition: { duration: 0.3 }
      }}
      {...bind()}
    >
      {/* Animated background gradient */}
      <motion.div
        className="absolute inset-0 opacity-20"
        style={{
          background: `radial-gradient(circle at ${mouseX}px ${mouseY}px, ${glowColor}60, transparent 70%)`
        }}
        animate={{
          background: [
            `radial-gradient(circle at 20% 20%, ${glowColor}40, transparent 70%)`,
            `radial-gradient(circle at 80% 80%, ${glowColor}40, transparent 70%)`,
            `radial-gradient(circle at 20% 80%, ${glowColor}40, transparent 70%)`,
            `radial-gradient(circle at 80% 20%, ${glowColor}40, transparent 70%)`
          ]
        }}
        transition={{ duration: 8, repeat: Infinity, ease: "linear" }}
      />

      {/* Floating particles */}
      <div className="absolute inset-0 pointer-events-none">
        {Array.from({ length: 8 }, (_, i) => (
          <motion.div
            key={i}
            className="absolute w-1 h-1 rounded-full opacity-60"
            style={{ backgroundColor: glowColor }}
            animate={{
              x: [
                Math.random() * 100 + '%',
                Math.random() * 100 + '%',
                Math.random() * 100 + '%'
              ],
              y: [
                Math.random() * 100 + '%',
                Math.random() * 100 + '%',
                Math.random() * 100 + '%'
              ],
              scale: [0, 1.5, 0],
              opacity: [0, 0.8, 0],
              filter: [
                'blur(0px) brightness(1)',
                'blur(1px) brightness(1.5)',
                'blur(0px) brightness(1)'
              ]
            }}
            transition={{
              duration: Math.random() * 4 + 3,
              repeat: Infinity,
              delay: Math.random() * 2,
              ease: "easeInOut"
            }}
          />
        ))}
      </div>

      {/* Shimmer effect */}
      <motion.div
        className="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent"
        initial={{ x: '-100%', skewX: -15 }}
        whileHover={{ x: '100%' }}
        transition={{ duration: 1.2, ease: "easeOut" }}
      />

      {/* Content */}
      <div className="relative z-10 h-full">
        {children}
      </div>

      {/* Border glow */}
      <motion.div
        className="absolute inset-0 rounded-2xl border border-white/20"
        style={{
          boxShadow: `inset 0 1px 0 0 rgba(255, 255, 255, 0.1)`
        }}
        whileHover={{
          borderColor: `${glowColor}60`,
          boxShadow: `inset 0 1px 0 0 ${glowColor}40, 0 0 20px ${glowColor}30`
        }}
        transition={{ duration: 0.3 }}
      />
    </motion.div>
  );
}