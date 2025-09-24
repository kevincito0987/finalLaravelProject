import React, { useState, useEffect } from 'react';
import { Watch, Smartphone, Tablet, Laptop } from 'lucide-react';
import { motion } from 'framer-motion';
import SmartWatchOptimizer from './SmartWatchOptimizer';

interface ResponsiveWrapperProps {
  children: React.ReactNode;
  className?: string;
}

export default function ResponsiveWrapper({ children, className = '' }: ResponsiveWrapperProps) {
  const [deviceType, setDeviceType] = useState<'watch' | 'mobile' | 'tablet' | 'desktop'>('desktop');
  const [showDeviceInfo, setShowDeviceInfo] = useState(false);

  useEffect(() => {
    // Simple device detection based on screen width
    const detectDevice = () => {
      const width = window.innerWidth;
      if (width < 280) return 'watch';
      if (width < 768) return 'mobile';
      if (width < 1024) return 'tablet';
      return 'desktop';
    };

    setDeviceType(detectDevice());
    
    const handleResize = () => {
      setDeviceType(detectDevice());
    };

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const getDeviceIcon = () => {
    switch (deviceType) {
      case 'watch': return <Watch className="w-4 h-4" />;
      case 'mobile': return <Smartphone className="w-4 h-4" />;
      case 'tablet': return <Tablet className="w-4 h-4" />;
      default: return <Laptop className="w-4 h-4" />;
    }
  };

  // Apply SmartWatchOptimizer for watch-sized screens
  if (deviceType === 'watch') {
    return (
      <div className={`relative ${className}`}>
        {/* Device indicator for development */}
        {process.env.NODE_ENV === 'development' && (
          <motion.div 
            className="fixed top-2 right-2 z-50 bg-blue-100 dark:bg-blue-900/30 text-white-100 dark:text-white-400 rounded-full px-2 py-1 text-xs flex items-center space-x-1 cursor-pointer shadow-lg"
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            onClick={() => setShowDeviceInfo(!showDeviceInfo)}
          >
            {getDeviceIcon()}
          </motion.div>
        )}
        
        <SmartWatchOptimizer>
          {children}
        </SmartWatchOptimizer>
      </div>
    );
  }

  return (
    <div className={`relative ${className}`}>
      {/* Device indicator for development */}
      {process.env.NODE_ENV === 'development' && (
        <motion.div 
          className="fixed top-2 right-2 z-50 bg-blue-100 dark:bg-blue-900/30 text-white-800 dark:white-white-300 rounded-full px-2 py-1 text-xs flex items-center space-x-1 cursor-pointer shadow-lg"
          whileHover={{ scale: 1.1 }}
          whileTap={{ scale: 0.9 }}
          onClick={() => setShowDeviceInfo(!showDeviceInfo)}
        >
          {getDeviceIcon()}
          {deviceType !== 'watch' && <span className="ml-1">{deviceType}</span>}
        </motion.div>
      )}

      {/* Apply responsive classes based on device type */}
      <div className={`
        ${deviceType === 'mobile' ? 'xs:text-sm xs:p-2 xs:space-y-2' : ''}
      `}>
        {children}
      </div>
    </div>
  );
}