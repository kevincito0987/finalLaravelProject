import React, { createContext, useContext, useState, useEffect } from 'react';
import { mockUser, simulateNetworkDelay } from '../lib/mockData';
import { SafeStorage } from '../utils/storage';
import type { Database } from '../lib/database.types';
import { jwtDecode } from 'jwt-decode';

type Member = Database['public']['Tables']['members']['Row'];
type PersonaProfile = Database['public']['Tables']['personas_profiles']['Row'];

interface User extends Member {
  profile?: PersonaProfile;
  avatar?: string;
}

interface GoogleUser {
  email: string;
  name: string;
  picture: string;
  given_name?: string;
  family_name?: string;
}

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, lastname: string, email: string, password: string) => Promise<void>;
  logout: () => void;
  updateUserAvatar?: (avatarUrl: string) => void;
  handleGoogleLogin: (credentialResponse: any) => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Simular carga inicial y verificar usuario guardado
    const initializeAuth = async () => {
      try {
        await simulateNetworkDelay(1000);
        
        // Intentar recuperar usuario del almacenamiento seguro
        const savedUser = SafeStorage.getItem('habita_user');
        if (savedUser) {
          try {
            const parsedUser = JSON.parse(savedUser);
            setUser(parsedUser);
          } catch (error) {
            console.error('Error al parsear usuario guardado:', error);
            SafeStorage.removeItem('habita_user');
          }
        }
      } catch (error) {
        console.error('Error en inicialización de auth:', error);
      } finally {
        setLoading(false);
      }
    };
    
    initializeAuth();
  }, []);

  const login = async (email: string, password: string) => {
    setLoading(true);
    try {
      await simulateNetworkDelay(1500);
      
      // Simular validación de credenciales
      if (email === 'demo@habita.app' && password === 'demo123') {
        const userData: User = {
          ...mockUser,
          profile: mockUser.profile,
          avatar: `https://images.unsplash.com/photo-1494790108755-2616b612b786?w=100&h=100&fit=crop&crop=face`
        };
        
        setUser(userData);
        
        // Guardar usuario de forma segura
        SafeStorage.setItem('habita_user', JSON.stringify(userData));
      } else {
        throw new Error('Credenciales inválidas. Usa: demo@habita.app / demo123');
      }
    } catch (error) {
      console.error('Error en login:', error);
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const register = async (name: string, lastname: string, email: string, password: string) => {
    setLoading(true);
    try {
      await simulateNetworkDelay(2000);
      
      // Simular registro exitoso
      const newUser: User = {
        id: Date.now(),
        username: email.split('@')[0],
        email: email,
        password: password,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
        profile: {
          id: Date.now(),
          name: name,
          lastname: lastname,
          city_id: 1,
          person_type_id: 1,
          member_id: Date.now(),
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString()
        },
        avatar: `https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&crop=face`
      };
      
      setUser(newUser);
      
      // Guardar usuario de forma segura
      SafeStorage.setItem('habita_user', JSON.stringify(newUser));
    } catch (error) {
      console.error('Error en registro:', error);
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const updateUserAvatar = (avatarUrl: string) => {
    if (user) {
      const updatedUser = {
        ...user,
        avatar: avatarUrl
      };
      setUser(updatedUser);
      
      // Actualizar en almacenamiento
      SafeStorage.setItem('habita_user', JSON.stringify(updatedUser));
    }
  };

  const handleGoogleLogin = async (credentialResponse: any) => {
    setLoading(true);
    try {
      await simulateNetworkDelay(1500);
      
      // Decode the JWT token from Google
      const decodedUser = jwtDecode<GoogleUser>(credentialResponse.credential);
      
      // Create a user from the Google profile
      const googleUser: User = {
        id: Date.now(),
        username: decodedUser.email.split('@')[0],
        email: decodedUser.email,
        password: '', // Not needed for OAuth
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
        profile: {
          id: Date.now(),
          name: decodedUser.given_name || decodedUser.name.split(' ')[0],
          lastname: decodedUser.family_name || decodedUser.name.split(' ').slice(1).join(' '),
          city_id: 1,
          person_type_id: 1,
          member_id: Date.now(),
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString()
        },
        avatar: decodedUser.picture
      };
      
      setUser(googleUser);
      
      // Guardar usuario de forma segura
      SafeStorage.setItem('habita_user', JSON.stringify(googleUser));
    } catch (error) {
      console.error('Error during Google login:', error);
      throw new Error('Error al iniciar sesión con Google');
    } finally {
      setLoading(false);
    }
  };

  const logout = () => {
    setUser(null);
    
    // Limpiar almacenamiento de forma segura
    SafeStorage.removeItem('habita_user');
    
    // Limpiar cualquier dato temporal que pueda estar causando problemas
    try {
      const keysToRemove = [];
      for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && (
          key.startsWith('habita_') || 
          key.includes('chat') || 
          key.includes('message') || 
          key.includes('analytics') || 
          key.includes('bolt.new') ||
          key.includes('api')
        )) {
          keysToRemove.push(key);
        }
      }
      
      keysToRemove.forEach(key => SafeStorage.removeItem(key));
    } catch (error) {
      console.error('Error al limpiar datos en logout:', error);
    }
  };

  return (
    <AuthContext.Provider value={{ 
      user, 
      loading, 
      login, 
      register, 
      logout, 
      updateUserAvatar, 
      handleGoogleLogin 
    }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}