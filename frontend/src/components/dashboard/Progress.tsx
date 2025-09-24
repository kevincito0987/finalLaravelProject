import React, { useState } from 'react';
import { TrendingUp, Calendar, Target, Award, BarChart3, Loader } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import {
  LineChart,
  AreaChart,
  Area,
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
} from 'recharts';
import { useTheme } from '../../context/ThemeContext';
// Componentes UI importados (asumo que existen)
import AnimatedCard, { useTypewriter, ParticleEffect } from '../ui/AnimatedCard';
import PowerPointTransition from '../ui/PowerPointTransition';

// --- INTERFACES DE TIPADO ---

interface EmotionalType {
  id: number;
  description: string;
}

interface BlogEntry {
  blog_date: string; 
  emotional_type_id: number; 
}

interface UserHabit {
  habit_id: number;
  registration_date: string; 
}

// Nueva Interfaz para tipar el dato completo en el PieChart
interface PieChartDataEntry {
  name: string;
  value: number;
  percentage: number;
}

interface ProgressProps {
  currentLanguage?: string;
  // Nota: En una implementación real, el componente recibiría los datos por props:
  // blogs: BlogEntry[];
  // userHabits: UserHabit[];
  // emotionalTypes: EmotionalType[];
  // loading: boolean;
}

// --- VALORES POR DEFECTO Y ESTRUCTURAS BÁSICAS ---

const DEFAULT_EMOTIONAL_TYPES: EmotionalType[] = [
    { id: 1, description: 'Tristeza' },
    { id: 2, description: 'Neutro' },
    { id: 3, description: 'Calma' },
    { id: 4, description: 'Bien' },
    { id: 5, description: 'Felicidad' },
];

const EMPTY_BLOGS: BlogEntry[] = [];
const EMPTY_HABITS: UserHabit[] = [];

// --- COMPONENTE PRINCIPAL ---

export default function Progress({ currentLanguage = 'es' }: ProgressProps) {
  const [timeRange, setTimeRange] = useState('week');
  const { theme } = useTheme();

  // 1. Sustitución de Hooks por valores vacíos/por defecto:
  const blogs: BlogEntry[] = EMPTY_BLOGS;
  const userHabits: UserHabit[] = EMPTY_HABITS;
  const emotionalTypes: EmotionalType[] = DEFAULT_EMOTIONAL_TYPES;
  const blogsLoading = false; // Simular que la carga inicial terminó
  const habitsLoading = false; // Simular que la carga inicial terminó

  // 2. Lógica para generar datos de la semana (ahora con datos vacíos)
  const generateWeeklyData = () => {
    const days = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    const now = new Date();
    
    return days.map((day, index) => {
      const date = new Date(now);
      date.setDate(date.getDate() - (6 - index));
      
      // La lógica de filtrado permanece, pero siempre retornará cero con los datos vacíos
      const dayBlogs = blogs.filter(blog => {
        const blogDate = new Date(blog.blog_date || '');
        return blogDate.toDateString() === date.toDateString();
      });
      
      const dayHabits = userHabits.filter(habit => {
        const habitDate = new Date(habit.registration_date || '');
        return habitDate.toDateString() === date.toDateString();
      });
      
      // Fallback: 3 (Neutral) si no hay blogs
      const avgMood = dayBlogs.length > 0 
        ? dayBlogs.reduce((sum, blog) => sum + blog.emotional_type_id, 0) / dayBlogs.length
        : 3;
      
      return {
        day,
        mood: Math.round(avgMood * 10) / 10,
        activities: dayHabits.length,
        date: date.toISOString().split('T')[0],
        moodLabel: emotionalTypes.find(t => t.id === Math.round(avgMood))?.description || 'Neutral'
      };
    });
  };

  const weeklyData = generateWeeklyData();

  // 3. Datos para distribución emocional (vacíos por defecto)
  const emotionalDistribution = emotionalTypes.reduce((acc, type) => {
    const count = blogs.filter(blog => blog.emotional_type_id === type.id).length;
    // Solo agrega si hay datos para evitar el 0% en la leyenda si no hay blogs
    if (count > 0 && blogs.length > 0) {
      acc.push({
        name: type.description,
        value: count,
        percentage: Math.round((count / blogs.length) * 100)
      });
    }
    // Utilizamos la nueva interfaz PieChartDataEntry
  }, [] as PieChartDataEntry[]); 

  // Colores para los gráficos (depende solo del tema)
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

  const pieColors = [
    colors.primary,
    colors.secondary,
    colors.accent,
    colors.warning,
    colors.danger,
    colors.success,
    colors.info,
    colors.purple,
    colors.pink,
    colors.orange
  ];

  // Custom tooltip para los gráficos (sin cambios)
  const CustomTooltip = ({ active, payload, label }: any) => {
    if (active && payload && payload.length) {
      return (
        <div className={`p-4 rounded-xl shadow-lg border ${
          theme === 'light'
            ? 'bg-white border-gray-200'
            : 'bg-gray-800 border-gray-600'
        }`}>
          <p className={`font-semibold ${
            theme === 'light' ? 'text-gray-900' : 'text-white'
          }`}>
            {label}
          </p>
          {payload.map((entry: any, index: number) => (
            <p key={index} style={{ color: entry.color }} className="text-sm">
              {entry.name}: {entry.value}
              {entry.name === 'mood' && ` (${entry.payload?.moodLabel})`}
            </p>
          ))}
        </div>
      );
    }
    return null;
  };

  // 4. Lógica de logros (debe manejar la ausencia de datos)
  const calculateAchievements = () => {
    // Estas métricas serán 0 o 3 con datos vacíos
    const consecutiveDays = blogs.length;
    const totalActivities = userHabits.length;
    const avgMood = blogs.length > 0 
      ? blogs.reduce((sum, blog) => sum + blog.emotional_type_id, 0) / blogs.length 
      : 3;

    return [
      { 
        title: '7-Day Streak', 
        description: 'Completar check-ins por 7 días consecutivos', 
        earned: consecutiveDays >= 7, 
        icon: '🔥' 
      },
      { 
        title: 'Semana Mindful', 
        description: 'Completar 5+ actividades de bienestar esta semana', 
        earned: totalActivities >= 5, 
        icon: '🧘' 
      },
      { 
        title: 'Estado Positivo', 
        description: 'Mantener un estado emocional promedio positivo', 
        earned: avgMood >= 4, 
        icon: '😊' 
      },
      { 
        title: 'Seguimiento Constante', 
        description: 'Usar todas las funciones de seguimiento', 
        earned: blogs.length > 0 && userHabits.length > 0, 
        icon: '📊' 
      },
    ];
  };

  const achievements = calculateAchievements();

  // Traducciones (sin cambios, usando strings fijos para el español)
  const translations = {
    es: {
      progressTracking: "Seguimiento de Progreso",
      averageState: "Estado Promedio",
      activities: "Actividades",
      checkIns: "Check-ins",
      achievements: "Logros",
      weeklyTrends: "Tendencias de Bienestar Semanal",
      emotionalDistribution: "Distribución de Estados Emocionales",
      emotionalSummary: "Resumen Emocional",
      wellnessAchievements: "Logros de Bienestar",
      obtained: "Obtenido"
    },
    // ... otras traducciones
  };

  const t = translations[currentLanguage as keyof typeof translations] || translations.es; // Fallback a 'es'

  const titleText = t.progressTracking;
  const typedTitle = useTypewriter(titleText, 70);

  // El indicador de carga solo se mostraría si los datos se reciben por props y están cargando
  if (blogsLoading || habitsLoading) {
    return (
      <div className="flex items-center justify-center min-h-96">
        <motion.div
          animate={{ 
            rotate: 360,
            scale: [1, 1.3, 1],
            filter: ['hue-rotate(0deg)', 'hue-rotate(360deg)']
          }}
          transition={{ 
            rotate: { duration: 2, repeat: Infinity, ease: "linear" },
            scale: { duration: 1.5, repeat: Infinity, repeatType: "reverse" },
            filter: { duration: 3, repeat: Infinity, ease: "linear" }
          }}
        >
          <Loader className="w-8 h-8 text-emerald-600" />
        </motion.div>
      </div>
    );
  }

  return (
    <div className="space-y-6 relative">
      {/* Efecto de partículas de progreso */}
      <ParticleEffect count={20} color="blue" />

      {/* Header con animación de máquina de escribir y selector */}
      <PowerPointTransition type="flip" duration={1200}>
        <motion.div 
          className="flex items-center justify-between"
          whileHover={{ scale: 1.01 }}
        >
          <div className="flex items-center">
            <motion.div
              animate={{ 
                rotate: [0, 360],
                scale: [1, 1.2, 1],
                y: [0, -5, 0]
              }}
              transition={{ 
                duration: 4, 
                repeat: Infinity,
                ease: "easeInOut"
              }}
            >
              <TrendingUp className="w-6 h-6 text-emerald-600 dark:text-emerald-400 mr-3" />
            </motion.div>
            <motion.h2 
              className={`text-2xl font-bold ${
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
          
          <motion.div 
            className={`flex rounded-lg ${
              theme === 'light' ? 'bg-emerald-100' : 'bg-gray-100 dark:bg-gray-700'
            }`}
            initial={{ opacity: 0, x: 50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 1 }}
            whileHover={{ scale: 1.05 }}
          >
            {['week', 'month', 'year'].map((range, index) => (
              <motion.button
                key={range}
                onClick={() => setTimeRange(range)}
                className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                  timeRange === range
                    ? 'bg-emerald-600 text-white'
                    : theme === 'light'
                    ? 'text-emerald-800 hover:text-emerald-900 hover:bg-emerald-200'
                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                }`}
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 1.2 + index * 0.1 }}
              >
                {range === 'week' ? 'Semana' : range === 'month' ? 'Mes' : 'Año'}
              </motion.button>
            ))}
          </motion.div>
        </motion.div>
      </PowerPointTransition>

      {/* Key Metrics con animaciones tipo PowerPoint */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        {[
          {
            icon: TrendingUp,
            label: t.averageState,
            // Valores calculados en base a datos vacíos (retornarán valores por defecto)
            value: weeklyData.length > 0 
              ? (weeklyData.reduce((sum, d) => sum + d.mood, 0) / weeklyData.length).toFixed(1) + '/5'
              : '3.0/5', // Estado promedio por defecto (Neutral)
            gradient: 'from-emerald-400 to-emerald-600',
            bgLight: 'bg-gradient-to-br from-emerald-50 to-emerald-100',
            borderLight: 'border-emerald-200',
            type: 'glow' as const,
            delay: 0.2
          },
          {
            icon: Target,
            label: t.activities,
            value: weeklyData.reduce((sum, d) => sum + d.activities, 0), // 0 actividades
            gradient: 'from-blue-400 to-blue-600',
            bgLight: 'bg-gradient-to-br from-blue-50 to-blue-100',
            borderLight: 'border-blue-200',
            type: 'bounce' as const,
            delay: 0.4
          },
          {
            icon: Calendar,
            label: t.checkIns,
            value: blogs.length, // 0 check-ins
            gradient: 'from-purple-400 to-purple-600',
            bgLight: 'bg-gradient-to-br from-purple-50 to-purple-100',
            borderLight: 'border-purple-200',
            type: 'flip' as const,
            delay: 0.6
          },
          {
            icon: Award,
            label: t.achievements,
            value: `${achievements.filter(a => a.earned).length}/${achievements.length}`, // 0/4 logros
            gradient: 'from-orange-400 to-orange-600',
            bgLight: 'bg-gradient-to-br from-orange-50 to-orange-100',
            borderLight: 'border-orange-200',
            type: 'zoom' as const,
            delay: 0.8
          }
        ].map((stat, index) => (
          <AnimatedCard
            key={index}
            delay={stat.delay}
            type={stat.type}
            hoverScale={1.08}
            hoverRotate={index % 2 === 0 ? 4 : -4}
            className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform relative overflow-hidden ${
              theme === 'light'
                ? `${stat.bgLight} border-2 ${stat.borderLight}`
                : 'bg-white dark:bg-gray-800 shadow-sm'
            }`}
          >
            {/* Efecto de ondas en hover */}
            <motion.div
              className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent"
              initial={{ x: '-100%', skewX: -15 }}
              whileHover={{ x: '100%' }}
              transition={{ duration: 0.8 }}
            />
            
            <div className="flex items-center relative z-10">
              <motion.div 
                className={`p-3 rounded-lg ${
                  theme === 'light' ? 'bg-white/80' : 'bg-gray-100 dark:bg-gray-700'
                }`}
                whileHover={{ 
                  scale: 1.3, 
                  rotate: 20,
                  boxShadow: "0 15px 30px -5px rgba(0, 0, 0, 0.15)"
                }}
                transition={{ type: "spring", stiffness: 400 }}
              >
                <stat.icon className="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
              </motion.div>
              <div className="ml-4">
                <motion.p 
                  className={`text-sm font-medium ${
                    theme === 'light' ? 'text-gray-700' : 'text-gray-600 dark:text-gray-400'
                  }`}
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: stat.delay + 0.2 }}
                >
                  {stat.label}
                </motion.p>
                <motion.p 
                  className={`text-2xl font-bold ${
                    theme === 'light' ? 'text-gray-900' : 'text-gray-900 dark:text-white'
                  }`}
                  initial={{ opacity: 0, scale: 0.3 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: stat.delay + 0.4, type: "spring", stiffness: 200 }}
                >
                  {stat.value}
                </motion.p>
              </div>
            </div>
          </AnimatedCard>
        ))}
      </div>

      {/* Charts mejorados con Recharts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <PowerPointTransition type="wipe" delay={1000}>
          <motion.div 
            className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
              theme === 'light'
                ? 'bg-gradient-to-br from-white to-emerald-50 border-2 border-emerald-200'
                : 'bg-white dark:bg-gray-800 shadow-sm'
            }`}
            whileHover={{ scale: 1.02, y: -5 }}
          >
            <div className="flex items-center mb-4">
              <motion.div
                animate={{ 
                  rotate: [0, 360],
                  scale: [1, 1.2, 1]
                }}
                transition={{ 
                  duration: 5, 
                  repeat: Infinity,
                  ease: "linear"
                }}
              >
                <BarChart3 className="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" />
              </motion.div>
              <h3 className={`text-lg font-semibold ${
                theme === 'light' ? 'text-emerald-900' : 'text-gray-900 dark:text-white'
              }`}>
                {t.weeklyTrends}
              </h3>
            </div>
            
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                {/* Los datos serán una línea plana en 3.0 (Neutral) */}
                <AreaChart data={weeklyData}>
                  <defs>
                    <linearGradient id="moodGradient" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor={colors.primary} stopOpacity={0.8}/>
                      <stop offset="95%" stopColor={colors.primary} stopOpacity={0.1}/>
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke={theme === 'light' ? '#e5e7eb' : '#374151'} />
                  <XAxis 
                    dataKey="day" 
                    stroke={theme === 'light' ? '#6b7280' : '#9ca3af'}
                    fontSize={12}
                  />
                  <YAxis 
                    domain={[1, 5]}
                    stroke={theme === 'light' ? '#6b7280' : '#9ca3af'}
                    fontSize={12}
                  />
                  <Tooltip content={<CustomTooltip />} />
                  <Area
                    type="monotone"
                    dataKey="mood"
                    stroke={colors.primary}
                    strokeWidth={3}
                    fill="url(#moodGradient)"
                    dot={{ fill: colors.primary, strokeWidth: 2, r: 6 }}
                    activeDot={{ r: 8, stroke: colors.primary, strokeWidth: 2 }}
                  />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </motion.div>
        </PowerPointTransition>

        <PowerPointTransition type="shimmer" delay={1200}>
          <motion.div 
            className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
              theme === 'light'
                ? 'bg-gradient-to-br from-white to-blue-50 border-2 border-blue-200'
                : 'bg-white dark:bg-gray-800 shadow-sm'
            }`}
            whileHover={{ scale: 1.02, y: -5 }}
          >
            <div className="flex items-center mb-4">
              <motion.div
                animate={{ 
                  scale: [1, 1.3, 1],
                  rotate: [0, 180, 360]
                }}
                transition={{ 
                  duration: 4, 
                  repeat: Infinity,
                  ease: "easeInOut"
                }}
              >
                <Target className="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" />
              </motion.div>
              <h3 className={`text-lg font-semibold ${
                theme === 'light' ? 'text-blue-900' : 'text-gray-900 dark:text-white'
              }`}>
                {t.activities}
              </h3>
            </div>
            
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                {/* Los datos serán barras a cero */}
                <BarChart data={weeklyData}>
                  <CartesianGrid strokeDasharray="3 3" stroke={theme === 'light' ? '#e5e7eb' : '#374151'} />
                  <XAxis 
                    dataKey="day" 
                    stroke={theme === 'light' ? '#6b7280' : '#9ca3af'}
                    fontSize={12}
                  />
                  <YAxis 
                    stroke={theme === 'light' ? '#6b7280' : '#9ca3af'}
                    fontSize={12}
                  />
                  <Tooltip content={<CustomTooltip />} />
                  <Bar 
                    dataKey="activities" 
                    fill={colors.secondary}
                    radius={[4, 4, 0, 0]}
                  />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </motion.div>
        </PowerPointTransition>
      </div>

      {/* Distribución emocional con gráfico de dona */}
      <PowerPointTransition type="spiral" delay={1400}>
        <motion.div 
          className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
            theme === 'light'
              ? 'bg-gradient-to-br from-white to-purple-50 border-2 border-purple-200'
              : 'bg-white dark:bg-gray-800 shadow-sm'
          }`}
          whileHover={{ scale: 1.01, y: -3 }}
        >
          <div className="flex items-center mb-4">
            <motion.div
              animate={{ 
                rotate: [0, 360],
                scale: [1, 1.4, 1],
                filter: ['hue-rotate(0deg)', 'hue-rotate(360deg)']
              }}
              transition={{ 
                duration: 6, 
                repeat: Infinity,
                ease: "linear"
              }}
            >
              <Award className="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" />
            </motion.div>
            <h3 className={`text-lg font-semibold ${
              theme === 'light' ? 'text-purple-900' : 'text-gray-900 dark:text-white'
            }`}>
              {t.emotionalDistribution}
            </h3>
          </div>
          
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
            <div className="h-64">
              {/* Se mostrará el mensaje "No hay datos suficientes..." */}
              {emotionalDistribution.length > 0 ? (
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={emotionalDistribution}
                      cx="50%"
                      cy="50%"
                      innerRadius={60}
                      outerRadius={100}
                      paddingAngle={5}
                      dataKey="value"
                    >
                      {emotionalDistribution.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={pieColors[index % pieColors.length]} />
                      ))}
                    </Pie>
                    <Tooltip 
                      // TIPADO CORREGIDO: Usamos la nueva interfaz para evitar 'any'
                      formatter={(value: number, name: string, props: { payload: PieChartDataEntry }) => [
                        `${value} veces (${props.payload.percentage}%)`,
                        'Frecuencia'
                      ]}
                    />
                    <Legend />
                  </PieChart>
                </ResponsiveContainer>
              ) : (
                <div className="flex items-center justify-center h-full">
                  <p className={`text-center ${
                    theme === 'light' ? 'text-gray-600' : 'text-gray-400'
                  }`}>
                    No hay datos suficientes para mostrar la distribución
                  </p>
                </div>
              )}
            </div>
            
            <div className="space-y-3">
              <h4 className={`font-semibold ${
                theme === 'light' ? 'text-purple-900' : 'text-gray-900 dark:text-white'
              }`}>
                {t.emotionalSummary}
              </h4>
              {/* Mostrará solo las categorías sin porcentajes si emotionalDistribution está vacío, o nada si se filtra */}
              {emotionalDistribution.length > 0 ? (
                  emotionalDistribution.slice(0, 5).map((item, index) => (
                    <motion.div 
                      key={item.name}
                      className="flex items-center justify-between"
                      initial={{ opacity: 0, x: 20 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ delay: 1.6 + index * 0.1 }}
                    >
                      <div className="flex items-center">
                        <div 
                          className="w-4 h-4 rounded-full mr-3"
                          style={{ backgroundColor: pieColors[index % pieColors.length] }}
                        />
                        <span className={`text-sm ${
                          theme === 'light' ? 'text-purple-800' : 'text-gray-700 dark:text-gray-300'
                        }`}>
                          {item.name}
                        </span>
                      </div>
                      <span className={`text-sm font-semibold ${
                        theme === 'light' ? 'text-purple-900' : 'text-gray-900 dark:text-white'
                      }`}>
                        {item.percentage}%
                      </span>
                    </motion.div>
                  ))
              ) : (
                <p className={`text-sm ${theme === 'light' ? 'text-purple-700' : 'text-gray-500 dark:text-gray-400'}`}>
                    {t.emotionalDistribution} requiere al menos un check-in completado.
                </p>
              )}
            </div>
          </div>
        </motion.div>
      </PowerPointTransition>

      {/* Achievements con animaciones individuales espectaculares */}
      <PowerPointTransition type="cube" delay={1600}>
        <motion.div 
          className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
            theme === 'light'
              ? 'bg-gradient-to-br from-white to-orange-50 border-2 border-orange-200'
              : 'bg-white dark:bg-gray-800 shadow-sm'
          }`}
          whileHover={{ scale: 1.01, y: -3 }}
        >
          <div className="flex items-center mb-4">
            <motion.div
              animate={{ 
                rotate: [0, 360],
                scale: [1, 1.4, 1],
                filter: ['hue-rotate(0deg)', 'hue-rotate(360deg)']
              }}
              transition={{ 
                duration: 6, 
                repeat: Infinity,
                ease: "linear"
              }}
            >
              <Award className="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" />
            </motion.div>
            <h3 className={`text-lg font-semibold ${
              theme === 'light' ? 'text-orange-900' : 'text-gray-900 dark:text-white'
            }`}>
              {t.wellnessAchievements}
            </h3>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {/* Todos los logros estarán marcados como NO obtenidos */}
            {achievements.map((achievement, index) => (
              <motion.div
                key={index}
                className={`p-4 rounded-2xl border-2 transition-all duration-300 relative overflow-hidden group ${
                  achievement.earned // Esto siempre será false con datos vacíos
                    ? theme === 'light'
                      ? 'border-emerald-300 bg-emerald-100 shadow-lg'
                      : 'border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30'
                    : theme === 'light'
                    ? 'border-gray-300 bg-gray-50 hover:bg-gray-100'
                    : 'border-gray-200 dark:border-gray-600'
                }`}
                initial={{ opacity: 0, scale: 0.5, rotate: 45 }}
                animate={{ opacity: 1, scale: 1, rotate: 0 }}
                transition={{ 
                  delay: 1.8 + index * 0.2,
                  type: "spring",
                  stiffness: 200
                }}
                whileHover={{ 
                  scale: 1.05, 
                  rotate: achievement.earned ? 2 : 0,
                  transition: { duration: 0.2 }
                }}
              >
                {/* Efecto de celebración (no se mostrará) */}
                <AnimatePresence>
                  {achievement.earned && (
                    <motion.div
                      className="absolute inset-0 bg-gradient-to-r from-emerald-400/20 to-blue-400/20"
                      initial={{ scale: 0, rotate: 180 }}
                      animate={{ scale: 1, rotate: 0 }}
                      transition={{ duration: 0.5 }}
                    />
                  )}
                </AnimatePresence>
                
                <div className="flex items-start relative z-10">
                  <motion.div 
                    className={`text-2xl mr-3 ${achievement.earned ? '' : 'grayscale opacity-50'}`}
                    animate={achievement.earned ? {
                      scale: [1, 1.3, 1],
                      rotate: [0, 10, -10, 0]
                    } : {}}
                    transition={{ 
                      duration: 2, 
                      repeat: Infinity,
                      repeatDelay: 3
                    }}
                  >
                    {achievement.icon}
                  </motion.div>
                  <div>
                    <motion.h4 
                      className={`font-medium ${
                        achievement.earned
                          ? theme === 'light'
                            ? 'text-emerald-900'
                            : 'text-emerald-900 dark:text-emerald-100'
                          : theme === 'light'
                          ? 'text-gray-800'
                          : 'text-gray-900 dark:text-white'
                      }`}
                      initial={{ opacity: 0, y: 10 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ delay: 2 + index * 0.2 }}
                    >
                      {achievement.title}
                    </motion.h4>
                    <motion.p 
                      className={`text-sm mt-1 ${
                        theme === 'light' 
                          ? achievement.earned ? 'text-emerald-800' : 'text-gray-600'
                          : 'text-gray-600 dark:text-gray-400'
                      }`}
                      initial={{ opacity: 0, y: 10 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ delay: 2.2 + index * 0.2 }}
                    >
                      {achievement.description}
                    </motion.p>
                    <AnimatePresence>
                      {achievement.earned && (
                        <motion.span 
                          className={`inline-block mt-2 px-2 py-1 text-xs rounded-full ${
                            theme === 'light'
                              ? 'bg-emerald-200 text-emerald-900'
                              : 'bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200'
                          }`}
                          initial={{ opacity: 0, scale: 0, rotate: -180 }}
                          animate={{ opacity: 1, scale: 1, rotate: 0 }}
                          transition={{ delay: 2.4 + index * 0.2, type: "spring" }}
                        >
                          ✓ {t.obtained}
                        </motion.span>
                      )}
                    </AnimatePresence>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        </motion.div>
      </PowerPointTransition>
    </div>
  );
}