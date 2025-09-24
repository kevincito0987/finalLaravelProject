import React from 'react';
import { Mail, BookOpen, MapPin, Github, Shield, AlertTriangle, Phone } from 'lucide-react';
// Importación del logo. Asumo que la ruta es correcta.
import CommTechLogo from '../ui/CommTechLogo'; 

// 1. DEFINICIÓN DEL TIPO: Creamos una unión de tipos para 'Theme'
type Theme = 'light' | 'dark';

export default function Footer() {
  // 2. APLICACIÓN DEL TIPO: Forzamos a TypeScript a reconocer que 'theme' puede ser 'light' o 'dark'.
  const theme = 'dark' as Theme; // Explicitly cast 'dark' to the union type Theme.

  // URL de imagen abstracta para el fondo (mantenida por estética)
  const backgroundImageUrl = 'https://i.imgur.com/cmhilCx.png';

  return (
    <footer className={`transition-all duration-500 relative ${
      // *********** SOLUCIÓN ts(2367) ***********
      // La comparación ahora es válida porque 'theme' es de tipo 'light' | 'dark'.
      theme === 'light' ? 'bg-slate-800' : 'bg-gray-900' 
    }`}>
      {/* Background image and overlay */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
          style={{
            backgroundImage: `url('${backgroundImageUrl}')`
          }}
        />
        <div className={`absolute inset-0 ${
          // *********** SOLUCIÓN ts(2367) ***********
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
              <CommTechLogo size="md" /> 
            </div>
            
            <p className="text-gray-400 max-w-md mb-6 leading-relaxed">
              Plataforma para la Comunicación Alternativa y Aumentativa (CAA). 
              Fomentamos la autonomía y el aprendizaje inclusivo para personas con 
              trastornos del lenguaje en Colombia.
            </p>
            
            {/* Contact Info */}
            <div className="space-y-3 text-sm text-gray-400">
              <div className="flex items-center">
                <Mail className="w-4 h-4 mr-3" />
                <span>contacto@comunicatech.app</span>
              </div>
              <div className="flex items-center">
                <Phone className="w-4 h-4 mr-3" />
                {/* Los datos de contacto del frontend de Habita (teléfono y email) han sido sustituidos por los de ComunicaTech */}
                <span>Contacto de Soporte: (57) 310-XXX-XXXX</span>
              </div>
              <div className="flex items-center">
                <MapPin className="w-4 h-4 mr-3" />
                <span>Operando en Colombia y Latinoamérica</span>
              </div>
              <div className="flex items-center">
                <Github className="w-4 h-4 mr-3" />
                <a href="https://github.com/kevincito0987/platica-frontend" className="hover:text-white transition-colors">
                  Backend en GitHub (Laravel)
                </a>
              </div>
            </div>
          </div>

          {/* Platform Links */}
          <div>
            <h3 className="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-6">
              Plataforma
            </h3>
            <ul className="space-y-4">
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Lecciones Interactivas
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Tarjetas de Comunicación
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Panel para Terapeutas
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Comunidad y Apoyo
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Centro de Ayuda
                </a>
              </li>
            </ul>
          </div>

          {/* Legal Links */}
          <div>
            <h3 className="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-6">
              Legal y Ética
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
                  Declaración de Accesibilidad ♿
                </a>
              </li>
              <li>
                <a href="#" className="text-base text-gray-300 hover:text-white transition-colors">
                  Protección de Datos
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
            <div className="absolute inset-0 z-0 rounded-xl overflow-hidden">
              <div 
                className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
                style={{
                  backgroundImage: `url('${backgroundImageUrl}')`
                }}
              />
              <div className="absolute inset-0 bg-yellow-900/90" />
            </div>
            
            <div className="flex items-start relative z-10">
              <AlertTriangle className="w-6 h-6 text-yellow-500 mr-3 mt-1 flex-shrink-0" />
              <div>
                <h4 className="text-lg font-semibold text-yellow-400 mb-2">
                  Aviso de Soporte Profesional
                </h4>
                <p className="text-yellow-200 text-sm leading-relaxed">
                  ComunicaTech es una herramienta de apoyo educativo y comunicativo. 
                  No reemplaza la intervención de terapeutas del lenguaje, psicólogos 
                  o educadores especiales. Consulta a un profesional para un diagnóstico y plan de 
                  tratamiento personalizado.
                </p>
              </div>
            </div>
          </div>

          {/* Accessibility Disclaimer */}
          <div className="bg-blue-900/20 border border-blue-800 rounded-xl p-6 mb-8 relative">
            <div className="absolute inset-0 z-0 rounded-xl overflow-hidden">
              <div 
                className="absolute inset-0 w-full h-full bg-cover bg-center opacity-5"
                style={{
                  backgroundImage: `url('${backgroundImageUrl}')`
                }}
              />
              <div className="absolute inset-0 bg-blue-900/90" />
            </div>
            
            <div className="flex items-start relative z-10">
              <Shield className="w-6 h-6 text-blue-400 mr-3 mt-1 flex-shrink-0" />
              <div>
                <h4 className="text-lg font-semibold text-blue-400 mb-3">
                  Compromiso con la Accesibilidad
                </h4>
                <p className="text-blue-200 text-sm leading-relaxed">
                  Diseñado bajo estándares de accesibilidad (WCAG) para usuarios con 
                  TEA, afasia y disartria. Priorizamos íconos grandes, texto claro y 
                  soporte multilenguaje para la inclusión.
                </p>
                <div className="mt-3 text-xs text-blue-300">
                  <BookOpen className="w-4 h-4 inline mr-1" />
                  Soporte para múltiples idiomas con Laravel Localization.
                </div>
              </div>
            </div>
          </div>

          {/* Copyright and Technology Stack */}
          <div className="text-center">
            <p className="text-gray-400 text-sm mb-4">
              © 2025 ComunicaTech. Todos los derechos reservados. Forjamos el puente donde el silencio se rompe.
            </p>
            <p className="text-gray-500 text-xs">
              Categoría: Comunicación, Inclusión, Aprendizaje, Accesibilidad.
            </p>
            
            {/* Technology Stack */}
            <div className="mt-6 flex flex-wrap justify-center gap-2">
              <span className="text-gray-400 text-xs mr-2">Tecnologías Clave:</span>
              {[
                'Laravel 11 ✨', 'MySQL/PostgreSQL 🗄️', 'TypeScript', 'React', 
                'Tailwind CSS', 'Repository Pattern ♟️', 'Localization 🗣️'
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