// custom.d.ts

import 'react';

// Extiende las propiedades de los elementos <style> para incluir 'jsx'
declare module 'react' {
  interface StyleHTMLAttributes<T> extends React.HTMLAttributes<T> {
    /**
     * Propiedad utilizada por 'styled-jsx' para CSS scope.
     */
    jsx?: boolean;
    
    /**
     * Propiedad utilizada por 'styled-jsx' para CSS global.
     */
    global?: boolean; 
  }
}