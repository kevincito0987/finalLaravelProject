import React, { useState, useRef, useEffect } from 'react';
import { 
  Bell, 
  Heart, 
  MessageCircle, 
  TrendingUp, 
  Shield, 
  Calendar,
  Check,
  X,
  Settings,
  MoreHorizontal,
  Clock,
  AlertTriangle
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { useTheme } from '../../context/ThemeContext';
import { createPortal } from 'react-dom';

interface Notification {
  id: string;
  type: 'checkin' | 'community' | 'achievement' | 'reminder' | 'emergency' | 'system';
  title: string;
  message: string;
  timestamp: string;
  read: boolean;
  priority: 'low' | 'medium' | 'high' | 'urgent';
  actionUrl?: string;
}

interface NotificationsDropdownProps {
  isOpen: boolean;
  onClose: () => void;
  triggerRef: React.RefObject<HTMLButtonElement>;
}

export default function NotificationsDropdown({ isOpen, onClose, triggerRef }: NotificationsDropdownProps) {
  const [notifications, setNotifications] = useState<Notification[]>([
    {
      id: '1',
      type: 'checkin',
      title: 'Recordatorio de Check-in',
      message: '¡Es hora de tu check-in emocional diario! ¿Cómo te sientes hoy?',
      timestamp: '2 min',
      read: false,
      priority: 'medium'
    },
    {
      id: '2',
      type: 'achievement',
      title: '🎉 ¡Nuevo logro desbloqueado!',
      message: 'Has completado 7 días consecutivos de check-ins. ¡Increíble progreso!',
      timestamp: '1 hora',
      read: false,
      priority: 'high'
    },
    {
      id: '3',
      type: 'community',
      title: 'Nueva respuesta en la comunidad',
      message: 'Sarah M. respondió a tu publicación sobre técnicas de respiración.',
      timestamp: '3 horas',
      read: true,
      priority: 'low'
    },
    {
      id: '4',
      type: 'reminder',
      title: 'Momento de mindfulness',
      message: 'Tu sesión de meditación matutina te está esperando.',
      timestamp: '5 horas',
      read: true,
      priority: 'medium'
    },
    {
      id: '5',
      type: 'system',
      title: 'Actualización de privacidad',
      message: 'Hemos actualizado nuestra política de privacidad. Revisa los cambios.',
      timestamp: '1 día',
      read: false,
      priority: 'low'
    },
    {
      id: '6',
      type: 'emergency',
      title: 'Recursos de apoyo disponibles',
      message: 'Recuerda que tienes acceso 24/7 a recursos de crisis y apoyo emocional.',
      timestamp: '2 días',
      read: true,
      priority: 'urgent'
    }
  ]);

  const dropdownRef = useRef<HTMLDivElement>(null);
  const [filter, setFilter] = useState<'all' | 'unread'>('all');
  const { theme } = useTheme();

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (
        dropdownRef.current && 
        !dropdownRef.current.contains(event.target as Node) &&
        triggerRef.current &&
        !triggerRef.current.contains(event.target as Node)
      ) {
        onClose();
      }
    };

    if (isOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }

    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [isOpen, onClose, triggerRef]);

  const getNotificationIcon = (type: Notification['type']) => {
    switch (type) {
      case 'checkin': return Heart;
      case 'community': return MessageCircle;
      case 'achievement': return TrendingUp;
      case 'reminder': return Calendar;
      case 'emergency': return Shield;
      case 'system': return Settings;
      default: return Bell;
    }
  };

  const getNotificationColor = (type: Notification['type'], priority: Notification['priority']) => {
    if (priority === 'urgent') return 'text-red-500 dark:text-red-400';
    
    switch (type) {
      case 'checkin': return 'text-emerald-500 dark:text-emerald-400';
      case 'community': return 'text-blue-500 dark:text-blue-400';
      case 'achievement': return 'text-purple-500 dark:text-purple-400';
      case 'reminder': return 'text-orange-500 dark:text-orange-400';
      case 'emergency': return 'text-red-500 dark:text-red-400';
      case 'system': return 'text-slate-500 dark:text-gray-400';
      default: return 'text-slate-500 dark:text-gray-400';
    }
  };

  const getPriorityIndicator = (priority: Notification['priority']) => {
    switch (priority) {
      case 'urgent': return 'bg-red-400';
      case 'high': return 'bg-orange-400';
      case 'medium': return 'bg-yellow-400';
      case 'low': return 'bg-green-400';
      default: return 'bg-slate-400';
    }
  };

  const markAsRead = (id: string) => {
    setNotifications(prev => 
      prev.map(notif => 
        notif.id === id ? { ...notif, read: true } : notif
      )
    );
  };

  const markAllAsRead = () => {
    setNotifications(prev => 
      prev.map(notif => ({ ...notif, read: true }))
    );
  };

  const deleteNotification = (id: string) => {
    setNotifications(prev => prev.filter(notif => notif.id !== id));
  };

  const filteredNotifications = filter === 'unread' 
    ? notifications.filter(n => !n.read)
    : notifications;

  const unreadCount = notifications.filter(n => !n.read).length;

  if (!isOpen) return null;

  // Create a portal to render the dropdown at the root level to avoid z-index issues
  return createPortal(
    <div className="fixed inset-0 bg-transparent z-[9999]">
      <div className="absolute right-2 sm:right-4 top-12 sm:top-16 w-72 sm:w-96">
        <motion.div 
          ref={dropdownRef}
          className="bg-white/90 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl sm:rounded-3xl shadow-2xl border border-white/50 dark:border-gray-700/50 overflow-hidden"
          initial={{ opacity: 0, y: -20, scale: 0.95 }}
          animate={{ opacity: 1, y: 0, scale: 1 }}
          exit={{ opacity: 0, y: -20, scale: 0.95 }}
          transition={{ duration: 0.2 }}
        >
          {/* Header */}
          <div className="p-4 sm:p-6 border-b border-white/40 dark:border-gray-700/30 bg-white/60 dark:bg-gray-800/80 backdrop-blur-sm">
            <div className="flex items-center justify-between mb-3 sm:mb-4">
              <div className="flex items-center space-x-3">
                <motion.div 
                  className="p-1.5 sm:p-2 bg-white/90 dark:bg-gray-700/80 rounded-lg sm:rounded-xl shadow-sm"
                  whileHover={{ scale: 1.1, rotate: 15 }}
                  whileTap={{ scale: 0.9 }}
                >
                  <Bell className="w-4 h-4 sm:w-5 sm:h-5 text-slate-600 dark:text-emerald-400" />
                </motion.div>
                <div>
                  <h3 className="font-semibold text-slate-800 dark:text-white text-sm sm:text-lg">Notificaciones</h3>
                  {unreadCount > 0 && (
                    <span className="text-xs text-slate-600 dark:text-gray-400">
                      {unreadCount} nuevas
                    </span>
                  )}
                </div>
              </div>
              <motion.button
                onClick={onClose}
                className="p-1.5 sm:p-2 hover:bg-white/80 dark:hover:bg-gray-600/60 rounded-lg sm:rounded-xl transition-all duration-200"
                whileHover={{ scale: 1.1, rotate: 90 }}
                whileTap={{ scale: 0.9 }}
              >
                <X className="w-3 h-3 sm:w-4 sm:h-4 text-slate-600 dark:text-gray-400" />
              </motion.button>
            </div>

            {/* Filter tabs */}
            <div className="flex space-x-1 bg-white/80 dark:bg-gray-700/60 rounded-lg sm:rounded-2xl p-1 backdrop-blur-sm">
              <motion.button
                onClick={() => setFilter('all')}
                className={`flex-1 px-2 sm:px-4 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium rounded-lg sm:rounded-xl transition-all duration-200 ${
                  filter === 'all'
                    ? 'bg-white dark:bg-gray-600 text-slate-700 dark:text-white shadow-sm'
                    : 'text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white hover:bg-white/60 dark:hover:bg-gray-600/40'
                }`}
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
              >
                Todas ({notifications.length})
              </motion.button>
              <motion.button
                onClick={() => setFilter('unread')}
                className={`flex-1 px-2 sm:px-4 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium rounded-lg sm:rounded-xl transition-all duration-200 ${
                  filter === 'unread'
                    ? 'bg-white dark:bg-gray-600 text-slate-700 dark:text-white shadow-sm'
                    : 'text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white hover:bg-white/60 dark:hover:bg-gray-600/40'
                }`}
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
              >
                No leídas ({unreadCount})
              </motion.button>
            </div>
          </div>

          {/* Actions */}
          <AnimatePresence>
            {unreadCount > 0 && (
              <motion.div 
                className="px-4 sm:px-6 py-2 sm:py-3 border-b border-white/40 dark:border-gray-700/30 bg-white/40 dark:bg-gray-800/30"
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                exit={{ opacity: 0, height: 0 }}
              >
                <motion.button
                  onClick={markAllAsRead}
                  className="text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium transition-colors"
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                >
                  Marcar todas como leídas
                </motion.button>
              </motion.div>
            )}
          </AnimatePresence>

          {/* Notifications List */}
          <div className="max-h-72 sm:max-h-96 overflow-y-auto">
            <AnimatePresence>
              {filteredNotifications.length === 0 ? (
                <motion.div 
                  className="p-6 sm:p-8 text-center"
                  initial={{ opacity: 0, scale: 0.8 }}
                  animate={{ opacity: 1, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.8 }}
                >
                  <motion.div 
                    className="w-12 h-12 sm:w-16 sm:h-16 bg-white/80 dark:bg-gray-700 rounded-xl sm:rounded-2xl flex items-center justify-center mx-auto mb-3 sm:mb-4"
                    animate={{ 
                      rotate: [0, 10, -10, 0],
                      scale: [1, 1.1, 1]
                    }}
                    transition={{ 
                      duration: 3, 
                      repeat: Infinity,
                      repeatType: "reverse"
                    }}
                  >
                    <Bell className="w-6 h-6 sm:w-8 sm:h-8 text-slate-400 dark:text-gray-500" />
                  </motion.div>
                  <p className={`text-sm ${
                    theme === 'light' ? 'text-slate-600' : 'text-gray-400'
                  }`}>
                    {filter === 'unread' ? 'No tienes notificaciones sin leer' : 'No hay notificaciones'}
                  </p>
                  <p className={`text-xs sm:text-sm mt-2 ${
                    theme === 'light' ? 'text-slate-500' : 'text-gray-500'
                  }`}>
                    Te notificaremos sobre actualizaciones importantes
                  </p>
                </motion.div>
              ) : (
                <div className="divide-y divide-white/40 dark:divide-gray-700/30">
                  {filteredNotifications.map((notification, index) => {
                    const IconComponent = getNotificationIcon(notification.type);
                    const iconColor = getNotificationColor(notification.type, notification.priority);
                    
                    return (
                      <motion.div
                        key={notification.id}
                        className={`p-3 sm:p-5 hover:bg-white/60 dark:hover:bg-gray-700/50 transition-all duration-200 group ${
                          !notification.read ? 'bg-blue-50/60 dark:bg-emerald-900/10 border-l-4 border-l-emerald-400' : ''
                        }`}
                        initial={{ opacity: 0, x: -20 }}
                        animate={{ opacity: 1, x: 0 }}
                        exit={{ opacity: 0, x: 20 }}
                        transition={{ delay: index * 0.05 }}
                        whileHover={{ x: 5 }}
                      >
                        <div className="flex items-start space-x-3 sm:space-x-4">
                          {/* Icon with priority indicator */}
                          <div className="relative flex-shrink-0">
                            <motion.div 
                              className={`p-2 sm:p-3 rounded-xl sm:rounded-2xl bg-white/90 dark:bg-gray-700/80 shadow-sm ${iconColor}`}
                              whileHover={{ scale: 1.1, rotate: 10 }}
                              whileTap={{ scale: 0.9 }}
                            >
                              <IconComponent className="w-3 h-3 sm:w-5 sm:h-5" />
                            </motion.div>
                            {notification.priority !== 'low' && (
                              <motion.div 
                                className={`absolute -top-1 -right-1 w-2 h-2 sm:w-3 sm:h-3 rounded-full ${getPriorityIndicator(notification.priority)} ring-2 ring-white dark:ring-gray-800`}
                                animate={{ 
                                  scale: [1, 1.2, 1],
                                  opacity: [0.7, 1, 0.7]
                                }}
                                transition={{ 
                                  duration: 2, 
                                  repeat: Infinity,
                                  repeatType: "reverse"
                                }}
                              />
                            )}
                          </div>

                          {/* Content */}
                          <div className="flex-1 min-w-0">
                            <div className="flex items-start justify-between">
                              <div className="flex-1">
                                <p className={`text-xs sm:text-sm font-semibold ${
                                  !notification.read 
                                    ? 'text-slate-800 dark:text-white' 
                                    : 'text-slate-600 dark:text-gray-300'
                                }`}>
                                  {notification.title}
                                </p>
                                <p className="text-xs sm:text-sm text-slate-600 dark:text-gray-400 mt-1 line-clamp-2 leading-relaxed">
                                  {notification.message}
                                </p>
                                <div className="flex items-center space-x-2 mt-2 sm:mt-3">
                                  <Clock className="w-2.5 h-2.5 sm:w-3 sm:h-3 text-slate-500 dark:text-gray-500" />
                                  <span className="text-xxs sm:text-xs text-slate-500 dark:text-gray-500 font-medium">
                                    {notification.timestamp}
                                  </span>
                                  {!notification.read && (
                                    <div className="flex items-center space-x-1">
                                      <motion.span 
                                        className="w-1 h-1 sm:w-1.5 sm:h-1.5 bg-emerald-500 rounded-full"
                                        animate={{ 
                                          scale: [1, 1.5, 1],
                                          opacity: [0.7, 1, 0.7]
                                        }}
                                        transition={{ 
                                          duration: 2, 
                                          repeat: Infinity,
                                          repeatType: "reverse"
                                        }}
                                      />
                                      <span className="text-xxs sm:text-xs text-emerald-600 dark:text-emerald-400 font-medium">Nuevo</span>
                                    </div>
                                  )}
                                </div>
                              </div>

                              {/* Actions */}
                              <div className="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-all duration-200">
                                {!notification.read && (
                                  <motion.button
                                    onClick={() => markAsRead(notification.id)}
                                    className="p-1 sm:p-2 hover:bg-white/90 dark:hover:bg-gray-600/80 rounded-lg sm:rounded-xl transition-all duration-200"
                                    whileHover={{ scale: 1.2, rotate: 15 }}
                                    whileTap={{ scale: 0.9 }}
                                    title="Marcar como leída"
                                  >
                                    <Check className="w-3 h-3 sm:w-4 sm:h-4 text-slate-600 dark:text-gray-400" />
                                  </motion.button>
                                )}
                                <motion.button
                                  onClick={() => deleteNotification(notification.id)}
                                  className="p-1 sm:p-2 hover:bg-white/90 dark:hover:bg-gray-600/80 rounded-lg sm:rounded-xl transition-all duration-200"
                                  whileHover={{ scale: 1.2, rotate: -15 }}
                                  whileTap={{ scale: 0.9 }}
                                  title="Eliminar notificación"
                                >
                                  <X className="w-3 h-3 sm:w-4 sm:h-4 text-slate-600 dark:text-gray-400" />
                                </motion.button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </motion.div>
                    );
                  })}
                </div>
              )}
            </AnimatePresence>
          </div>

          {/* Footer */}
          <div className="p-3 sm:p-4 border-t border-white/40 dark:border-gray-700/30 bg-white/60 dark:bg-gray-800/50 backdrop-blur-sm">
            <div className="flex items-center justify-between">
              <motion.button 
                className="text-xs sm:text-sm text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white transition-colors flex items-center space-x-1 sm:space-x-2 px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg sm:rounded-xl hover:bg-white/80 dark:hover:bg-gray-700/60"
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
              >
                <Settings className="w-3 h-3 sm:w-4 sm:h-4" />
                <span>Configurar</span>
              </motion.button>
              <motion.button 
                className="text-xs sm:text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg sm:rounded-xl hover:bg-emerald-50/80 dark:hover:bg-emerald-900/20 transition-all duration-200"
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
              >
                Ver todas
              </motion.button>
            </div>
          </div>
        </motion.div>
      </div>
    </div>,
    document.body
  );
}