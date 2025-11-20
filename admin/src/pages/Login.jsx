import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Login = () => {
  const { register, handleSubmit, setError, formState: { errors, isSubmitting } } = useForm();
  const { login, token, error, setError: setAuthError } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    if (token) {
      navigate('/', { replace: true });
    }
  }, [token, navigate]);

  const onSubmit = async (values) => {
    try {
      await login(values);
      const redirectTo = location.state?.from?.pathname || '/';
      navigate(redirectTo, { replace: true });
    } catch (err) {
      const message = err.response?.data?.message || 'Unable to login. Check your credentials.';
      setError('email', { type: 'server', message });
      setAuthError(message);
    }
  };

  return (
    <div className="auth-wrapper">
      <form className="auth-card" onSubmit={handleSubmit(onSubmit)}>
        <h1>CMS Admin</h1>
        <p>Log in with your Laravel credentials.</p>
        {error && <div className="alert">{error}</div>}
        <label htmlFor="email">
          Email
          <input id="email" type="email" autoComplete="email" {...register('email', { required: 'Email is required' })} />
          {errors.email && <small className="field-error">{errors.email.message}</small>}
        </label>
        <label htmlFor="password">
          Password
          <input id="password" type="password" autoComplete="current-password" {...register('password', { required: 'Password is required' })} />
          {errors.password && <small className="field-error">{errors.password.message}</small>}
        </label>
        <button type="submit" disabled={isSubmitting}>
          {isSubmitting ? 'Signing in...' : 'Login'}
        </button>
      </form>
    </div>
  );
};

export default Login;
