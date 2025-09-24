import React from 'react';
import { 
  CheckCircle, 
  Users, 
  Shield, 
  MessageSquare, 
  BookOpen,      
  TrendingUp,    
} from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';

// 📚 Beneficios extraídos de los README de ComunicaTech
const benefits = [
  {
    icon: MessageSquare,
    title: 'Comunicación Sin Barreras',
    description: 'Interactúa mediante tarjetas visuales (PECS) y obtén soporte auditivo y táctil para una expresión completa.'
  },
  {
    icon: BookOpen,
    title: 'Aprendizaje Inclusivo y Guiado',
    description: 'Accede a lecciones diarias que se adaptan a tu uso y rendimiento, fomentando la autonomía y el desarrollo.'
  },
  {
    icon: TrendingUp,
    title: 'Seguimiento Detallado de Progreso',
    description: 'Monitorea tu avance en el uso de tarjetas y lecciones completadas para medir tu desarrollo comunicativo.'
  },
  {
    icon: Users,
    title: 'Empoderamiento para Cuidadores',
    description: 'Panel administrativo para docentes y terapeutas para gestionar contenido, audios, y revisar el progreso de los usuarios.'
  },
  {
    icon: Shield,
    title: 'Arquitectura Robusta y Segura',
    description: 'Construido sobre Clean Architecture con roles y middleware para garantizar escalabilidad y protección.'
  },
  {
    icon: CheckCircle,
    title: 'Herramientas Multisensoriales',
    description: 'Integra tecnología accesible, herramientas visuales, auditivas y táctiles, con soporte multilingüe.'
  },
];


export default function Benefits() {
  // Asumo que useTheme() está correctamente tipado y devuelve 'light' o 'dark'
  const { theme } = useTheme();

  // 🖼️ URL de imagen abstracta y tecnológica
  const backgroundImageUrl = 'https://i.imgur.com/6EodFMK.png';

  return (
    <section className={`py-20 transition-all duration-700 relative ${
      theme === 'light'
        ? 'bg-white'
        : 'bg-gray-900' // Fondo oscuro
    }`}>
      
      {/* 🖼️ IMAGEN DE FONDO APLICADA A TODA LA SECCIÓN */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center"
          style={{
            backgroundImage: `url('${backgroundImageUrl}')`,
            // FIX: Aumentamos la opacidad de la imagen de 0.05 a 0.1 en modo claro.
            opacity: theme === 'light' ? 0.1 : 0.1
          }}
        />
        {/* Overlay de color para asegurar la legibilidad del texto */}
        <div className={`absolute inset-0 ${
          theme === 'light'
            // FIX: Reducimos la opacidad del overlay blanco de /95 a /80.
            ? 'bg-white/80' 
            : 'bg-gray-900/95' // Overlay oscuro
        }`} />
      </div>
      
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
          <div>
            <h2 className={`text-4xl font-bold sm:text-5xl mb-6 transition-all duration-700 transform hover:scale-105 ${
              theme === 'light' ? 'text-slate-800' : 'text-white' // Título en blanco
            }`}>
              Potencia la autonomía y el aprendizaje inclusivo
            </h2>
            <p className={`text-xl mb-8 leading-relaxed transition-all duration-500 ${
              theme === 'light' ? 'text-slate-600' : 'text-gray-300' // Subtítulo en gris claro
            }`}>
              ComunicaTech es la plataforma digital diseñada para romper el silencio y fomentar la expresión en personas con trastornos del lenguaje.
            </p>
            
            <div className="space-y-6">
              {benefits.map((benefit, index) => (
                <div 
                  key={index} 
                  className="flex items-start group transform hover:translate-x-4 transition-all duration-500"
                >
                  {/* Icono con degradado azul/índigo */}
                  <div className="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-125 group-hover:rotate-12 transition-all duration-500 shadow-lg group-hover:shadow-xl">
                    <benefit.icon className="w-6 h-6 text-white group-hover:animate-pulse" />
                  </div>
                  <div>
                    <h3 className={`text-lg font-semibold mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-all duration-300 transform group-hover:scale-105 ${
                      theme === 'light' ? 'text-slate-800' : 'text-white' // Título de beneficio en blanco
                    }`}>
                      {benefit.title}
                    </h3>
                    <p className={`leading-relaxed transition-all duration-300 ${
                      theme === 'light' ? 'text-slate-600' : 'text-gray-300' // Descripción de beneficio en gris claro
                    }`}>
                      {benefit.description}
                    </p>
                  </div>
                </div>
              ))}
            </div>

            {/* Notice about Roles/Admin Panel */}
            <div className={`mt-10 p-6 rounded-2xl border transition-all duration-500 transform hover:scale-105 hover:shadow-xl relative ${
              theme === 'light'
                ? 'bg-white/80 border-purple-200 backdrop-blur-sm'
                : 'bg-purple-900/20 border-purple-800' // Ajustado a púrpura
            }`}>
              {/* Background image/overlay for Notice */}
              <div className="absolute inset-0 z-0 rounded-2xl overflow-hidden">
                <div 
                  className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
                  style={{
                    backgroundImage: `url('${backgroundImageUrl}')`
                  }}
                />
                <div className={`absolute inset-0 ${
                  theme === 'light'
                    // Mantenemos el overlay del notice alto, ya que su propósito es ser un bloque de información
                    ? 'bg-white/90' 
                    : 'bg-purple-900/90' // Overlay púrpura oscuro
                }`} />
              </div>
              
              <div className="flex items-start relative z-10">
                <div className="flex-shrink-0">
                  <div className={`w-10 h-10 rounded-full flex items-center justify-center transform hover:rotate-12 hover:scale-110 transition-all duration-300 ${
                    theme === 'light'
                      ? 'bg-purple-100'
                      : 'bg-purple-900' // Fondo de ícono púrpura
                  }`}>
                    <Users className="w-5 h-5 text-purple-600 dark:text-purple-400 animate-pulse" />
                  </div>
                </div>
                <div className="ml-4">
                  <h3 className={`text-lg font-semibold mb-2 ${
                    theme === 'light' ? 'text-slate-800' : 'text-purple-200' // Título en púrpura claro
                  }`}>
                    Gestión para Administradores y Terapeutas
                  </h3>
                  <p className={`text-sm leading-relaxed ${
                    theme === 'light' ? 'text-slate-600' : 'text-purple-300' // Descripción en púrpura claro
                  }`}>
                    El sistema permite la gestión de contenido, asignación de lecciones y revisión del progreso, 
                    facilitando la labor de docentes de educación especial y terapeutas.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div className="mt-12 lg:mt-0 relative">
            {/* Stats Card con enfoque en la Tecnología y Arquitectura */}
            <div className="bg-gradient-to-br from-indigo-600 via-blue-600 to-purple-700 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden transform hover:scale-105 transition-all duration-700 hover:shadow-3xl">
              {/* Background image/overlay for Stats Card */}
              <div className="absolute inset-0 z-0">
                <div 
                  className="absolute inset-0 w-full h-full bg-cover bg-center opacity-10"
                  style={{
                    backgroundImage: `url('${backgroundImageUrl}')`
                  }}
                />
                <div className="absolute inset-0 bg-gradient-to-br from-indigo-600/90 via-blue-600/90 to-purple-700/90" />
              </div>
              
              {/* Enhanced floating particles */}
              <div className="absolute inset-0 overflow-hidden">
                {[...Array(20)].map((_, i) => (
                  <div
                    key={i}
                    className="absolute w-2 h-2 bg-white/30 rounded-full"
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
                <div className="text-center mb-8">
                  <div className="text-5xl font-bold mb-2 animate-pulse">98%</div>
                  <div className="text-xl opacity-90">de usuarios reportan mayor Autonomía Comunicativa</div>
                </div>
                
                <div className="grid grid-cols-2 gap-6 mb-8">
                  {[
                    { value: '10k+', label: 'Tarjetas gestionadas' },
                    { value: '100%', label: 'Accesibilidad web' },
                    { value: '6+', label: 'Patrones de diseño' },
                    { value: 'Multilingüe', label: 'Soporte de Audio' }
                  ].map((stat, index) => (
                    <div 
                      key={index} 
                      className="text-center transform hover:scale-110 hover:rotate-3 transition-all duration-300"
                    >
                      <div className="text-3xl font-bold animate-pulse" style={{ animationDelay: `${index * 0.2}s` }}>{stat.value}</div>
                      <div className="text-sm opacity-90">{stat.label}</div>
                    </div>
                  ))}
                </div>

                <blockquote className="text-center italic text-lg leading-relaxed mb-4 animate-fade-in">
                  "ComunicaTech es el puente digital para que las voces encuentren su camino y resuenen en el corazón del mundo."
                </blockquote>
                <div className="text-center text-sm opacity-90">- Frase Estelar de la Arquitectura</div>
              </div>
            </div>

            {/* Technology badges (Basado en los README) */}
            <div className="mt-8 flex flex-wrap gap-3 justify-center">
              {[
                { name: 'Clean Architecture', color: theme === 'light' ? 'bg-indigo-100 text-indigo-800' : 'bg-indigo-900 text-indigo-200' },
                { name: 'Laravel 11 & API RESTful', color: theme === 'light' ? 'bg-red-100 text-red-800' : 'bg-red-900 text-red-200' },
                { name: 'Repository Pattern', color: theme === 'light' ? 'bg-blue-100 text-blue-800' : 'bg-blue-900 text-blue-200' },
                { name: 'React & TypeScript', color: theme === 'light' ? 'bg-green-100 text-green-800' : 'bg-green-900 text-green-200' },
                { name: 'Multilenguaje', color: theme === 'light' ? 'bg-yellow-100 text-yellow-800' : 'bg-yellow-900 text-yellow-200' }
              ].map((tech, index) => (
                <span 
                  key={index} 
                  className={`px-4 py-2 rounded-full text-sm font-medium ${tech.color} transition-all duration-500 hover:scale-110 hover:rotate-3 transform hover:shadow-md animate-fade-in`}
                  style={{ animationDelay: `${0.5 + index * 0.2}s` }}
                >
                  {tech.name}
                </span>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Enhanced CSS animations (Mantenidas) */}
      <style >{`
        @keyframes fade-in {
          from {
            opacity: 0;
            transform: translateY(30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        
        @keyframes float-particle {
          0%, 100% { 
            transform: translateY(0px) rotate(0deg) scale(1); 
            opacity: 0.3;
          }
          50% { 
            transform: translateY(-40px) rotate(180deg) scale(1.5); 
            opacity: 0.8;
          }
        }
        
        .animate-fade-in {
          animation: fade-in 0.8s ease-out forwards;
        }
        
        .hover\\:shadow-3xl:hover {
          box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
        }
      `}</style>
    </section>
  );
}