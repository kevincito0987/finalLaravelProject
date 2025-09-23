import React, { createContext, useContext, useState, useEffect } from 'react';

interface User {
  id: number;
  email: string;
  name: string;
  avatar?: string;
  // Si Laravel incluye más campos, agrégalos aquí
}

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string) => Promise<void>;
  logout: () => void;
  fetchUser: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);
  const tokenKey = 'habita_token';

  const fetchUser = async () => {
    const token = localStorage.getItem(tokenKey);
    if (!token) return;

    try {
      const res = await fetch('/api/user', {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
        },
      });

      if (!res.ok) throw new Error('No se pudo obtener el usuario');

      const data = await res.json();
      setUser(data);
    } catch (error) {
      console.error('Error al obtener usuario:', error);
      logout(); // Token inválido
    }
  };

  useEffect(() => {
    fetchUser().finally(() => setLoading(false));
  }, []);

  const login = async (email: string, password: string) => {
    setLoading(true);
    try {
      const res = await fetch('/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });

      const data = await res.json();
      if (!res.ok || !data.token) throw new Error(data.message || 'Login fallido');

      localStorage.setItem(tokenKey, data.token);
      await fetchUser();
    } catch (error) {
      console.error('Error en login:', error);
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const register = async (name: string, email: string, password: string) => {
    setLoading(true);
    try {
      const res = await fetch('/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password }),
      });

      const data = await res.json();
      if (!res.ok || !data.token) throw new Error(data.message || 'Registro fallido');

      localStorage.setItem(tokenKey, data.token);
      await fetchUser();
    } catch (error) {
      console.error('Error en registro:', error);
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const logout = () => {
    localStorage.removeItem(tokenKey);
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout, fetchUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) throw new Error('useAuth debe usarse dentro de AuthProvider');
  return context;
}
