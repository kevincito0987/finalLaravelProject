import React from 'react';
import { Mail, Phone, MapPin, Github, Shield, AlertTriangle } from 'lucide-react';
import { useTheme } from '../../context/ThemeContext';
import HabitaLogo from '../ui/HabitaLogo';

export default function Footer() {
  const { theme } = useTheme();

  return (
    <footer className={`transition-all duration-500 relative ${
      theme === 'light' ? 'bg-slate-800' : 'bg-gray-900'
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
            ? 'bg-slate-800/95'
            : 'bg-gray-900/95'
        }`} />
      </div>
      
      <div className="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Brand Section */}
          <div className="col-span-1 md:col-span-2">
            <div className="flex items-center mb-6">
              <HabitaLogo size="md" />
            </div>
            <p className="text-gray-400 max-w-md mb-6 leading-relaxed">
              Tu espacio digital de bienestar emocional y productividad personal. 
              Combinamos voz e inteligencia artificial para acompañarte en tu crecimiento diario.
            </p>
            
            {/* Contact Info */}
            <div className="space-y-3 text-sm text-gray-400">
              <div className="flex items-center">
                <Mail className="w-4 h-4 mr-3" />
                <span>soporte@habita.app</span>
              </div>
              <div className="flex items-center">
                <Phone className="w-4 h-4 mr-3" />
                <span>Línea de Crisis: 988 (Colombia)</span>
              </div>
              <div className="flex items-center">
                <MapPin className="w-4 h-4 mr-3" />
                <span>Disponible en Colombia y Latinoamérica</span>
              </div>
              <div className="flex items-center">
                <Github className="w-4 h-4 mr-3" />
                <a href="https://github.com/habita-team" className="hover:text-white transition-colors">
                  Síguenos en GitHub
                </a>
              </div>
            </div>
          </div>

          {/* Support Links */}
          <div>
            <h3 className="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-6">
              Soporte y Ayuda
            </h3>
            <ul className="space-y-4">
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Centro de Ayuda
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Recursos de Crisis
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Guías de Bienestar
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Comunidad
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Contactar Soporte
                </a>
              </li>
            </ul>
          </div>

          {/* Legal Links */}
          <div>
            <h3 className="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-6">
              Legal y Privacidad
            </h3>
            <ul className="space-y-4">
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Política de Privacidad
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Términos de Servicio
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Protección de Datos
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Accesibilidad
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Código de Ética
                </a>
              </li>
            </ul>
          </div>
        </div>

        {/* Important Disclaimers */}
        <div className="mt-12 pt-8 border-t border-gray-700">
          <div className="bg-yellow-900/20 border border-yellow-800 rounded-xl p-6 mb-8 relative">
            {/* Background landscape image */}
            <div className="absolute inset-0 z-0 rounded-xl overflow-hidden">
              <div 
                className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
                style={{
                  backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
                }}
              />
              <div className="absolute inset-0 bg-yellow-900/90" />
            </div>
            
            <div className="flex items-start relative z-10">
              <AlertTriangle className="w-6 h-6 text-yellow-500 mr-3 mt-1 flex-shrink-0" />
              <div>
                <h4 className="text-lg font-semibold text-yellow-400 mb-2">
                  Aviso Importante sobre Bienestar
                </h4>
                <p className="text-yellow-200 text-sm leading-relaxed">
                  <strong>Habita no es una herramienta médica ni reemplaza la atención profesional en salud mental.</strong> 
                  Si estás experimentando una crisis de salud mental, por favor contacta inmediatamente a los servicios 
                  de emergencia locales o llama a la línea de crisis 988. Habita es una herramienta de bienestar 
                  complementaria diseñada para el autocuidado diario.
                </p>
              </div>
            </div>
          </div>

          {/* Crisis Resources */}
          <div className="bg-blue-900/20 border border-blue-800 rounded-xl p-6 mb-8 relative">
            {/* Background landscape image */}
            <div className="absolute inset-0 z-0 rounded-xl overflow-hidden">
              <div 
                className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
                style={{
                  backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=center')`
                }}
              />
              <div className="absolute inset-0 bg-blue-900/90" />
            </div>
            
            <div className="flex items-start relative z-10">
              <Shield className="w-6 h-6 text-blue-400 mr-3 mt-1 flex-shrink-0" />
              <div>
                <h4 className="text-lg font-semibold text-blue-400 mb-3">
                  Recursos de Crisis Inmediata
                </h4>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                  <div>
                    <p className="text-blue-300 font-medium">Colombia</p>
                    <p className="text-blue-200">Línea 106: Emergencias</p>
                    <p className="text-blue-200">Línea 123: Salud Mental</p>
                  </div>
                  <div>
                    <p className="text-blue-300 font-medium">México</p>
                    <p className="text-blue-200">Línea de la Vida: 800-911-2000</p>
                  </div>
                  <div>
                    <p className="text-blue-300 font-medium">Argentina</p>
                    <p className="text-blue-200">Centro de Asistencia: 135</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Copyright and Team */}
          <div className="text-center">
            <p className="text-gray-400 text-sm mb-4">
              © 2025 Habita. Todos los derechos reservados. Desarrollado con ❤️ para el bienestar emocional.
            </p>
            <p className="text-gray-500 text-xs">
              Categoría: Bienestar, Autocuidado, Estilo de vida • 
              Desarrollado por el equipo de 7 desarrolladores comprometidos con la salud mental accesible.
            </p>
            
            {/* Technology Stack */}
            <div className="mt-6 flex flex-wrap justify-center gap-2">
              {[
                'React', 'Flutter', 'C#', 'PostgreSQL', 'Supabase', 
                'ElevenLabs', 'Tavus', 'RevenueCat', 'Algorand'
              ].map((tech, index) => (
                <span 
                  key={index} 
                  className="px-3 py-1 bg-gray-800 text-gray-400 text-xs rounded-full hover:bg-gray-700 transition-colors"
                >
                  {tech}
                </span>
              ))}
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}