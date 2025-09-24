import React from 'react';
import { CheckCircle, Heart, Brain, Users, Shield, Zap } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';

const benefits = [
  {
    icon: Brain,
    title: 'Mayor autoconciencia emocional',
    description: 'Aprende a identificar y nombrar tus emociones con la ayuda de IA conversacional.'
  },
  {
    icon: Heart,
    title: 'Hábitos de autocuidado sostenibles',
    description: 'Desarrolla rutinas diarias de bienestar que se adapten a tu estilo de vida.'
  },
  {
    icon: Users,
    title: 'Conexiones humanas auténticas',
    description: 'Únete a una comunidad de apoyo y comparte historias reales de crecimiento.'
  },
  {
    icon: Zap,
    title: 'Alertas proactivas de bienestar',
    description: 'Recibe recomendaciones inteligentes antes de que los patrones negativos se intensifiquen.'
  },
  {
    icon: Shield,
    title: 'Acceso a profesionales certificados',
    description: 'Conecta con psicólogos y coaches cuando necesites apoyo especializado.'
  },
  {
    icon: CheckCircle,
    title: 'Herramientas basadas en evidencia',
    description: 'Técnicas respaldadas por la ciencia del bienestar y la psicología positiva.'
  },
];


export default function Benefits() {
  const { theme } = useTheme();

  return (
    <section className={`py-20 transition-all duration-700 relative ${
      theme === 'light'
        ? 'bg-white'
        : 'bg-gray-50 dark:bg-gray-800'
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
            ? 'bg-white/95'
            : 'bg-gray-50/95 dark:bg-gray-800/95'
        }`} />
      </div>
      
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
          <div>
            <h2 className={`text-4xl font-bold sm:text-5xl mb-6 transition-all duration-700 transform hover:scale-105 ${
              theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
            }`}>
              Transforma tu bienestar emocional día a día
            </h2>
            <p className={`text-xl mb-8 leading-relaxed transition-all duration-500 ${
              theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
            }`}>
              Únete a miles de usuarios que ya han comenzado su viaje hacia un mejor bienestar 
              emocional con las herramientas integrales de Habita.
            </p>
            
            <div className="space-y-6">
              {benefits.map((benefit, index) => (
                <div 
                  key={index} 
                  className="flex items-start group transform hover:translate-x-4 transition-all duration-500"
                >
                  <div className="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-125 group-hover:rotate-12 transition-all duration-500 shadow-lg group-hover:shadow-xl">
                    <benefit.icon className="w-6 h-6 text-white group-hover:animate-pulse" />
                  </div>
                  <div>
                    <h3 className={`text-lg font-semibold mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-all duration-300 transform group-hover:scale-105 ${
                      theme === 'light' ? 'text-slate-800' : 'text-gray-900 dark:text-white'
                    }`}>
                      {benefit.title}
                    </h3>
                    <p className={`leading-relaxed transition-all duration-300 ${
                      theme === 'light' ? 'text-slate-600' : 'text-gray-600 dark:text-gray-300'
                    }`}>
                      {benefit.description}
                    </p>
                  </div>
                </div>
              ))}
            </div>

            {/* Important Notice with enhanced animations */}
            <div className={`mt-10 p-6 rounded-2xl border transition-all duration-500 transform hover:scale-105 hover:shadow-xl relative ${
              theme === 'light'
                ? 'bg-white/80 border-blue-200 backdrop-blur-sm'
                : 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800'
            }`}>
              {/* Background landscape image */}
              <div className="absolute inset-0 z-0 rounded-2xl overflow-hidden">
                <div 
                  className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
                  style={{
                    backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
                  }}
                />
                <div className={`absolute inset-0 ${
                  theme === 'light'
                    ? 'bg-white/90'
                    : 'bg-blue-50/90 dark:bg-blue-900/90'
                }`} />
              </div>
              
              <div className="flex items-start relative z-10">
                <div className="flex-shrink-0">
                  <div className={`w-10 h-10 rounded-full flex items-center justify-center transform hover:rotate-12 hover:scale-110 transition-all duration-300 ${
                    theme === 'light'
                      ? 'bg-blue-100'
                      : 'bg-blue-100 dark:bg-blue-900'
                  }`}>
                    <Shield className="w-5 h-5 text-blue-600 dark:text-blue-400 animate-pulse" />
                  </div>
                </div>
                <div className="ml-4">
                  <h3 className={`text-lg font-semibold mb-2 ${
                    theme === 'light' ? 'text-slate-800' : 'text-blue-800 dark:text-blue-200'
                  }`}>
                    Apoyo disponible 24/7
                  </h3>
                  <p className={`text-sm leading-relaxed ${
                    theme === 'light' ? 'text-slate-600' : 'text-blue-700 dark:text-blue-300'
                  }`}>
                    Acceso inmediato a recursos de bienestar y contactos de emergencia cuando necesites ayuda. 
                    Recuerda: Habita complementa pero no reemplaza la atención profesional en salud mental.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div className="mt-12 lg:mt-0 relative">
            {/* Stats Card with enhanced animations */}
            <div className="bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden transform hover:scale-105 transition-all duration-700 hover:shadow-3xl">
              {/* Background landscape image */}
              <div className="absolute inset-0 z-0">
                <div 
                  className="absolute inset-0 w-full h-full bg-cover bg-center opacity-10"
                  style={{
                    backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
                  }}
                />
                <div className="absolute inset-0 bg-gradient-to-br from-emerald-500/90 via-blue-500/90 to-purple-600/90" />
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
                  <div className="text-5xl font-bold mb-2 animate-pulse">94%</div>
                  <div className="text-xl opacity-90">de usuarios reportan mayor autoconciencia emocional</div>
                </div>
                
                <div className="grid grid-cols-2 gap-6 mb-8">
                  {[
                    { value: '10k+', label: 'Usuarios activos' },
                    { value: '24/7', label: 'Apoyo disponible' },
                    { value: '15+', label: 'Herramientas de IA' },
                    { value: '100%', label: 'Privacidad garantizada' }
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
                  "Habita me ayudó a entender mejor mis emociones y desarrollar mecanismos de autocuidado más saludables. 
                  Es como tener un compañero de bienestar siempre disponible."
                </blockquote>
                <div className="text-center text-sm opacity-90">- María, usuaria de Habita</div>
              </div>
            </div>

            {/* Technology badges with enhanced animations */}
            <div className="mt-8 flex flex-wrap gap-3 justify-center">
              {[
                { name: 'IA Conversacional', color: theme === 'light' ? 'bg-emerald-100 text-emerald-800' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' },
                { name: 'Voz Natural', color: theme === 'light' ? 'bg-blue-100 text-blue-800' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' },
                { name: 'Blockchain Seguro', color: theme === 'light' ? 'bg-purple-100 text-purple-800' : 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' },
                { name: 'Multiplataforma', color: theme === 'light' ? 'bg-orange-100 text-orange-800' : 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' }
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

      {/* Enhanced CSS animations */}
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