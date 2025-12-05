<template class="h-full">
    <QuillEditor :options="options" contentType="html" />
</template>

<script setup lang="ts">
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['blockquote', 'code-block'],
  ['link', 'image', 'video', 'formula'],

  [{ 'header': 1 }, { 'header': 2 }],               // custom button values
  [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['clean']                                         // remove formatting button
];


const options = ref({
    modules:{
        toolbar: toolbarOptions
    },
    placeholder:'Add Content Here ...',
    readOnly:false,
    them:'snow'
});

</script>

<style scoped>
/* Quill editor height parent wrapper অনুযায়ী */
.ql-editor, .ql-container {
  min-height: 300px; /* wrapper অনুযায়ী match করবে */
  max-height: 100%;
  color:#fff;
}

.ql-toolbar .ql-formats button svg {
  stroke: white;        /* icon color */
  fill: white;          /* icon fill color */
}

/* hovered / active state */
.ql-toolbar .ql-formats button:hover svg,
.ql-toolbar .ql-formats button.ql-active svg {
  stroke: #fbbf24;      /* example: yellow on hover/active */
  fill: #fbbf24;
}

/* dropdown select text color */
.ql-toolbar .ql-picker-label {
  color: white !important;
}
.ql-toolbar .ql-picker-item {
  color: white !important;
  background: #1f2937 !important; /* dark dropdown bg */
}
</style>

