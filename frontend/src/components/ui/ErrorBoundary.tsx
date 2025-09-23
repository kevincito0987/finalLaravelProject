import React, { Component, ErrorInfo, ReactNode } from 'react';
import { AlertTriangle, RefreshCw } from 'lucide-react';
import { SafeStorage } from '../../utils/storage';

interface Props {
  children: ReactNode;
  fallback?: ReactNode;
}

interface State {
  hasError: boolean;
  error?: Error;
  errorInfo?: ErrorInfo;
  errorCount: number;
}

class ErrorBoundary extends Component<Props, State> {
  constructor(props: Props) {
    super(props);
    this.state = { 
      hasError: false,
      errorCount: 0
    };
  }

  static getDerivedStateFromError(error: Error): Partial<State> {
    return { hasError: true, error };
  }

  componentDidCatch(error: Error, errorInfo: ErrorInfo) {
    // Ignore certain errors that are related to the chat API
    if (
      error.message.includes('/api/chats/') || 
      error.message.includes('bolt.new/api') || 
      error.message.includes('analytics_client') ||
      error.message.includes('Failed to persist')
    ) {
      console.log('Ignoring known API error:', error.message);
      this.setState(prevState => ({ 
        hasError: false,
        errorCount: prevState.errorCount + 1
      }));
      
      // Clean up localStorage if we're getting repeated errors
      if (this.state.errorCount > 3) {
        this.cleanupLocalStorage();
        this.setState({ errorCount: 0 });
      }
      
      return;
    }
    
    console.error('ErrorBoundary caught an error:', error, errorInfo);
    this.setState({ error, errorInfo });
    
    // Limpiar datos problemáticos del localStorage
    this.cleanupLocalStorage();
  }

  cleanupLocalStorage() {
    try {
      // Limpiar datos que puedan estar causando problemas
      const keysToRemove = [];
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && (
          key.includes('chat') || 
          key.includes('message') || 
          key.includes('analytics') || 
          key.includes('bolt.new') ||
          key.includes('api')
        )) {
          keysToRemove.push(key);
        }
      }
      
      console.log('Limpiando datos problemáticos:', keysToRemove);
      keysToRemove.forEach(key => {
        try {
          localStorage.removeItem(key);
        } catch (e) {
          console.error(`Error al eliminar ${key}:`, e);
        }
      });
    } catch (error) {
      console.error('Error al limpiar localStorage:', error);
    }
  }

  handleReset = () => {
    // Limpiar datos problemáticos antes de reintentar
    this.cleanupLocalStorage();
    
    // Reiniciar el estado del componente
    this.setState({ 
      hasError: false, 
      error: undefined, 
      errorInfo: undefined,
      errorCount: 0
    });
  };

  render() {
    if (this.state.hasError) {
      if (this.props.fallback) {
        return this.props.fallback;
      }

      return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
          <div className="max-w-md w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div className="flex items-center mb-4">
              <AlertTriangle className="w-6 h-6 text-red-500 mr-2" />
              <h2 className="text-lg font-semibold text-gray-900 dark:text-white">
                Algo salió mal
              </h2>
            </div>
            <p className="text-gray-600 dark:text-gray-400 mb-4">
              Ha ocurrido un error inesperado. Por favor, intenta recargar la página.
            </p>
            {this.state.error && (
              <div className="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p className="text-sm text-red-700 dark:text-red-300 font-mono">
                  {this.state.error.toString()}
                </p>
              </div>
            )}
            <button
              onClick={this.handleReset}
              className="w-full bg-emerald-600 text-white py-2 px-4 rounded-lg hover:bg-emerald-700 transition-colors flex items-center justify-center"
            >
              <RefreshCw className="w-4 h-4 mr-2" />
              Reiniciar aplicación
            </button>
          </div>
        </div>
      );
    }

    return this.props.children;
  }
}

export default ErrorBoundary;