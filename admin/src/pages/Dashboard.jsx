import { useEffect, useState } from 'react';
import api from '../api/client';
import { useAuth } from '../context/AuthContext';
import LoadingScreen from '../components/common/LoadingScreen';

const Dashboard = () => {
  const { stats, user } = useAuth();
  const [latestPosts, setLatestPosts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadPosts = async () => {
      try {
        const { data } = await api.get('/posts', { params: { per_page: 5 } });
        setLatestPosts(data.data || data);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    };

    loadPosts();
  }, []);

  if (loading && !stats) {
    return <LoadingScreen message="Loading dashboard..." />;
  }

  return (
    <div className="card-stack">
      <section className="card">
        <h1>Welcome back, {user?.name}</h1>
        <p>Here is a quick overview of what&apos;s happening.</p>
        <div className="stat-grid">
          <div className="stat">
            <span>Total Posts</span>
            <strong>{stats?.posts ?? 0}</strong>
          </div>
          <div className="stat">
            <span>Published Posts</span>
            <strong>{stats?.published_posts ?? 0}</strong>
          </div>
          <div className="stat">
            <span>Pages</span>
            <strong>{stats?.pages ?? 0}</strong>
          </div>
          <div className="stat">
            <span>Drafts</span>
            <strong>{stats?.drafts ?? 0}</strong>
          </div>
        </div>
      </section>

      <section className="card">
        <div className="card__header">
          <h2>Latest posts</h2>
        </div>
        {latestPosts.length === 0 && <p>No posts yet.</p>}
        <ul className="list">
          {latestPosts.map((post) => (
            <li key={post.id}>
              <span>
                {post.title}
                <small>{post.is_published ? 'Published' : 'Draft'}</small>
              </span>
              <small>{new Date(post.published_at ?? post.created_at).toLocaleDateString()}</small>
            </li>
          ))}
        </ul>
      </section>
    </div>
  );
};

export default Dashboard;
