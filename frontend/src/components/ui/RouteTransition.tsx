import React from 'react';
import EnhancedRouteTransition from './EnhancedRouteTransition';

interface RouteTransitionProps {
  children: React.ReactNode;
}

export default function RouteTransition({ children }: RouteTransitionProps) {
  return <EnhancedRouteTransition>{children}</EnhancedRouteTransition>;
}