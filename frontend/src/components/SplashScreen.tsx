import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Heart, Brain, Users, Shield, Sparkles, Zap, Hexagon, Layers, Aperture, Star, CheckCircle, Globe, Mic, Target } from 'lucide-react';
import { useTheme } from '../context/ThemeContext';
import HabitaLogo from './ui/CommTechLogo';
import { useTranslation } from 'react-i18next';

interface SplashScreenProps {
  onComplete: () => void;
  userName?: string;
}

export default function SplashScreen({ onComplete, userName = 'a tu bienestar' }: SplashScreenProps) {
  const [currentStep, setCurrentStep] = useState(0);
  const [currentLanguage, setCurrentLanguage] = useState('es');
  const { theme } = useTheme();
  const { t, i18n } = useTranslation();
  
  const toggleLanguage = () => {
    setCurrentLanguage(prev => {
      const nextLang = prev === 'es' ? 'en' : prev === 'en' ? 'fr' : prev === 'fr' ? 'pt' : 'es';
      i18n.changeLanguage(nextLang);
      return nextLang;
    });
  };

  const splashContent = [
    {
      title: t('Bienvenido a Habita'),
      message: t('Tu espacio seguro para cultivar bienestar emocional día a día.'),
      icon: Heart,
      color: "emerald",
      image: "https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400&h=300&fit=crop&crop=center",
      tip: t('Cada día es una nueva oportunidad para cuidar tu bienestar')
    },
    {
      title: t('Eres importante'),
      message: t('Cada check-in, cada momento de autocuidado, es un paso hacia tu mejor versión.'),
      icon: Brain,
      color: "blue",
      image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop&crop=center",
      tip: t('Tu mente merece la misma atención que tu cuerpo')
    },
    {
      title: t('Estamos contigo'),
      message: t('No importa cómo te sientas hoy, estamos aquí para acompañarte en tu camino.'),
      icon: Users,
      color: "purple",
      image: "https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=400&h=300&fit=crop&crop=center",
      tip: t('Una comunidad de apoyo te espera para compartir experiencias')
    },
    {
      title: t('Pequeños pasos, grandes cambios'),
      message: t('Celebramos cada uno de tus avances en este viaje de bienestar.'),
      icon: Zap,
      color: "orange",
      image: "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=300&fit=crop&crop=center",
      tip: t('Solo 5 minutos al día pueden transformar tu bienestar emocional')
    },
    {
      title: t('Razones para quedarte'),
      message: t('Descubre por qué miles de usuarios eligen Habita para su bienestar diario'),
      icon: Star,
      color: "pink",
      isReasons: true
    }
  ];

  const reasonsToStay = [
    {
      icon: Mic,
      title: t('IA Conversacional'),
      description: t('Habla con tu compañero emocional 24/7'),
      image: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=300&h=200&fit=crop&crop=center",
      benefit: t('Reduce ansiedad en un 40%')
    },
    {
      icon: Target,
      title: t('Seguimiento Inteligente'),
      description: t('Patrones emocionales y alertas proactivas'),
      image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=300&h=200&fit=crop&crop=center",
      benefit: t('Mejora autoconciencia en 7 días')
    },
    {
      icon: Users,
      title: t('Comunidad de Apoyo'),
      description: t('Conecta con personas en la misma jornada'),
      image: "https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=300&h=200&fit=crop&crop=center",
      benefit: t('94% se sienten menos solos')
    },
    {
      icon: Shield,
      title: t('Soporte de Crisis'),
      description: t('Acceso inmediato a recursos profesionales'),
      image: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=300&h=200&fit=crop&crop=center",
      benefit: t('Disponible 24/7')
    }
  ];

  useEffect(() => {
    if (currentStep < splashContent.length) {
      const timer = setTimeout(() => {
        setCurrentStep(prev => prev + 1);
      }, currentStep === splashContent.length - 1 ? 4000 : 3000);
      
      return () => clearTimeout(timer);
    } else {
      const finalTimer = setTimeout(() => {
        onComplete();
      }, 2000);
      
      return () => clearTimeout(finalTimer);
    }
  }, [currentStep, splashContent.length, onComplete]);

  const getColorClass = (color: string) => {
    const colorMap: Record<string, string> = {
      emerald: 'from-emerald-500 to-green-500',
      blue: 'from-blue-500 to-cyan-500',
      purple: 'from-purple-500 to-pink-500',
      orange: 'from-orange-500 to-red-500',
      pink: 'from-pink-500 to-rose-500'
    };
    
    return colorMap[color] || 'from-emerald-500 to-green-500';
  };

  return (
    <motion.div 
      className={`fixed inset-0 z-[9999] flex flex-col ${
        theme === 'light'
          ? 'bg-gradient-to-br from-blue-100 via-blue-200 to-purple-200'
          : 'bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900'
      }`}
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
    >
      {/* Language Selector */}
      <div className="absolute top-4 right-4 z-20">
        <motion.button
          onClick={toggleLanguage}
          className={`flex items-center space-x-1 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-300 ${
            theme === 'light'
              ? 'bg-white/80 text-slate-700 hover:bg-white shadow-sm'
              : 'bg-gray-800/80 text-gray-300 hover:bg-gray-700'
          }`}
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
        >
          <Globe className="w-3 h-3 mr-1" />
          <span>{currentLanguage.toUpperCase()}</span>
        </motion.button>
      </div>

      {/* Background image - Using a beautiful aurora borealis image */}
      <div className="absolute inset-0 z-0">
        <motion.div 
          className="absolute inset-0 w-full h-full bg-cover bg-center opacity-20"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=1920&h=1080&fit=crop&crop=center')`
          }}
          animate={{ 
            scale: [1, 1.05, 1],
            opacity: [0.20, 0.25, 0.20]
          }}
          transition={{ duration: 8, repeat: Infinity }}
        />
        
        {/* Enhanced gradient overlay */}
        <div className={`absolute inset-0 ${
          theme === 'light'
            ? 'bg-gradient-to-br from-blue-100/85 via-blue-200/75 to-purple-200/85'
            : 'bg-gradient-to-br from-gray-900/85 via-gray-800/75 to-gray-900/85'
        }`} />
      </div>

      {/* Enhanced floating particles */}
      <div className="absolute inset-0 overflow-hidden">
        {[...Array(80)].map((_, i) => (
          <motion.div
            key={i}
            className={`absolute rounded-full ${
              theme === 'light' 
                ? 'bg-emerald-400/40' 
                : 'bg-emerald-400/30'
            }`}
            style={{
              width: `${Math.random() * 8 + 2}px`,
              height: `${Math.random() * 8 + 2}px`
            }}
            initial={{
              x: Math.random() * window.innerWidth,
              y: Math.random() * window.innerHeight,
              scale: Math.random() * 0.5 + 0.5
            }}
            animate={{
              y: [null, Math.random() * -200 - 50],
              x: [null, Math.random() * 150 - 75],
              opacity: [0, 0.8, 0],
              scale: [0, Math.random() * 2 + 0.5, 0],
              rotate: [0, 360]
            }}
            transition={{
              duration: Math.random() * 8 + 4,
              repeat: Infinity,
              delay: Math.random() * 5,
              ease: "easeOut"
            }}
          />
        ))}
      </div>

      {/* Logo animation - Centrado en la parte superior */}
      <div className="flex justify-center pt-16 pb-8">
        <motion.div
          initial={{ scale: 0, rotate: -180, y: -50 }}
          animate={{ scale: 1, rotate: 0, y: 0 }}
          transition={{ 
            type: "spring", 
            stiffness: 200, 
            damping: 15,
            duration: 2
          }}
        >
          <HabitaLogo size="xl" animated />
        </motion.div>
      </div>

      {/* Content area - PERFECTAMENTE CENTRADO */}
      <div className="flex-1 flex items-center justify-center px-6">
        <div className="w-full max-w-6xl">
          <AnimatePresence mode="wait">
            {currentStep < splashContent.length && (
              <motion.div
                key={currentStep}
                initial={{ opacity: 0, y: 50, scale: 0.9, rotateX: 15 }}
                animate={{ opacity: 1, y: 0, scale: 1, rotateX: 0 }}
                exit={{ opacity: 0, y: -50, scale: 0.9, rotateX: -15 }}
                transition={{ duration: 0.8, ease: "easeOut" }}
                className="w-full"
              >
                {splashContent[currentStep].isReasons ? (
                  // Reasons to stay section - Enhanced
                  <div className="space-y-8 text-center">
                    <div className="mb-12">
                      <motion.div
                        className={`p-6 rounded-full bg-gradient-to-r ${getColorClass(splashContent[currentStep].color)} mb-8 mx-auto w-24 h-24 flex items-center justify-center shadow-2xl`}
                        animate={{ 
                          scale: [1, 1.3, 1],
                          rotate: [0, 360],
                          boxShadow: [
                            "0 25px 50px -12px rgba(0, 0, 0, 0.25)",
                            "0 35px 70px -15px rgba(0, 0, 0, 0.4)",
                            "0 25px 50px -12px rgba(0, 0, 0, 0.25)"
                          ]
                        }}
                        transition={{ 
                          duration: 4, 
                          repeat: Infinity,
                          ease: "easeInOut"
                        }}
                      >
                        <Star className="w-12 h-12 text-white" />
                      </motion.div>
                      
                      <h2 className={`text-4xl font-bold mb-6 ${
                        theme === 'light' ? 'text-slate-800' : 'text-white'
                      }`}>
                        {splashContent[currentStep].title}
                      </h2>
                      
                      <p className={`text-xl mb-8 ${
                        theme === 'light' ? 'text-slate-700' : 'text-gray-300'
                      }`}>
                        {splashContent[currentStep].message}
                      </p>
                    </div>

                    {/* Interactive reasons grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                      {reasonsToStay.map((reason, index) => (
                        <motion.div
                          key={index}
                          className={`relative rounded-3xl overflow-hidden shadow-2xl group cursor-pointer ${
                            theme === 'light'
                              ? 'bg-white/95 backdrop-blur-sm border border-white/60'
                              : 'bg-gray-800/95 backdrop-blur-sm border border-gray-700/50'
                          }`}
                          initial={{ opacity: 0, y: 30, scale: 0.8, rotateY: 45 }}
                          animate={{ opacity: 1, y: 0, scale: 1, rotateY: 0 }}
                          transition={{ 
                            delay: 0.5 + index * 0.2,
                            duration: 0.8,
                            type: "spring",
                            stiffness: 100
                          }}
                          whileHover={{ 
                            scale: 1.08, 
                            y: -15,
                            rotateY: 5,
                            transition: { duration: 0.3 }
                          }}
                        >
                          {/* Background image with overlay */}
                          <div className="relative h-32 overflow-hidden">
                            <motion.div
                              className="absolute inset-0 bg-cover bg-center"
                              style={{ backgroundImage: `url(${reason.image})` }}
                              whileHover={{ scale: 1.15 }}
                              transition={{ duration: 0.6 }}
                            />
                            <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent" />
                            
                            {/* Benefit badge */}
                            <motion.div 
                              className="absolute top-3 right-3 bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-semibold"
                              initial={{ scale: 0, rotate: 180 }}
                              animate={{ scale: 1, rotate: 0 }}
                              transition={{ delay: 1 + index * 0.1, type: "spring" }}
                            >
                              {reason.benefit}
                            </motion.div>
                          </div>

                          {/* Content */}
                          <div className="p-6">
                            <div className="flex items-center mb-3">
                              <motion.div 
                                className="p-2 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-xl mr-3"
                                whileHover={{ rotate: 15, scale: 1.2 }}
                              >
                                <reason.icon className="w-5 h-5 text-white" />
                              </motion.div>
                              <h3 className={`font-bold text-lg ${
                                theme === 'light' ? 'text-slate-800' : 'text-white'
                              }`}>
                                {reason.title}
                              </h3>
                            </div>
                            <p className={`text-sm leading-relaxed ${
                              theme === 'light' ? 'text-slate-600' : 'text-gray-300'
                            }`}>
                              {reason.description}
                            </p>
                          </div>

                          {/* Hover effect overlay */}
                          <motion.div
                            className="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            initial={false}
                          />
                        </motion.div>
                      ))}
                    </div>

                    {/* Call to action */}
                    <motion.div
                      className="mt-12"
                      initial={{ opacity: 0, y: 20 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ delay: 2 }}
                    >
                      <motion.div
                        className={`inline-flex items-center px-8 py-4 rounded-2xl font-semibold text-lg ${
                          theme === 'light'
                            ? 'bg-emerald-600 text-white'
                            : 'bg-emerald-500 text-white'
                        }`}
                        animate={{ 
                          scale: [1, 1.05, 1],
                          boxShadow: [
                            "0 10px 25px -5px rgba(16, 185, 129, 0.3)",
                            "0 20px 40px -5px rgba(16, 185, 129, 0.4)",
                            "0 10px 25px -5px rgba(16, 185, 129, 0.3)"
                          ]
                        }}
                        transition={{ duration: 2, repeat: Infinity }}
                      >
                        <CheckCircle className="w-6 h-6 mr-3" />
                        {t('¡Comencemos tu viaje de bienestar!')}
                      </motion.div>
                    </motion.div>
                  </div>
                ) : (
                  // Regular content sections - Enhanced and CENTERED with images
                  <div className="flex flex-col lg:flex-row items-center justify-center space-y-8 lg:space-y-0 lg:space-x-16">
                    {/* Text content */}
                    <div className="flex-1 max-w-2xl text-center lg:text-left">
                      <motion.div
                        className={`p-6 rounded-full bg-gradient-to-r ${getColorClass(splashContent[currentStep].color)} mb-8 mx-auto lg:mx-0 w-20 h-20 flex items-center justify-center shadow-2xl`}
                        animate={{ 
                          scale: [1, 1.4, 1],
                          rotate: [0, 15, -15, 0],
                          boxShadow: [
                            "0 20px 40px -10px rgba(0, 0, 0, 0.3)",
                            "0 30px 60px -15px rgba(0, 0, 0, 0.4)",
                            "0 20px 40px -10px rgba(0, 0, 0, 0.3)"
                          ]
                        }}
                        transition={{ 
                          duration: 3, 
                          repeat: Infinity,
                          repeatDelay: 2
                        }}
                      >
                        {(() => {
                          const Icon = splashContent[currentStep].icon;
                          return <Icon className="w-10 h-10 text-white" />;
                        })()}
                      </motion.div>
                      
                      <h2 className={`text-4xl lg:text-5xl font-bold mb-6 ${
                        theme === 'light' ? 'text-slate-800' : 'text-white'
                      }`}>
                        {splashContent[currentStep].title}
                      </h2>
                      
                      <p className={`text-xl lg:text-2xl mb-6 leading-relaxed ${
                        theme === 'light' ? 'text-slate-700' : 'text-gray-300'
                      }`}>
                        {splashContent[currentStep].message}
                      </p>

                      {/* Wellness tip */}
                      <motion.div
                        className={`p-4 rounded-2xl border-l-4 border-emerald-500 ${
                          theme === 'light'
                            ? 'bg-emerald-50/80 backdrop-blur-sm'
                            : 'bg-emerald-900/20 backdrop-blur-sm'
                        }`}
                        initial={{ opacity: 0, x: -20 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ delay: 0.5 }}
                      >
                        <p className={`text-lg font-medium ${
                          theme === 'light' ? 'text-emerald-800' : 'text-emerald-200'
                        }`}>
                          {splashContent[currentStep].tip}
                        </p>
                      </motion.div>
                    </div>

                    {/* Image content */}
                    <motion.div 
                      className="flex-1 max-w-md lg:max-w-lg lg:ml-12"
                      initial={{ opacity: 0, scale: 0.8, x: 50, rotateY: 30 }}
                      animate={{ opacity: 1, scale: 1, x: 0, rotateY: 0 }}
                      transition={{ delay: 0.3, duration: 0.8 }}
                    >
                      <div className="relative">
                        <motion.div
                          className="rounded-3xl overflow-hidden shadow-2xl"
                          whileHover={{ scale: 1.05, rotate: 2, y: -10 }}
                          transition={{ duration: 0.3 }}
                        >
                          <motion.img
                            src={splashContent[currentStep].image}
                            alt="Wellness inspiration"
                            className="w-full h-64 lg:h-80 object-cover"
                            animate={{ 
                              filter: [
                                "brightness(1) contrast(1)",
                                "brightness(1.1) contrast(1.1)",
                                "brightness(1) contrast(1)"
                              ]
                            }}
                            transition={{ duration: 4, repeat: Infinity }}
                          />
                          
                          {/* Overlay with gradient */}
                          <div className="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent" />
                          
                          {/* Floating elements */}
                          <motion.div
                            className="absolute top-4 right-4 w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center"
                            animate={{ 
                              scale: [1, 1.3, 1],
                              rotate: [0, 360]
                            }}
                            transition={{ duration: 6, repeat: Infinity }}
                          >
                            <Sparkles className="w-6 h-6 text-white" />
                          </motion.div>
                        </motion.div>

                        {/* Decorative elements around image */}
                        <motion.div
                          className="absolute -top-4 -left-4 w-8 h-8 bg-emerald-400 rounded-full opacity-60"
                          animate={{ 
                            scale: [1, 1.8, 1],
                            opacity: [0.6, 1, 0.6]
                          }}
                          transition={{ duration: 3, repeat: Infinity }}
                        />
                        <motion.div
                          className="absolute -bottom-4 -right-4 w-6 h-6 bg-blue-400 rounded-full opacity-60"
                          animate={{ 
                            scale: [1, 1.5, 1],
                            opacity: [0.6, 1, 0.6]
                          }}
                          transition={{ duration: 2, repeat: Infinity, delay: 1 }}
                        />
                      </div>
                    </motion.div>
                  </div>
                )}
              </motion.div>
            )}
          </AnimatePresence>
        </div>
      </div>

      {/* Progress dots - Fixed at bottom with enhanced animations */}
      <div className="flex justify-center space-x-3 pb-8">
        {splashContent.map((_, index) => (
          <motion.div
            key={index}
            className={`rounded-full transition-all duration-700 ${
              index === currentStep 
                ? theme === 'light' 
                  ? 'bg-emerald-500 w-12 h-4' 
                  : 'bg-emerald-400 w-12 h-4'
                : index < currentStep
                ? theme === 'light'
                  ? 'bg-emerald-300 w-4 h-4'
                  : 'bg-emerald-700 w-4 h-4'
                : theme === 'light'
                ? 'bg-gray-300 w-4 h-4'
                : 'bg-gray-700 w-4 h-4'
            }`}
            animate={index === currentStep ? {
              scale: [1, 1.3, 1],
              opacity: [0.7, 1, 0.7]
            } : {}}
            transition={{ duration: 1.5, repeat: Infinity }}
            whileHover={{ scale: 1.2 }}
          />
        ))}
      </div>

      {/* Welcome message - Enhanced */}
      <AnimatePresence>
        {currentStep >= splashContent.length && (
          <motion.div
            className="absolute inset-0 flex items-center justify-center"
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{ opacity: 1, scale: 1 }}
            exit={{ opacity: 0, scale: 0.8 }}
          >
            <div className="text-center">
              <h2 className={`text-4xl font-bold mb-6 ${
                theme === 'light' ? 'text-slate-800' : 'text-white'
              }`}>
                {t('¡Bienvenido')}
                {userName ? `, ${userName}` : ''}!
              </h2>
              <p className={`text-xl mb-8 ${
                theme === 'light' ? 'text-slate-700' : 'text-gray-300'
              }`}>
                {t('Estamos preparando tu experiencia personalizada...')}
              </p>

              {/* Enhanced loading indicator */}
              <div className="flex flex-col items-center">
                <div className="relative w-24 h-24 mb-6">
                  <motion.div 
                    className={`absolute inset-0 border-4 rounded-full ${
                      theme === 'light' ? 'border-emerald-200' : 'border-emerald-900'
                    }`}
                  />
                  <motion.div 
                    className={`absolute inset-0 border-4 border-t-transparent rounded-full ${
                      theme === 'light' ? 'border-emerald-500' : 'border-emerald-500'
                    }`}
                    animate={{ rotate: 360 }}
                    transition={{ duration: 1.5, repeat: Infinity, ease: "linear" }}
                  />
                  <motion.div 
                    className={`absolute inset-2 border-2 border-r-transparent rounded-full ${
                      theme === 'light' ? 'border-blue-400' : 'border-blue-400'
                    }`}
                    animate={{ rotate: -360 }}
                    transition={{ duration: 2, repeat: Infinity, ease: "linear" }}
                  />
                  <motion.div 
                    className={`absolute inset-4 border-2 border-l-transparent rounded-full ${
                      theme === 'light' ? 'border-purple-400' : 'border-purple-400'
                    }`}
                    animate={{ rotate: 360 }}
                    transition={{ duration: 2.5, repeat: Infinity, ease: "linear" }}
                  />
                </div>
                <p className={`text-lg font-medium ${
                  theme === 'light' ? 'text-slate-600' : 'text-gray-400'
                }`}>
                  {t('Estamos preparando tu experiencia personalizada...')}
                </p>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </motion.div>
  );
}