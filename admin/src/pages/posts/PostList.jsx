import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../../api/client';

const PostList = () => {
  const [posts, setPosts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  const loadPosts = async () => {
    setLoading(true);
    setError(null);
    try {
      const { data } = await api.get('/posts');
      setPosts(data.data || data);
    } catch (err) {
      setError('Unable to load posts.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadPosts();
  }, []);

  const removePost = async (post) => {
    if (!window.confirm(`Delete "${post.title}"?`)) {
      return;
    }

    try {
      await api.delete(`/posts/${post.id}`);
      await loadPosts();
    } catch (err) {
      alert('Something went wrong while removing the post.');
    }
  };

  const togglePublish = async (post) => {
    try {
      await api.patch(`/posts/${post.id}/publish`, { is_published: !post.is_published });
      await loadPosts();
    } catch (err) {
      alert('Unable to update publish status.');
    }
  };

  if (loading) {
    return <div className="card">Loading posts...</div>;
  }

  return (
    <section className="card">
      <div className="card__header">
        <div>
          <h1>Posts</h1>
          <p>Manage articles for the public site.</p>
        </div>
        <button type="button" onClick={() => navigate('/posts/new')}>New Post</button>
      </div>

      {error && <div className="alert">{error}</div>}

      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {posts.map((post) => (
            <tr key={post.id}>
              <td>{post.title}</td>
              <td>
                <span className={post.is_published ? 'badge badge--success' : 'badge'}>
                  {post.is_published ? 'Published' : 'Draft'}
                </span>
              </td>
              <td>{new Date(post.updated_at).toLocaleDateString()}</td>
              <td>
                <div className="table-actions">
                  <button type="button" onClick={() => togglePublish(post)}>
                    {post.is_published ? 'Unpublish' : 'Publish'}
                  </button>
                  <Link to={`/posts/${post.id}/edit`}>Edit</Link>
                  <button type="button" onClick={() => removePost(post)}>Delete</button>
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  );
};

export default PostList;
