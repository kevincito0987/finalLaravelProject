import React from 'react';
import { Heart, TrendingUp, MessageCircle, Target, Calendar, Award, Loader, UserCheck, Zap, Shield, Users } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';

const steps = [
  {
    icon: UserCheck,
    title: 'Verificación de Red de Apoyo',
    description: 'Confirma que tienes al menos una persona de apoyo en tus contactos de emergencia. Tu bienestar es nuestra prioridad.',
    color: 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400',
    gradient: 'from-blue-500 to-cyan-500'
  },
  {
    icon: Heart,
    title: 'Bienvenida Personalizada',
    description: 'Cada mañana, tu avatar IA te saluda con un mensaje adaptado a tu patrón emocional de los últimos días.',
    color: 'bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400',
    gradient: 'from-emerald-500 to-green-500'
  },
  {
    icon: TrendingUp,
    title: 'Check-in Emocional por Voz',
    description: 'Habla con tu compañero emocional sobre cómo te sientes. La IA te ayuda a nombrar emociones sin juzgar.',
    color: 'bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400',
    gradient: 'from-purple-500 to-pink-500'
  },
  {
    icon: Zap,
    title: 'Prácticas de Autocuidado',
    description: 'Elige una práctica diaria: respiración, gratitud, caminata mindful. Todo guiado por voz, sin complicaciones.',
    color: 'bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400',
    gradient: 'from-orange-500 to-red-500'
  },
  {
    icon: Shield,
    title: 'Seguimiento Inteligente',
    description: 'Registra hábitos saludables y recibe alertas automáticas si detectamos patrones que requieren atención.',
    color: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400',
    gradient: 'from-yellow-500 to-orange-500'
  },
  {
    icon: Users,
    title: 'Conecta y Crece',
    description: 'Únete a comunidades de bienestar, comparte historias reales y accede a profesionales certificados.',
    color: 'bg-teal-100 dark:bg-teal-900 text-teal-600 dark:text-teal-400',
    gradient: 'from-teal-500 to-blue-500'
  },
];

export default function HowItWorks() {
  const { theme } = useTheme();

  return (
    <section id="how-it-works" className={`py-12 sm:py-16 lg:py-20 transition-all duration-700 relative ${
      theme === 'light'
        ? 'bg-gradient-to-br from-blue-50 via-blue-100 to-purple-100'
        : 'bg-gray-50 dark:bg-gray-800'
    }`}>
      {/* Background landscape image */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-10"
          style={{
            backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
          }}
        />
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-gradient-to-br from-blue-50/90 via-blue-100/85 to-purple-100/90'
            : 'bg-gradient-to-br from-gray-50/90 to-gray-800/90 dark:from-gray-800/90 dark:to-gray-900/90'
        }`} />
      </div>
      
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-8 sm:mb-12 lg:mb-16">
          <h2 className={`text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold mb-3 sm:mb-4 transition-all duration-700 transform hover:scale-105 ${
            theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
          }`}>
            Cómo funciona Habita
          </h2>
          <p className={`max-w-3xl mx-auto text-sm sm:text-base lg:text-xl transition-all duration-500 ${
            theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
          }`}>
            Tu camino hacia el bienestar emocional en seis pasos simples, 
            diseñados para acompañarte sin ser invasivos.
          </p>
        </div>

        <div className="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-2 lg:grid-cols-3">
          {steps.map((step, index) => (
            <div key={index} className="relative group perspective-1000 text-center">
              {/* Card with 3D effect */}
              <div className={`rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-2xl transition-all duration-700 hover:-translate-y-4 border h-full transform hover:rotate-y-5 hover:rotate-z-2 ${
                theme === 'light'
                  ? 'bg-white/90 border-white/60 backdrop-blur-sm'
                  : 'bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700'
              }`}>
                {/* Step number with enhanced animation */}
                <div className="absolute -top-4 -left-4 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-xl transform group-hover:scale-125 group-hover:rotate-12 transition-all duration-500">
                  {index + 1}
                </div>

                {/* Icon with enhanced animations */}
                <div className={`inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-2xl ${step.color} mb-4 sm:mb-6 group-hover:scale-125 transition-all duration-500 shadow-lg group-hover:shadow-2xl`}>
                  <step.icon className="w-6 h-6 sm:w-8 sm:h-8 group-hover:animate-pulse" />
                </div>

                {/* Content with enhanced hover effects */}
                <h3 className={`text-base sm:text-lg lg:text-xl font-bold mb-2 sm:mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-emerald-600 group-hover:to-blue-600 transition-all duration-500 transform group-hover:scale-105 ${
                  theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
                }`}>
                  {step.title}
                </h3>
                
                <p className={`text-xs sm:text-sm leading-relaxed transition-all duration-500 group-hover:text-opacity-90 ${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
                }`}>
                  {step.description}
                </p>

                {/* Enhanced decorative gradient line */}
                <div className={`mt-4 sm:mt-6 h-1 w-0 group-hover:w-full bg-gradient-to-r ${step.gradient} rounded-full transition-all duration-700 shadow-lg mx-auto`}></div>
              </div>

              {/* Connection line for larger screens with enhanced animation */}
              {index < steps.length - 1 && (
                <div className="hidden lg:block absolute top-1/2 -right-4 w-8 h-0.5 bg-gradient-to-r from-emerald-300 to-blue-300 dark:from-emerald-600 dark:to-blue-600 transform group-hover:scale-x-125 group-hover:translate-y-2 transition-all duration-500"></div>
              )}
            </div>
          ))}
        </div>

        {/* Bottom CTA with enhanced effects */}
        <div className="mt-12 sm:mt-16 text-center">
          <div className="bg-gradient-to-r from-emerald-500 to-blue-500 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden transform hover:scale-105 transition-all duration-500 hover:shadow-2xl">
            {/* Background landscape image */}
            <div className="absolute inset-0 z-0">
              <div 
                className="absolute inset-0 w-full h-full bg-cover bg-center opacity-10"
                style={{
                  backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
                }}
              />
              <div className="absolute inset-0 bg-gradient-to-r from-emerald-500/95 to-blue-500/95" />
            </div>
            
            {/* Enhanced floating particles */}
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
                ¿Listo para comenzar tu viaje de bienestar?
              </h3>
              <p className="text-sm sm:text-lg opacity-90 mb-4 sm:mb-6 max-w-2xl mx-auto">
                Únete a miles de personas que ya están transformando su bienestar emocional 
                con Habita. Es gratis para empezar.
              </p>
              <button className="bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:scale-110 hover:-translate-y-1 text-sm sm:text-base">
                Comenzar ahora - Es gratis
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Enhanced CSS animations */}
      <style jsx>{`
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