import React, { useState, useEffect } from 'react';
import { Activity, Plus, Clock, Target, Trophy, Calendar, Loader, CheckCircle, Globe } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useHabits } from '../../hooks/useHabits';
import { useTheme } from '../../contexts/ThemeContext';
import AnimatedCard, { useTypewriter, ParticleEffect } from '../ui/AnimatedCard';
import PowerPointTransition from '../ui/PowerPointTransition';

export default function Activities({ currentLanguage = 'es' }) {
  const { availableHabits, userHabits, loading, addHabit, removeHabit, updateHabit, createCustomHabit } = useHabits();
  const [showAddForm, setShowAddForm] = useState(false);
  const [selectedHabits, setSelectedHabits] = useState<number[]>([]);
  const [customHabitText, setCustomHabitText] = useState('');
  const [isAdding, setIsAdding] = useState(false);
  const [editingHabit, setEditingHabit] = useState<{ id: number, description: string } | null>(null);
  const { theme } = useTheme();
  const [isSmartwatch, setIsSmartwatch] = useState(false);

  // Detect if we're on a smartwatch-sized screen
  useEffect(() => {
    const checkSmartwatch = () => {
      setIsSmartwatch(window.innerWidth < 280);
    };
    
    checkSmartwatch();
    window.addEventListener('resize', checkSmartwatch);
    return () => window.removeEventListener('resize', checkSmartwatch);
  }, []);

  const handleAddHabits = async () => {
    if (selectedHabits.length === 0 && !customHabitText.trim()) return;
    
    setIsAdding(true);
    try {
      // Add selected habits
      for (const habitId of selectedHabits) {
        await addHabit(habitId);
      }
      
      // Add custom habit if provided
      if (customHabitText.trim()) {
        await createCustomHabit(customHabitText.trim());
      }
      
      setSelectedHabits([]);
      setCustomHabitText('');
      setShowAddForm(false);
    } catch (error) {
      console.error('Error adding habits:', error);
      alert('Error al agregar los hábitos. Puede que algunos ya los tengas registrados.');
    } finally {
      setIsAdding(false);
    }
  };

  const handleHabitToggle = (habitId: number) => {
    setSelectedHabits(prev => 
      prev.includes(habitId) 
        ? prev.filter(id => id !== habitId)
        : [...prev, habitId]
    );
  };

  const handleRemoveHabit = async (habitId: number) => {
    try {
      await removeHabit(habitId);
    } catch (error) {
      console.error('Error removing habit:', error);
      alert('Error al remover el hábito.');
    }
  };

  const startEditHabit = (habit: { id: number, description: string }) => {
    setEditingHabit(habit);
  };

  const saveEditedHabit = async () => {
    if (!editingHabit) return;
    
    try {
      await updateHabit(editingHabit.id, editingHabit.description);
      setEditingHabit(null);
    } catch (error) {
      console.error('Error updating habit:', error);
      alert('Error al actualizar el hábito.');
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

  const completedToday = userHabits.filter(h => {
    const today = new Date().toDateString();
    const habitDate = new Date(h.registration_date || '').toDateString();
    return today === habitDate;
  }).length;

  const totalTime = completedToday * 15;
  const bestStreak = Math.max(userHabits.length, 1);

  const translations = {
    es: {
      activities: "Actividades de Bienestar",
      addHabit: "Agregar Hábito",
      currentStatus: "Completados Hoy",
      timeInvested: "Tiempo Invertido",
      activeHabits: "Hábitos Activos",
      addNewHabits: "Agregar Nuevos Hábitos de Bienestar",
      selectHabits: "Selecciona uno o más hábitos",
      add: "Agregar",
      adding: "Agregando...",
      cancel: "Cancelar",
      myHabits: "Mis Hábitos de Bienestar",
      noHabits: "Aún no tienes hábitos registrados.",
      addFirstHabit: "¡Agrega tu primer hábito de bienestar!",
      remove: "Remover",
      minutes: "min",
      of: "de",
      customHabit: "Hábito personalizado",
      enterCustomHabit: "Ingresa un hábito personalizado...",
      edit: "Editar",
      save: "Guardar",
      update: "Actualizar"
    },
    en: {
      activities: "Wellness Activities",
      addHabit: "Add Habit",
      currentStatus: "Completed Today",
      timeInvested: "Time Invested",
      activeHabits: "Active Habits",
      addNewHabits: "Add New Wellness Habits",
      selectHabits: "Select one or more habits",
      add: "Add",
      adding: "Adding...",
      cancel: "Cancel",
      myHabits: "My Wellness Habits",
      noHabits: "You don't have any habits registered yet.",
      addFirstHabit: "Add your first wellness habit!",
      remove: "Remove",
      minutes: "min",
      of: "of",
      customHabit: "Custom habit",
      enterCustomHabit: "Enter a custom habit...",
      edit: "Edit",
      save: "Save",
      update: "Update"
    },
    fr: {
      activities: "Activités de Bien-être",
      addHabit: "Ajouter Habitude",
      currentStatus: "Complétés Aujourd'hui",
      timeInvested: "Temps Investi",
      activeHabits: "Habitudes Actives",
      addNewHabits: "Ajouter de Nouvelles Habitudes de Bien-être",
      selectHabits: "Sélectionnez une ou plusieurs habitudes",
      add: "Ajouter",
      adding: "Ajout en cours...",
      cancel: "Annuler",
      myHabits: "Mes Habitudes de Bien-être",
      noHabits: "Vous n'avez pas encore d'habitudes enregistrées.",
      addFirstHabit: "Ajoutez votre première habitude de bien-être!",
      remove: "Supprimer",
      minutes: "min",
      of: "sur",
      customHabit: "Habitude personnalisée",
      enterCustomHabit: "Entrez une habitude personnalisée...",
      edit: "Modifier",
      save: "Enregistrer",
      update: "Mettre à jour"
    },
    pt: {
      activities: "Atividades de Bem-estar",
      addHabit: "Adicionar Hábito",
      currentStatus: "Completados Hoje",
      timeInvested: "Tempo Investido",
      activeHabits: "Hábitos Ativos",
      addNewHabits: "Adicionar Novos Hábitos de Bem-estar",
      selectHabits: "Selecione um ou mais hábitos",
      add: "Adicionar",
      adding: "Adicionando...",
      cancel: "Cancelar",
      myHabits: "Meus Hábitos de Bem-estar",
      noHabits: "Você ainda não tem hábitos registrados.",
      addFirstHabit: "Adicione seu primeiro hábito de bem-estar!",
      remove: "Remover",
      minutes: "min",
      of: "de",
      customHabit: "Hábito personalizado",
      enterCustomHabit: "Digite um hábito personalizado...",
      edit: "Editar",
      save: "Salvar",
      update: "Atualizar"
    }
  };

  const t = translations[currentLanguage as keyof typeof translations];

  const titleText = t.activities;
  const typedTitle = useTypewriter(titleText, 80);

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-96">
        <motion.div
          animate={{ 
            rotate: 360
          }}
          transition={{ 
            duration: 2, 
            repeat: Infinity,
            ease: "linear"
          }}
        >
          <Loader className="w-4 h-4 sm:w-8 sm:h-8 text-emerald-600 drop-shadow-lg" />
        </motion.div>
      </div>
    );
  }

  return (
    <div className="space-y-3 sm:space-y-6 relative">
      {/* Efecto de partículas de fondo */}
      <ParticleEffect count={15} color="emerald" />

      {/* Header con animación de máquina de escribir */}
      <PowerPointTransition type="spiral" duration={1500}>
        <motion.div 
          className="flex items-center justify-between"
          whileHover={{ scale: 1.01 }}
        >
          <div className="flex items-center">
            <motion.div
              animate={{ 
                rotate: [0, 360],
                scale: [1, 1.3, 1],
                filter: ["drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))", "drop-shadow(0 0 10px rgba(16, 185, 129, 0.8))", "drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))"]
              }}
              transition={{ 
                duration: 3, 
                repeat: Infinity,
                ease: "easeInOut"
              }}
            >
              <Activity className="w-3 h-3 sm:w-6 sm:h-6 text-emerald-600 dark:text-emerald-400 mr-1.5 sm:mr-3" />
            </motion.div>
            <motion.h2 
              className={`text-sm sm:text-2xl font-bold ${
                theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
              }`}
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.5 }}
            >
              {typedTitle}
              <motion.span
                animate={{ opacity: [1, 0] }}
                transition={{ duration: 0.8, repeat: Infinity, repeatType: "reverse" }}
              >
                |
              </motion.span>
            </motion.h2>
          </div>
          <motion.button
            onClick={() => setShowAddForm(true)}
            className="bg-emerald-600 text-white px-2 py-1 sm:px-4 sm:py-2 rounded-lg hover:bg-emerald-700 transition-colors flex items-center relative overflow-hidden text-xxs sm:text-sm"
            whileHover={{ scale: 1.05, rotate: 2 }}
            whileTap={{ scale: 0.95 }}
            initial={{ opacity: 0, x: 50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 1 }}
          >
            <motion.div
              className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
              initial={{ x: '-100%' }}
              whileHover={{ x: '100%' }}
              transition={{ duration: 0.6 }}
            />
            <motion.div
              animate={{ rotate: [0, 90, 180, 270, 360], scale: [1, 1.2, 1] }}
              transition={{ duration: 3, repeat: Infinity, ease: "linear" }}
            >
              <Plus className="w-2 h-2 sm:w-4 sm:h-4 mr-1 sm:mr-2" />
            </motion.div>
            <span className="relative z-10">{t.addHabit}</span>
          </motion.button>
        </motion.div>
      </PowerPointTransition>

      {/* Stats con animaciones escalonadas tipo PowerPoint - Responsive Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-6">
        {[
          {
            icon: Target,
            label: t.currentStatus,
            value: `${completedToday}/${userHabits.length}`,
            gradient: 'from-emerald-400 to-emerald-600',
            bgLight: 'bg-gradient-to-br from-emerald-50 to-emerald-100',
            borderLight: 'border-emerald-200',
            type: 'bounce' as const,
            delay: 0.2
          },
          {
            icon: Clock,
            label: t.timeInvested,
            value: `${totalTime} ${t.minutes}`,
            gradient: 'from-blue-400 to-blue-600',
            bgLight: 'bg-gradient-to-br from-blue-50 to-blue-100',
            borderLight: 'border-blue-200',
            type: 'flip' as const,
            delay: 0.4
          },
          {
            icon: Trophy,
            label: t.activeHabits,
            value: userHabits.length,
            gradient: 'from-purple-400 to-purple-600',
            bgLight: 'bg-gradient-to-br from-purple-50 to-purple-100',
            borderLight: 'border-purple-200',
            type: 'glow' as const,
            delay: 0.6
          }
        ].map((stat, index) => (
          <AnimatedCard
            key={index}
            delay={stat.delay}
            type={stat.type}
            hoverScale={1.08}
            hoverRotate={index % 2 === 0 ? 3 : -3}
            className={`rounded-lg sm:rounded-2xl p-2 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform relative overflow-hidden ${
              theme === 'light'
                ? `${stat.bgLight} border-2 ${stat.borderLight}`
                : 'bg-white dark:bg-gray-800 shadow-sm'
            }`}
          >
            {/* Efecto de brillo en hover */}
            <motion.div
              className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
              initial={{ x: '-100%' }}
              whileHover={{ x: '100%' }}
              transition={{ duration: 0.8 }}
            />
            
            <div className="flex items-center relative z-10">
              <motion.div 
                className={`p-1.5 sm:p-3 rounded-lg ${
                  theme === 'light' ? 'bg-white/80' : 'bg-gray-100 dark:bg-gray-700'
                }`}
                whileHover={{ 
                  scale: 1.2, 
                  rotate: 15,
                  boxShadow: "0 10px 25px -5px rgba(0, 0, 0, 0.1)"
                }}
                transition={{ type: "spring", stiffness: 300 }}
              >
                <stat.icon className="w-3 h-3 sm:w-6 sm:h-6 text-emerald-600 dark:text-emerald-400" />
              </motion.div>
              <div className="ml-2 sm:ml-4">
                <motion.p 
                  className={`text-xxs sm:text-sm font-medium ${
                    theme === 'light' ? 'text-gray-700' : 'text-gray-600 dark:text-gray-400'
                  }`}
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: stat.delay + 0.2 }}
                >
                  {stat.label}
                </motion.p>
                <motion.p 
                  className={`text-sm sm:text-2xl font-bold ${
                    theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
                  }`}
                  initial={{ opacity: 0, scale: 0.5 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: stat.delay + 0.4, type: "spring" }}
                >
                  {stat.value}
                </motion.p>
              </div>
            </div>
          </AnimatedCard>
        ))}
      </div>

      {/* Add Habit Form con animación de entrada espectacular */}
      <AnimatePresence>
        {showAddForm && (
          <PowerPointTransition type="cube" duration={800}>
            <motion.div 
              className={`rounded-lg sm:rounded-2xl p-3 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
                theme === 'light'
                  ? 'bg-gradient-to-br from-white to-emerald-50 border-2 border-emerald-200'
                  : 'bg-white dark:bg-gray-800 shadow-sm'
              }`}
              layout
              initial={{ opacity: 0, height: 0, scale: 0.8 }}
              animate={{ opacity: 1, height: 'auto', scale: 1 }}
              exit={{ opacity: 0, height: 0, scale: 0.8 }}
              whileHover={{ scale: 1.01, y: -3 }}
            >
              {/* Efectos de fondo animados */}
              <motion.div
                className="absolute inset-0 bg-gradient-to-r from-emerald-400/10 to-blue-400/10"
                animate={{ 
                  background: [
                    'linear-gradient(45deg, rgba(16, 185, 129, 0.1), rgba(59, 130, 246, 0.1))',
                    'linear-gradient(45deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1))'
                  ]
                }}
                transition={{ duration: 5, repeat: Infinity }}
              />
              
              <div className="relative z-10">
                <motion.h3 
                  className={`text-xs sm:text-lg font-semibold mb-2 sm:mb-4 ${
                    theme === 'light' ? 'text-emerald-900' : 'text-gray-900 dark:text-white'
                  }`}
                  initial={{ opacity: 0, y: -20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: 0.2 }}
                >
                  {t.addNewHabits}
                </motion.h3>
                
                <div className="space-y-2 sm:space-y-4">
                  <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.4 }}
                  >
                    <label className={`block text-xxs sm:text-sm font-medium mb-1 sm:mb-2 ${
                      theme === 'light' ? 'text-emerald-800' : 'text-gray-700 dark:text-gray-300'
                    }`}>
                      {t.selectHabits}
                    </label>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5 sm:gap-3">
                      {availableHabits
                        .filter(habit => !userHabits.some(uh => uh.habit_id === habit.id))
                        .map((habit, index) => (
                          <motion.button
                            key={habit.id}
                            type="button"
                            onClick={() => handleHabitToggle(habit.id)}
                            className={`p-1.5 sm:p-3 rounded-lg text-xxs sm:text-sm font-medium transition-all duration-300 flex items-center ${
                              selectedHabits.includes(habit.id)
                                ? theme === 'light'
                                  ? 'bg-emerald-200 text-emerald-900 border-2 border-emerald-400'
                                  : 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 border-2 border-emerald-500'
                                : theme === 'light'
                                ? 'bg-white text-emerald-800 hover:bg-emerald-100 border-2 border-emerald-200 hover:border-emerald-300'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 border-2 border-transparent'
                            }`}
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.5 + index * 0.05 }}
                            whileHover={{ scale: 1.05 }}
                            whileTap={{ scale: 0.95 }}
                          >
                            <motion.div
                              className={`w-3 h-3 sm:w-5 sm:h-5 rounded-full flex items-center justify-center mr-1 sm:mr-2 ${
                                selectedHabits.includes(habit.id)
                                  ? 'bg-emerald-500'
                                  : theme === 'light'
                                  ? 'bg-emerald-100 border-2 border-emerald-300'
                                  : 'bg-gray-600 border-2 border-emerald-600'
                              }`}
                              whileHover={{ scale: 1.2 }}
                            >
                              {selectedHabits.includes(habit.id) ? (
                                <CheckCircle className="w-2 h-2 sm:w-4 sm:h-4 text-white" />
                              ) : (
                                <CheckCircle className="w-2 h-2 sm:w-4 sm:h-4 text-emerald-300 dark:text-emerald-600" />
                              )}
                            </motion.div>
                            <span className="line-clamp-1">{habit.description}</span>
                          </motion.button>
                        ))}
                    </div>
                  </motion.div>

                  {/* Custom Habit Input */}
                  <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.5 }}
                  >
                    <label className={`block text-xxs sm:text-sm font-medium mb-1 sm:mb-2 ${
                      theme === 'light' ? 'text-emerald-800' : 'text-gray-700 dark:text-gray-300'
                    }`}>
                      {t.customHabit}
                    </label>
                    <input
                      type="text"
                      value={customHabitText}
                      onChange={(e) => setCustomHabitText(e.target.value)}
                      placeholder={t.enterCustomHabit}
                      className={`w-full p-1.5 sm:p-3 rounded-lg text-xxs sm:text-sm border-2 transition-all duration-300 ${
                        theme === 'light'
                          ? 'bg-white text-emerald-800 border-emerald-200 focus:border-emerald-400 focus:ring-emerald-400'
                          : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:border-emerald-500 focus:ring-emerald-500'
                      }`}
                    />
                  </motion.div>
                  
                  <motion.div 
                    className="flex space-x-2 sm:space-x-3"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.6 }}
                  >
                    <motion.button
                      onClick={handleAddHabits}
                      disabled={(selectedHabits.length === 0 && !customHabitText.trim()) || isAdding}
                      className="bg-emerald-600 text-white px-2 py-1 sm:px-4 sm:py-2 rounded-lg hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center relative overflow-hidden text-xxs sm:text-sm"
                      whileHover={{ scale: 1.05 }}
                      whileTap={{ scale: 0.95 }}
                    >
                      <motion.div
                        className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
                        initial={{ x: '-100%' }}
                        whileHover={{ x: '100%' }}
                        transition={{ duration: 0.6 }}
                      />
                      <motion.div
                        className="relative z-10 flex items-center"
                        animate={isAdding ? { scale: [1, 1.1, 1] } : {}}
                        transition={{ duration: 0.5, repeat: Infinity }}
                      >
                        {isAdding ? (
                          <>
                            <motion.div
                              animate={{ rotate: 360 }}
                              transition={{ duration: 1, repeat: Infinity, ease: "linear" }}
                            >
                              <Loader className="w-2 h-2 sm:w-4 sm:h-4 mr-1 sm:mr-2" />
                            </motion.div>
                            {t.adding}
                          </>
                        ) : (
                          <>
                            {t.add} {selectedHabits.length > 0 ? `(${selectedHabits.length})` : ''}
                            {customHabitText.trim() && selectedHabits.length > 0 ? ' + 1' : ''}
                            {customHabitText.trim() && selectedHabits.length === 0 ? '(1)' : ''}
                          </>
                        )}
                      </motion.div>
                    </motion.button>
                    
                    <motion.button
                      onClick={() => {
                        setShowAddForm(false);
                        setSelectedHabits([]);
                        setCustomHabitText('');
                      }}
                      className={`px-2 py-1 sm:px-4 sm:py-2 rounded-lg transition-colors text-xxs sm:text-sm ${
                        theme === 'light'
                          ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200'
                          : 'bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-400 dark:hover:bg-gray-500'
                      }`}
                      whileHover={{ scale: 1.05 }}
                      whileTap={{ scale: 0.95 }}
                    >
                      {t.cancel}
                    </motion.button>
                  </motion.div>
                </div>
              </div>
            </motion.div>
          </PowerPointTransition>
        )}
      </AnimatePresence>

      {/* User Habits List con animaciones individuales */}
      <PowerPointTransition type="honeycomb" delay={800}>
        <motion.div 
          className={`rounded-lg sm:rounded-2xl p-3 sm:p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
            theme === 'light'
              ? 'bg-gradient-to-br from-white to-blue-50 border-2 border-blue-200'
              : 'bg-white dark:bg-gray-800 shadow-sm'
          }`}
          whileHover={{ scale: 1.01, y: -3 }}
        >
          <motion.h3 
            className={`text-xs sm:text-lg font-semibold mb-2 sm:mb-4 ${
              theme === 'light' ? 'text-blue-900' : 'text-gray-900 dark:text-white'
            }`}
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 1 }}
          >
            {t.myHabits}
          </motion.h3>
          
          {userHabits.length === 0 ? (
            <motion.div 
              className="text-center py-4 sm:py-8"
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ delay: 1.2 }}
            >
              <motion.div
                animate={{ 
                  rotate: [0, 10, -10, 0],
                  scale: [1, 1.1, 1],
                  filter: ["drop-shadow(0 0 0px rgba(107, 114, 128, 0.5))", "drop-shadow(0 0 10px rgba(107, 114, 128, 0.8))", "drop-shadow(0 0 0px rgba(107, 114, 128, 0.5))"]
                }}
                transition={{ 
                  duration: 3, 
                  repeat: Infinity,
                  repeatType: "reverse"
                }}
              >
                <Activity className="w-6 h-6 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-4 opacity-50 text-gray-400" />
              </motion.div>
              <p className={`text-xxs sm:text-base ${
                theme === 'light' ? 'text-blue-800' : 'text-gray-500 dark:text-gray-400'
              }`}>
                {t.noHabits}
              </p>
              <p className={`text-xxs sm:text-sm ${
                theme === 'light' ? 'text-blue-700' : 'text-gray-500 dark:text-gray-400'
              }`}>
                {t.addFirstHabit}
              </p>
            </motion.div>
          ) : (
            <div className="space-y-1.5 sm:space-y-3">
              {userHabits.map((userHabit, index) => {
                const isToday = new Date().toDateString() === new Date(userHabit.registration_date || '').toDateString();
                
                return (
                  <motion.div
                    key={userHabit.id}
                    className={`p-2 sm:p-4 rounded-lg sm:rounded-2xl border-2 transition-all duration-300 group relative overflow-hidden ${
                      isToday
                        ? theme === 'light'
                          ? 'border-emerald-300 bg-emerald-100 shadow-lg'
                          : 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30'
                        : theme === 'light'
                        ? 'border-blue-200 hover:border-blue-300 bg-white hover:bg-blue-50 shadow-md'
                        : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 hover:shadow-md'
                    }`}
                    initial={{ opacity: 0, x: -50, scale: 0.9 }}
                    animate={{ opacity: 1, x: 0, scale: 1 }}
                    transition={{ 
                      delay: 1.4 + index * 0.1,
                      type: "spring",
                      stiffness: 100
                    }}
                    whileHover={{ 
                      scale: 1.02, 
                      x: 10,
                      transition: { duration: 0.2 }
                    }}
                  >
                    {/* Efecto de brillo en hover */}
                    <motion.div
                      className="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent"
                      initial={{ x: '-100%' }}
                      whileHover={{ x: '100%' }}
                      transition={{ duration: 0.8 }}
                    />
                    
                    <div className="flex items-center justify-between relative z-10">
                      <div className="flex items-center space-x-1.5 sm:space-x-3">
                        <motion.div 
                          className={`w-3 h-3 sm:w-6 sm:h-6 rounded-full flex items-center justify-center ${
                            isToday
                              ? 'bg-emerald-600 border-emerald-600'
                              : theme === 'light'
                              ? 'bg-emerald-100 border-2 border-emerald-400'
                              : 'bg-gray-600 border-2 border-emerald-600'
                          }`}
                          whileHover={{ scale: 1.2, rotate: 180 }}
                          transition={{ duration: 0.3 }}
                          onClick={() => {
                            // This would toggle the completion status in a real app
                            alert('Esta función marcaría el hábito como completado hoy');
                          }}
                        >
                          <CheckCircle className={`w-2 h-2 sm:w-4 sm:h-4 ${
                            isToday ? 'text-white' : theme === 'light' ? 'text-emerald-400' : 'text-emerald-600'
                          }`} />
                        </motion.div>
                        
                        <div>
                          {editingHabit && editingHabit.id === userHabit.habit_id ? (
                            <div className="flex items-center">
                              <input
                                type="text"
                                value={editingHabit.description}
                                onChange={(e) => setEditingHabit({...editingHabit, description: e.target.value})}
                                className={`w-full p-1 sm:p-2 text-xxs sm:text-sm border rounded-lg ${
                                  theme === 'light'
                                    ? 'border-blue-300 focus:border-blue-500 focus:ring-blue-500'
                                    : 'border-gray-600 dark:border-gray-500 focus:border-blue-500 focus:ring-blue-500 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
                                }`}
                                autoFocus
                              />
                              <motion.button
                                onClick={saveEditedHabit}
                                className={`ml-2 p-1 sm:p-2 rounded-lg ${
                                  theme === 'light'
                                    ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                    : 'bg-emerald-700 text-emerald-100 hover:bg-emerald-600'
                                }`}
                                whileHover={{ scale: 1.1 }}
                                whileTap={{ scale: 0.9 }}
                              >
                                {t.save}
                              </motion.button>
                            </div>
                          ) : (
                            <motion.p 
                              className={`font-medium text-xxs sm:text-base ${
                                isToday
                                  ? theme === 'light'
                                    ? 'text-emerald-900'
                                    : 'text-emerald-900 dark:text-emerald-100'
                                  : theme === 'light'
                                  ? 'text-blue-900'
                                  : 'text-gray-900 dark:text-white'
                              }`}
                              initial={{ opacity: 0 }}
                              animate={{ opacity: 1 }}
                              transition={{ delay: 1.6 + index * 0.1 }}
                            >
                              {userHabit.habits.description}
                            </motion.p>
                          )}
                          <motion.div 
                            className={`flex items-center space-x-2 sm:space-x-4 text-xxs sm:text-sm ${
                              theme === 'light' 
                                ? isToday ? 'text-emerald-800' : 'text-blue-700'
                                : 'text-gray-600 dark:text-gray-400'
                            }`}
                            initial={{ opacity: 0, y: 10 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 1.8 + index * 0.1 }}
                          >
                            <span className="flex items-center">
                              <motion.div
                                animate={{ rotate: [0, 360] }}
                                transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
                              >
                                <Calendar className="w-2 h-2 sm:w-3 sm:h-3 mr-0.5 sm:mr-1" />
                              </motion.div>
                              <span className="line-clamp-1">Agregado {formatDate(userHabit.registration_date || '')}</span>
                            </span>
                          </motion.div>
                        </div>
                      </div>
                      
                      <div className="flex space-x-1 sm:space-x-2">
                        {!editingHabit || editingHabit.id !== userHabit.habit_id ? (
                          <motion.button
                            onClick={() => startEditHabit({
                              id: userHabit.habit_id,
                              description: userHabit.habits.description
                            })}
                            className="text-blue-500 hover:text-blue-700 text-xxs sm:text-sm font-medium px-1.5 py-0.5 sm:px-3 sm:py-1 rounded-lg hover:bg-blue-50 transition-all duration-200 relative overflow-hidden"
                            whileHover={{ scale: 1.1, rotate: -5 }}
                            whileTap={{ scale: 0.9 }}
                          >
                            <motion.div
                              className="absolute inset-0 bg-blue-100"
                              initial={{ scale: 0 }}
                              whileHover={{ scale: 1 }}
                              transition={{ duration: 0.2 }}
                            />
                            <span className="relative z-10">{t.edit}</span>
                          </motion.button>
                        ) : null}
                        
                        <motion.button
                          onClick={() => handleRemoveHabit(userHabit.habit_id)}
                          className="text-red-500 hover:text-red-700 text-xxs sm:text-sm font-medium px-1.5 py-0.5 sm:px-3 sm:py-1 rounded-lg hover:bg-red-50 transition-all duration-200 relative overflow-hidden"
                          whileHover={{ scale: 1.1, rotate: 5 }}
                          whileTap={{ scale: 0.9 }}
                        >
                          <motion.div
                            className="absolute inset-0 bg-red-100"
                            initial={{ scale: 0 }}
                            whileHover={{ scale: 1 }}
                            transition={{ duration: 0.2 }}
                          />
                          <span className="relative z-10">{t.remove}</span>
                        </motion.button>
                      </div>
                    </div>
                  </motion.div>
                );
              })}
            </div>
          )}
        </motion.div>
      </PowerPointTransition>
    </div>
  );
}