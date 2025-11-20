import { useEffect, useState } from 'react';
import api from '../../api/client';

const MediaManager = () => {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [uploading, setUploading] = useState(false);

  const loadMedia = async () => {
    setLoading(true);
    try {
      const { data } = await api.get('/media');
      setItems(data.data || data);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadMedia();
  }, []);

  const handleUpload = async (event) => {
    const file = event.target.files?.[0];
    if (!file) {
      return;
    }

    const formData = new FormData();
    formData.append('file', file);

    setUploading(true);
    try {
      await api.post('/media/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });
      await loadMedia();
      event.target.value = '';
    } catch (err) {
      alert('Upload failed. Please try again.');
    } finally {
      setUploading(false);
    }
  };

  const removeItem = async (item) => {
    if (!window.confirm(`Delete ${item.original_name}?`)) {
      return;
    }

    try {
      await api.delete(`/media/${item.id}`);
      loadMedia();
    } catch (err) {
      alert('Unable to delete the file.');
    }
  };

  return (
    <section className="card">
      <div className="card__header">
        <div>
          <h1>Media Manager</h1>
          <p>Upload and reuse assets.</p>
        </div>
        <label className="upload">
          <input type="file" onChange={handleUpload} />
          {uploading ? 'Uploading...' : 'Upload'}
        </label>
      </div>

      {loading ? (
        <p>Loading files...</p>
      ) : (
        <div className="media-grid">
          {items.map((item) => (
            <article key={item.id} className="media-card">
              <div className="media-card__body">
                <p>{item.original_name}</p>
                <small>{Math.round(item.size / 1024)} KB</small>
                <small>{item.mime_type}</small>
              </div>
              <button type="button" onClick={() => removeItem(item)}>Delete</button>
            </article>
          ))}
        </div>
      )}
    </section>
  );
};

export default MediaManager;
