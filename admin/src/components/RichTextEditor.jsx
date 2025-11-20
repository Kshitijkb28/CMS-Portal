import ReactQuill from 'react-quill';

const toolbar = [
  [{ header: [1, 2, false] }],
  ['bold', 'italic', 'underline', 'blockquote'],
  [{ list: 'ordered' }, { list: 'bullet' }],
  ['link', 'clean'],
];

const RichTextEditor = ({ value, onChange }) => (
  <ReactQuill theme="snow" value={value} onChange={onChange} modules={{ toolbar }} />
);

export default RichTextEditor;
