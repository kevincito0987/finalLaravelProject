import React, { useEffect, useState } from 'react';
import { motion } from 'framer-motion';

interface SmartWatchOptimizerProps {
  children: React.ReactNode;
}

export default function SmartWatchOptimizer({ children }: SmartWatchOptimizerProps) {
  const [isSmartwatch, setIsSmartwatch] = useState(false);
  const [viewportHeight, setViewportHeight] = useState(window.innerHeight);
  const [viewportWidth, setViewportWidth] = useState(window.innerWidth);

  useEffect(() => {
    // Detect if we're on a smartwatch-sized screen
    const checkSmartwatch = () => {
      const width = window.innerWidth;
      const height = window.innerHeight;
      
      setViewportWidth(width);
      setViewportHeight(height);
      setIsSmartwatch(width < 280 || (width < 320 && height < 320));
    };
    
    checkSmartwatch();
    window.addEventListener('resize', checkSmartwatch);
    return () => window.removeEventListener('resize', checkSmartwatch);
  }, []);

  // Apply smartwatch-specific optimizations
  if (isSmartwatch) {
    return (
      <div className="smartwatch-container text-xxs">
        <style jsx global>{`
          /* Global smartwatch optimizations */
          body {
            font-size: 10px !important;
            overflow-x: hidden !important;
          }
          
          h1, h2, h3 {
            font-size: 14px !important;
            margin-bottom: 4px !important;
            line-height: 1.2 !important;
          }
          
          h4, h5, h6 {
            font-size: 12px !important;
            margin-bottom: 2px !important;
            line-height: 1.2 !important;
          }
          
          p {
            font-size: 10px !important;
            margin-bottom: 4px !important;
            line-height: 1.2 !important;
          }
          
          button, a {
            font-size: 10px !important;
            padding: 4px 8px !important;
            min-height: 24px !important;
          }
          
          .smartwatch-container {
            max-width: ${viewportWidth}px;
            max-height: ${viewportHeight}px;
            overflow: auto;
            padding: 4px !important;
          }
          
          /* Reduce all spacing */
          .p-6, .p-8, .p-4 {
            padding: 4px !important;
          }
          
          .m-6, .m-8, .m-4 {
            margin: 4px !important;
          }
          
          .space-y-6, .space-y-8, .space-y-4 {
            margin-top: 4px !important;
            margin-bottom: 4px !important;
          }
          
          /* Make all icons smaller */
          svg {
            width: 12px !important;
            height: 12px !important;
          }
          
          /* Optimize grid layouts */
          .grid {
            grid-template-columns: 1fr !important;
            gap: 4px !important;
          }
          
          /* Optimize text */
          .text-xs, .text-sm, .text-base, .text-lg, .text-xl {
            font-size: 10px !important;
            line-height: 1.2 !important;
          }
          
          /* Optimize buttons */
          button, .button {
            min-height: 24px !important;
            min-width: 24px !important;
          }
          
          /* Optimize navigation */
          nav {
            padding: 2px !important;
          }
          
          /* Optimize header */
          header {
            padding: 2px !important;
            min-height: 30px !important;
          }
          
          /* Optimize cards */
          .rounded-2xl, .rounded-3xl {
            border-radius: 8px !important;
          }
          
          /* Ensure content is centered */
          .text-center {
            text-align: center !important;
          }
          
          /* Ensure all content is visible */
          .overflow-hidden {
            overflow: visible !important;
          }
          
          /* Optimize for touch */
          button, a, [role="button"] {
            min-width: 30px !important;
            min-height: 30px !important;
          }
        `}</style>
        
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.5 }}
        >
          {children}
        </motion.div>
      </div>
    );
  }

  // Return normal content for non-smartwatch devices
  return <>{children}</>;
}