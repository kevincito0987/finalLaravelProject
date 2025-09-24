import React, { useState, useEffect } from 'react';
// Se mantienen solo los iconos necesarios para el layout de mood y notas
import { Heart, Loader, CheckCircle } from 'lucide-react'; 
import { motion } from 'framer-motion';
import { useTheme } from '../../context/ThemeContext';
// Se asumen estas importaciones de UI/animación
import { useTypewriter, ParticleEffect } from '../ui/AnimatedCard'; 
import PowerPointTransition from '../ui/PowerPointTransition';

// Se eliminó la prop currentLanguage
function CheckIn() {
  const [selectedMood, setSelectedMood] = useState<number | null>(null);
  const [notes, setNotes] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [loading, setLoading] = useState(true); // Estado de carga simulado para el efecto visual inicial
  
  const { theme } = useTheme();

  // --- DATOS Y LÓGICA ESTÁTICA SIMPLIFICADA (Reemplazo de useEmotionalTypes) ---
  const moods = [
    { emoji: '😊', label: 'Feliz', value: 4, color: 'text-emerald-500', description: 'Me siento feliz' },
    { emoji: '😢', label: 'Triste', value: 1, color: 'text-red-500', description: 'Me siento triste' },
    { emoji: '😰', label: 'Ansioso', value: 6, color: 'text-purple-500', description: 'Me siento ansioso' },
    { emoji: '😌', label: 'Relajado', value: 8, color: 'text-blue-500', description: 'Me siento relajado' },
    { emoji: '😤', label: 'Enojado', value: 7, color: 'text-red-600', description: 'Me siento enojado' }
  ];

  // --- EFECTO DE CARGA INICIAL SIMULADO ---
  useEffect(() => {
    const timer = setTimeout(() => {
        setLoading(false);
    }, 1000); // 1 segundo de carga simulada
    return () => clearTimeout(timer);
  }, []);

  // --- LÓGICA DE ENVÍO SIMPLIFICADA (Reemplazo de handleSubmit) ---
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedMood) return;

    setIsSubmitting(true);
    
    // Simula una llamada a API
    setTimeout(() => {
      setIsSubmitting(false);
      
      // Reseteo de estados
      setSelectedMood(null);
      setNotes('');
      
      alert('¡Check-in guardado exitosamente! (Simulación)');
    }, 1500);
  };
  
  // --- TEXTOS HARDCODEADOS EN ESPAÑOL (Reemplazo de traducciones) ---
  const aiGreeting = "Hola, es un nuevo día lleno de posibilidades. Respira profundo y recuerda: eres suficiente tal como eres. ¿Cómo te sientes en este momento?";
  const typedGreeting = useTypewriter(aiGreeting, 50);

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-96">
        <motion.div
          animate={{ rotate: 360 }}
          transition={{ duration: 2, repeat: Infinity, ease: "linear" }}
        >
          <Loader className="w-8 h-8 text-emerald-600" />
        </motion.div>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto space-y-8 relative">
      {/* Efecto de partículas de fondo */}
      <ParticleEffect count={20} color="emerald" />

      {/* Header with AI Greeting - Animación espectacular */}
      <PowerPointTransition type="dissolve" duration={1500}>
        <motion.div 
          className="bg-gradient-to-r from-emerald-500 to-blue-500 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden"
          whileHover={{ scale: 1.02, y: -5 }}
          initial={{ opacity: 0, scale: 0.8, rotateY: 90 }}
          animate={{ opacity: 1, scale: 1, rotateY: 0 }}
          transition={{ duration: 1, ease: [0.25, 0.46, 0.45, 0.94] }}
        >
          {/* ... Efectos de fondo y Partículas (Se mantienen las animaciones visuales) ... */}
          <motion.div 
            className="absolute inset-0 bg-white/10"
            animate={{ 
              background: [
                'radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%)',
                'radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 0%, transparent 50%)',
                'radial-gradient(circle at 50% 20%, rgba(255,255,255,0.1) 0%, transparent 50%)',
                'radial-gradient(circle at 50% 80%, rgba(255,255,255,0.1) 0%, transparent 50%)'
              ]
            }}
            transition={{ duration: 8, repeat: Infinity }}
          />
          <div className="absolute inset-0">
            {[...Array(15)].map((_, i) => (
              <motion.div
                key={i}
                className="absolute w-2 h-2 bg-white/20 rounded-full"
                animate={{
                  x: [0, Math.random() * 100 - 50],
                  y: [0, Math.random() * 100 - 50],
                  scale: [0, 1, 0],
                  opacity: [0, 1, 0]
                }}
                transition={{
                  duration: Math.random() * 4 + 2,
                  repeat: Infinity,
                  delay: Math.random() * 2
                }}
                style={{
                  left: `${Math.random() * 100}%`,
                  top: `${Math.random() * 100}%`
                }}
              />
            ))}
          </div>
          
          <div className="relative z-10">
            <motion.div 
              className="flex items-center mb-6"
              initial={{ opacity: 0, x: -50 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 0.5 }}
            >
              <motion.div
                animate={{ 
                  scale: [1, 1.2, 1],
                  rotate: [0, 10, -10, 0]
                }}
                transition={{ 
                  duration: 3, 
                  repeat: Infinity,
                  repeatDelay: 2
                }}
              >
                <Heart className="w-12 h-12 mr-4" />
              </motion.div>
              <h2 className="text-4xl font-bold">Check-in Emocional Diario</h2> 
            </motion.div>
            
            <motion.div 
              className="bg-white/20 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/30 transition-all duration-500 border border-white/30"
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.8 }}
              whileHover={{ scale: 1.02 }}
            >
              <motion.p 
                className="text-xl mb-3"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ delay: 1 }}
              >
                🤖 <strong>Tu compañero IA dice:</strong>
              </motion.p>
              <motion.p 
                className="text-2xl italic"
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ delay: 1.2 }}
              >
                "{typedGreeting}"
                <motion.span
                  animate={{ opacity: [1, 0] }}
                  transition={{ duration: 0.8, repeat: Infinity, repeatType: "reverse" }}
                >
                  |
                </motion.span>
              </motion.p>
            </motion.div>
          </div>
        </motion.div>
      </PowerPointTransition>

      {/* Formulario principal */}
      <PowerPointTransition type="honeycomb" delay={500}>
        <motion.div 
          className={`rounded-3xl p-8 backdrop-blur-sm relative overflow-hidden ${
            theme === 'light' 
              ? 'bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-200 shadow-xl' 
              : 'bg-white dark:bg-gray-800 shadow-lg'
          }`}
          whileHover={{ scale: 1.01, y: -3 }}
        >
          <form onSubmit={handleSubmit} className="space-y-10 relative z-10">
            {/* Mood Selection */}
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.3 }}
            >
              <motion.label 
                className={`block text-xl font-bold mb-8 ${
                  theme === 'light' ? 'text-emerald-900' : 'text-gray-700 dark:text-gray-300'
                }`}
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.5 }}
              >
                ¿Cómo te sientes hoy? 💭
              </motion.label>
              
              <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                {moods.map((mood, index) => (
                  <motion.button
                    key={mood.value}
                    type="button"
                    onClick={() => {
                      setSelectedMood(mood.value);
                    }}
                    className={`p-8 rounded-3xl border-2 transition-all duration-500 group relative overflow-hidden ${
                      selectedMood === mood.value
                        ? theme === 'light'
                          ? 'border-emerald-500 bg-emerald-200 shadow-2xl scale-110'
                          : 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 shadow-lg scale-110'
                        : theme === 'light'
                        ? 'border-emerald-300 hover:border-emerald-400 hover:bg-emerald-100 hover:shadow-xl bg-white/80'
                        : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 hover:shadow-md'
                    }`}
                    initial={{ opacity: 0, scale: 0.3, rotate: 180 }}
                    animate={{ opacity: 1, scale: 1, rotate: 0 }}
                    transition={{ 
                      delay: 0.7 + index * 0.1,
                      type: "spring",
                      stiffness: 200
                    }}
                    whileHover={{ 
                      scale: selectedMood === mood.value ? 1.15 : 1.1, 
                      rotate: 6,
                      transition: { duration: 0.2 }
                    }}
                    whileTap={{ scale: 0.95 }}
                  >
                    {/* Efecto de brillo en hover */}
                    <motion.div 
                      className="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent"
                      initial={{ x: '-100%' }}
                      whileHover={{ x: '100%' }}
                      transition={{ duration: 0.6 }}
                    />
                    
                    <motion.div 
                      className="text-5xl mb-4 relative z-10"
                      animate={selectedMood === mood.value ? {
                        scale: [1, 1.3, 1],
                        rotate: [0, 15, -15, 0]
                      } : {}}
                      transition={{ 
                        duration: 2, 
                        repeat: Infinity,
                        repeatDelay: 1
                      }}
                    >
                      {mood.emoji}
                    </motion.div>
                    <div className={`text-sm font-bold ${mood.color} mb-2 relative z-10`}>
                      {mood.label}
                    </div>
                    <div className={`text-xs relative z-10 ${
                      theme === 'light' ? 'text-emerald-800' : 'text-gray-600 dark:text-gray-400'
                    }`}>
                      {mood.description}
                    </div>
                  </motion.button>
                ))}
              </div>
            </motion.div>

            {/* Notes */}
            <PowerPointTransition type="zoom" delay={1600}>
              <motion.div
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 1.8 }}
              >
                <motion.label 
                  className={`block text-xl font-bold mb-8 ${
                    theme === 'light' ? 'text-emerald-900' : 'text-gray-700 dark:text-gray-300'
                  }`}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: 2 }}
                >
                  Reflexiones adicionales ✍️
                </motion.label>
                
                <motion.textarea
                  value={notes}
                  onChange={(e) => setNotes(e.target.value)}
                  rows={5}
                  className={`w-full px-6 py-4 border-2 rounded-2xl shadow-sm focus:ring-emerald-500 focus:border-emerald-500 resize-none transition-all duration-500 ${
                    theme === 'light'
                      ? 'border-emerald-200 bg-white/90 text-emerald-900 focus:bg-emerald-50 placeholder-emerald-600'
                      : 'border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white'
                  }`}
                  placeholder="Comparte lo que está en tu mente..."
                  initial={{ opacity: 0, scale: 0.9 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: 2.2 }}
                  whileFocus={{ scale: 1.02 }}
                />
              </motion.div>
            </PowerPointTransition>

            {/* Submit Button */}
            <motion.button
              type="submit"
              disabled={!selectedMood || isSubmitting}
              className="w-full bg-gradient-to-r from-emerald-600 to-blue-600 text-white py-5 px-8 rounded-2xl hover:from-emerald-700 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-500 font-bold text-xl shadow-2xl relative overflow-hidden group"
              initial={{ opacity: 0, y: 50 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 2.4 }}
              whileHover={{ scale: 1.02, y: -5 }}
              whileTap={{ scale: 0.98 }}
            >
              {/* Efecto de brillo en hover */}
              <motion.div 
                className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
                initial={{ x: '-100%' }}
                whileHover={{ x: '100%' }}
                transition={{ duration: 1 }}
              />
              
              <motion.div
                className="relative z-10 flex items-center justify-center"
                animate={isSubmitting ? { scale: [1, 1.05, 1] } : {}}
                transition={{ duration: 0.5, repeat: Infinity }}
              >
                {isSubmitting ? (
                  <>
                    <motion.div
                      animate={{ rotate: 360 }}
                      transition={{ duration: 1, repeat: Infinity, ease: "linear" }}
                    >
                      <Loader className="w-6 h-6 mr-3" />
                    </motion.div>
                    Guardando...
                  </>
                ) : (
                  'Guardar mi check-in diario 💚'
                )}
              </motion.div>
            </motion.button>
          </form>
        </motion.div>
      </PowerPointTransition>

      {/* Se eliminó la sección de "Historial de Check-ins" (Recent Check-ins) */}
    </div>
  );
}

// Se eliminó Sentry.withProfiler
export default CheckIn;