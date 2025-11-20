import { NavLink, Outlet } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

const links = [
  { to: '/', label: 'Dashboard' },
  { to: '/posts', label: 'Posts' },
  { to: '/pages', label: 'Pages' },
  { to: '/media', label: 'Media' },
];

const AdminLayout = () => {
  const { user, logout } = useAuth();

  return (
    <div className="admin-shell">
      <aside className="sidebar">
        <div className="sidebar__brand">
          <span>CMS</span>
          <small>React + Laravel</small>
        </div>
        <nav className="sidebar__nav">
          {links.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              end={link.to === '/'}
              className={({ isActive }) => (isActive ? 'sidebar__link sidebar__link--active' : 'sidebar__link')}
            >
              {link.label}
            </NavLink>
          ))}
        </nav>
        {user && (
          <div className="sidebar__user">
            <p>{user.name}</p>
            <button type="button" onClick={logout}>Logout</button>
          </div>
        )}
      </aside>
      <main className="admin-content">
        <Outlet />
      </main>
    </div>
  );
};

export default AdminLayout;
