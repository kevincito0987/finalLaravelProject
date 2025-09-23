import React, { useState, useEffect } from 'react';
import { Heart, TrendingUp, MessageCircle, Target, Calendar, Award, Loader, Globe } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useEmotionalBlogs } from '../../hooks/useEmotionalBlogs';
import { useHabits } from '../../hooks/useHabits';
import { useEmotionalTypes } from '../../hooks/useEmotionalTypes';
import { useTheme } from '../../contexts/ThemeContext';
import AnimatedCard, { useTypewriter, ParticleEffect } from '../ui/AnimatedCard';
import PowerPointTransition from '../ui/PowerPointTransition';
import {
  LineChart,
  Line,
  BarChart,
  Bar,
  PieChart,
  Pie,
  Cell,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
  Area,
  AreaChart
} from 'recharts';

interface OverviewProps {
  currentLanguage?: string;
}

export default function Overview({ currentLanguage = 'es' }: OverviewProps) {
  const { blogs, loading: blogsLoading } = useEmotionalBlogs();
  const { userHabits, loading: habitsLoading } = useHabits();
  const { emotionalTypes } = useEmotionalTypes();
  const { theme } = useTheme();
  const [showCharts, setShowCharts] = useState(false);
  const [isSmartwatch, setIsSmartwatch] = useState(false);
  const [selectedMood, setSelectedMood] = useState<number | null>(4); // Default to 'Bien' (index 3)

  // Detect if we're on a smartwatch-sized screen
  useEffect(() => {
    const checkSmartwatch = () => {
      setIsSmartwatch(window.innerWidth < 280);
    };
    
    checkSmartwatch();
    window.addEventListener('resize', checkSmartwatch);
    return () => window.removeEventListener('resize', checkSmartwatch);
  }, []);

  // Calcular estadísticas basadas únicamente en datos de la DB
  const todaysMood = blogs.length > 0 ? blogs[0].emotional_type_id : 3;
  const weekStreak = Math.min(blogs.length, 7);
  const monthlyGoal = Math.min((blogs.length / 30) * 100, 100);
  const achievements = userHabits.length + blogs.length;

  // Generar datos de la semana basados en datos reales de la DB
  const generateWeeklyData = () => {
    const days = currentLanguage === 'es' 
      ? ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom']
      : currentLanguage === 'fr'
      ? ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']
      : currentLanguage === 'pt'
      ? ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom']
      : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    const now = new Date();
    
    return days.map((day, index) => {
      const date = new Date(now);
      date.setDate(date.getDate() - (6 - index));
      
      const dayBlogs = blogs.filter(blog => {
        const blogDate = new Date(blog.blog_date || '');
        return blogDate.toDateString() === date.toDateString();
      });
      
      const dayHabits = userHabits.filter(habit => {
        const habitDate = new Date(habit.registration_date || '');
        return habitDate.toDateString() === date.toDateString();
      });
      
      const avgMood = dayBlogs.length > 0 
        ? dayBlogs.reduce((sum, blog) => sum + blog.emotional_type_id, 0) / dayBlogs.length
        : 3;
      
      return {
        day,
        mood: Math.round(avgMood * 10) / 10, // Redondear a 1 decimal
        activities: dayHabits.length,
        date: date.toISOString().split('T')[0],
        moodLabel: emotionalTypes.find(t => t.id === Math.round(avgMood))?.description || 'Neutral'
      };
    });
  };

  const weeklyData = generateWeeklyData();

  // Colores para los gráficos
  const colors = {
    primary: theme === 'light' ? '#10b981' : '#34d399',
    secondary: theme === 'light' ? '#3b82f6' : '#60a5fa',
    accent: theme === 'light' ? '#8b5cf6' : '#a78bfa',
    warning: theme === 'light' ? '#f59e0b' : '#fbbf24',
    danger: theme === 'light' ? '#ef4444' : '#f87171',
    success: theme === 'light' ? '#22c55e' : '#4ade80',
    info: theme === 'light' ? '#06b6d4' : '#22d3ee',
    purple: theme === 'light' ? '#a855f7' : '#c084fc',
    pink: theme === 'light' ? '#ec4899' : '#f472b6',
    orange: theme === 'light' ? '#f97316' : '#fb923c'
  };

  // Custom tooltip para los gráficos
  const CustomTooltip = ({ active, payload, label }: any) => {
    if (active && payload && payload.length) {
      return (
        <div className={`p-2 sm:p-4 rounded-lg sm:rounded-xl shadow-lg border ${
          theme === 'light'
            ? 'bg-white border-gray-200'
            : 'bg-gray-800 border-gray-600'
        }`}>
          <p className={`font-semibold text-xs sm:text-sm ${
            theme === 'light' ? 'text-gray-900' : 'text-white'
          }`}>
            {label}
          </p>
          {payload.map((entry: any, index: number) => (
            <p key={index} style={{ color: entry.color }} className="text-xxs sm:text-xs">
              {entry.name}: {entry.value}
              {entry.name === 'mood' && ` (${entry.payload?.moodLabel})`}
            </p>
          ))}
        </div>
      );
    }
    return null;
  };

  const recentActivities = [
    ...blogs.slice(0, 2).map(blog => ({
      type: 'checkin',
      title: currentLanguage === 'es' ? 'Check-in emocional completado' : 
             currentLanguage === 'fr' ? 'Check-in émotionnel complété' :
             currentLanguage === 'pt' ? 'Check-in emocional concluído' :
             'Emotional check-in completed',
      time: formatTimeAgo(blog.blog_date || '', currentLanguage),
      icon: Heart,
      color: 'text-emerald-600 dark:text-emerald-400'
    })),
    ...userHabits.slice(0, 2).map(habit => ({
      type: 'habit',
      title: `${currentLanguage === 'es' ? 'Hábito' : 
              currentLanguage === 'fr' ? 'Habitude' :
              currentLanguage === 'pt' ? 'Hábito' :
              'Habit'}: ${habit.habits.description}`,
      time: formatTimeAgo(habit.registration_date || '', currentLanguage),
      icon: TrendingUp,
      color: 'text-blue-600 dark:text-blue-400'
    }))
  ].sort((a, b) => new Date(b.time).getTime() - new Date(a.time).getTime()).slice(0, 3);

  function formatTimeAgo(dateString: string, lang: string) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now.getTime() - date.getTime());
    const diffHours = Math.ceil(diffTime / (1000 * 60 * 60));
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (lang === 'es') {
      if (diffHours < 24) return `Hace ${diffHours} horas`;
      if (diffDays === 1) return 'Ayer';
      return `Hace ${diffDays} días`;
    } else if (lang === 'fr') {
      if (diffHours < 24) return `Il y a ${diffHours} heures`;
      if (diffDays === 1) return 'Hier';
      return `Il y a ${diffDays} jours`;
    } else if (lang === 'pt') {
      if (diffHours < 24) return `${diffHours} horas atrás`;
      if (diffDays === 1) return 'Ontem';
      return `${diffDays} dias atrás`;
    } else {
      if (diffHours < 24) return `${diffHours} hours ago`;
      if (diffDays === 1) return 'Yesterday';
      return `${diffDays} days ago`;
    }
  }

  const getMoodEmoji = (moodId: number) => {
    const emojiMap: { [key: number]: string } = {
      1: '😢', 2: '😕', 3: '😐', 4: '😊', 5: '😄',
      6: '😰', 7: '😤', 8: '😌', 9: '💪', 10: '🙏'
    };
    return emojiMap[moodId] || '😐';
  };

  const translations = {
    es: {
      welcomeText: "Tu Panel de Bienestar",
      currentStatus: "Estado Actual",
      currentStreak: "Racha Actual",
      monthlyProgress: "Progreso Mensual",
      achievements: "Logros",
      quickCheckIn: "Check-in Rápido",
      howFeelNow: "¿Cómo te sientes en este momento?",
      updateCheckIn: "Actualizar Check-in",
      recentActivity: "Actividad Reciente",
      noRecentActivity: "No hay actividad reciente",
      weeklyTrends: "Tendencias de Bienestar Semanal",
      visualizationAvailable: "Visualización de progreso disponible con más datos",
      continueTracking: "Continúa registrando tu bienestar diario",
      days: "días",
      neutral: "Neutral",
      emotionalState: "Estado Emocional",
      activities: "Actividades",
      trackProgress: "Rastrea tu progreso, conecta con otros y construye hábitos saludables."
    },
    en: {
      welcomeText: "Your Wellness Dashboard",
      currentStatus: "Current Status",
      currentStreak: "Current Streak",
      monthlyProgress: "Monthly Progress",
      achievements: "Achievements",
      quickCheckIn: "Quick Check-in",
      howFeelNow: "How are you feeling right now?",
      updateCheckIn: "Update Check-in",
      recentActivity: "Recent Activity",
      noRecentActivity: "No recent activity",
      weeklyTrends: "Weekly Wellness Trends",
      visualizationAvailable: "Progress visualization available with more data",
      continueTracking: "Continue tracking your daily wellness",
      days: "days",
      neutral: "Neutral",
      emotionalState: "Emotional State",
      activities: "Activities",
      trackProgress: "Track your progress, connect with others, and build healthy habits."
    },
    fr: {
      welcomeText: "Votre Tableau de Bord Bien-être",
      currentStatus: "État Actuel",
      currentStreak: "Série Actuelle",
      monthlyProgress: "Progrès Mensuel",
      achievements: "Réalisations",
      quickCheckIn: "Check-in Rapide",
      howFeelNow: "Comment vous sentez-vous en ce moment?",
      updateCheckIn: "Mettre à jour le Check-in",
      recentActivity: "Activité Récente",
      noRecentActivity: "Aucune activité récente",
      weeklyTrends: "Tendances Hebdomadaires de Bien-être",
      visualizationAvailable: "Visualisation des progrès disponible avec plus de données",
      continueTracking: "Continuez à suivre votre bien-être quotidien",
      days: "jours",
      neutral: "Neutre",
      emotionalState: "État Émotionnel",
      activities: "Activités",
      trackProgress: "Suivez vos progrès, connectez-vous avec d'autres et développez des habitudes saines."
    },
    pt: {
      welcomeText: "Seu Painel de Bem-estar",
      currentStatus: "Estado Atual",
      currentStreak: "Sequência Atual",
      monthlyProgress: "Progresso Mensal",
      achievements: "Conquistas",
      quickCheckIn: "Check-in Rápido",
      howFeelNow: "Como você está se sentindo agora?",
      updateCheckIn: "Atualizar Check-in",
      recentActivity: "Atividade Recente",
      noRecentActivity: "Nenhuma atividade recente",
      weeklyTrends: "Tendências Semanais de Bem-estar",
      visualizationAvailable: "Visualização de progresso disponível com mais dados",
      continueTracking: "Continue registrando seu bem-estar diário",
      days: "dias",
      neutral: "Neutro",
      emotionalState: "Estado Emocional",
      activities: "Atividades",
      trackProgress: "Acompanhe seu progresso, conecte-se com outros e construa hábitos saudáveis."
    }
  };

  const t = translations[currentLanguage as keyof typeof translations];

  const welcomeText = t.welcomeText;
  const typedText = useTypewriter(welcomeText, 100);

  // Efecto para mostrar gráficos después de un tiempo
  useEffect(() => {
    const timer = setTimeout(() => {
      setShowCharts(true);
    }, 1500);
    
    return () => clearTimeout(timer);
  }, []);

  if (blogsLoading || habitsLoading) {
    return (
      <div className="flex items-center justify-center min-h-96">
        <motion.div
          animate={{ 
            rotate: 360,
            scale: [1, 1.3, 1],
            filter: ["brightness(1) saturate(1)", "brightness(1.2) saturate(1.5)", "brightness(1) saturate(1)"]
          }}
          transition={{ 
            rotate: { duration: 2, repeat: Infinity, ease: "linear" },
            scale: { duration: 1.5, repeat: Infinity, repeatType: "reverse" },
            filter: { duration: 2, repeat: Infinity, repeatType: "reverse" }
          }}
        >
          <Loader className="w-8 h-8 text-emerald-600 drop-shadow-lg" />
        </motion.div>
      </div>
    );
  }

  const statsData = [
    {
      icon: Heart,
      label: t.currentStatus,
      value: (
        <div className="flex items-center">
          <motion.span 
            className="text-2xl sm:text-4xl mr-2 sm:mr-3"
            animate={{ 
              scale: [1, 1.2, 1],
              rotate: [0, 10, -10, 0],
              filter: ["drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))", "drop-shadow(0 0 8px rgba(16, 185, 129, 0.8))", "drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))"]
            }}
            transition={{ 
              duration: 2, 
              repeat: Infinity,
              repeatDelay: 3
            }}
          >
            {getMoodEmoji(todaysMood)}
          </motion.span>
          <span className="text-sm sm:text-lg font-semibold">
            {emotionalTypes.find(t => t.id === todaysMood)?.description || t.neutral}
          </span>
        </div>
      ),
      gradient: 'from-emerald-400 to-emerald-600',
      bgLight: 'bg-gradient-to-br from-emerald-50 to-emerald-100',
      bgDark: 'dark:bg-gray-800',
      borderLight: 'border-emerald-200',
      type: 'glow' as const
    },
    {
      icon: Calendar,
      label: t.currentStreak,
      value: `${weekStreak} ${t.days}`,
      gradient: 'from-blue-400 to-blue-600',
      bgLight: 'bg-gradient-to-br from-blue-50 to-blue-100',
      bgDark: 'dark:bg-gray-800',
      borderLight: 'border-blue-200',
      type: 'bounce' as const
    },
    {
      icon: Target,
      label: t.monthlyProgress,
      value: `${Math.round(monthlyGoal)}%`,
      gradient: 'from-purple-400 to-purple-600',
      bgLight: 'bg-gradient-to-br from-purple-50 to-purple-100',
      bgDark: 'dark:bg-gray-800',
      borderLight: 'border-purple-200',
      type: 'flip' as const
    },
    {
      icon: Award,
      label: t.achievements,
      value: achievements,
      gradient: 'from-orange-400 to-orange-600',
      bgLight: 'bg-gradient-to-br from-orange-50 to-orange-100',
      bgDark: 'dark:bg-gray-800',
      borderLight: 'border-orange-200',
      type: 'zoom' as const
    }
  ];

  return (
    <div className="space-y-3 sm:space-y-6 relative">
      {/* Efecto de partículas de fondo */}
      <ParticleEffect count={15} color="emerald" />

      {/* Welcome Section con animación de máquina de escribir */}
      <PowerPointTransition type="dissolve" duration={1200}>
        <motion.div 
          className="bg-gradient-to-r from-emerald-500 to-blue-500 rounded-xl sm:rounded-2xl p-4 sm:p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 relative overflow-hidden"
          whileHover={{ scale: 1.02, y: -5 }}
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
          
          <div className="relative z-10">
            <motion.h2 
              className="text-xl sm:text-3xl font-bold mb-1 sm:mb-3"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.5 }}
            >
              {typedText}
              <motion.span
                animate={{ opacity: [1, 0] }}
                transition={{ duration: 0.8, repeat: Infinity, repeatType: "reverse" }}
              >
                |
              </motion.span>
            </motion.h2>
            <motion.p 
              className="opacity-90 text-xs sm:text-lg"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 1 }}
            >
              {t.trackProgress}
            </motion.p>
          </div>
        </motion.div>
      </PowerPointTransition>

      {/* Quick Stats con animaciones escalonadas - Responsive Grid */}
      <div className="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-6">
        {statsData.map((stat, index) => (
          <AnimatedCard
            key={index}
            delay={index * 0.2}
            type={stat.type}
            hoverScale={1.08}
            hoverRotate={index % 2 === 0 ? 2 : -2}
            className={`${
              theme === 'light' 
                ? `${stat.bgLight} border-2 ${stat.borderLight} shadow-lg` 
                : `bg-white ${stat.bgDark} shadow-sm`
            } rounded-lg sm:rounded-2xl p-2 sm:p-6 backdrop-blur-sm relative overflow-hidden`}
          >
            {/* Efecto de brillo en hover */}
            <motion.div
              className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
              initial={{ x: '-100%' }}
              whileHover={{ x: '100%' }}
              transition={{ duration: 0.6 }}
            />
            
            <div className="flex items-center relative z-10">
              <motion.div 
                className={`p-2 sm:p-4 bg-gradient-to-r ${stat.gradient} rounded-lg sm:rounded-2xl shadow-lg`}
                whileHover={{ 
                  scale: 1.1, 
                  rotate: 12,
                  boxShadow: "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)"
                }}
                transition={{ type: "spring", stiffness: 300 }}
              >
                <stat.icon className="w-3 h-3 sm:w-7 sm:h-7 text-white" />
              </motion.div>
              <div className="ml-2 sm:ml-4">
                <p className={`text-xxs sm:text-sm font-semibold mb-0.5 sm:mb-1 ${
                  theme === 'light' ? 'text-gray-700' : 'text-gray-600 dark:text-gray-400'
                }`}>
                  {stat.label}
                </p>
                <div className={`text-sm sm:text-2xl font-bold ${
                  theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
                }`}>
                  {stat.value}
                </div>
              </div>
            </div>
          </AnimatedCard>
        ))}
      </div>

      {/* Quick Actions con transiciones PowerPoint - Responsive Layout */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-6">
        <PowerPointTransition type="wipe" delay={800}>
          <motion.div 
            className={`${
              theme === 'light' 
                ? 'bg-gradient-to-br from-white to-emerald-50 border-2 border-emerald-100 shadow-lg' 
                : 'bg-white dark:bg-gray-800 shadow-sm'
            } rounded-lg sm:rounded-2xl p-3 sm:p-8 backdrop-blur-sm relative overflow-hidden`}
            whileHover={{ scale: 1.02, y: -5 }}
          >
            <motion.h3 
              className={`text-sm sm:text-xl font-bold mb-2 sm:mb-4 ${
                theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
              }`}
              initial={{ opacity: 0, x: -20 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 1 }}
            >
              {t.quickCheckIn}
            </motion.h3>
            <motion.p 
              className={`mb-3 sm:mb-6 text-xxs sm:text-base ${
                theme === 'light' ? 'text-gray-700' : 'text-gray-600 dark:text-gray-400'
              }`}
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 1.2 }}
            >
              {t.howFeelNow}
            </motion.p>
            
            <div className="grid grid-cols-5 gap-1 sm:gap-6 mb-3 sm:mb-6">
              {[
                { emoji: '😢', label: 'Triste', value: 1 },
                { emoji: '😕', label: 'Bajo', value: 2 },
                { emoji: '😐', label: 'Neutral', value: 3 },
                { emoji: '😊', label: 'Bien', value: 4 },
                { emoji: '😄', label: 'Genial', value: 5 }
              ].map((mood, index) => (
                <motion.button
                  key={index}
                  onClick={() => setSelectedMood(mood.value)}
                  className={`p-1 sm:p-4 rounded-lg sm:rounded-2xl transition-all duration-300 group ${
                    selectedMood === mood.value 
                      ? theme === 'light'
                        ? 'bg-emerald-100 scale-110 shadow-md'
                        : 'bg-emerald-100 dark:bg-emerald-900 scale-110 shadow-md'
                      : theme === 'light'
                      ? 'hover:bg-emerald-50 border-2 border-transparent hover:border-emerald-200 hover:shadow-md'
                      : 'hover:bg-gray-100 dark:hover:bg-gray-700'
                  }`}
                  initial={{ opacity: 0, scale: 0.5, rotate: 180 }}
                  animate={{ opacity: 1, scale: 1, rotate: 0 }}
                  transition={{ 
                    delay: 1.4 + index * 0.1,
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
                  <span className="text-sm sm:text-2xl block">{mood.emoji}</span>
                </motion.button>
              ))}
            </div>
            
            <motion.button 
              className="w-full bg-gradient-to-r from-emerald-600 to-blue-600 text-white py-2 sm:py-3 px-3 sm:px-6 rounded-lg sm:rounded-2xl hover:from-emerald-700 hover:to-blue-700 transition-all duration-300 font-semibold text-xs sm:text-base relative overflow-hidden"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 2 }}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              <motion.div
                className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
                initial={{ x: '-100%' }}
                whileHover={{ x: '100%' }}
                transition={{ duration: 0.6 }}
              />
              <span className="relative z-10">{t.updateCheckIn}</span>
            </motion.button>
          </motion.div>
        </PowerPointTransition>

        <PowerPointTransition type="shimmer" delay={1000}>
          <motion.div 
            className={`${
              theme === 'light' 
                ? 'bg-gradient-to-br from-white to-blue-50 border-2 border-blue-100 shadow-lg' 
                : 'bg-white dark:bg-gray-800 shadow-sm'
            } rounded-lg sm:rounded-2xl p-3 sm:p-8 backdrop-blur-sm`}
            whileHover={{ scale: 1.02, y: -5 }}
          >
            <motion.h3 
              className={`text-sm sm:text-xl font-bold mb-3 sm:mb-6 ${
                theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
              }`}
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 1.2 }}
            >
              {t.recentActivity}
            </motion.h3>
            
            <div className="space-y-2 sm:space-y-4">
              {recentActivities.length > 0 ? (
                recentActivities.map((activity, index) => (
                  <motion.div 
                    key={index} 
                    className={`flex items-center p-2 sm:p-4 rounded-lg sm:rounded-2xl transition-all duration-300 group ${
                      theme === 'light'
                        ? 'bg-gradient-to-r from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 border border-gray-200 hover:shadow-md'
                        : 'bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600'
                    }`}
                    initial={{ opacity: 0, x: -50 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 1.4 + index * 0.2 }}
                    whileHover={{ scale: 1.02, x: 10 }}
                  >
                    <motion.div 
                      className="p-1.5 sm:p-3 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-lg sm:rounded-2xl mr-2 sm:mr-4 shadow-lg"
                      whileHover={{ 
                        scale: 1.1, 
                        rotate: 12,
                        boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1)"
                      }}
                    >
                      <activity.icon className="w-3 h-3 sm:w-5 sm:h-5 text-white" />
                    </motion.div>
                    <div>
                      <p className={`text-xxs sm:text-base font-semibold ${
                        theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
                      }`}>
                        {activity.title}
                      </p>
                      <p className={`text-xxs sm:text-sm ${
                        theme === 'light' ? 'text-gray-600' : 'text-gray-500 dark:text-gray-400'
                      }`}>
                        {activity.time}
                      </p>
                    </div>
                  </motion.div>
                ))
              ) : (
                <motion.div 
                  className="text-center py-4 sm:py-8"
                  initial={{ opacity: 0, scale: 0.8 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: 1.4 }}
                >
                  <motion.div
                    animate={{ 
                      scale: [1, 1.1, 1],
                      opacity: [0.5, 1, 0.5],
                      filter: ["drop-shadow(0 0 0px rgba(107, 114, 128, 0.5))", "drop-shadow(0 0 10px rgba(107, 114, 128, 0.8))", "drop-shadow(0 0 0px rgba(107, 114, 128, 0.5))"]
                    }}
                    transition={{ 
                      duration: 2, 
                      repeat: Infinity 
                    }}
                  >
                    <MessageCircle className="w-6 h-6 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-4 text-gray-400" />
                  </motion.div>
                  <p className={`text-xxs sm:text-sm ${
                    theme === 'light' ? 'text-gray-600' : 'text-gray-500 dark:text-gray-400'
                  }`}>
                    {t.noRecentActivity}
                  </p>
                </motion.div>
              )}
            </div>
          </motion.div>
        </PowerPointTransition>
      </div>

      {/* Progress Chart con animación de entrada espectacular - Responsive */}
      <PowerPointTransition type="cube" delay={1200}>
        <motion.div 
          className={`${
            theme === 'light' 
              ? 'bg-gradient-to-br from-white to-purple-50 border-2 border-purple-100 shadow-lg' 
              : 'bg-white dark:bg-gray-800 shadow-sm'
          } rounded-lg sm:rounded-2xl p-3 sm:p-8 backdrop-blur-sm relative overflow-hidden`}
          whileHover={{ scale: 1.01, y: -3 }}
        >
          <div className="flex items-center justify-between mb-3 sm:mb-6">
            <motion.h3 
              className={`text-sm sm:text-xl font-bold ${
                theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
              }`}
              initial={{ opacity: 0, y: -20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 1.4 }}
            >
              {t.weeklyTrends}
            </motion.h3>
            <motion.div
              animate={{ 
                rotate: [0, 360],
                scale: [1, 1.1, 1],
                filter: ["drop-shadow(0 0 0px rgba(107, 114, 128, 0.5))", "drop-shadow(0 0 5px rgba(107, 114, 128, 0.8))", "drop-shadow(0 0 0px rgba(107, 114, 128, 0.5))"]
              }}
              transition={{ 
                duration: 4, 
                repeat: Infinity,
                ease: "linear"
              }}
            >
              <TrendingUp className="w-3 h-3 sm:w-6 sm:h-6 text-gray-400" />
            </motion.div>
          </div>
          
          <AnimatePresence>
            {showCharts ? (
              <motion.div 
                className="h-32 sm:h-64"
                initial={{ opacity: 0, scale: 0.9 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.8, type: "spring" }}
              >
                <ResponsiveContainer width="100%" height="100%">
                  <AreaChart data={weeklyData}>
                    <defs>
                      <linearGradient id="moodGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="5%" stopColor={colors.primary} stopOpacity={0.8}/>
                        <stop offset="95%" stopColor={colors.primary} stopOpacity={0.1}/>
                      </linearGradient>
                      <linearGradient id="activitiesGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="5%" stopColor={colors.secondary} stopOpacity={0.8}/>
                        <stop offset="95%" stopColor={colors.secondary} stopOpacity={0.1}/>
                      </linearGradient>
                    </defs>
                    <CartesianGrid strokeDasharray="3 3" stroke={theme === 'light' ? '#e5e7eb' : '#374151'} />
                    <XAxis 
                      dataKey="day" 
                      stroke={theme === 'light' ? '#6b7280' : '#9ca3af'}
                      fontSize={isSmartwatch ? 8 : 12}
                      tick={{ fontSize: isSmartwatch ? 8 : 12 }}
                    />
                    <YAxis 
                      domain={[0, 5]}
                      stroke={theme === 'light' ? '#6b7280' : '#9ca3af'}
                      fontSize={isSmartwatch ? 8 : 12}
                      tick={{ fontSize: isSmartwatch ? 8 : 12 }}
                    />
                    <Tooltip content={<CustomTooltip />} />
                    <Legend wrapperStyle={{ fontSize: isSmartwatch ? 8 : 12 }} />
                    <Area
                      type="monotone"
                      dataKey="mood"
                      name={t.emotionalState}
                      stroke={colors.primary}
                      strokeWidth={isSmartwatch ? 1 : 3}
                      fill="url(#moodGradient)"
                      dot={{ fill: colors.primary, strokeWidth: isSmartwatch ? 1 : 2, r: isSmartwatch ? 3 : 6 }}
                      activeDot={{ r: isSmartwatch ? 4 : 8, stroke: colors.primary, strokeWidth: isSmartwatch ? 1 : 2 }}
                    />
                    <Area
                      type="monotone"
                      dataKey="activities"
                      name={t.activities}
                      stroke={colors.secondary}
                      strokeWidth={isSmartwatch ? 1 : 2}
                      fill="url(#activitiesGradient)"
                      dot={{ fill: colors.secondary, strokeWidth: isSmartwatch ? 1 : 2, r: isSmartwatch ? 2 : 5 }}
                      activeDot={{ r: isSmartwatch ? 3 : 7, stroke: colors.secondary, strokeWidth: isSmartwatch ? 1 : 2 }}
                    />
                  </AreaChart>
                </ResponsiveContainer>
              </motion.div>
            ) : (
              <motion.div 
                className={`h-24 sm:h-48 rounded-lg sm:rounded-2xl flex items-center justify-center transition-all duration-300 ${
                  theme === 'light'
                    ? 'bg-gradient-to-r from-purple-50 to-blue-50 hover:from-purple-100 hover:to-blue-100 border-2 border-purple-100'
                    : 'bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 hover:from-emerald-100 hover:to-blue-100 dark:hover:from-gray-600 dark:hover:to-gray-500'
                }`}
                initial={{ opacity: 0, scale: 0.8 }}
                animate={{ opacity: 1, scale: 1 }}
                exit={{ opacity: 0, scale: 0.8 }}
                transition={{ delay: 1.6 }}
              >
                <div className="text-center">
                  <motion.div
                    animate={{ 
                      y: [0, -10, 0],
                      scale: [1, 1.1, 1],
                      filter: ["drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))", "drop-shadow(0 0 10px rgba(16, 185, 129, 0.8))", "drop-shadow(0 0 0px rgba(16, 185, 129, 0.5))"]
                    }}
                    transition={{ 
                      duration: 2, 
                      repeat: Infinity,
                      repeatType: "reverse"
                    }}
                  >
                    <TrendingUp className="w-8 h-8 sm:w-16 sm:h-16 text-emerald-600 dark:text-emerald-400 mx-auto mb-2 sm:mb-4" />
                  </motion.div>
                  <motion.p 
                    className={`font-semibold text-xxs sm:text-base ${
                      theme === 'light' ? 'text-gray-800' : 'text-gray-600 dark:text-gray-400'
                    }`}
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 1.8 }}
                  >
                    {t.visualizationAvailable}
                  </motion.p>
                  <motion.p 
                    className={`text-xxs sm:text-sm mt-1 sm:mt-2 ${
                      theme === 'light' ? 'text-gray-600' : 'text-gray-500 dark:text-gray-500'
                    }`}
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 2 }}
                  >
                    {t.continueTracking}
                  </motion.p>
                </div>
              </motion.div>
            )}
          </AnimatePresence>
        </motion.div>
      </PowerPointTransition>
    </div>
  );
}