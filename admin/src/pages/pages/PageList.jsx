import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../../api/client';

const PageList = () => {
  const [pages, setPages] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  const loadPages = async () => {
    setLoading(true);
    try {
      const { data } = await api.get('/pages');
      setPages(data.data || data);
    } catch (err) {
      alert('Unable to load pages.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadPages();
  }, []);

  const removePage = async (page) => {
    if (!window.confirm(`Delete "${page.title}"?`)) {
      return;
    }

    try {
      await api.delete(`/pages/${page.id}`);
      loadPages();
    } catch (err) {
      alert('Unable to remove the page right now.');
    }
  };

  if (loading) {
    return <div className="card">Loading pages...</div>;
  }

  return (
    <section className="card">
      <div className="card__header">
        <div>
          <h1>Pages</h1>
        </div>
        <button type="button" onClick={() => navigate('/pages/new')}>New Page</button>
      </div>

      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Updated</th>
            <th />
          </tr>
        </thead>
        <tbody>
          {pages.map((page) => (
            <tr key={page.id}>
              <td>{page.title}</td>
              <td>{page.slug}</td>
              <td>
                <span className={page.is_published ? 'badge badge--success' : 'badge'}>
                  {page.is_published ? 'Published' : 'Draft'}
                </span>
              </td>
              <td>{new Date(page.updated_at).toLocaleDateString()}</td>
              <td className="table-actions">
                <Link to={`/pages/${page.id}/edit`}>Edit</Link>
                <button type="button" onClick={() => removePage(page)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </section>
  );
};

export default PageList;
