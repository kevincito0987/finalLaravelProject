import React from 'react';
import { Heart, MessageCircle, TrendingUp, Shield, Users, Mic, Brain, Calendar, Zap } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';


const features = [
  {
    icon: Mic,
    title: 'Check-in Emocional por Voz',
    description: 'Habla con tu compañero emocional IA. Te ayuda a nombrar emociones y crear tu bitácora personal de bienestar.',
    color: 'from-emerald-500 to-green-500'
  },
  {
    icon: Brain,
    title: 'Conversación IA Personalizada',
    description: 'Avatar IA que se adapta a tu patrón emocional. Bienvenidas matutinas personalizadas con tono empático.',
    color: 'from-blue-500 to-cyan-500'
  },
  {
    icon: Calendar,
    title: 'Mini Prácticas de Autocuidado',
    description: 'Rutinas simples diarias: respiración 4x4, gratitud, caminatas mindful. Todo guiado por voz.',
    color: 'from-purple-500 to-pink-500'
  },
  {
    icon: TrendingUp,
    title: 'Seguimiento de Hábitos Saludables',
    description: 'Visualiza tu progreso en sueño, alimentación, tiempo al aire libre y contacto social.',
    color: 'from-orange-500 to-red-500'
  },
  {
    icon: Zap,
    title: 'Alertas Inteligentes',
    description: 'Análisis automático de patrones emocionales con recomendaciones proactivas de bienestar.',
    color: 'from-yellow-500 to-orange-500'
  },
  {
    icon: Users,
    title: 'Red de Apoyo y Comunidad',
    description: 'Conecta con grupos de pares, historias reales de bienestar y acceso a profesionales certificados.',
    color: 'from-teal-500 to-blue-500'
  },
];

export default function Features() {
  const { theme } = useTheme();

  return (
    <section id="features" className={`py-12 sm:py-16 lg:py-20 transition-all duration-700 ease-out relative ${
      theme === 'light'
        ? 'bg-white'
        : 'bg-white dark:bg-gray-900'
    }`}>
      {/* Background landscape image with reduced opacity */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
          style={{
            backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
          }}
        />
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-white/95'
            : 'bg-white/95 dark:bg-gray-900/95'
        }`} />
      </div>
      
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-8 sm:mb-12 lg:mb-16 animate-fade-in-up">
          <h2 className={`text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold mb-3 sm:mb-4 transition-all duration-700 transform hover:scale-105 ${
            theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
          }`}>
            Todo lo que necesitas para tu bienestar diario
          </h2>
          <p className={`max-w-3xl mx-auto text-sm sm:text-base lg:text-xl transition-all duration-500 ${
            theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
          }`}>
            Herramientas diseñadas por expertos en bienestar, potenciadas por IA conversacional 
            para acompañarte en tu crecimiento personal.
          </p>
        </div>

        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {features.map((feature, index) => (
            <div
              key={index}
              className={`group relative p-4 sm:p-6 lg:p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-700 hover:-translate-y-4 hover:scale-105 border animate-fade-in-up transform hover:rotate-1 text-center ${
                theme === 'light'
                  ? 'bg-white/90 border-white/60 backdrop-blur-sm hover:bg-white/95'
                  : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'
              }`}
              style={{ animationDelay: `${index * 0.15}s` }}
            >
              {/* Gradient background on hover */}
              <div className={`absolute inset-0 bg-gradient-to-r ${feature.color} rounded-2xl opacity-0 group-hover:opacity-10 transition-all duration-700`} />
              
              {/* Floating particles effect */}
              <div className="absolute inset-0 overflow-hidden rounded-2xl">
                {[...Array(5)].map((_, i) => (
                  <div
                    key={i}
                    className="absolute w-1 h-1 bg-emerald-400 rounded-full opacity-0 group-hover:opacity-60 transition-all duration-1000"
                    style={{
                      left: `${20 + i * 15}%`,
                      top: `${20 + i * 10}%`,
                      animationDelay: `${i * 0.2}s`
                    }}
                  />
                ))}
              </div>
              
              <div className="relative z-10">
                {/* Icon with enhanced animations */}
                <div className={`inline-flex items-center justify-center p-3 sm:p-4 bg-gradient-to-r ${feature.color} rounded-xl mb-4 sm:mb-6 shadow-lg group-hover:scale-125 group-hover:rotate-12 transition-all duration-500 group-hover:shadow-2xl`}>
                  <feature.icon className="w-6 h-6 sm:w-8 sm:h-8 text-white group-hover:animate-pulse" />
                </div>
                
                <h3 className={`text-base sm:text-lg lg:text-xl font-bold mb-2 sm:mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-emerald-600 group-hover:to-blue-600 transition-all duration-500 transform group-hover:scale-105 ${
                  theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
                }`}>
                  {feature.title}
                </h3>
                
                <p className={`text-xs sm:text-sm leading-relaxed transition-all duration-500 group-hover:text-opacity-90 ${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
                }`}>
                  {feature.description}
                </p>

                {/* Decorative elements */}
                <div className="absolute top-4 right-4 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-emerald-400 rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-ping transition-all duration-500"></div>
                <div className="absolute bottom-4 left-4 w-1 h-1 sm:w-1.5 sm:h-1.5 bg-blue-400 rounded-full opacity-0 group-hover:opacity-100 group-hover:animate-bounce transition-all duration-700" style={{ animationDelay: '0.2s' }}></div>
              </div>
            </div>
          ))}
        </div>

        {/* Technology Integration Section */}
        <div className={`mt-12 sm:mt-16 lg:mt-20 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 xl:p-12 transition-all duration-700 relative overflow-hidden hover:shadow-2xl animate-fade-in-up transform hover:scale-102 ${
          theme === 'light'
            ? 'bg-gradient-to-r from-blue-50 to-purple-50'
            : 'bg-gradient-to-r from-gray-800 to-gray-700'
        }`} style={{ animationDelay: '0.8s' }}>
          {/* Background landscape image with reduced opacity */}
          <div className="absolute inset-0 z-0">
            <div 
              className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
              style={{
                backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
              }}
            />
            <div className={`absolute inset-0 ${
              theme === 'light'
                ? 'bg-gradient-to-r from-blue-50/95 to-purple-50/95'
                : 'bg-gradient-to-r from-gray-800/95 to-gray-700/95'
            }`} />
          </div>
          
          <div className="relative z-10">
            <div className="text-center mb-6 sm:mb-8">
              <h3 className={`text-xl sm:text-2xl font-bold mb-2 sm:mb-4 transition-all duration-500 hover:scale-105 ${
                theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
              }`}>
                Potenciado por tecnología de vanguardia
              </h3>
              <p className={`max-w-2xl mx-auto text-xs sm:text-sm lg:text-base transition-all duration-500 ${
                theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
              }`}>
                Integramos las mejores herramientas de IA para crear una experiencia de bienestar única y personalizada.
              </p>
            </div>
            
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
              {[
                { name: 'ElevenLabs', desc: 'IA de Voz', icon: '🎙️' },
                { name: 'Tavus', desc: 'Avatar IA', icon: '🤖' },
                { name: 'RevenueCat', desc: 'Suscripciones', icon: '💎' },
                { name: 'Algorand', desc: 'Blockchain', icon: '🔗' }
              ].map((tech, index) => (
                <div 
                  key={index} 
                  className={`text-center p-3 sm:p-4 rounded-xl shadow-sm transition-all duration-500 hover:scale-110 hover:shadow-xl hover:-translate-y-2 animate-fade-in-up group hover:rotate-3 ${
                    theme === 'light'
                      ? 'bg-white/90 backdrop-blur-sm hover:bg-white'
                      : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700'
                  }`}
                  style={{ animationDelay: `${0.9 + index * 0.1}s` }}
                >
                  <div className="text-xl sm:text-2xl lg:text-3xl mb-1 sm:mb-2 group-hover:animate-bounce group-hover:scale-125 transition-all duration-300">{tech.icon}</div>
                  <div className={`font-semibold text-xs sm:text-sm transition-all duration-300 group-hover:text-emerald-600 ${
                    theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
                  }`}>{tech.name}</div>
                  <div className={`text-xs transition-all duration-300 ${
                    theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-400'
                  }`}>{tech.desc}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Enhanced CSS animations */}
      <style jsx>{`
        @keyframes fade-in-up {
          from {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
          }
          to {
            opacity: 1;
            transform: translateY(0) scale(1);
          }
        }
        
        .animate-fade-in-up {
          animation: fade-in-up 0.8s ease-out forwards;
          opacity: 0;
        }

        .hover\\:scale-102:hover {
          transform: scale(1.02);
        }
      `}</style>
    </section>
  );
}