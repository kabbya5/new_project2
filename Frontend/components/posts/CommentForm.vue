<template>
    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
        <div class="relative h-fit">
            <button 
                @click="closeModal"
                class="absolute bg-black text-white w-10 h-10 rounded-full top-[-20px] right-[-20px]"> 
                    X 
            </button>

            <div class="w-full max-w-[800px] max-h-[700px] overflow-auto bg-white shadow-lx border border-2 border-slate-300">
                <div class="card pb-4 px-4">
                    <h2 class="text-3xl font-bold text-black tracking-wide text-center py-4 border-b-2 border-gray-200"> {{ post.user.name }}'s  Post </h2>

                    <div class="post-details mt-4">
                        <p class="text-md font-lg leading-7">{{ strLimit(post.content, 400) }}</p>
                        <img v-if="post.img_url" :src="post.img_url" alt="" class="w-full mt-3">
                    </div>

                    <div class="comment-box px-4">
                        <div class="flex items-center mt-4 border-gray-200 border-t-2 pt-3">
                            <img class="h-10 w-10 rounded-full" :src="post.user.profile_picture" :alt="post.user.name">
                            <form @submit.prevent="addComment(post.id)" class="w-full flex justify-between items-center">
                                <input 
                                    type="text" 
                                    class="border-none h-10 mx-1 px-2 w-2/3 bg-gray-200 dark:bg-slate-200" 
                                    v-model="post.comment.text" 
                                    placeholder="Add Your Comment"
                                >
                                <input 
                                    type="file" 
                                    class="hidden" 
                                    id="comment_image" 
                                    @change="handleFileChange($event)" 
                                    accept="image/*"
                                >
                                <label for="comment_image"> 
                                    <FontAwesomeIcon :icon="['fas','camera']" class="text-2xl text-black dark:text-white" />
                                </label>

                                <button type="submit"> 
                                    <FontAwesomeIcon class="text-2xl text-sky-800 dark:text-white" :icon="['fas', 'paper-plane']"/>
                                </button>
                            </form>
                        </div>

                        <div v-if="post.comment.previewUrl" class="mt-2 relative">
                            <button
                                @click="clearPreview(post)"
                                class="absolute bg-black text-white w-10 h-10 rounded-full top-[10px] right-[10px]"> 
                                        X 
                            </button>
                            <img class="h-full w-full" :src="post.comment.previewUrl" :alt="post.comment.previewUrl">
                        </div>
                    </div>

                    <div class="post-comment px-4 my-4">
                        <div class="mb-6 w-full max-w-[400px]" v-if="post.comments" v-for="comment in post.comments" :key="comment.id"> 
                            <div class="user-info flex items-top">
                                <img class="h-10 w-10 rounded-full" :src="post.user.profile_picture" :alt="post.user.name">
                                <div class="pl-3">
                                    <p class="text-black dark:text-white" :class="{ 'font-md text-sm': post.page, 'font-bold text-md': !post.page }"> {{ post.user.name }}</p>
                                    <p class="font-semibold text-gray-600 text-sm text-"> {{ comment.content }} </p>
                                    <img class="mt-2" :src="getFileUrl(comment.img_url)" :alt="post.title">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { usePostsStore } from '@/stores/posts';
import getFileUrl from '~/utils/fileUrl';
const postsStore = usePostsStore();

const prop = defineProps<{
  post: {
    id: number;
    title: string;
    content: string;
    img_url:string;
    user: {
      profile_picture: string;
      name: string;
    };
    comment: {
      image: File | null;
      previewUrl: string | null;
      text: string;
    };
  };
}>();

const post = prop.post;
if (!post.comment) {
    post.comment = {
        text: '',
        image: null as File | null,
        previewUrl: null as string | null
    };
}

const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        const file = input.files[0];
        post.comment.image = file; 
        post.comment.previewUrl = URL.createObjectURL(file); 
    }
};

const clearPreview = (post: any) => {
    if (post.comment && post.comment.previewUrl) {
        URL.revokeObjectURL(post.comment.previewUrl); 
        post.comment.previewUrl = null; 
        post.comment.image = null;  
    }
};

const addComment = async (id: number) => {
    const { text, image } = post.comment;
    const formData = new FormData();
    formData.append('text', text);
    
    if (image) {
        formData.append('image', image);
    }

    if(image || text){
        postsStore.addComment(id, formData);

        post.comment.text = '';
        clearPreview(post);
        closeModal();
    }else{
        alert('add some comment');
    }
    
}
const emits = defineEmits(['showCommentForm']);

const closeModal = () => {
    emits('showCommentForm');
}



</script>
