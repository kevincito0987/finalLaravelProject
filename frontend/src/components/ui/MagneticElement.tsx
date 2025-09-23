import React, { useRef, useState } from 'react';
import { motion, useMotionValue, useSpring } from 'framer-motion';

interface MagneticElementProps {
  children: React.ReactNode;
  strength?: number;
  range?: number;
  className?: string;
  disabled?: boolean;
}

export default function MagneticElement({
  children,
  strength = 0.3,
  range = 100,
  className = '',
  disabled = false
}: MagneticElementProps) {
  const ref = useRef<HTMLDivElement>(null);
  const [isHovered, setIsHovered] = useState(false);

  const x = useMotionValue(0);
  const y = useMotionValue(0);

  const springX = useSpring(x, { stiffness: 500, damping: 100 });
  const springY = useSpring(y, { stiffness: 500, damping: 100 });

  // Transform values for 3D effects
  const rotateX = useTransform(mouseY, [-0.5, 0.5], [15 * intensity, -15 * intensity]);
  const rotateY = useTransform(mouseX, [-0.5, 0.5], [-15 * intensity, 15 * intensity]);
  const scale = useTransform(mouseX, [-0.5, 0.5], [1, 1.05]);

  // Gesture handling
  const bind = useGesture({
    onMove: ({ xy, currentTarget }) => {
      if (disabled || !currentTarget) return;

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
      className={`relative ${className}`}
      style={{
        x: springX,
        y: springY
      }}
      onMouseMove={handleMouseMove}
      onMouseLeave={handleMouseLeave}
      onMouseEnter={handleMouseEnter}
      whileHover={!disabled ? {
        scale: 1.05,
        transition: { duration: 0.3, ease: "easeOut" }
      } : undefined}
      {...bind()}
    >
      {/* Magnetic field visualization */}
      {isHovered && !disabled && (
        <motion.div
          className="absolute inset-0 rounded-full bg-gradient-to-r from-emerald-500/10 to-blue-500/10 -z-10"
          initial={{ scale: 0, opacity: 0 }}
          animate={{ scale: 1.5, opacity: 0.5 }}
          exit={{ scale: 0, opacity: 0 }}
          transition={{ duration: 0.3 }}
        />
      )}

      {children}
    </motion.div>
  );
}