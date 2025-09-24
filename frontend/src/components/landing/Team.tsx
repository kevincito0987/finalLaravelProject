import React from 'react';
import { Github, Linkedin, Code, Heart, UserCheck } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';
import HabitaLogo from '../ui/HabitaLogo';

const teamMembers = [
  {
    name: 'Santiago Arenas',
    role: 'Full-Stack Developer & Project Lead',
    github: 'Arenas07',
    image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face',
    bio: 'Líder del proyecto, especializado en arquitectura de sistemas y experiencia de usuario.',
    specialty: '🎩 Arquitectura & UX'
  },
  {
    name: 'Jose David Florez Navarrete',
    role: 'Backend Developer & AI Integration',
    github: 'JoseDFN',
    image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face',
    bio: 'Experto en integración de IA conversacional y desarrollo backend con C#.',
    specialty: '💼 Backend & IA'
  },
  {
    name: 'Juan Carlos Flórez',
    role: 'Frontend Developer & Mobile',
    github: 'juancarlosfc5',
    image: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face',
    bio: 'Especialista en React y Flutter, enfocado en interfaces de bienestar accesibles.',
    specialty: '👔 Frontend & Mobile'
  },
  {
    name: 'Juan David',
    role: 'Voice AI & Audio Processing',
    github: 'Juanfrxz',
    image: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150&h=150&fit=crop&crop=face',
    bio: 'Desarrollador especializado en procesamiento de voz e integración con ElevenLabs.',
    specialty: '🧑‍💻 Voice AI'
  },
  {
    name: 'Julián Camilo Villamizar Montañez',
    role: 'Database & Analytics',
    github: 'JulianCVM',
    image: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face',
    bio: 'Experto en PostgreSQL y análisis de datos emocionales para insights de bienestar.',
    specialty: '🧑‍🎓 Data & Analytics'
  },
  {
    name: 'Kevin Angarita',
    role: 'DevOps & Blockchain Integration',
    github: 'kevincito0987',
    image: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&h=150&fit=crop&crop=face',
    bio: 'Especialista en infraestructura cloud y integración blockchain con Algorand.',
    specialty: '👓 DevOps & Blockchain'
  },
  {
    name: 'Campus Lands Developer',
    role: 'Quality Assurance & Testing',
    github: 'addsdev-campuslands',
    image: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face',
    bio: 'Aseguramiento de calidad y testing de la experiencia de usuario en bienestar.',
    specialty: '🚀 QA & Testing'
  },
];
export default function Team() {
  const { theme } = useTheme();

  return (
    <section id="team" className={`py-20 transition-all duration-700 relative ${
      theme === 'light'
        ? 'bg-gradient-to-br from-blue-50 via-blue-100 to-purple-100'
        : 'bg-white dark:bg-gray-900'
    }`}>
      {/* Background landscape image */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
          style={{
            backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
          }}
        />
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-gradient-to-br from-blue-50/95 via-blue-100/90 to-purple-100/95'
            : 'bg-white/95 dark:bg-gray-900/95'
        }`} />
      </div>
      
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className={`text-4xl font-bold sm:text-5xl mb-4 transition-all duration-700 transform hover:scale-105 ${
            theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
          }`}>
            Conoce a nuestro equipo de desarrollo
          </h2>
          <p className={`max-w-3xl mx-auto text-xl transition-all duration-500 ${
            theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
          }`}>
            Siete desarrolladores apasionados comprometidos con hacer el bienestar emocional 
            accesible para todos a través de la tecnología.
          </p>
          
          {/* GitHub Project Link with enhanced animations */}
          <div className={`mt-8 inline-flex items-center px-6 py-3 rounded-xl transition-all duration-500 hover:scale-110 hover:shadow-xl transform hover:rotate-1 ${
            theme === 'light'
              ? 'bg-slate-800 text-white hover:bg-slate-700'
              : 'bg-gray-900 dark:bg-gray-700 text-white hover:bg-gray-800 dark:hover:bg-gray-600'
          }`}>
            <Github className="w-5 h-5 mr-2 animate-spin-slow" />
            <span className="font-medium">Síguenos en GitHub</span>
          </div>
        </div>

        <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          {teamMembers.map((member, index) => (
            <div
              key={index}
              className={`group rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-700 hover:-translate-y-4 hover:rotate-2 overflow-hidden border animate-fade-in ${
                theme === 'light'
                  ? 'bg-white/90 border-white/60 backdrop-blur-sm'
                  : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700'
              }`}
              style={{ animationDelay: `${index * 0.15}s` }}
            >
              {/* Image with enhanced hover effects */}
              <div className="relative aspect-w-3 aspect-h-3 overflow-hidden">
                <img
                  src={member.image}
                  alt={member.name}
                  className="w-full h-48 object-cover group-hover:scale-115 transition-all duration-700 filter group-hover:saturate-150"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                
                {/* Specialty badge with enhanced animations */}
                <div className={`absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-medium transform group-hover:scale-110 group-hover:translate-y-1 transition-all duration-500 ${
                  theme === 'light'
                    ? 'bg-white/95 text-slate-800'
                    : 'bg-white/95 dark:bg-gray-800/95 text-gray-800 dark:text-gray-200'
                }`}>
                  {member.specialty}
                </div>
              </div>
              
              <div className="p-6">
                <h3 className={`text-lg font-bold mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-all duration-300 transform group-hover:scale-105 ${
                  theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
                }`}>
                  {member.name}
                </h3>
                
                <p className="text-emerald-600 dark:text-emerald-400 text-sm font-medium mb-3">
                  {member.role}
                </p>
                
                <p className={`text-sm mb-4 leading-relaxed ${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
                }`}>
                  {member.bio}
                </p>
                
                {/* GitHub link with enhanced animations */}
                <div className="flex items-center justify-between">
                  <a
                    href={`https://github.com/${member.github}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className={`inline-flex items-center transition-all duration-300 hover:scale-105 ${
                      theme === 'light'
                        ? 'text-slate-500 hover:text-slate-700'
                        : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'
                    }`}
                  >
                    <Github className="w-4 h-4 mr-2 animate-pulse" style={{ animationDuration: '3s' }} />
                    <span className="text-sm font-medium">{member.github}</span>
                  </a>
                  
                  <div className="flex space-x-2">
                    <button className={`p-2 transition-all duration-300 rounded-full hover:scale-125 hover:bg-blue-100 dark:hover:bg-blue-900/30 ${
                      theme === 'light'
                        ? 'text-slate-400 hover:text-blue-600'
                        : 'text-gray-400 hover:text-blue-600 dark:hover:text-blue-400'
                    }`}>
                      <Linkedin size={16} />
                    </button>
                    <button className={`p-2 transition-all duration-300 rounded-full hover:scale-125 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 ${
                      theme === 'light'
                        ? 'text-slate-400 hover:text-emerald-600'
                        : 'text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400'
                    }`}>
                      <Code size={16} />
                    </button>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Team Stats with enhanced animations */}
        <div className={`mt-16 rounded-3xl p-8 transition-all duration-700 transform hover:scale-105 hover:shadow-xl relative ${
          theme === 'light'
            ? 'bg-white/80 backdrop-blur-sm'
            : 'bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-gray-800 dark:to-gray-700'
        }`} style={{ animationDelay: '0.8s' }}>
          {/* Background landscape image */}
          <div className="absolute inset-0 z-0 rounded-3xl overflow-hidden">
            <div 
              className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
              style={{
                backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
              }}
            />
            <div className={`absolute inset-0 ${
              theme === 'light'
                ? 'bg-white/95'
                : 'bg-gradient-to-r from-emerald-50/95 to-blue-50/95 dark:from-gray-800/95 dark:to-gray-700/95'
            }`} />
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-4 gap-6 text-center relative z-10">
            {[
              { value: '7', label: 'Desarrolladores', color: 'text-emerald-600 dark:text-emerald-400' },
              { value: '15+', label: 'Tecnologías', color: 'text-blue-600 dark:text-blue-400' },
              { value: '100%', label: 'Open Source', color: 'text-purple-600 dark:text-purple-400' },
              { value: '24/7', label: 'Compromiso', color: 'text-orange-600 dark:text-orange-400' }
            ].map((stat, index) => (
              <div 
                key={index}
                className="transform hover:scale-110 hover:rotate-3 transition-all duration-500"
              >
                <div className={`text-3xl font-bold ${stat.color} mb-2 animate-pulse`} style={{ animationDuration: '3s', animationDelay: `${index * 0.2}s` }}>{stat.value}</div>
                <div className={`${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
                }`}>{stat.label}</div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Enhanced CSS animations */}
      <style>{`
        @keyframes fade-in {
          from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
          }
          to {
            opacity: 1;
            transform: translateY(0) scale(1);
          }
        }
        
        @keyframes spin-slow {
          from {
            transform: rotate(0deg);
          }
          to {
            transform: rotate(360deg);
          }
        }
        
        .animate-fade-in {
          animation: fade-in 0.8s ease-out forwards;
          opacity: 0;
        }
        
        .animate-spin-slow {
          animation: spin-slow 8s linear infinite;
        }
        
        .group-hover\\:scale-115:group-hover {
          transform: scale(1.15);
        }
      `}</style>
    </section>
  );
}