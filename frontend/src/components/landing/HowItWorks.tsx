import React from 'react';
import { 
  UserCheck, 
  MessageSquare, 
  BookOpen, 
  TrendingUp, 
  Database, 
  Shield 
} from 'lucide-react'; // Importamos íconos relevantes para ComunicaTech
import { useTheme } from '../../context/ThemeContext';

const steps = [
  {
    icon: UserCheck,
    title: '1. Registro de Usuario y Roles',
    description: 'Inicia sesión con tu rol (Usuario o Administrador). La plataforma asigna automáticamente tus lecciones diarias.',
    color: 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400',
    gradient: 'from-indigo-500 to-blue-500'
  },
  {
    icon: MessageSquare,
    title: '2. Comunicación con Tarjetas PECS',
    description: 'Interactúa con tarjetas visuales (PECS). Toca la tarjeta para escuchar su frase clave y su audio multilingüe.',
    color: 'bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400',
    gradient: 'from-purple-500 to-pink-500'
  },
  {
    icon: BookOpen,
    title: '3. Acceso a Lecciones Diarias',
    description: 'Completa lecciones diarias personalizadas. El contenido es guiado y se adapta a tus necesidades de aprendizaje.',
    color: 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400',
    gradient: 'from-emerald-500 to-green-500'
  },
  {
    icon: TrendingUp,
    title: '4. Seguimiento de Progreso',
    description: 'Registramos automáticamente el uso de tarjetas y las lecciones completadas para medir tu avance en el tiempo.',
    color: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400',
    gradient: 'from-yellow-500 to-orange-500'
  },
  {
    icon: Database,
    title: '5. Gestión Centralizada (Admin)',
    description: 'Los terapeutas y administradores gestionan contenido: crean tarjetas, suben audios, y revisan el progreso de los usuarios.',
    color: 'bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400',
    gradient: 'from-red-500 to-orange-500'
  },
  {
    icon: Shield,
    title: '6. Seguridad y Adaptabilidad',
    description: 'El sistema utiliza Arquitectura Limpia y roles de acceso (middleware) para garantizar la seguridad y fácil escalabilidad.',
    color: 'bg-teal-100 dark:bg-teal-900 text-teal-600 dark:text-teal-400',
    gradient: 'from-teal-500 to-blue-500'
  },
];

export default function HowItWorks() {
  const { theme } = useTheme();

  // Usamos una imagen que evoque conexión o tecnología.
  const backgroundImageUrl = 'https://i.imgur.com/m2Lj5VO.png';

  return (
    <section id="how-it-works" className={`py-12 sm:py-16 lg:py-20 transition-all duration-700 relative overflow-hidden ${
      theme === 'light'
        ? 'bg-white' // Se ajusta para que el fondo se vea bien
        : 'bg-gray-900' // Fondo oscuro
    }`}>
      
      {/* 🖼️ IMAGEN DE FONDO APLICADA A TODA LA SECCIÓN */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center"
          style={{
            backgroundImage: `url('${backgroundImageUrl}')`,
            // Baja opacidad para que sea un fondo sutil
            opacity: theme === 'light' ? 0.05 : 0.1
          }}
        />
        {/* Overlay de color oscuro/claro para asegurar la legibilidad del texto */}
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-white/85' // Blanco semi-transparente en modo claro
            : 'bg-gray-900/85' // Oscuro semi-transparente en modo oscuro
        }`} />
      </div>
      
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-8 sm:mb-12 lg:mb-16">
          <h2 className={`text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold mb-3 sm:mb-4 transition-all duration-700 transform hover:scale-105 ${
            theme === 'light' ? 'text-slate-800' : 'text-white' // Título en blanco para modo oscuro
          }`}>
            Cómo funciona ComunicaTech
          </h2>
          <p className={`max-w-3xl mx-auto text-sm sm:text-base lg:text-xl transition-all duration-500 ${
            theme === 'light' ? 'text-slate-600' : 'text-gray-300' // Subtítulo en gris claro para modo oscuro
          }`}>
            Una plataforma diseñada para promover la autonomía, el aprendizaje y la gestión eficiente de contenidos.
          </p>
        </div>

        <div className="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
          {steps.map((step, index) => (
            <div key={index} className="relative group perspective-1000 text-center">
              {/* Card with 3D effect */}
              <div className={`rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-2xl transition-all duration-700 hover:-translate-y-4 border h-full transform hover:rotate-y-5 hover:rotate-z-2 ${
                theme === 'light'
                  ? 'bg-white/90 border-white/60 backdrop-blur-sm'
                  : 'bg-gray-800/90 dark:border-gray-700' // Fondo de tarjeta oscuro/semitransparente
              }`}>
                
                {/* Step number with enhanced animation (Ajustado a la izquierda) */}
                <div className="absolute -top-4 -left-4 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-xl transform group-hover:scale-125 group-hover:rotate-12 transition-all duration-500">
                  {index + 1}
                </div>

                {/* Icon with enhanced animations */}
                <div className={`inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-2xl ${step.color} mb-4 sm:mb-6 group-hover:scale-125 transition-all duration-500 shadow-lg group-hover:shadow-2xl`}>
                  <step.icon className="w-6 h-6 sm:w-8 sm:h-8 group-hover:animate-pulse" />
                </div>

                {/* Content with enhanced hover effects */}
                <h3 className={`text-base sm:text-lg lg:text-xl font-bold mb-2 sm:mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-400 group-hover:to-purple-400 transition-all duration-500 transform group-hover:scale-105 ${
                  theme === 'light' ? 'text-slate-800' : 'text-white' // Título de tarjeta en blanco
                }`}>
                  {step.title}
                </h3>
                
                <p className={`text-xs sm:text-sm leading-relaxed transition-all duration-500 group-hover:text-opacity-90 ${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-300' // Descripción en gris claro
                }`}>
                  {step.description}
                </p>

                {/* Enhanced decorative gradient line */}
                <div className={`mt-4 sm:mt-6 h-1 w-0 group-hover:w-full bg-gradient-to-r ${step.gradient} rounded-full transition-all duration-700 shadow-lg mx-auto`}></div>
              </div>

              {/* Connection line for larger screens (Mantener si es necesario) */}
              {index < steps.length - 1 && (
                <div className="hidden lg:block absolute top-1/2 -right-4 w-8 h-0.5 bg-gradient-to-r from-indigo-300 to-purple-300 dark:from-indigo-600 dark:to-purple-600 transform group-hover:scale-x-125 group-hover:translate-y-2 transition-all duration-500"></div>
              )}
            </div>
          ))}
        </div>

        {/* Bottom CTA (Ajustado para ComunicaTech) */}
        <div className="mt-12 sm:mt-16 text-center">
          <div className="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden transform hover:scale-105 transition-all duration-500 hover:shadow-2xl">
            
            {/* Background image/overlay for CTA */}
            <div className="absolute inset-0 z-0">
              <div 
                className="absolute inset-0 w-full h-full bg-cover bg-center opacity-10"
                style={{
                  backgroundImage: `url('${backgroundImageUrl}')`
                }}
              />
              <div className="absolute inset-0 bg-gradient-to-r from-indigo-600/95 to-blue-600/95" />
            </div>
            
            {/* Enhanced floating particles (Mantener efecto si te gusta) */}
            <div className="absolute inset-0 overflow-hidden">
              {[...Array(20)].map((_, i) => (
                <div
                  key={i}
                  className="absolute w-1 h-1 sm:w-2 sm:h-2 bg-white/20 rounded-full"
                  style={{
                    left: `${Math.random() * 100}%`,
                    top: `${Math.random() * 100}%`,
                    animation: `float-particle ${3 + Math.random() * 4}s ease-in-out infinite`,
                    animationDelay: `${Math.random() * 3}s`
                  }}
                />
              ))}
            </div>
            
            <div className="relative z-10">
              <h3 className="text-xl sm:text-2xl font-bold mb-3 sm:mb-4 animate-pulse">
                ComunicaTech: El puente digital para conectar el mundo.
              </h3>
              <p className="text-sm sm:text-lg opacity-90 mb-4 sm:mb-6 max-w-2xl mx-auto">
                Diseñado con tecnología de vanguardia y centrado en la autonomía y el aprendizaje inclusivo.
              </p>
              <button className="bg-white text-indigo-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-110 hover:-translate-y-1 text-sm sm:text-base">
                Explorar Tarjetas Interactivas
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Enhanced CSS animations (Mantenido) */}
      <style>{`
        .perspective-1000 {
          perspective: 1000px;
        }
        
        .hover\\:rotate-y-5:hover {
          transform: rotateY(5deg);
        }
        
        .hover\\:rotate-z-2:hover {
          transform: rotateZ(2deg);
        }
        
        @keyframes float-particle {
          0%, 100% { 
            transform: translateY(0px) rotate(0deg) scale(1); 
            opacity: 0.2;
          }
          50% { 
            transform: translateY(-30px) rotate(180deg) scale(1.5); 
            opacity: 0.8;
          }
        }
      `}</style>
    </section>
  );
}