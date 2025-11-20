import { Navigate, Outlet, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import LoadingScreen from './common/LoadingScreen';

const ProtectedRoute = () => {
  const { token, loading } = useAuth();
  const location = useLocation();

  if (loading) {
    return <LoadingScreen message="Preparing your workspace..." />;
  }

  if (!token) {
    return <Navigate to="/login" replace state={{ from: location }} />;
  }

  return <Outlet />;
};

export default ProtectedRoute;
