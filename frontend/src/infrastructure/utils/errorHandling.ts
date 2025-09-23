// Utility for handling errors and preventing error message cycles

// Keep track of error messages to prevent duplicates
const errorMessageTracker = {
    messages: new Set<string>(),
    lastShownTime: new Map<string, number>(),
    
    // Check if we should show this error message
    shouldShowError(message: string): boolean {
      // If we've never seen this message, show it
      if (!this.messages.has(message)) {
        this.messages.add(message);
        this.lastShownTime.set(message, Date.now());
        return true;
      }
      
      // If we've seen it, only show again after 5 minutes
      const lastShown = this.lastShownTime.get(message) || 0;
      const now = Date.now();
      const fiveMinutes = 5 * 60 * 1000;
      
      if (now - lastShown > fiveMinutes) {
        this.lastShownTime.set(message, now);
        return true;
      }
      
      return false;
    },
    
    // Clear all tracked errors
    clearAll() {
      this.messages.clear();
      this.lastShownTime.clear();
    }
  };
  
  // Intercept fetch to prevent unnecessary API calls
  export function setupFetchInterceptor() {
    const originalFetch = window.fetch;
    
    window.fetch = async function(input: RequestInfo | URL, init?: RequestInit) {
      const url = input instanceof Request ? input.url : input.toString();
      
      // Block requests to non-existent chat APIs
      if (url.includes('/api/chats/') || 
          url.includes('bolt.new/api/chats') || 
          url.includes('analytics_client')) {
        console.log('Blocked request to:', url);
        return new Response(JSON.stringify({ 
          error: 'API not available in demo mode' 
        }), { 
          status: 200,
          headers: { 'Content-Type': 'application/json' }
        });
      }
      
      // Let other requests proceed normally
      return originalFetch(input, init);
    };
  }
  
  // Global error handler to prevent console spam
  export function setupGlobalErrorHandlers() {
    // Store original console methods
    const originalConsoleError = console.error;
    
    // Override console.error to filter repetitive errors
    console.error = function(...args) {
      const errorMessage = args.map(arg => 
        typeof arg === 'string' ? arg : 
        arg instanceof Error ? arg.message : 
        JSON.stringify(arg)
      ).join(' ');
      
      // Filter out known API errors
      if (errorMessage.includes('/api/chats/') || 
          errorMessage.includes('bolt.new/api') || 
          errorMessage.includes('analytics_client') ||
          errorMessage.includes('Failed to persist') ||
          errorMessage.includes('FedCM get() rejects with') ||
          errorMessage.includes('GSI_LOGGER')) {
        // Completely suppress these errors without any console output
        return;
      }
      
      // Pass through other errors normally
      originalConsoleError.apply(console, args);
    };
    
    // Handle unhandled promise rejections
    window.addEventListener('unhandledrejection', (event) => {
      const errorMessage = event.reason?.message || String(event.reason);
      
      // Prevent default browser error handling for known API errors
      if (errorMessage.includes('/api/chats/') || 
          errorMessage.includes('bolt.new/api') || 
          errorMessage.includes('analytics_client') ||
          errorMessage.includes('Failed to persist') ||
          errorMessage.includes('FedCM get() rejects with') ||
          errorMessage.includes('GSI_LOGGER')) {
        event.preventDefault();
        // Completely suppress these errors without any console output
      }
    });
  }