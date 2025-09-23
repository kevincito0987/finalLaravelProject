import React, { useState, useRef, useEffect } from 'react';
import { 
  User, 
  Camera, 
  Edit3, 
  Save, 
  X, 
  Mail, 
  Phone, 
  MapPin, 
  Calendar,
  Shield,
  Download,
  Upload,
  Settings,
  Bell,
  Lock,
  Eye,
  EyeOff,
  Smartphone,
  Globe,
  Heart,
  Activity,
  TrendingUp,
  Award,
  Clock,
  Target,
  Loader,
  Image as ImageIcon
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useAuth } from '../../context/AuthContext';
import { useTheme } from '../../context/ThemeContext';
import AnimatedCard, { useTypewriter, ParticleEffect } from '../ui/AnimatedCard';
import PowerPointTransition from '../ui/PowerPointTransition';
import { useTranslation } from 'react-i18next';
interface ProfileData {
  name: string;
  lastname: string;
  email: string;
  phone: string;
  city: string;
  bio: string;
  avatar: string;
  birthDate: string;
  emergencyContact: string;
  emergencyPhone: string;
  preferences: {
    notifications: boolean;
    emailUpdates: boolean;
    dataSharing: boolean;
    darkMode: boolean;
  };
}

export default function Profile({ currentLanguage = 'es' }) {
  const { user, updateUserAvatar } = useAuth();
  const { theme } = useTheme();
  const { blogs } = useEmotionalBlogs();
  const { userHabits } = useHabits();
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [isEditing, setIsEditing] = useState(false);
  const [showPasswordChange, setShowPasswordChange] = useState(false);
  const [isExporting, setIsExporting] = useState(false);
  const [isUploading, setIsUploading] = useState(false);
  const { t, i18n } = useTranslation();

  // Estado del perfil con persistencia en localStorage
  const [profileData, setProfileData] = useState<ProfileData>(() => {
    const savedProfile = localStorage.getItem('habitaProfile');
    if (savedProfile) {
      return JSON.parse(savedProfile);
    }
    
    return {
      name: user?.profile?.name || '',
      lastname: user?.profile?.lastname || '',
      email: user?.email || '',
      phone: '+57 300 123 4567',
      city: 'Medellín, Colombia',
      bio: 'Enfocado en mi bienestar emocional y crecimiento personal. Me encanta la meditación y el ejercicio.',
      avatar: user?.avatar || 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face',
      birthDate: '1990-05-15',
      emergencyContact: 'María González',
      emergencyPhone: '+57 300 987 6543',
      preferences: {
        notifications: true,
        emailUpdates: true,
        dataSharing: false,
        darkMode: theme === 'dark'
      }
    };
  });

  const [tempProfileData, setTempProfileData] = useState(profileData);
  const [passwordData, setPasswordData] = useState({
    current: '',
    new: '',
    confirm: ''
  });
  const [showPasswords, setShowPasswords] = useState({
    current: false,
    new: false,
    confirm: false
  });

  // Guardar en localStorage cuando cambie profileData
  useEffect(() => {
    localStorage.setItem('habitaProfile', JSON.stringify(profileData));
  }, [profileData]);

  // Estadísticas del usuario
  const userStats = {
    totalCheckIns: blogs.length,
    activeHabits: userHabits.length,
    currentStreak: Math.min(blogs.length, 7),
    avgMood: blogs.length > 0 
      ? (blogs.reduce((sum, blog) => sum + blog.emotional_type_id, 0) / blogs.length).toFixed(1)
      : '0',
    joinDate: user?.created_at ? new Date(user.created_at).toLocaleDateString('es-ES') : 'Enero 2024',
    lastActivity: blogs.length > 0 ? new Date(blogs[0].blog_date || '').toLocaleDateString('es-ES') : 'Hoy'
  };

  const handleImageUpload = async (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (!file) return;

    setIsUploading(true);
    
    try {
      // Track image upload with Sentry
      Sentry.addBreadcrumb({
        category: 'profile',
        message: 'Starting profile image upload',
        level: 'info',
      });

      // Create a promise to handle the image processing
      const processImage = new Promise<string>((resolve, reject) => {
        const img = new Image();
        const canvas = document.createElement('canvas');
        canvas.width = 300;
        canvas.height = 300;
        
        img.onload = async () => {
          try {
            // Create a new instance of Pica for image resizing
            const pica = new Pica();
            
            // Resize the image using Pica
            const resizedCanvas = await pica.resize(img, canvas, {
              quality: 3,
              alpha: true
            });

            // Convert canvas to blob
            const blob = await pica.toBlob(resizedCanvas, 'image/jpeg', 0.9);
            
            // Try to upload to Supabase Storage, but fallback to object URL if it fails
            try {
              // First, check if the avatars bucket exists
              const { data: buckets, error: bucketsError } = await supabase.storage.listBuckets();
              
              if (bucketsError) {
                console.warn('Could not list buckets:', bucketsError);
                throw new Error('Storage not available');
              }
              
              const avatarsBucket = buckets?.find(bucket => bucket.name === 'avatars');
              
              if (!avatarsBucket) {
                console.warn('Avatars bucket not found, using local storage');
                throw new Error('Avatars bucket not found');
              }
              
              // Upload to Supabase Storage
              const fileName = `profile-${user?.id || 'user'}-${Date.now()}.jpg`;
              const { data, error } = await supabase.storage
                .from('avatars')
                .upload(fileName, blob, {
                  cacheControl: '3600',
                  upsert: true
                });
                
              if (error) {
                console.warn('Supabase storage upload failed:', error);
                throw error;
              }
              
              // Get public URL
              const { data: urlData } = supabase.storage
                .from('avatars')
                .getPublicUrl(fileName);
                
              resolve(urlData.publicUrl);
              
            } catch (uploadError) {
              console.warn('Supabase upload failed, using local storage:', uploadError);
              
              // Fallback to object URL for local storage
              const imageUrl = URL.createObjectURL(blob);
              
              // Store the blob data in localStorage for persistence
              const reader = new FileReader();
              reader.onload = () => {
                const base64 = reader.result as string;
                localStorage.setItem('habitaProfileImage', base64);
                resolve(imageUrl);
              };
              reader.readAsDataURL(blob);
            }
          } catch (resizeError) {
            console.error('Error resizing image:', resizeError);
            Sentry.captureException(resizeError);
            reject(resizeError);
          }
        };
        
        img.onerror = (err) => {
          console.error('Error loading image:', err);
          Sentry.captureException(err);
          reject('Error loading image');
        };
        
        img.src = URL.createObjectURL(file);
      });
      
      // Wait for image processing to complete
      const imageUrl = await processImage;
      
      // Update profile with new image URL
      const updatedProfile = { ...profileData, avatar: imageUrl };
      setProfileData(updatedProfile);
      setTempProfileData(updatedProfile);
      
      // Update in auth context
      if (updateUserAvatar) {
        updateUserAvatar(imageUrl);
      }
      
      // Track successful upload
      Sentry.captureMessage('Profile image uploaded successfully', 'info');

    } catch (error) {
      console.error('Error uploading image:', error);
      Sentry.captureException(error);
      alert('Error al subir la imagen. Por favor intenta de nuevo.');
    } finally {
      setIsUploading(false);
    }
  };

  const handleGooglePhotosUpload = () => {
    // Open Google Photos in a new tab
    window.open('https://photos.google.com', '_blank');
    
    // Track Google Photos click with Sentry
    Sentry.addBreadcrumb({
      category: 'profile',
      message: 'Opened Google Photos for image selection',
      level: 'info',
    });
  };

  const handleSave = () => {
    // Track profile save with Sentry
    Sentry.addBreadcrumb({
      category: 'profile',
      message: 'Saving profile changes',
      level: 'info',
    });
    
    setProfileData(tempProfileData);
    setIsEditing(false);
    
    // Track successful save with Sentry
    Sentry.captureMessage('Profile updated successfully', 'info');
  };

  const handleCancel = () => {
    setTempProfileData(profileData);
    setIsEditing(false);
    
    // Track cancel with Sentry
    Sentry.addBreadcrumb({
      category: 'profile',
      message: 'Cancelled profile editing',
      level: 'info',
    });
  };

  const handlePasswordChange = () => {
    if (passwordData.new !== passwordData.confirm) {
      alert('Las contraseñas nuevas no coinciden');
      return;
    }
    if (passwordData.new.length < 8) {
      alert('La contraseña debe tener al menos 8 caracteres');
      return;
    }
    
    // Track password change with Sentry
    Sentry.addBreadcrumb({
      category: 'profile',
      message: 'Changing password',
      level: 'info',
    });
    
    // Simular cambio de contraseña
    alert('Contraseña actualizada exitosamente');
    setPasswordData({ current: '', new: '', confirm: '' });
    setShowPasswordChange(false);
    
    // Track successful password change with Sentry
    Sentry.captureMessage('Password changed successfully', 'info');
  };

  const exportToPDF = async () => {
    setIsExporting(true);
    
    try {
      // Track PDF export with Sentry
      Sentry.addBreadcrumb({
        category: 'profile',
        message: 'Exporting profile to PDF',
        level: 'info',
      });
      
      const pdf = new jsPDF();
      
      // Configuración del PDF
      pdf.setFontSize(20);
      pdf.text('Reporte de Bienestar - Habita', 20, 30);
      
      pdf.setFontSize(12);
      pdf.text(`Generado el: ${new Date().toLocaleDateString('es-ES')}`, 20, 45);
      
      // Información personal
      pdf.setFontSize(16);
      pdf.text('Información Personal', 20, 65);
      pdf.setFontSize(12);
      pdf.text(`Nombre: ${profileData.name} ${profileData.lastname}`, 20, 80);
      pdf.text(`Email: ${profileData.email}`, 20, 90);
      pdf.text(`Ciudad: ${profileData.city}`, 20, 100);
      pdf.text(`Miembro desde: ${userStats.joinDate}`, 20, 110);
      
      // Estadísticas de bienestar
      pdf.setFontSize(16);
      pdf.text('Estadísticas de Bienestar', 20, 130);
      pdf.setFontSize(12);
      pdf.text(`Total de Check-ins: ${userStats.totalCheckIns}`, 20, 145);
      pdf.text(`Hábitos activos: ${userStats.activeHabits}`, 20, 155);
      pdf.text(`Racha actual: ${userStats.currentStreak} días`, 20, 165);
      pdf.text(`Estado emocional promedio: ${userStats.avgMood}/5`, 20, 175);
      pdf.text(`Última actividad: ${userStats.lastActivity}`, 20, 185);
      
      // Hábitos registrados
      if (userHabits.length > 0) {
        pdf.setFontSize(16);
        pdf.text('Hábitos de Bienestar', 20, 205);
        pdf.setFontSize(12);
        userHabits.slice(0, 10).forEach((habit, index) => {
          pdf.text(`• ${habit.habits.description}`, 25, 220 + (index * 10));
        });
      }
      
      // Información de contacto de emergencia
      pdf.setFontSize(16);
      pdf.text('Contacto de Emergencia', 20, 220 + (userHabits.length * 10) + 20);
      pdf.setFontSize(12);
      pdf.text(`Nombre: ${profileData.emergencyContact}`, 20, 235 + (userHabits.length * 10) + 20);
      pdf.text(`Teléfono: ${profileData.emergencyPhone}`, 20, 245 + (userHabits.length * 10) + 20);
      
      // Guardar el PDF
      pdf.save(`habita-reporte-${new Date().toISOString().split('T')[0]}.pdf`);
      
      // Track successful PDF export with Sentry
      Sentry.captureMessage('Profile exported to PDF successfully', 'info');
      
    } catch (error) {
      console.error('Error generating PDF:', error);
      alert('Error al generar el PDF');
      
      // Track error with Sentry
      Sentry.captureException(error);
    } finally {
      setIsExporting(false);
    }
  };

  const titleText = t('Mi Perfil');
  const typedTitle = useTypewriter(titleText, 100);

  return (
    <div className="space-y-6 relative">
      {/* Efecto de partículas de perfil */}
      <ParticleEffect count={25} color="purple" />

      {/* Header con animación de máquina de escribir */}
      <PowerPointTransition type="dissolve" duration={1200}>
        <motion.div 
          className="flex items-center justify-between"
          whileHover={{ scale: 1.01 }}
        >
          <div className="flex items-center">
            <motion.div
              animate={{ 
                rotate: [0, 360],
                scale: [1, 1.3, 1],
                filter: ['hue-rotate(0deg)', 'hue-rotate(360deg)']
              }}
              transition={{ 
                duration: 5, 
                repeat: Infinity,
                ease: "linear"
              }}
            >
              <User className="w-6 h-6 text-emerald-600 dark:text-emerald-400 mr-3" />
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
            className="flex items-center space-x-3"
            initial={{ opacity: 0, x: 50 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 1 }}
          >
            <motion.button
              onClick={exportToPDF}
              disabled={isExporting}
              className="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors flex items-center relative overflow-hidden"
              whileHover={{ scale: 1.05, rotate: 2 }}
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
                animate={isExporting ? { scale: [1, 1.1, 1] } : {}}
                transition={{ duration: 0.5, repeat: Infinity }}
              >
                {isExporting ? (
                  <>
                    <motion.div
                      animate={{ rotate: 360 }}
                      transition={{ duration: 1, repeat: Infinity, ease: "linear" }}
                    >
                      <Loader className="w-4 h-4 mr-2" />
                    </motion.div>
                    {t('Exportando...')}
                  </>
                ) : (
                  <>
                    <Download className="w-4 h-4 mr-2" />
                    {t('Exportar PDF')}
                  </>
                )}
              </motion.div>
            </motion.button>
            
            <motion.button
              onClick={() => setIsEditing(!isEditing)}
              className={`px-4 py-2 rounded-lg transition-colors flex items-center ${
                isEditing
                  ? theme === 'light'
                    ? 'bg-red-100 text-red-800 hover:bg-red-200'
                    : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800'
                  : theme === 'light'
                  ? 'bg-blue-100 text-blue-800 hover:bg-blue-200'
                  : 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800'
              }`}
              whileHover={{ scale: 1.05, rotate: isEditing ? -2 : 2 }}
              whileTap={{ scale: 0.95 }}
            >
              {isEditing ? (
                <>
                  <X className="w-4 h-4 mr-2" />
                  {t('Cancelar')}
                </>
              ) : (
                <>
                  <Edit3 className="w-4 h-4 mr-2" />
                  {t('Editar')}
                </>
              )}
            </motion.button>
          </motion.div>
        </motion.div>
      </PowerPointTransition>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Perfil Principal */}
        <div className="lg:col-span-1">
          <PowerPointTransition type="cube" delay={500}>
            <motion.div 
              className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
                theme === 'light'
                  ? 'bg-gradient-to-br from-white to-purple-50 border-2 border-purple-200'
                  : 'bg-gradient-to-br from-gray-800 to-purple-900/30 border-2 border-purple-800/30 shadow-[0_0_15px_rgba(139,92,246,0.3)]'
              }`}
              whileHover={{ scale: 1.02, y: -5 }}
            >
              {/* Animated background effects for dark mode */}
              {theme === 'dark' && (
                <>
                  <div className="absolute inset-0 overflow-hidden">
                    {[...Array(20)].map((_, i) => (
                      <motion.div
                        key={i}
                        className="absolute w-1 h-1 bg-purple-500/30 rounded-full"
                        animate={{
                          x: [Math.random() * 100, Math.random() * 100 + 50],
                          y: [Math.random() * 100, Math.random() * 100 - 50],
                          opacity: [0, 0.5, 0],
                          scale: [0, 1, 0]
                        }}
                        transition={{
                          duration: Math.random() * 5 + 3,
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
                  <motion.div
                    className="absolute inset-0 bg-gradient-to-br from-purple-900/10 to-blue-900/10"
                    animate={{
                      background: [
                        'radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.15), transparent 70%)',
                        'radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.15), transparent 70%)'
                      ]
                    }}
                    transition={{ duration: 10, repeat: Infinity, ease: "easeInOut" }}
                  />
                </>
              )}

              {/* Avatar Section */}
              <div className="text-center mb-6">
                <div className="relative inline-block">
                  <motion.div
                    className="relative w-32 h-32 mx-auto"
                    whileHover={{ scale: 1.1, rotate: 5 }}
                    transition={{ duration: 0.3 }}
                  >
                    <img
                      src={isEditing ? tempProfileData.avatar : profileData.avatar}
                      alt="Profile"
                      className={`w-32 h-32 rounded-full object-cover border-4 ${
                        theme === 'light' 
                          ? 'border-white shadow-lg' 
                          : 'border-purple-900/50 shadow-[0_0_15px_rgba(139,92,246,0.5)]'
                      }`}
                    />
                    {isUploading && (
                      <div className="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center">
                        <motion.div
                          animate={{ rotate: 360 }}
                          transition={{ duration: 1, repeat: Infinity, ease: "linear" }}
                        >
                          <Loader className="w-8 h-8 text-white" />
                        </motion.div>
                      </div>
                    )}
                  </motion.div>
                  
                  <AnimatePresence>
                    {isEditing && (
                      <motion.div 
                        className="absolute -bottom-2 -right-2"
                        initial={{ scale: 0, rotate: 180 }}
                        animate={{ scale: 1, rotate: 0 }}
                        exit={{ scale: 0, rotate: 180 }}
                      >
                        <div className="flex space-x-2">
                          <motion.button
                            onClick={() => fileInputRef.current?.click()}
                            className={`p-2 text-white rounded-full shadow-lg ${
                              theme === 'light'
                                ? 'bg-emerald-600 hover:bg-emerald-700'
                                : 'bg-emerald-500 hover:bg-emerald-600'
                            }`}
                            whileHover={{ scale: 1.2, rotate: 15 }}
                            whileTap={{ scale: 0.9 }}
                            title="Subir desde dispositivo"
                          >
                            <Upload className="w-4 h-4" />
                          </motion.button>
                          
                          <motion.button
                            onClick={handleGooglePhotosUpload}
                            className={`p-2 text-white rounded-full shadow-lg ${
                              theme === 'light'
                                ? 'bg-blue-600 hover:bg-blue-700'
                                : 'bg-blue-500 hover:bg-blue-600'
                            }`}
                            whileHover={{ scale: 1.2, rotate: -15 }}
                            whileTap={{ scale: 0.9 }}
                            title="Seleccionar de Google Fotos"
                          >
                            <ImageIcon className="w-4 h-4" />
                          </motion.button>
                        </div>
                      </motion.div>
                    )}
                  </AnimatePresence>
                </div>
                
                <input
                  ref={fileInputRef}
                  type="file"
                  accept="image/*"
                  onChange={handleImageUpload}
                  className="hidden"
                />
                
                <motion.h3 
                  className={`text-xl font-bold mt-4 ${
                    theme === 'light' ? 'text-purple-900' : 'text-purple-200'
                  }`}
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: 0.7 }}
                >
                  {isEditing ? `${tempProfileData.name} ${tempProfileData.lastname}` : `${profileData.name} ${profileData.lastname}`}
                </motion.h3>
                
                <motion.p 
                  className={`${
                    theme === 'light' ? 'text-purple-700' : 'text-purple-400'
                  }`}
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: 0.9 }}
                >
                  {isEditing ? tempProfileData.email : profileData.email}
                </motion.p>
              </div>

              {/* Quick Stats */}
              <div className="grid grid-cols-2 gap-4 mb-6">
                {[
                  { icon: Heart, label: t('Check-ins'), value: userStats.totalCheckIns, color: theme === 'light' ? 'text-red-500' : 'text-red-400' },
                  { icon: Activity, label: t('Hábitos'), value: userStats.activeHabits, color: theme === 'light' ? 'text-emerald-500' : 'text-emerald-400' },
                  { icon: TrendingUp, label: t('Racha'), value: `${userStats.currentStreak}d`, color: theme === 'light' ? 'text-blue-500' : 'text-blue-400' },
                  { icon: Award, label: t('Estado'), value: userStats.avgMood, color: theme === 'light' ? 'text-purple-500' : 'text-purple-400' }
                ].map((stat, index) => (
                  <motion.div
                    key={index}
                    className={`text-center p-3 rounded-xl ${
                      theme === 'light'
                        ? 'bg-white/80 border border-purple-200'
                        : 'bg-gray-800/80 border border-purple-800/30 backdrop-blur-sm'
                    }`}
                    initial={{ opacity: 0, scale: 0.5 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ delay: 1.1 + index * 0.1, type: "spring" }}
                    whileHover={{ scale: 1.05, y: -3 }}
                  >
                    <stat.icon className={`w-5 h-5 mx-auto mb-1 ${stat.color}`} />
                    <p className={`text-lg font-bold ${
                      theme === 'light' ? 'text-purple-900' : 'text-white'
                    }`}>
                      {stat.value}
                    </p>
                    <p className={`text-xs ${
                      theme === 'light' ? 'text-purple-700' : 'text-purple-300'
                    }`}>
                      {stat.label}
                    </p>
                  </motion.div>
                ))}
              </div>

              {/* Bio */}
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 1.5 }}
              >
                <h4 className={`font-semibold mb-2 ${
                  theme === 'light' ? 'text-purple-900' : 'text-purple-200'
                }`}>
                  {t('Acerca de mí')}
                </h4>
                {isEditing ? (
                  <textarea
                    value={tempProfileData.bio}
                    onChange={(e) => setTempProfileData(prev => ({ ...prev, bio: e.target.value }))}
                    className={`w-full p-3 border rounded-lg resize-none ${
                      theme === 'light'
                        ? 'border-purple-300 bg-white text-purple-900'
                        : 'border-purple-800/50 bg-gray-800/80 text-white'
                    }`}
                    rows={3}
                  />
                ) : (
                  <p className={`text-sm leading-relaxed ${
                    theme === 'light' ? 'text-purple-800' : 'text-purple-300'
                  }`}>
                    {profileData.bio}
                  </p>
                )}
              </motion.div>
            </motion.div>
          </PowerPointTransition>
        </div>

        {/* Información Detallada */}
        <div className="lg:col-span-2 space-y-6">
          {/* Información Personal */}
          <PowerPointTransition type="shimmer" delay={700}>
            <motion.div 
              className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
                theme === 'light'
                  ? 'bg-gradient-to-br from-white to-emerald-50 border-2 border-emerald-200'
                  : 'bg-gradient-to-br from-gray-800 to-emerald-900/20 border-2 border-emerald-800/30 shadow-[0_0_15px_rgba(16,185,129,0.2)]'
              }`}
              whileHover={{ scale: 1.01, y: -3 }}
            >
              {/* Animated background effects for dark mode */}
              {theme === 'dark' && (
                <>
                  <div className="absolute inset-0 overflow-hidden">
                    {[...Array(15)].map((_, i) => (
                      <motion.div
                        key={i}
                        className="absolute w-1 h-1 bg-emerald-500/20 rounded-full"
                        animate={{
                          x: [Math.random() * 100, Math.random() * 100 + 50],
                          y: [Math.random() * 100, Math.random() * 100 - 50],
                          opacity: [0, 0.5, 0],
                          scale: [0, 1, 0]
                        }}
                        transition={{
                          duration: Math.random() * 5 + 3,
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
                  <motion.div
                    className="absolute inset-0 bg-gradient-to-br from-emerald-900/10 to-blue-900/10"
                    animate={{
                      background: [
                        'radial-gradient(circle at 30% 30%, rgba(16, 185, 129, 0.1), transparent 70%)',
                        'radial-gradient(circle at 70% 70%, rgba(16, 185, 129, 0.1), transparent 70%)'
                      ]
                    }}
                    transition={{ duration: 8, repeat: Infinity, ease: "easeInOut" }}
                  />
                </>
              )}

              <div className="flex items-center justify-between mb-6">
                <h3 className={`text-lg font-semibold ${
                  theme === 'light' ? 'text-emerald-900' : 'text-emerald-200'
                }`}>
                  {t('Información Personal')}
                </h3>
                
                <AnimatePresence>
                  {isEditing && (
                    <motion.div 
                      className="flex space-x-2"
                      initial={{ opacity: 0, x: 20 }}
                      animate={{ opacity: 1, x: 0 }}
                      exit={{ opacity: 0, x: 20 }}
                    >
                      <motion.button
                        onClick={handleSave}
                        className="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors flex items-center"
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                      >
                        <Save className="w-4 h-4 mr-2" />
                        {t('Guardar')}
                      </motion.button>
                      <motion.button
                        onClick={handleCancel}
                        className={`px-4 py-2 rounded-lg transition-colors ${
                          theme === 'light'
                            ? 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                            : 'bg-gray-700 text-gray-200 hover:bg-gray-600'
                        }`}
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                      >
                        <X className="w-4 h-4 mr-2" />
                        {t('Cancelar')}
                      </motion.button>
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {[
                  { icon: User, label: t('Nombre'), key: 'name', value: isEditing ? tempProfileData.name : profileData.name },
                  { icon: User, label: t('Apellido'), key: 'lastname', value: isEditing ? tempProfileData.lastname : profileData.lastname },
                  { icon: Mail, label: t('Email'), key: 'email', value: isEditing ? tempProfileData.email : profileData.email },
                  { icon: Phone, label: t('Teléfono'), key: 'phone', value: isEditing ? tempProfileData.phone : profileData.phone },
                  { icon: MapPin, label: t('Ciudad'), key: 'city', value: isEditing ? tempProfileData.city : profileData.city },
                  { icon: Calendar, label: t('Fecha de Nacimiento'), key: 'birthDate', value: isEditing ? tempProfileData.birthDate : profileData.birthDate, type: 'date' }
                ].map((field, index) => (
                  <motion.div
                    key={field.key}
                    className={`p-4 rounded-xl border ${
                      theme === 'light'
                        ? 'border-emerald-200 bg-emerald-50'
                        : 'border-emerald-800/30 bg-emerald-900/10'
                    }`}
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.9 + index * 0.1 }}
                    whileHover={{ scale: 1.02 }}
                  >
                    <div className="flex items-center mb-2">
                      <field.icon className={`w-4 h-4 mr-2 ${
                        theme === 'light' ? 'text-emerald-600' : 'text-emerald-400'
                      }`} />
                      <label className={`text-sm font-medium ${
                        theme === 'light' ? 'text-emerald-800' : 'text-emerald-200'
                      }`}>
                        {field.label}
                      </label>
                    </div>
                    {isEditing ? (
                      <input
                        type={field.type || 'text'}
                        value={field.value}
                        onChange={(e) => setTempProfileData(prev => ({ 
                          ...prev, 
                          [field.key]: e.target.value 
                        }))}
                        className={`w-full p-2 border rounded-lg ${
                          theme === 'light'
                            ? 'border-emerald-300 bg-white text-emerald-900'
                            : 'border-emerald-800/50 bg-gray-800 text-white'
                        }`}
                      />
                    ) : (
                      <p className={`font-medium ${
                        theme === 'light' ? 'text-emerald-900' : 'text-emerald-100'
                      }`}>
                        {field.value}
                      </p>
                    )}
                  </motion.div>
                ))}
              </div>
            </motion.div>
          </PowerPointTransition>

          {/* Contacto de Emergencia */}
          <PowerPointTransition type="wipe" delay={900}>
            <motion.div 
              className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
                theme === 'light'
                  ? 'bg-gradient-to-br from-white to-red-50 border-2 border-red-200'
                  : 'bg-gradient-to-br from-gray-800 to-red-900/20 border-2 border-red-800/30 shadow-[0_0_15px_rgba(239,68,68,0.2)]'
              }`}
              whileHover={{ scale: 1.01, y: -3 }}
            >
              {/* Animated background effects for dark mode */}
              {theme === 'dark' && (
                <>
                  <div className="absolute inset-0 overflow-hidden">
                    {[...Array(10)].map((_, i) => (
                      <motion.div
                        key={i}
                        className="absolute w-1 h-1 bg-red-500/20 rounded-full"
                        animate={{
                          x: [Math.random() * 100, Math.random() * 100 + 50],
                          y: [Math.random() * 100, Math.random() * 100 - 50],
                          opacity: [0, 0.5, 0],
                          scale: [0, 1, 0]
                        }}
                        transition={{
                          duration: Math.random() * 5 + 3,
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
                  <motion.div
                    className="absolute inset-0 bg-gradient-to-br from-red-900/10 to-orange-900/10"
                    animate={{
                      background: [
                        'radial-gradient(circle at 30% 30%, rgba(239, 68, 68, 0.1), transparent 70%)',
                        'radial-gradient(circle at 70% 70%, rgba(239, 68, 68, 0.1), transparent 70%)'
                      ]
                    }}
                    transition={{ duration: 8, repeat: Infinity, ease: "easeInOut" }}
                  />
                </>
              )}

              <div className="flex items-center mb-4">
                <Shield className={`w-5 h-5 mr-2 ${
                  theme === 'light' ? 'text-red-600' : 'text-red-400'
                }`} />
                <h3 className={`text-lg font-semibold ${
                  theme === 'light' ? 'text-red-900' : 'text-red-200'
                }`}>
                  {t('Contacto de Emergencia')}
                </h3>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <motion.div
                  className={`p-4 rounded-xl border ${
                    theme === 'light'
                      ? 'border-red-200 bg-red-50'
                      : 'border-red-800/30 bg-red-900/10'
                  }`}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: 1.1 }}
                >
                  <label className={`text-sm font-medium ${
                    theme === 'light' ? 'text-red-800' : 'text-red-200'
                  }`}>
                    {t('Nombre del Contacto')}
                  </label>
                  {isEditing ? (
                    <input
                      type="text"
                      value={tempProfileData.emergencyContact}
                      onChange={(e) => setTempProfileData(prev => ({ 
                        ...prev, 
                        emergencyContact: e.target.value 
                      }))}
                      className={`w-full mt-1 p-2 border rounded-lg ${
                        theme === 'light'
                          ? 'border-red-300 bg-white text-red-900'
                          : 'border-red-800/50 bg-gray-800 text-white'
                      }`}
                    />
                  ) : (
                    <p className={`mt-1 font-medium ${
                      theme === 'light' ? 'text-red-900' : 'text-red-100'
                    }`}>
                      {profileData.emergencyContact}
                    </p>
                  )}
                </motion.div>

                <motion.div
                  className={`p-4 rounded-xl border ${
                    theme === 'light'
                      ? 'border-red-200 bg-red-50'
                      : 'border-red-800/30 bg-red-900/10'
                  }`}
                  initial={{ opacity: 0, x: 20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: 1.3 }}
                >
                  <label className={`text-sm font-medium ${
                    theme === 'light' ? 'text-red-800' : 'text-red-200'
                  }`}>
                    {t('Teléfono de Emergencia')}
                  </label>
                  {isEditing ? (
                    <input
                      type="tel"
                      value={tempProfileData.emergencyPhone}
                      onChange={(e) => setTempProfileData(prev => ({ 
                        ...prev, 
                        emergencyPhone: e.target.value 
                      }))}
                      className={`w-full mt-1 p-2 border rounded-lg ${
                        theme === 'light'
                          ? 'border-red-300 bg-white text-red-900'
                          : 'border-red-800/50 bg-gray-800 text-white'
                      }`}
                    />
                  ) : (
                    <p className={`mt-1 font-medium ${
                      theme === 'light' ? 'text-red-900' : 'text-red-100'
                    }`}>
                      {profileData.emergencyPhone}
                    </p>
                  )}
                </motion.div>
              </div>
            </motion.div>
          </PowerPointTransition>

          {/* Configuración y Seguridad */}
          <PowerPointTransition type="spiral" delay={1100}>
            <motion.div 
              className={`rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden ${
                theme === 'light'
                  ? 'bg-gradient-to-br from-white to-blue-50 border-2 border-blue-200'
                  : 'bg-gradient-to-br from-gray-800 to-blue-900/20 border-2 border-blue-800/30 shadow-[0_0_15px_rgba(59,130,246,0.2)]'
              }`}
              whileHover={{ scale: 1.01, y: -3 }}
            >
              {/* Animated background effects for dark mode */}
              {theme === 'dark' && (
                <>
                  <div className="absolute inset-0 overflow-hidden">
                    {[...Array(15)].map((_, i) => (
                      <motion.div
                        key={i}
                        className="absolute w-1 h-1 bg-blue-500/20 rounded-full"
                        animate={{
                          x: [Math.random() * 100, Math.random() * 100 + 50],
                          y: [Math.random() * 100, Math.random() * 100 - 50],
                          opacity: [0, 0.5, 0],
                          scale: [0, 1, 0]
                        }}
                        transition={{
                          duration: Math.random() * 5 + 3,
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
                  <motion.div
                    className="absolute inset-0 bg-gradient-to-br from-blue-900/10 to-cyan-900/10"
                    animate={{
                      background: [
                        'radial-gradient(circle at 30% 30%, rgba(59, 130, 246, 0.1), transparent 70%)',
                        'radial-gradient(circle at 70% 70%, rgba(59, 130, 246, 0.1), transparent 70%)'
                      ]
                    }}
                    transition={{ duration: 8, repeat: Infinity, ease: "easeInOut" }}
                  />
                </>
              )}

              <div className="flex items-center justify-between mb-6">
                <div className="flex items-center">
                  <Settings className={`w-5 h-5 mr-2 ${
                    theme === 'light' ? 'text-blue-600' : 'text-blue-400'
                  }`} />
                  <h3 className={`text-lg font-semibold ${
                    theme === 'light' ? 'text-blue-900' : 'text-blue-200'
                  }`}>
                    {t('Configuración y Seguridad')}
                  </h3>
                </div>
                
                <motion.button
                  onClick={() => setShowPasswordChange(!showPasswordChange)}
                  className={`px-4 py-2 rounded-lg transition-colors flex items-center ${
                    theme === 'light'
                      ? 'bg-blue-100 text-blue-800 hover:bg-blue-200'
                      : 'bg-blue-900/30 text-blue-200 hover:bg-blue-800/50'
                  }`}
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                >
                  <Lock className="w-4 h-4 mr-2" />
                  {t('Cambiar Contraseña')}
                </motion.button>
              </div>

              {/* Preferencias */}
              <div className="space-y-4">
                {[
                  { key: 'notifications', label: t('Notificaciones push'), icon: Bell },
                  { key: 'emailUpdates', label: t('Actualizaciones por email'), icon: Mail },
                  { key: 'dataSharing', label: t('Compartir datos para investigación'), icon: Shield }
                ].map((pref, index) => (
                  <motion.div
                    key={pref.key}
                    className="flex items-center justify-between"
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 1.3 + index * 0.1 }}
                  >
                    <div className="flex items-center">
                      <pref.icon className={`w-4 h-4 mr-3 ${
                        theme === 'light' ? 'text-blue-600' : 'text-blue-400'
                      }`} />
                      <span className={`${
                        theme === 'light' ? 'text-blue-800' : 'text-blue-200'
                      }`}>
                        {pref.label}
                      </span>
                    </div>
                    <motion.button
                      onClick={() => {
                        if (isEditing) {
                          setTempProfileData(prev => ({
                            ...prev,
                            preferences: {
                              ...prev.preferences,
                              [pref.key]: !prev.preferences[pref.key as keyof typeof prev.preferences]
                            }
                          }));
                        } else {
                          setProfileData(prev => ({
                            ...prev,
                            preferences: {
                              ...prev.preferences,
                              [pref.key]: !prev.preferences[pref.key as keyof typeof prev.preferences]
                            }
                          }));
                        }
                      }}
                      className={`w-12 h-6 rounded-full transition-all duration-300 relative ${
                        (isEditing ? tempProfileData : profileData).preferences[pref.key as keyof typeof profileData.preferences]
                          ? theme === 'light' ? 'bg-emerald-500' : 'bg-emerald-600'
                          : theme === 'light' ? 'bg-gray-300' : 'bg-gray-600'
                      }`}
                      whileHover={{ scale: 1.1 }}
                      whileTap={{ scale: 0.9 }}
                    >
                      <motion.div
                        className={`w-5 h-5 rounded-full shadow-md absolute top-0.5 ${
                          theme === 'light' ? 'bg-white' : 'bg-gray-200'
                        }`}
                        animate={{
                          x: (isEditing ? tempProfileData : profileData).preferences[pref.key as keyof typeof profileData.preferences] ? 26 : 2
                        }}
                        transition={{ type: "spring", stiffness: 500, damping: 30 }}
                      />
                    </motion.button>
                  </motion.div>
                ))}
              </div>

              {/* Cambio de Contraseña */}
              <AnimatePresence>
                {showPasswordChange && (
                  <motion.div
                    className={`mt-6 p-4 rounded-xl border ${
                      theme === 'light'
                        ? 'border-blue-200 bg-blue-50'
                        : 'border-blue-800/30 bg-blue-900/10'
                    }`}
                    initial={{ opacity: 0, height: 0 }}
                    animate={{ opacity: 1, height: 'auto' }}
                    exit={{ opacity: 0, height: 0 }}
                  >
                    <h4 className={`font-semibold mb-4 ${
                      theme === 'light' ? 'text-blue-900' : 'text-blue-200'
                    }`}>
                      {t('Cambiar Contraseña')}
                    </h4>
                    
                    <div className="space-y-4">
                      {[
                        { key: 'current', label: t('Contraseña actual'), placeholder: t('Contraseña actual') },
                        { key: 'new', label: t('Nueva contraseña'), placeholder: t('Nueva contraseña') },
                        { key: 'confirm', label: t('Confirmar contraseña'), placeholder: t('Confirmar contraseña') }
                      ].map((field) => (
                        <div key={field.key}>
                          <label className={`block text-sm font-medium mb-1 ${
                            theme === 'light' ? 'text-blue-800' : 'text-blue-200'
                          }`}>
                            {field.label}
                          </label>
                          <div className="relative">
                            <input
                              type={showPasswords[field.key as keyof typeof showPasswords] ? 'text' : 'password'}
                              value={passwordData[field.key as keyof typeof passwordData]}
                              onChange={(e) => setPasswordData(prev => ({ 
                                ...prev, 
                                [field.key]: e.target.value 
                              }))}
                              placeholder={field.placeholder}
                              className={`w-full p-2 pr-10 border rounded-lg ${
                                theme === 'light'
                                  ? 'border-blue-300 bg-white text-blue-900'
                                  : 'border-blue-800/50 bg-gray-800 text-white'
                              }`}
                            />
                            <button
                              type="button"
                              onClick={() => setShowPasswords(prev => ({ 
                                ...prev, 
                                [field.key]: !prev[field.key as keyof typeof prev] 
                              }))}
                              className="absolute right-2 top-1/2 transform -translate-y-1/2"
                            >
                              {showPasswords[field.key as keyof typeof showPasswords] ? (
                                <EyeOff className={`w-4 h-4 ${theme === 'light' ? 'text-gray-400' : 'text-gray-500'}`} />
                              ) : (
                                <Eye className={`w-4 h-4 ${theme === 'light' ? 'text-gray-400' : 'text-gray-500'}`} />
                              )}
                            </button>
                          </div>
                        </div>
                      ))}
                      
                      <div className="flex space-x-3 pt-2">
                        <motion.button
                          onClick={handlePasswordChange}
                          className={`bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors ${
                            theme === 'dark' ? 'shadow-[0_0_10px_rgba(16,185,129,0.3)]' : ''
                          }`}
                          whileHover={{ scale: 1.05 }}
                          whileTap={{ scale: 0.95 }}
                        >
                          {t('Actualizar Contraseña')}
                        </motion.button>
                        <motion.button
                          onClick={() => {
                            setShowPasswordChange(false);
                            setPasswordData({ current: '', new: '', confirm: '' });
                          }}
                          className={`px-4 py-2 rounded-lg transition-colors ${
                            theme === 'light'
                              ? 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                              : 'bg-gray-700 text-gray-200 hover:bg-gray-600'
                          }`}
                          whileHover={{ scale: 1.05 }}
                          whileTap={{ scale: 0.95 }}
                        >
                          {t('Cancelar')}
                        </motion.button>
                      </div>
                    </div>
                  </motion.div>
                )}
              </AnimatePresence>
            </motion.div>
          </PowerPointTransition>
        </div>
      </div>
    </div>
  );
}