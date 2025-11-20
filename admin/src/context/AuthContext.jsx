import { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react';
import api, { setAuthToken, setUnauthorizedHandler } from '../api/client';

const AuthContext = createContext(null);

const TOKEN_KEY = 'cms_token';
const USER_KEY = 'cms_user';

export const AuthProvider = ({ children }) => {
  const [token, setToken] = useState(() => localStorage.getItem(TOKEN_KEY));
  const [user, setUser] = useState(() => {
    const stored = localStorage.getItem(USER_KEY);
    return stored ? JSON.parse(stored) : null;
  });
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(!!token);
  const [error, setError] = useState(null);

  const logout = useCallback(async () => {
    try {
      await api.post('/logout');
    } catch (err) {
      // Best effort only.
    } finally {
      setAuthToken(null);
      localStorage.removeItem(TOKEN_KEY);
      localStorage.removeItem(USER_KEY);
      setToken(null);
      setUser(null);
      setStats(null);
      setLoading(false);
    }
  }, []);

  const fetchProfile = useCallback(async () => {
    if (!token) {
      return;
    }

    try {
      const { data } = await api.get('/me');
      setUser(data.user);
      setStats(data.stats);
      localStorage.setItem(USER_KEY, JSON.stringify(data.user));
      setLoading(false);
    } catch (err) {
      setError('Your session expired. Please log in again.');
      await logout();
    }
  }, [logout, token]);

  useEffect(() => {
    setUnauthorizedHandler(() => {
      logout();
    });
  }, [logout]);

  useEffect(() => {
    if (token) {
      setAuthToken(token);
      localStorage.setItem(TOKEN_KEY, token);
      fetchProfile();
    } else {
      setLoading(false);
    }
  }, [token, fetchProfile]);

  const login = useCallback(async (credentials) => {
    setError(null);
    const { data } = await api.post('/login', credentials);

    setToken(data.token);
    setAuthToken(data.token);
    localStorage.setItem(TOKEN_KEY, data.token);
    localStorage.setItem(USER_KEY, JSON.stringify(data.user));
    setUser(data.user);
    setStats(null);
    await fetchProfile();
  }, [fetchProfile]);

  const value = useMemo(() => ({
    token,
    user,
    stats,
    loading,
    error,
    login,
    logout,
    refreshProfile: fetchProfile,
    setError,
  }), [token, user, stats, loading, error, login, logout, fetchProfile]);

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);

  if (!context) {
    throw new Error('useAuth must be used inside an AuthProvider');
  }

  return context;
};
