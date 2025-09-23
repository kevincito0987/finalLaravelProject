import React from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, Heart, Mic, Smartphone } from 'lucide-react';

export default function CallToAction() {
  return (
    <section className="py-12 sm:py-20 bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600 relative overflow-hidden">
      {/* Background landscape image */}
      <div className="absolute inset-0 z-0">
        <div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-15"
          style={{
            backgroundImage: `url('https://images.pexels.com/photos/1261728/pexels-photo-1261728.jpeg?auto=compress&cs=tinysrgb&w=1920&h=1080&fit=crop&crop=bottom')`
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-br from-emerald-500/90 via-blue-500/90 to-purple-600/90" />
      </div>
      
      {/* Gradient overlays with enhanced animations */}
      <div className="absolute top-0 left-0 w-full h-full z-0">
        <div className="absolute top-20 left-20 w-40 h-40 bg-white/10 rounded-full blur-xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-60 h-60 bg-white/5 rounded-full blur-2xl animate-float" style={{ animationDuration: '15s' }}></div>
        <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-white/5 rounded-full blur-3xl animate-pulse" style={{ animationDuration: '10s' }}></div>
        
        {/* Enhanced floating particles */}
        {[...Array(30)].map((_, i) => (
          <div
            key={i}
            className="absolute w-1 h-1 sm:w-2 sm:h-2 bg-white/30 rounded-full"
            style={{
              left: `${Math.random() * 100}%`,
              top: `${Math.random() * 100}%`,
              animation: `float-particle ${3 + Math.random() * 5}s ease-in-out infinite`,
              animationDelay: `${Math.random() * 5}s`
            }}
          />
        ))}
      </div>
      
      <div className="relative max-w-6xl mx-auto text-center px-4 sm:px-6 lg:px-8 z-10">
        <div className="flex justify-center mb-6 sm:mb-8">
          <div className="p-3 sm:p-4 bg-white/20 rounded-xl sm:rounded-2xl backdrop-blur-sm transform hover:scale-110 hover:rotate-12 transition-all duration-500">
            <Heart className="w-8 h-8 sm:w-12 sm:h-12 text-white animate-pulse" />
          </div>
        </div>
        
        <h2 className="text-2xl sm:text-3xl md:text-4xl lg:text-6xl font-bold text-white mb-4 sm:mb-6 leading-tight animate-fade-in">
          ¿Listo para comenzar tu
          <br />
          <span className="text-yellow-300 animate-color-shift">viaje de bienestar?</span>
        </h2>
        
        <p className="text-base sm:text-lg md:text-xl lg:text-2xl text-white/90 max-w-3xl mx-auto mb-6 sm:mb-10 leading-relaxed animate-fade-in" style={{ animationDelay: '0.3s' }}>
          Únete a miles de usuarios que ya están transformando su bienestar emocional 
          con Habita. Tu compañero de IA está esperando para acompañarte.
        </p>

        {/* Features highlight with enhanced animations */}
        <div className="flex flex-wrap justify-center gap-3 sm:gap-6 mb-6 sm:mb-10 animate-fade-in" style={{ animationDelay: '0.6s' }}>
          {[
            { icon: Mic, text: 'Check-in por voz' },
            { icon: Heart, text: 'IA empática' },
            { icon: Smartphone, text: 'Disponible 24/7' }
          ].map((feature, index) => (
            <div 
              key={index} 
              className="flex items-center bg-white/20 backdrop-blur-sm rounded-lg sm:rounded-xl px-3 sm:px-4 py-1.5 sm:py-2 transform hover:scale-110 hover:bg-white/30 transition-all duration-500"
              style={{ animationDelay: `${0.8 + index * 0.2}s` }}
            >
              <feature.icon className="w-4 h-4 sm:w-5 sm:h-5 text-white mr-1.5 sm:mr-2 animate-pulse" />
              <span className="text-white font-medium text-xs sm:text-sm">{feature.text}</span>
            </div>
          ))}
        </div>

        <div className="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center mb-6 sm:mb-10 animate-fade-in" style={{ animationDelay: '1s' }}>
          <Link
            to="/register"
            className="group inline-flex items-center justify-center px-6 sm:px-10 py-3 sm:py-4 bg-white text-emerald-600 text-sm sm:text-lg font-bold rounded-xl sm:rounded-2xl hover:bg-gray-100 transition-all duration-500 transform hover:scale-110 hover:-translate-y-2 shadow-2xl hover:shadow-3xl relative overflow-hidden"
          >
            {/* Shimmer effect */}
            <div className="absolute inset-0 bg-gradient-to-r from-transparent via-emerald-100 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-all duration-1000"></div>
            
            <Mic className="mr-2 sm:mr-3 h-4 w-4 sm:h-6 sm:w-6 group-hover:animate-bounce" />
            <span className="relative z-10 text-xs sm:text-base">Comenzar gratis ahora</span>
            <ArrowRight className="ml-2 sm:ml-3 h-3 w-3 sm:h-5 sm:w-5 group-hover:translate-x-2 transition-transform duration-300" />
          </Link>
          
          <Link
            to="/login"
            className="inline-flex items-center justify-center px-6 sm:px-10 py-3 sm:py-4 border-2 border-white text-sm sm:text-lg font-bold rounded-xl sm:rounded-2xl text-white bg-transparent hover:bg-white/10 transition-all duration-500 backdrop-blur-sm transform hover:scale-110 hover:-translate-y-2"
          >
            <span className="text-xs sm:text-base">Ya tengo cuenta</span>
          </Link>
        </div>

        {/* Trust indicators with enhanced animations */}
        <div className="flex flex-col sm:flex-row items-center justify-center space-y-2 sm:space-y-0 sm:space-x-4 lg:space-x-8 text-white/80 text-xs sm:text-sm animate-fade-in" style={{ animationDelay: '1.2s' }}>
          {[
            'Gratis para empezar',
            'Sin tarjeta de crédito',
            'Cancela cuando quieras',
            '100% privado y seguro'
          ].map((item, index) => (
            <div key={index} className="flex items-center transform hover:scale-110 transition-all duration-300">
              <span className="mr-2 animate-pulse" style={{ animationDelay: `${index * 0.2}s` }}>✓</span>
              <span className="text-xxs sm:text-xs">{item}</span>
            </div>
          ))}
        </div>

        {/* App availability with enhanced animations */}
        <div className="mt-8 sm:mt-12 p-4 sm:p-6 bg-white/10 backdrop-blur-sm rounded-xl sm:rounded-2xl transform hover:scale-105 transition-all duration-500 animate-fade-in" style={{ animationDelay: '1.4s' }}>
          <p className="text-white/90 mb-3 sm:mb-4 font-medium text-sm">Próximamente disponible en:</p>
          <div className="flex justify-center space-x-4 sm:space-x-6">
            <div className="flex items-center bg-white/20 rounded-lg sm:rounded-xl px-3 sm:px-4 py-1.5 sm:py-2 transform hover:scale-110 hover:bg-white/30 transition-all duration-300">
              <span className="text-xl sm:text-2xl mr-2 animate-bounce" style={{ animationDuration: '2s' }}>📱</span>
              <span className="text-white font-medium text-xs sm:text-sm">iOS & Android</span>
            </div>
            <div className="flex items-center bg-white/20 rounded-lg sm:rounded-xl px-3 sm:px-4 py-1.5 sm:py-2 transform hover:scale-110 hover:bg-white/30 transition-all duration-300">
              <span className="text-xl sm:text-2xl mr-2 animate-bounce" style={{ animationDuration: '2s', animationDelay: '0.5s' }}>🌐</span>
              <span className="text-white font-medium text-xs sm:text-sm">Web App</span>
            </div>
          </div>
        </div>
      </div>

      {/* Enhanced CSS animations */}
      <style jsx>{`
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
        
        @keyframes float {
          0%, 100% {
            transform: translateY(0) scale(1);
          }
          50% {
            transform: translateY(-30px) scale(1.1);
          }
        }
        
        @keyframes float-particle {
          0%, 100% { 
            transform: translateY(0px) rotate(0deg) scale(1); 
            opacity: 0.3;
          }
          50% { 
            transform: translateY(-50px) rotate(180deg) scale(1.5); 
            opacity: 0.8;
          }
        }
        
        @keyframes color-shift {
          0%, 100% {
            color: #fde047;
            text-shadow: 0 0 15px rgba(253, 224, 71, 0.5);
          }
          50% {
            color: #fef08a;
            text-shadow: 0 0 25px rgba(253, 224, 71, 0.8);
          }
        }
        
        .animate-fade-in {
          animation: fade-in 1s ease-out forwards;
        }
        
        .animate-float {
          animation: float 10s ease-in-out infinite;
        }
        
        .animate-color-shift {
          animation: color-shift 3s ease-in-out infinite;
        }
        
        .hover\\:shadow-3xl:hover {
          box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
        }
      `}</style>
    </section>
  );
}