import React from 'react';
import { 
  Volume2, 
  MessageSquare, 
  BookOpen, 
  Database, 
  Shield, 
  GitBranch, // Icono para Arquitectura Limpia
} from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';
import MyCustomIcon from '../ui/LaravelIcon'; 



const features = [
  {
    icon: MessageSquare,
    title: 'Tarjetas Interactivas (PECS)',
    description: 'CRUD completo para crear tarjetas con imagen, frase clave, traducción y audio, esencial para la comunicación alternativa.',
    color: 'from-indigo-500 to-blue-500' 
  },
  {
    icon: Volume2,
    title: 'Audio Multilingüe y Voz IA',
    description: 'Soporte para múltiples idiomas (Laravel Localization) con audios por frase e integración con ElevenLabs para voz natural.',
    color: 'from-purple-500 to-pink-500' 
  },
  {
    icon: BookOpen,
    title: 'Lecciones Personalizadas y Refuerzo',
    description: 'Módulo de lecciones diarias y de refuerzo, asignadas automáticamente y con registro de progreso del usuario.',
    color: 'from-green-500 to-teal-500' 
  },
  {
    icon: Database,
    title: 'Gestión Centralizada de Contenido',
    description: 'Panel administrativo para controlar usuarios, roles, tarjetas, audios y traducciones de forma eficiente vía API RESTful.',
    color: 'from-orange-500 to-red-500'
  },
  {
    icon: GitBranch,
    title: 'Arquitectura Escalable (Clean)',
    description: 'Implementación de Arquitectura Limpia (Clean Architecture) y patrones como Repository y Strategy para la robustez del Backend.',
    color: 'from-amber-500 to-yellow-500' // Nuevo color/icono para destacar la arquitectura
  },
  {
    icon: Shield,
    title: 'Roles de Acceso y Privacidad',
    description: 'Registro de usuarios con roles (Admin/Usuario), protección de rutas mediante Laravel middleware y FormRequest para validaciones.',
    color: 'from-teal-500 to-blue-500'
  },
];

export default function Features() {
  const { theme } = useTheme();

  // URL de la imagen de fondo (puedes reemplazarla con una tuya)
  // Utilizo una imagen que evoca el tema de la comunicación/educación digital
  const backgroundImageUrl = 'https://i.imgur.com/FWcU3LK.png';

  return (
    <section id="features" className={`py-12 sm:py-16 lg:py-20 transition-all duration-700 ease-out relative overflow-hidden ${
      theme === 'light'
        ? 'bg-white'
        : 'bg-gray-900' // Fondo oscuro en modo oscuro
    }`}>
      
      {/* 🖼️ IMAGEN DE FONDO APLICADA A TODA LA SECCIÓN */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center"
          style={{
            backgroundImage: `url('${backgroundImageUrl}')`,
            // 💡 Aplico un filtro oscuro fuerte (dark/navy overlay) para que el texto blanco sea legible
            // Y una baja opacidad para que sea sutil, como en tu ejemplo de Habita.
            opacity: theme === 'light' ? 0.05 : 0.15 
          }}
        />
        {/* Overlay de color oscuro/claro para asegurar la legibilidad del texto */}
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-white/80' // Blanco semi-transparente en modo claro
            : 'bg-[#151a24]/80' // Tono muy oscuro (navy/blackish) semi-transparente en modo oscuro
        }`} />
      </div>
      
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-8 sm:mb-12 lg:mb-16 animate-fade-in-up">
          <h2 className={`text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold mb-3 sm:mb-4 transition-all duration-700 transform hover:scale-105 ${
            theme === 'light' ? 'text-slate-800' : 'text-white' // Texto blanco en modo oscuro
          }`}>
            Funcionalidades Clave para la Inclusión
          </h2>
          <p className={`max-w-3xl mx-auto text-sm sm:text-base lg:text-xl transition-all duration-500 ${
            theme === 'light' ? 'text-slate-600' : 'text-gray-300' // Texto gris claro en modo oscuro
          }`}>
            Herramientas diseñadas para usuarios, terapeutas y cuidadores, centradas en la accesibilidad y la autonomía comunicativa.
          </p>
        </div>

        {/* 🧩 BLOQUE DE CARACTERÍSTICAS */}
        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {features.map((feature, index) => (
            <div
              key={index}
              className={`group relative p-4 sm:p-6 lg:p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-700 hover:-translate-y-4 hover:scale-105 border animate-fade-in-up transform hover:rotate-1 text-center ${
                theme === 'light'
                  ? 'bg-white/90 border-white/60 backdrop-blur-sm hover:bg-white/95'
                  : 'bg-gray-800/90 dark:border-gray-700 hover:bg-gray-700/90' // Fondo de tarjeta oscuro/semitransparente
              }`}
              style={{ animationDelay: `${index * 0.15}s` }}
            >
              {/* Gradient background on hover */}
              <div className={`absolute inset-0 bg-gradient-to-r ${feature.color} rounded-2xl opacity-0 group-hover:opacity-10 transition-all duration-700`} />
              
              <div className="relative z-10">
                {/* Icon with enhanced animations */}
                <div className={`inline-flex items-center justify-center p-3 sm:p-4 bg-gradient-to-r ${feature.color} rounded-xl mb-4 sm:mb-6 shadow-lg group-hover:scale-125 group-hover:rotate-12 transition-all duration-500 group-hover:shadow-2xl`}>
                  <feature.icon className="w-6 h-6 sm:w-8 sm:h-8 text-white group-hover:animate-pulse" />
                </div>
                
                <h3 className={`text-base sm:text-lg lg:text-xl font-bold mb-2 sm:mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-indigo-400 group-hover:to-purple-400 transition-all duration-500 transform group-hover:scale-105 ${
                  theme === 'light' ? 'text-slate-800' : 'text-white' // Texto blanco
                }`}>
                  {feature.title}
                </h3>
                
                <p className={`text-xs sm:text-sm leading-relaxed transition-all duration-500 group-hover:text-opacity-90 ${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-300' // Texto gris claro
                }`}>
                  {feature.description}
                </p>
              </div>
            </div>
          ))}
        </div>

        {/* 💻 Technology Integration Section - TECNOLOGÍAS ACTUALIZADAS */}
        <div className={`mt-12 sm:mt-16 lg:mt-20 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 xl:p-12 transition-all duration-700 relative overflow-hidden hover:shadow-2xl animate-fade-in-up transform hover:scale-102 ${
          theme === 'light'
            ? 'bg-gradient-to-r from-blue-50 to-purple-50'
            : 'bg-gradient-to-r from-gray-900/70 to-gray-800/70 backdrop-blur-sm border border-gray-700' // Fondo oscuro y borroso
        }`} style={{ animationDelay: '0.8s' }}>
          
          {/* Background overlay (Mantenido y adaptado al nuevo fondo de imagen) */}
          <div className="absolute inset-0 z-0">
            {/* Solo un degradado sutil de overlay, la imagen base está en el padre */}
            <div className={`absolute inset-0 ${
              theme === 'light'
                ? 'bg-gradient-to-r from-blue-50/95 to-purple-50/95'
                : 'bg-black/10' // Overlay aún más sutil en dark mode
            }`} />
          </div>
          
          <div className="relative z-10">
            <div className="text-center mb-6 sm:mb-8">
              <h3 className={`text-xl sm:text-2xl font-bold mb-2 sm:mb-4 transition-all duration-500 hover:scale-105 ${
                theme === 'light' ? 'text-slate-800' : 'text-white'
              }`}>
                Potenciado por tecnología de vanguardia
              </h3>
              <p className={`max-w-2xl mx-auto text-xs sm:text-sm lg:text-base transition-all duration-500 ${
                theme === 'light' ? 'text-slate-600' : 'text-gray-300'
              }`}>
                Integramos un stack moderno basado en React/TypeScript y Laravel 11 bajo una Arquitectura Limpia para máxima robustez y escalabilidad.
              </p>
            </div>
            
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
              {[
                // TECNOLOGÍAS CLAVE DEL PROYECTO
                { name: 'ElevenLabs', desc: 'Voz natural (TTS)', icon: '🎙️' }, 
                { name: 'React & TypeScript', desc: 'Frontend Robusto', icon: '⚛️' },
                { name: 'Laravel 11', desc: 'Backend (API RESTful)', icon: <MyCustomIcon className="w-10 h-10 mx-auto"/>  }, // Icono Server para BE
                { name: 'Clean Architecture', desc: 'Escalabilidad & Tests', icon: <GitBranch className="w-6 h-6 mx-auto" /> } // Icono GitBranch para arquitectura
              ].map((tech, index) => (
                <div 
                  key={index} 
                  className={`text-center p-3 sm:p-4 rounded-xl shadow-sm transition-all duration-500 hover:scale-110 hover:shadow-xl hover:-translate-y-2 animate-fade-in-up group hover:rotate-3 ${
                    theme === 'light'
                      ? 'bg-white/90 backdrop-blur-sm hover:bg-white'
                      : 'bg-gray-700/70 dark:hover:bg-gray-600/70 border border-gray-700' // Fondo de tecnología oscuro y borroso
                  }`}
                  style={{ animationDelay: `${0.9 + index * 0.1}s` }}
                >
                  <div className="text-xl sm:text-2xl lg:text-3xl mb-1 sm:mb-2 group-hover:animate-bounce group-hover:scale-125 transition-all duration-300">
                    {typeof tech.icon === 'string' ? tech.icon : tech.icon}
                  </div>
                  <div className={`font-semibold text-xs sm:text-sm transition-all duration-300 group-hover:text-indigo-400 ${
                    theme === 'light' ? 'text-slate-800' : 'text-white'
                  }`}>{tech.name}</div>
                  <div className={`text-xs transition-all duration-300 ${
                    theme === 'light' ? 'text-slate-600' : 'text-gray-300'
                  }`}>{tech.desc}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Enhanced CSS animations (Mantenido) */}
      <style>{`
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