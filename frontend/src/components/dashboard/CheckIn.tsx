import React, { useState, useEffect, useRef } from 'react';
import { Heart, MessageCircle, Calendar, Target, Award, Clock, Loader, CheckCircle, Play, Pause, Mic, MicOff, Volume2 } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useTheme } from '../../context/ThemeContext';
import AnimatedCard, { useTypewriter, ParticleEffect } from '../ui/AnimatedCard';
import PowerPointTransition from '../ui/PowerPointTransition';

function CheckIn({ currentLanguage = 'es' }) {
  const [selectedMood, setSelectedMood] = useState<number | null>(null);
  const [notes, setNotes] = useState('');
  const [activities, setActivities] = useState<number[]>([]);
  const [isRecording, setIsRecording] = useState(false);
  const [voiceNote, setVoiceNote] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [aiResponse, setAiResponse] = useState('');

  const { blogs, createBlog, loading: blogsLoading } = useEmotionalBlogs();
  const { emotionalTypes, loading: typesLoading } = useEmotionalTypes();
  const { availableHabits, addHabit, loading: habitsLoading } = useHabits();
  const { theme } = useTheme();
  const { 
    generateSpeech, 
    playAudio, 
    pauseAudio, 
    isGenerating, 
    isPlaying,
    startListening
  } = useElevenLabsVoice({
    onTranscriptionReceived: (text) => {
      setVoiceNote(text);
      setIsRecording(false);
      
      // Generate AI response to the voice note
      const aiResponses = [
        "Entiendo que te sientas ansioso por el trabajo. Es completamente normal sentir esa presión. ¿Qué aspectos específicos te generan más ansiedad?",
        "Me parece muy positivo que mantengas una actitud positiva a pesar de la ansiedad. Los ejercicios de respiración son una excelente herramienta. ¿Has probado también la meditación guiada?",
        "La ansiedad laboral es algo que muchas personas experimentan. Tus ejercicios de respiración son una gran estrategia. ¿Hay alguna otra técnica que te haya funcionado en el pasado?",
        "Reconocer tus emociones como lo estás haciendo es el primer paso hacia el bienestar. ¿Te gustaría que exploremos juntos algunas técnicas adicionales para manejar la ansiedad laboral?"
      ];
      
      const randomResponse = aiResponses[Math.floor(Math.random() * aiResponses.length)];
      setAiResponse(randomResponse);
      
      // Generate speech for AI response after a short delay
      setTimeout(() => {
        generateSpeech(randomResponse);
      }, 1000);
      
      // Track voice transcription with Sentry
      Sentry.addBreadcrumb({
        category: 'voice',
        message: 'Voice transcription received',
        level: 'info',
      });
    }
  });

  // Mapear tipos emocionales de la DB a moods con emojis
  const moods = emotionalTypes.map((type) => ({
    emoji: getMoodEmoji(type.id),
    label: type.description,
    value: type.id,
    color: getMoodColor(type.id),
    description: `Me siento ${type.description.toLowerCase()}`
  }));

  function getMoodEmoji(typeId: number): string {
    const emojiMap: { [key: number]: string } = {
      1: '😢', 2: '😕', 3: '😐', 4: '😊', 5: '😄',
      6: '😰', 7: '😤', 8: '😌', 9: '💪', 10: '🙏'
    };
    return emojiMap[typeId] || '😐';
  }

  function getMoodColor(typeId: number): string {
    const colorMap: { [key: number]: string } = {
      1: 'text-red-500', 2: 'text-orange-500', 3: 'text-yellow-500',
      4: 'text-emerald-500', 5: 'text-green-500', 6: 'text-purple-500',
      7: 'text-red-600', 8: 'text-blue-500', 9: 'text-emerald-600', 10: 'text-blue-600'
    };
    return colorMap[typeId] || 'text-gray-500';
  }

  const handleActivityToggle = (habitId: number) => {
    setActivities(prev => 
      prev.includes(habitId) 
        ? prev.filter(id => id !== habitId)
        : [...prev, habitId]
    );
  };

  const toggleRecording = () => {
    setIsRecording(!isRecording);
    if (!isRecording) {
      // Start listening for voice input
      startListening();
      
      // Track recording start with Sentry
      Sentry.addBreadcrumb({
        category: 'voice',
        message: 'Started voice recording',
        level: 'info',
      });
    } else {
      // Track recording stop with Sentry
      Sentry.addBreadcrumb({
        category: 'voice',
        message: 'Stopped voice recording',
        level: 'info',
      });
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedMood) return;

    setIsSubmitting(true);
    try {
      // Track check-in submission with Sentry
      Sentry.addBreadcrumb({
        category: 'check-in',
        message: `Submitting check-in with mood: ${selectedMood}`,
        level: 'info',
      });
      
      const blogContent = [
        notes,
        voiceNote,
        activities.length > 0 ? `Actividades realizadas: ${activities.map(id => 
          availableHabits.find(h => h.id === id)?.description
        ).join(', ')}` : ''
      ].filter(Boolean).join('\n\n');

      await createBlog(blogContent || 'Check-in diario', selectedMood);

      for (const habitId of activities) {
        try {
          await addHabit(habitId);
        } catch (error) {
          console.log('Habit already exists or error adding:', error);
        }
      }

      setSelectedMood(null);
      setNotes('');
      setActivities([]);
      setVoiceNote('');
      setAiResponse('');
      
      // Track successful check-in with Sentry
      Sentry.captureMessage('Check-in completed successfully', 'info');
      
      alert('¡Check-in guardado exitosamente! Tu compañero IA ha registrado tu estado emocional.');
    } catch (error) {
      console.error('Error saving check-in:', error);
      
      // Track error with Sentry
      Sentry.captureException(error);
      
      alert('Error al guardar el check-in. Por favor intenta de nuevo.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - date.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) return 'Hoy';
    if (diffDays === 2) return 'Ayer';
    return `Hace ${diffDays - 1} días`;
  };

  const translations = {
    es: {
      greeting: "Hola, es un nuevo día lleno de posibilidades. Respira profundo y recuerda: eres suficiente tal como eres. ¿Cómo te sientes en este momento?",
      dailyCheckIn: "Check-in Emocional Diario",
      howFeelToday: "¿Cómo te sientes hoy?",
      voiceCheckIn: "Check-in por Voz",
      voiceCompanion: "Habla con tu compañero emocional",
      startTalking: "Comenzar a hablar",
      stopRecording: "Detener grabación",
      recording: "Grabando...",
      voiceTranscription: "Transcripción de tu voz",
      activitiesToday: "¿Qué actividades realizaste hoy?",
      additionalReflections: "Reflexiones adicionales",
      shareThoughts: "Comparte lo que está en tu mente...",
      saveCheckIn: "Guardar mi check-in diario",
      checkInHistory: "Historial de Check-ins",
      noCheckIns: "Aún no tienes check-ins registrados",
      completeFirst: "¡Completa tu primer check-in arriba!",
      aiResponse: "Respuesta de tu compañero IA",
      playResponse: "Reproducir respuesta",
      pauseResponse: "Pausar respuesta"
    },
    en: {
      greeting: "Hello, it's a new day full of possibilities. Take a deep breath and remember: you are enough just as you are. How are you feeling right now?",
      dailyCheckIn: "Daily Emotional Check-in",
      howFeelToday: "How are you feeling today?",
      voiceCheckIn: "Voice Check-in",
      voiceCompanion: "Talk to your emotional companion",
      startTalking: "Start talking",
      stopRecording: "Stop recording",
      recording: "Recording...",
      voiceTranscription: "Voice transcription",
      activitiesToday: "What activities did you do today?",
      additionalReflections: "Additional reflections",
      shareThoughts: "Share what's on your mind...",
      saveCheckIn: "Save my daily check-in",
      checkInHistory: "Check-in History",
      noCheckIns: "You don't have any check-ins yet",
      completeFirst: "Complete your first check-in above!",
      aiResponse: "Your AI companion's response",
      playResponse: "Play response",
      pauseResponse: "Pause response"
    },
    fr: {
      greeting: "Bonjour, c'est un nouveau jour plein de possibilités. Respirez profondément et rappelez-vous: vous êtes suffisant tel que vous êtes. Comment vous sentez-vous en ce moment?",
      dailyCheckIn: "Check-in Émotionnel Quotidien",
      howFeelToday: "Comment vous sentez-vous aujourd'hui?",
      voiceCheckIn: "Check-in Vocal",
      voiceCompanion: "Parlez à votre compagnon émotionnel",
      startTalking: "Commencer à parler",
      stopRecording: "Arrêter l'enregistrement",
      recording: "Enregistrement...",
      voiceTranscription: "Transcription de votre voix",
      activitiesToday: "Quelles activités avez-vous fait aujourd'hui?",
      additionalReflections: "Réflexions supplémentaires",
      shareThoughts: "Partagez ce qui vous préoccupe...",
      saveCheckIn: "Enregistrer mon check-in quotidien",
      checkInHistory: "Historique des Check-ins",
      noCheckIns: "Vous n'avez pas encore de check-ins",
      completeFirst: "Complétez votre premier check-in ci-dessus!",
      aiResponse: "Réponse de votre compagnon IA",
      playResponse: "Jouer la réponse",
      pauseResponse: "Mettre en pause"
    },
    pt: {
      greeting: "Olá, é um novo dia cheio de possibilidades. Respire fundo e lembre-se: você é suficiente como está. Como você está se sentindo agora?",
      dailyCheckIn: "Check-in Emocional Diário",
      howFeelToday: "Como você está se sentindo hoje?",
      voiceCheckIn: "Check-in por Voz",
      voiceCompanion: "Fale com seu companheiro emocional",
      startTalking: "Começar a falar",
      stopRecording: "Parar gravação",
      recording: "Gravando...",
      voiceTranscription: "Transcrição da sua voz",
      activitiesToday: "Quais atividades você fez hoje?",
      additionalReflections: "Reflexões adicionais",
      shareThoughts: "Compartilhe o que está em sua mente...",
      saveCheckIn: "Salvar meu check-in diário",
      checkInHistory: "Histórico de Check-ins",
      noCheckIns: "Você ainda não tem check-ins",
      completeFirst: "Complete seu primeiro check-in acima!",
      aiResponse: "Resposta do seu companheiro IA",
      playResponse: "Reproduzir resposta",
      pauseResponse: "Pausar resposta"
    }
  };

  const t = translations[currentLanguage as keyof typeof translations];
  const aiGreeting = t.greeting;
  const typedGreeting = useTypewriter(aiGreeting, 50);

  if (typesLoading || habitsLoading) {
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
          {/* Efectos de fondo animados */}
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
          
          {/* Partículas flotantes */}
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
              <h2 className="text-4xl font-bold">{t.dailyCheckIn}</h2>
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
                {t.howFeelToday} 💭
              </motion.label>
              
              <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                {moods.map((mood, index) => (
                  <motion.button
                    key={mood.value}
                    type="button"
                    onClick={() => {
                      setSelectedMood(mood.value);
                      
                      // Track mood selection with Sentry
                      Sentry.addBreadcrumb({
                        category: 'check-in',
                        message: `Selected mood: ${mood.label}`,
                        level: 'info',
                      });
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

            {/* Voice Check-in with ElevenLabs Integration */}
            <PowerPointTransition type="shimmer" delay={1200}>
              <motion.div 
                className={`rounded-3xl p-8 relative overflow-hidden ${
                  theme === 'light'
                    ? 'bg-gradient-to-r from-blue-100 to-purple-100 border-2 border-blue-200 shadow-lg'
                    : 'bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200 dark:border-blue-800'
                }`}
                whileHover={{ scale: 1.02, y: -3 }}
              >
                <div className="relative z-10">
                  <motion.div 
                    className="flex items-center mb-6"
                    initial={{ opacity: 0, x: -30 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 1.4 }}
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
                      <Mic className="w-8 h-8 text-blue-600 dark:text-blue-400 mr-4" />
                    </motion.div>
                    <h3 className={`text-xl font-bold ${
                      theme === 'light' ? 'text-blue-900' : 'text-gray-900 dark:text-white'
                    }`}>
                      {t.voiceCheckIn} (Powered by ElevenLabs)
                    </h3>
                  </motion.div>
                  
                  <motion.p 
                    className={`mb-6 text-lg ${
                      theme === 'light' ? 'text-blue-800' : 'text-gray-600 dark:text-gray-400'
                    }`}
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 1.6 }}
                  >
                    {t.voiceCompanion}
                  </motion.p>
                  
                  <div className="flex items-center space-x-6">
                    <motion.button
                      type="button"
                      onClick={toggleRecording}
                      disabled={isGenerating || isPlaying}
                      className={`flex items-center px-8 py-4 rounded-2xl font-semibold transition-all duration-500 relative overflow-hidden ${
                        isRecording
                          ? 'bg-red-500 text-white hover:bg-red-600'
                          : 'bg-blue-500 text-white hover:bg-blue-600'
                      } disabled:opacity-50 disabled:cursor-not-allowed`}
                      whileHover={{ scale: 1.05 }}
                      whileTap={{ scale: 0.95 }}
                      animate={isRecording ? {
                        boxShadow: [
                          "0 0 20px rgba(239, 68, 68, 0.5)",
                          "0 0 40px rgba(239, 68, 68, 0.8)",
                          "0 0 20px rgba(239, 68, 68, 0.5)"
                        ]
                      } : {}}
                      transition={{ duration: 1, repeat: Infinity }}
                    >
                      {/* Efectos de ondas para grabación */}
                      <AnimatePresence>
                        {isRecording && (
                          <>
                            <motion.div 
                              className="absolute inset-0 bg-red-400 rounded-2xl"
                              initial={{ scale: 1, opacity: 0.3 }}
                              animate={{ scale: 1.5, opacity: 0 }}
                              transition={{ duration: 1, repeat: Infinity }}
                            />
                            <motion.div 
                              className="absolute inset-0 bg-red-300 rounded-2xl"
                              initial={{ scale: 1, opacity: 0.2 }}
                              animate={{ scale: 1.8, opacity: 0 }}
                              transition={{ duration: 1, repeat: Infinity, delay: 0.3 }}
                            />
                          </>
                        )}
                      </AnimatePresence>
                      
                      <motion.div
                        animate={isRecording ? { scale: [1, 1.2, 1] } : {}}
                        transition={{ duration: 0.5, repeat: Infinity }}
                        className="relative z-10 flex items-center"
                      >
                        {isRecording ? (
                          <>
                            <MicOff className="w-6 h-6 mr-3" />
                            {t.stopRecording}
                          </>
                        ) : (
                          <>
                            <Mic className="w-6 h-6 mr-3" />
                            {t.startTalking}
                          </>
                        )}
                      </motion.div>
                    </motion.button>
                    
                    <AnimatePresence>
                      {isRecording && (
                        <motion.div 
                          className="flex items-center space-x-3"
                          initial={{ opacity: 0, x: 20 }}
                          animate={{ opacity: 1, x: 0 }}
                          exit={{ opacity: 0, x: 20 }}
                        >
                          <div className="flex space-x-1">
                            {[...Array(5)].map((_, i) => (
                              <motion.div
                                key={i}
                                className="w-2 bg-red-500 rounded-full"
                                animate={{
                                  height: [15, Math.random() * 30 + 15, 15],
                                  opacity: [0.5, 1, 0.5]
                                }}
                                transition={{
                                  duration: 0.5,
                                  repeat: Infinity,
                                  delay: i * 0.1
                                }}
                              />
                            ))}
                          </div>
                          <motion.span 
                            className="text-red-600 dark:text-red-400 font-semibold text-lg"
                            animate={{ opacity: [1, 0.5, 1] }}
                            transition={{ duration: 1, repeat: Infinity }}
                          >
                            {t.recording}
                          </motion.span>
                        </motion.div>
                      )}
                    </AnimatePresence>
                  </div>

                  <AnimatePresence>
                    {voiceNote && (
                      <motion.div 
                        className={`mt-6 p-6 rounded-2xl border transition-all duration-500 ${
                          theme === 'light'
                            ? 'bg-white/90 border-blue-200 shadow-sm backdrop-blur-sm'
                            : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600'
                        }`}
                        initial={{ opacity: 0, height: 0 }}
                        animate={{ opacity: 1, height: 'auto' }}
                        exit={{ opacity: 0, height: 0 }}
                        whileHover={{ scale: 1.02 }}
                      >
                        <div className="flex items-center mb-3">
                          <motion.div
                            animate={{ scale: [1, 1.2, 1] }}
                            transition={{ duration: 2, repeat: Infinity }}
                          >
                            <Volume2 className="w-5 h-5 text-gray-500 dark:text-gray-400 mr-3" />
                          </motion.div>
                          <span className={`text-sm font-semibold ${
                            theme === 'light' ? 'text-blue-900' : 'text-gray-700 dark:text-gray-300'
                          }`}>
                            {t.voiceTranscription}
                          </span>
                        </div>
                        <p className={`${
                          theme === 'light' ? 'text-blue-800' : 'text-gray-600 dark:text-gray-400'
                        }`}>
                          {voiceNote}
                        </p>
                      </motion.div>
                    )}
                  </AnimatePresence>

                  {/* AI Response with ElevenLabs Voice */}
                  <AnimatePresence>
                    {aiResponse && (
                      <motion.div 
                        className={`mt-6 p-6 rounded-2xl border transition-all duration-500 ${
                          theme === 'light'
                            ? 'bg-emerald-50/90 border-emerald-200 shadow-sm backdrop-blur-sm'
                            : 'bg-emerald-900/20 dark:bg-emerald-900/30 border-emerald-800/50 dark:border-emerald-800/30'
                        }`}
                        initial={{ opacity: 0, height: 0 }}
                        animate={{ opacity: 1, height: 'auto' }}
                        exit={{ opacity: 0, height: 0 }}
                        whileHover={{ scale: 1.02 }}
                      >
                        <div className="flex items-center mb-3">
                          <motion.div
                            animate={{ 
                              scale: [1, 1.2, 1],
                              rotate: [0, 10, -10, 0]
                            }}
                            transition={{ duration: 3, repeat: Infinity }}
                          >
                            <Heart className="w-5 h-5 text-emerald-500 dark:text-emerald-400 mr-3" />
                          </motion.div>
                          <span className={`text-sm font-semibold ${
                            theme === 'light' ? 'text-emerald-900' : 'text-emerald-300'
                          }`}>
                            {t.aiResponse}
                          </span>
                        </div>
                        <p className={`mb-4 ${
                          theme === 'light' ? 'text-emerald-800' : 'text-emerald-300'
                        }`}>
                          {aiResponse}
                        </p>
                        
                        <div className="flex items-center">
                          <motion.button
                            type="button"
                            onClick={isPlaying ? pauseAudio : playAudio}
                            className={`flex items-center px-4 py-2 rounded-xl font-medium transition-all duration-300 ${
                              theme === 'light'
                                ? 'bg-emerald-200 text-emerald-800 hover:bg-emerald-300'
                                : 'bg-emerald-800/50 text-emerald-200 hover:bg-emerald-700/50'
                            }`}
                            whileHover={{ scale: 1.05 }}
                            whileTap={{ scale: 0.95 }}
                            disabled={isGenerating}
                            onClick={() => {
                              if (isPlaying) {
                                pauseAudio();
                                
                                // Track audio pause with Sentry
                                Sentry.addBreadcrumb({
                                  category: 'audio',
                                  message: 'Paused AI response audio',
                                  level: 'info',
                                });
                              } else {
                                playAudio();
                                
                                // Track audio play with Sentry
                                Sentry.addBreadcrumb({
                                  category: 'audio',
                                  message: 'Played AI response audio',
                                  level: 'info',
                                });
                              }
                            }}
                          >
                            {isGenerating ? (
                              <Loader className="w-4 h-4 mr-2 animate-spin" />
                            ) : isPlaying ? (
                              <Pause className="w-4 h-4 mr-2" />
                            ) : (
                              <Play className="w-4 h-4 mr-2" />
                            )}
                            {isGenerating 
                              ? 'Generando audio...' 
                              : isPlaying 
                                ? t.pauseResponse 
                                : t.playResponse
                            }
                          </motion.button>
                          
                          {isGenerating && (
                            <motion.div 
                              className="ml-3 text-emerald-600 dark:text-emerald-400 text-sm"
                              animate={{ opacity: [0.5, 1, 0.5] }}
                              transition={{ duration: 1.5, repeat: Infinity }}
                            >
                              Generando voz con ElevenLabs...
                            </motion.div>
                          )}
                        </div>
                      </motion.div>
                    )}
                  </AnimatePresence>
                </div>
              </motion.div>
            </PowerPointTransition>

            {/* Activities */}
            <PowerPointTransition type="wipe" delay={1600}>
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
                  {t.activitiesToday} 🎯
                </motion.label>
                
                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                  {availableHabits.map((habit, index) => (
                    <motion.button
                      key={habit.id}
                      type="button"
                      onClick={() => {
                        handleActivityToggle(habit.id);
                        
                        // Track activity toggle with Sentry
                        Sentry.addBreadcrumb({
                          category: 'check-in',
                          message: `Toggled activity: ${habit.description}`,
                          level: 'info',
                        });
                      }}
                      className={`p-5 rounded-2xl text-sm font-semibold transition-all duration-500 border-2 relative overflow-hidden group ${
                        activities.includes(habit.id)
                          ? theme === 'light'
                            ? 'bg-emerald-200 text-emerald-900 border-emerald-400 scale-105 shadow-xl'
                            : 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 border-emerald-500 scale-105'
                          : theme === 'light'
                          ? 'bg-white/90 text-emerald-800 hover:bg-emerald-100 border-emerald-200 hover:border-emerald-300 shadow-md hover:shadow-lg'
                          : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 border-transparent'
                      }`}
                      initial={{ opacity: 0, scale: 0.5, rotate: 90 }}
                      animate={{ opacity: 1, scale: 1, rotate: 0 }}
                      transition={{ 
                        delay: 2.2 + index * 0.05,
                        type: "spring",
                        stiffness: 150
                      }}
                      whileHover={{ 
                        scale: activities.includes(habit.id) ? 1.1 : 1.05, 
                        rotate: 3,
                        transition: { duration: 0.2 }
                      }}
                      whileTap={{ scale: 0.95 }}
                    >
                      {/* Efecto de brillo en hover */}
                      <motion.div 
                        className="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent"
                        initial={{ x: '-100%' }}
                        whileHover={{ x: '100%' }}
                        transition={{ duration: 0.7 }}
                      />
                      <span className="relative z-10">{habit.description}</span>
                    </motion.button>
                  ))}
                </div>
              </motion.div>
            </PowerPointTransition>

            {/* Notes */}
            <PowerPointTransition type="zoom" delay={2000}>
              <motion.div
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 2.2 }}
              >
                <motion.label 
                  className={`block text-xl font-bold mb-8 ${
                    theme === 'light' ? 'text-emerald-900' : 'text-gray-700 dark:text-gray-300'
                  }`}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: 2.4 }}
                >
                  {t.additionalReflections} ✍️
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
                  placeholder={t.shareThoughts}
                  initial={{ opacity: 0, scale: 0.9 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: 2.6 }}
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
              transition={{ delay: 2.8 }}
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
                  t.saveCheckIn + ' 💚'
                )}
              </motion.div>
            </motion.button>
          </form>
        </motion.div>
      </PowerPointTransition>

      {/* Recent Check-ins */}
      <PowerPointTransition type="spiral" delay={1000}>
        <motion.div 
          className={`rounded-3xl p-8 relative overflow-hidden ${
            theme === 'light'
              ? 'bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-200 shadow-xl'
              : 'bg-white dark:bg-gray-800 shadow-lg'
          }`}
          whileHover={{ scale: 1.01, y: -3 }}
        >
          <div className="relative z-10">
            <motion.div 
              className="flex items-center mb-8"
              initial={{ opacity: 0, x: -30 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 1.2 }}
            >
              <motion.div
                animate={{ 
                  rotate: [0, 360],
                  scale: [1, 1.1, 1]
                }}
                transition={{ 
                  duration: 4, 
                  repeat: Infinity,
                  ease: "linear"
                }}
              >
                <Calendar className="w-8 h-8 text-gray-600 dark:text-gray-400 mr-4" />
              </motion.div>
              <h3 className={`text-2xl font-bold ${
                theme === 'light' ? 'text-purple-900' : 'text-gray-900 dark:text-white'
              }`}>
                {t.checkInHistory}
              </h3>
            </motion.div>
            
            {blogsLoading ? (
              <div className="flex items-center justify-center py-12">
                <motion.div
                  animate={{ rotate: 360 }}
                  transition={{ duration: 2, repeat: Infinity, ease: "linear" }}
                >
                  <Loader className="w-8 h-8 text-emerald-600" />
                </motion.div>
              </div>
            ) : (
              <div className="space-y-4">
                {blogs.slice(0, 5).map((blog, index) => {
                  const mood = emotionalTypes.find(t => t.id === blog.emotional_type_id);
                  const moodEmoji = getMoodEmoji(blog.emotional_type_id);
                  
                  return (
                    <motion.div 
                      key={blog.id} 
                      className={`flex items-center justify-between p-6 rounded-2xl transition-all duration-500 group relative overflow-hidden ${
                        theme === 'light'
                          ? 'bg-gradient-to-r from-white to-purple-50 hover:from-purple-50 hover:to-purple-100 border-2 border-purple-200 hover:shadow-xl shadow-lg'
                          : 'bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 hover:shadow-md'
                      }`}
                      initial={{ opacity: 0, x: -50 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: 1.4 + index * 0.1 }}
                      whileHover={{ scale: 1.02, x: 10 }}
                      onClick={() => {
                        // Track check-in history item click with Sentry
                        Sentry.addBreadcrumb({
                          category: 'check-in',
                          message: `Viewed check-in history item: ${blog.id}`,
                          level: 'info',
                        });
                      }}
                    >
                      {/* Efecto de brillo en hover */}
                      <motion.div 
                        className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
                        initial={{ x: '-100%' }}
                        whileHover={{ x: '100%' }}
                        transition={{ duration: 0.7 }}
                      />
                      
                      <div className="flex items-center relative z-10">
                        <motion.div 
                          className="text-4xl mr-6"
                          whileHover={{ 
                            scale: 1.2,
                            rotate: [0, 10, -10, 0]
                          }}
                          transition={{ duration: 0.5 }}
                        >
                          {moodEmoji}
                        </motion.div>
                        <div>
                          <p className={`font-bold text-lg ${
                            theme === 'light' ? 'text-purple-900' : 'text-gray-900 dark:text-white'
                          }`}>
                            {formatDate(blog.blog_date || '')}
                          </p>
                          <p className={`${
                            theme === 'light' ? 'text-purple-700' : 'text-gray-600 dark:text-gray-400'
                          }`}>
                            {mood?.description} • {blog.summary.slice(0, 50)}...
                          </p>
                        </div>
                      </div>
                      <motion.div
                        whileHover={{ scale: 1.2, rotate: 15 }}
                        transition={{ duration: 0.3 }}
                      >
                        <Target className="w-6 h-6 text-gray-400 group-hover:text-emerald-500 transition-colors duration-500 relative z-10" />
                      </motion.div>
                    </motion.div>
                  );
                })}
                
                {blogs.length === 0 && (
                  <motion.div 
                    className="text-center py-12"
                    initial={{ opacity: 0, scale: 0.8 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ delay: 1.4 }}
                  >
                    <motion.div
                      animate={{ 
                        scale: [1, 1.1, 1],
                        opacity: [0.5, 1, 0.5]
                      }}
                      transition={{ 
                        duration: 2, 
                        repeat: Infinity 
                      }}
                    >
                      <Heart className="w-16 h-16 mx-auto mb-6 text-gray-400" />
                    </motion.div>
                    <p className={`text-lg ${
                      theme === 'light' ? 'text-purple-800' : 'text-gray-500 dark:text-gray-400'
                    }`}>
                      {t.noCheckIns}
                    </p>
                    <p className={`${
                      theme === 'light' ? 'text-purple-700' : 'text-gray-500 dark:text-gray-500'
                    }`}>
                      {t.completeFirst}
                    </p>
                  </motion.div>
                )}
              </div>
            )}
          </div>
        </motion.div>
      </PowerPointTransition>
    </div>
  );
}

export default Sentry.withProfiler(CheckIn);