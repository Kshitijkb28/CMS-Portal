import { useEffect, useState } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import api from '../../api/client';
import RichTextEditor from '../../components/RichTextEditor';

const PostForm = () => {
  const { id } = useParams();
  const isEdit = Boolean(id);
  const navigate = useNavigate();
  const [categories, setCategories] = useState([]);
  const { register, handleSubmit, control, setValue, formState: { errors, isSubmitting } } = useForm({
    defaultValues: {
      title: '',
      slug: '',
      excerpt: '',
      body: '',
      category_id: '',
      is_published: false,
      featured_image_path: '',
      meta_title: '',
      meta_description: '',
    },
  });

  useEffect(() => {
    const loadCategories = async () => {
      try {
        const { data } = await api.get('/categories');
        setCategories(data.data || data);
      } catch (err) {
        setCategories([]);
      }
    };

    loadCategories();
  }, []);

  useEffect(() => {
    const loadPost = async () => {
      if (!isEdit) {
        return;
      }

      try {
        const { data } = await api.get(`/posts/${id}`);
        Object.entries(data).forEach(([key, value]) => {
          if (value !== null && value !== undefined) {
            setValue(key, value);
          }
        });
      } catch (err) {
        alert('Unable to load the post.');
      }
    };

    loadPost();
  }, [id, isEdit, setValue]);

  const onSubmit = async (values) => {
    const payload = {
      ...values,
      is_published: Boolean(values.is_published),
      category_id: values.category_id || null,
    };

    try {
      if (isEdit) {
        await api.put(`/posts/${id}`, payload);
      } else {
        await api.post('/posts', payload);
      }

      navigate('/posts');
    } catch (err) {
      alert('Unable to save the post.');
    }
  };

  return (
    <form className="card form" onSubmit={handleSubmit(onSubmit)}>
      <div className="card__header">
        <div>
          <h1>{isEdit ? 'Edit Post' : 'New Post'}</h1>
          <p>Write rich content that will appear on the public site.</p>
        </div>
        <button type="submit" disabled={isSubmitting}>
          {isSubmitting ? 'Saving...' : 'Save'}
        </button>
      </div>

      <label>
        Title
        <input {...register('title', { required: 'Title is required' })} />
        {errors.title && <small className="field-error">{errors.title.message}</small>}
      </label>

      <label>
        Slug (optional)
        <input {...register('slug')} placeholder="auto-generated when left empty" />
      </label>

      <label>
        Excerpt
        <textarea rows={2} {...register('excerpt')} />
      </label>

      <label>
        Category
        <select {...register('category_id')}>
          <option value="">Uncategorized</option>
          {categories.map((category) => (
            <option key={category.id} value={category.id}>{category.name}</option>
          ))}
        </select>
      </label>

      <label>
        Body
        <Controller
          control={control}
          name="body"
          rules={{ required: 'Body is required' }}
          render={({ field }) => (
            <RichTextEditor value={field.value} onChange={field.onChange} />
          )}
        />
        {errors.body && <small className="field-error">{errors.body.message}</small>}
      </label>

      <label className="checkbox">
        <input type="checkbox" {...register('is_published')} />
        Publish immediately
      </label>

      <label>
        Featured image path
        <input {...register('featured_image_path')} placeholder="Optional path from media manager" />
      </label>

      <label>
        Meta title
        <input {...register('meta_title')} />
      </label>

      <label>
        Meta description
        <textarea rows={2} {...register('meta_description')} />
      </label>
    </form>
  );
};

export default PostForm;
