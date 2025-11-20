import { Route, Routes } from 'react-router-dom';
import ProtectedRoute from './components/ProtectedRoute';
import AdminLayout from './components/layout/AdminLayout';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import PostList from './pages/posts/PostList';
import PostForm from './pages/posts/PostForm';
import PageList from './pages/pages/PageList';
import PageForm from './pages/pages/PageForm';
import MediaManager from './pages/media/MediaManager';

const App = () => (
  <Routes>
    <Route path="/login" element={<Login />} />
    <Route element={<ProtectedRoute />}>
      <Route element={<AdminLayout />}>
        <Route index element={<Dashboard />} />
        <Route path="posts" element={<PostList />} />
        <Route path="posts/new" element={<PostForm />} />
        <Route path="posts/:id/edit" element={<PostForm />} />
        <Route path="pages" element={<PageList />} />
        <Route path="pages/new" element={<PageForm />} />
        <Route path="pages/:id/edit" element={<PageForm />} />
        <Route path="media" element={<MediaManager />} />
      </Route>
    </Route>
    <Route path="*" element={<Login />} />
  </Routes>
);

export default App;
