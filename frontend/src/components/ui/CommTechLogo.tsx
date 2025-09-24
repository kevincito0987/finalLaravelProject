import React from 'react';

interface HabitaLogoProps {
  className?: string;
  size?: 'sm' | 'md' | 'lg' | 'xl' | 'xs'; // Added 'xs' for smartwatches
  variant?: 'full' | 'icon' | 'text';
  animated?: boolean;
}

export default function HabitaLogo({ 
  className = '', 
  size = 'md', 
  variant = 'full',
  animated = false 
}: HabitaLogoProps) {
  const sizeClasses = {
    xs: 'w-4 h-4', // Extra small for smartwatches
    sm: 'w-6 h-6',
    md: 'w-8 h-8',
    lg: 'w-12 h-12',
    xl: 'w-16 h-16'
  };

  const textSizes = {
    xs: 'text-sm', // Extra small for smartwatches
    sm: 'text-lg',
    md: 'text-xl',
    lg: 'text-2xl',
    xl: 'text-3xl'
  };

  const LogoIcon = () => (
    <div className={`${sizeClasses[size]} relative ${animated ? 'animate-pulse' : ''}`}>
      <svg
        viewBox="0 0 120 120"
        className="w-full h-full"
        xmlns="http://www.w3.org/2000/svg"
      >
        <defs>
          {/* Gradiente principal más vibrante */}
          <linearGradient id="primaryGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="#06d6a0" />
            <stop offset="25%" stopColor="#118ab2" />
            <stop offset="50%" stopColor="#073b4c" />
            <stop offset="75%" stopColor="#ffd166" />
            <stop offset="100%" stopColor="#f72585" />
          </linearGradient>

          {/* Gradiente secundario para elementos internos */}
          <radialGradient id="innerGradient" cx="50%" cy="30%" r="70%">
            <stop offset="0%" stopColor="#ffffff" stopOpacity="1" />
            <stop offset="50%" stopColor="#f8fafc" stopOpacity="0.9" />
            <stop offset="100%" stopColor="#e2e8f0" stopOpacity="0.7" />
          </radialGradient>

          {/* Gradiente para el brillo */}
          <linearGradient id="glowGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="#10b981" stopOpacity="0.8" />
            <stop offset="50%" stopColor="#3b82f6" stopOpacity="0.6" />
            <stop offset="100%" stopColor="#8b5cf6" stopOpacity="0.4" />
          </linearGradient>

          {/* Filtros avanzados */}
          <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
            <feMerge> 
              <feMergeNode in="coloredBlur"/>
              <feMergeNode in="SourceGraphic"/>
            </feMerge>
          </filter>

          <filter id="innerShadow" x="-50%" y="-50%" width="200%" height="200%">
            <feOffset dx="0" dy="2"/>
            <feGaussianBlur stdDeviation="2" result="offset-blur"/>
            <feFlood floodColor="#000000" floodOpacity="0.2"/>
            <feComposite in2="offset-blur" operator="in"/>
            <feMerge> 
              <feMergeNode/>
              <feMergeNode in="SourceGraphic"/>
            </feMerge>
          </filter>

          <filter id="dropShadow" x="-50%" y="-50%" width="200%" height="200%">
            <feDropShadow dx="0" dy="4" stdDeviation="6" floodColor="#000000" floodOpacity="0.25"/>
          </filter>
        </defs>

        {/* Círculo exterior con efecto de brillo */}
        <circle
          cx="60"
          cy="60"
          r="55"
          fill="url(#primaryGradient)"
          filter="url(#dropShadow)"
          className={animated ? 'animate-pulse' : ''}
        />

        {/* Anillo interior decorativo */}
        <circle
          cx="60"
          cy="60"
          r="48"
          fill="none"
          stroke="url(#glowGradient)"
          strokeWidth="1"
          opacity="0.6"
        />

        {/* Símbolo principal: Lotus + Cerebro + Corazón */}
        <g transform="translate(60, 60)">
          {/* Base tipo lotus - representa crecimiento y transformación */}
          <g opacity="0.9">
            {/* Pétalos exteriores */}
            <path
              d="M 0 -35 Q -15 -25 -25 -10 Q -15 5 0 -5 Q 15 5 25 -10 Q 15 -25 0 -35 Z"
              fill="url(#innerGradient)"
              opacity="0.8"
              filter="url(#innerShadow)"
            />
            <path
              d="M -35 0 Q -25 -15 -10 -25 Q 5 -15 -5 0 Q 5 15 -10 25 Q -25 15 -35 0 Z"
              fill="url(#innerGradient)"
              opacity="0.7"
              filter="url(#innerShadow)"
            />
            <path
              d="M 35 0 Q 25 -15 10 -25 Q -5 -15 5 0 Q -5 15 10 25 Q 25 15 35 0 Z"
              fill="url(#innerGradient)"
              opacity="0.7"
              filter="url(#innerShadow)"
            />
            <path
              d="M 0 35 Q -15 25 -25 10 Q -15 -5 0 5 Q 15 -5 25 10 Q 15 25 0 35 Z"
              fill="url(#innerGradient)"
              opacity="0.8"
              filter="url(#innerShadow)"
            />
          </g>

          {/* Ondas cerebrales estilizadas - representa la mente */}
          <g opacity="0.9" filter="url(#glow)">
            <path
              d="M -20 -8 Q -15 -15 -10 -8 Q -5 -1 0 -8 Q 5 -15 10 -8 Q 15 -1 20 -8"
              stroke="url(#innerGradient)"
              strokeWidth="2.5"
              fill="none"
              strokeLinecap="round"
            />
            <path
              d="M -18 -18 Q -12 -25 -6 -18 Q 0 -11 6 -18 Q 12 -25 18 -18"
              stroke="url(#innerGradient)"
              strokeWidth="2"
              fill="none"
              strokeLinecap="round"
              opacity="0.8"
            />
            <path
              d="M -15 -28 Q -8 -35 0 -28 Q 8 -21 15 -28"
              stroke="url(#innerGradient)"
              strokeWidth="1.5"
              fill="none"
              strokeLinecap="round"
              opacity="0.6"
            />
          </g>

          {/* Corazón central estilizado - representa las emociones */}
          <g transform="scale(0.8)">
            <path
              d="M 0 8 C -12 -4 -20 -4 -20 8 C -20 20 0 35 0 35 C 0 35 20 20 20 8 C 20 -4 12 -4 0 8 Z"
              fill="url(#innerGradient)"
              filter="url(#glow)"
              opacity="0.95"
            />
            {/* Detalle interno del corazón */}
            <path
              d="M 0 12 C -8 2 -14 2 -14 12 C -14 18 0 28 0 28 C 0 28 14 18 14 12 C 14 2 8 2 0 12 Z"
              fill="url(#glowGradient)"
              opacity="0.6"
            />
          </g>

          {/* Puntos de energía orbitales - representan el crecimiento dinámico */}
          <g opacity="0.8">
            <circle cx="-30" cy="-15" r="2.5" fill="url(#innerGradient)">
              {animated && (
                <animateTransform
                  attributeName="transform"
                  type="rotate"
                  values="0 0 0;360 0 0"
                  dur="8s"
                  repeatCount="indefinite"
                />
              )}
            </circle>
            <circle cx="30" cy="-15" r="2.5" fill="url(#innerGradient)">
              {animated && (
                <animateTransform
                  attributeName="transform"
                  type="rotate"
                  values="120 0 0;480 0 0"
                  dur="8s"
                  repeatCount="indefinite"
                />
              )}
            </circle>
            <circle cx="0" cy="-35" r="2.5" fill="url(#innerGradient)">
              {animated && (
                <animateTransform
                  attributeName="transform"
                  type="rotate"
                  values="240 0 0;600 0 0"
                  dur="8s"
                  repeatCount="indefinite"
                />
              )}
            </circle>
            <circle cx="-25" cy="25" r="2" fill="url(#innerGradient)" opacity="0.7">
              {animated && (
                <animate attributeName="opacity" values="0.7;1;0.7" dur="3s" repeatCount="indefinite" />
              )}
            </circle>
            <circle cx="25" cy="25" r="2" fill="url(#innerGradient)" opacity="0.7">
              {animated && (
                <animate attributeName="opacity" values="0.7;1;0.7" dur="3s" repeatCount="indefinite" begin="1s" />
              )}
            </circle>
          </g>

          {/* Líneas de conexión sutiles - representan la interconexión */}
          <g opacity="0.3" stroke="url(#innerGradient)" strokeWidth="0.5" fill="none">
            <path d="M -15 -15 Q 0 -5 15 -15" />
            <path d="M -15 15 Q 0 5 15 15" />
            <path d="M -15 -15 Q -5 0 -15 15" />
            <path d="M 15 -15 Q 5 0 15 15" />
          </g>
        </g>

        {/* Efecto de brillo exterior */}
        {animated && (
          <circle
            cx="60"
            cy="60"
            r="58"
            fill="none"
            stroke="url(#glowGradient)"
            strokeWidth="2"
            opacity="0.4"
          >
            <animate attributeName="stroke-width" values="2;4;2" dur="4s" repeatCount="indefinite" />
            <animate attributeName="opacity" values="0.4;0.8;0.4" dur="4s" repeatCount="indefinite" />
          </circle>
        )}
      </svg>
    </div>
  );

  const LogoText = () => (
    <span className={`${textSizes[size]} font-bold bg-gradient-to-r from-emerald-600 via-blue-600 to-purple-600 bg-clip-text text-transparent tracking-wide`}>
      ComunicaTech
    </span>
  );

  if (variant === 'icon') {
    return <LogoIcon />;
  }

  if (variant === 'text') {
    return <LogoText />;
  }

  return (
    <div className={`flex items-center space-x-3 ${className}`}>
      <LogoIcon />
      <LogoText />
    </div>
  );
}