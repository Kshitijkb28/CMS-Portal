import { useEffect } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useNavigate, useParams } from 'react-router-dom';
import api from '../../api/client';
import RichTextEditor from '../../components/RichTextEditor';

const PageForm = () => {
  const { id } = useParams();
  const isEdit = Boolean(id);
  const navigate = useNavigate();
  const { register, handleSubmit, control, setValue, formState: { errors, isSubmitting } } = useForm({
    defaultValues: {
      title: '',
      slug: '',
      excerpt: '',
      body: '',
      is_published: false,
      meta_title: '',
      meta_description: '',
    },
  });

  useEffect(() => {
    const loadPage = async () => {
      if (!isEdit) {
        return;
      }

      try {
        const { data } = await api.get(`/pages/${id}`);
        Object.entries(data).forEach(([key, value]) => {
          if (value !== null && value !== undefined) {
            setValue(key, value);
          }
        });
      } catch (err) {
        alert('Unable to load the page.');
      }
    };

    loadPage();
  }, [id, isEdit, setValue]);

  const onSubmit = async (values) => {
    const payload = {
      ...values,
      is_published: Boolean(values.is_published),
    };

    try {
      if (isEdit) {
        await api.put(`/pages/${id}`, payload);
      } else {
        await api.post('/pages', payload);
      }

      navigate('/pages');
    } catch (err) {
      alert('Unable to save the page.');
    }
  };

  return (
    <form className="card form" onSubmit={handleSubmit(onSubmit)}>
      <div className="card__header">
        <div>
          <h1>{isEdit ? 'Edit Page' : 'New Page'}</h1>
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
        Slug
        <input {...register('slug')} />
      </label>

      <label>
        Excerpt
        <textarea rows={2} {...register('excerpt')} />
      </label>

      <label>
        Body
        <Controller
          control={control}
          name="body"
          rules={{ required: 'Body is required' }}
          render={({ field }) => <RichTextEditor value={field.value} onChange={field.onChange} />}
        />
        {errors.body && <small className="field-error">{errors.body.message}</small>}
      </label>

      <label className="checkbox">
        <input type="checkbox" {...register('is_published')} />
        Publish
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

export default PageForm;
