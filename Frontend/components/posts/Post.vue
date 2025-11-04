<template>
    <PostsCommentForm v-if="isVisiableComment" :post="post"  @showCommentForm="showCommentForm"/>
    
    <div class="post mb-5 border border-2 border-gray-200 p-4 w-full">
        <div class="post-header d-flex justify-between items-center">
            <div class="user-info flex items-center">
                <img class="h-10 w-10 rounded-full" :src="`https://via.placeholder.com/500x480.png/0000bb?text=${post.user.name}`" :alt="post.user.name">
                 <div class="pl-3">
                    <div v-if="post.page">
                        <p class="font-bold text-black dark:text-white"> {{ post.page.name }} </p>
                    </div>
                    <p class="text-black dark:text-white" :class="{ 'font-md text-sm': post.page, 'font-bold text-md': !post.page }"> {{ post.user.name }}</p>
                    <p class="font-md text-gray-500 text-sm"> {{ post.created_at }}</p>
                 </div>
            </div>
        </div>
        <div class="post-body pt-4 text-[#313131] dark:text-white text-md font-[500]  line-heig leading-6">
            <p class="font-bold text-2xl tracking-[0.5px;]"> {{ post.title }}</p>
            <p class="mt-2 tracking-[0.3px] font-md text-justify">
                <span v-if="isExpanded"> {{ post.content }} 
                    <button @click="toggleExpanded" style="font-weight: 600;"> {{'See less'}} </button>
                </span> 
                
                <span v-else> 
                    {{ strLimit(post.content, 400) }} <button @click="toggleExpanded" style="font-weight: 600;"> {{'See More'}} </button>
                </span>
            </p>
    
            <img v-if="post.img_url" :src="post.img_url" alt="" class="w-full mt-3">
        </div>
    
        <div class="post-footer pt-4">
            <div class="py-2 border-t-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                <div class="flex justify-between align-items-center">
    
                    <div class="flex items-center" v-if="post.likes_count > 0">
                        <span  class="flex items-center justify-center mr-1 w-6 h-6 rounded-full bg-blue-800">
                            <FontAwesomeIcon  class="text-md text-white" :icon="['fas',  'thumbs-up']" />
                        </span>
                        
                    
                        <span v-if="post.likes_count > 1">
                            {{ post.isLiked ? userName + ' and ' + (post.likes_count - 1) + ' others' : post.likes_count }}
                        </span>
    
                        <span v-else-if="post.likes_count === 1">
                            {{ post.isLiked ? userName : post.likes_count }}
                        </span>
                    </div>
    
                    <span v-if="post.comments_count > 0">
                        {{  post.comments_count}} 
                        <FontAwesomeIcon  class="text-md text-gray-500" :icon="['fas',  'comment']" />
                    </span>
    
                    <span> 2 Shers  </span>
                </div>
            </div>
    
            <div class="py-2 border-t-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                <div class="flex justify-around align-items-center">
                    <button @click="handelLike(post.id)" :class="post.isLiked ? 'text-blue-800 origin-center rotate-12]' : 'text-gray-500'"> <FontAwesomeIcon  class="text-xl" :icon="['fas', 'thumbs-up']" /> Like </button>
                    <button class="text-gray-500" @click="showCommentForm"> <FontAwesomeIcon class="text-xl" :icon="['fas',  'comment']" /> Comment </button>
                    <span class="text-gray-500"> <FontAwesomeIcon class="text-xl" :icon="['fas', 'share']" /> Share </span>
                </div>
            </div>
    
            <div class="comment mt-3 border-gray-200 border-t-2 pt-3">
                <div v-if="post.comments"> 
                    <div class="user-info flex items-end">
                        <img class="h-8 w-8 rounded-full" :src="post.user.profile_picture" :alt="post.user.name">
                        <div class="pl-3">
                            <p class="text-black dark:text-white" :class="{ 'font-md text-sm': post.page, 'font-bold text-md': !post.page }"> {{ post.user.name }}</p>
                            <p class="font-semibold text-gray-600 text-sm"> CV upload kore Jache na </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </template>
    
    <script setup lang="ts">
    import { usePostsStore } from '@/stores/posts';
    import { useAuthStore } from '~/stores/authStore';
    
    const {getUser} = useAuthStore();
    
    const postsStore = usePostsStore(); 
    
    defineProps({
      post: {
        type: Object,
        required: true,
      },
    });
    
    const isVisiableComment = ref(false);
    const isExpanded = ref(false);
    
    const toggleExpanded = () => {
        isExpanded.value = !isExpanded.value;
    }
    
    const handelLike = (postId:number) => { 
        postsStore.likePost(postId);
    }
    
    const userData = await getUser();
    const userName = userData.name;
    const { $echo } = useNuxtApp();
    
    
    onMounted(() => {
        const posts = postsStore.posts;
        $echo.channel('post.like')
        .listen('LikedEvent', (e:any) => {
            const post = posts.find(p => p.id === e.post_id);
            post.likes_count =  e.likes_count;
        })

        $echo.channel('post.comment')
       
        .listen('CommentEvent', (e:any) => {
            console.log(e);
            var post = postsStore.posts.find(p => p.id === e.post_id);
            post.comments.unshift(e.comment);
            post.comments_count++; 
        });
    });

    const showCommentForm = () =>{
        isVisiableComment.value = !isVisiableComment.value
    }
    </script>
    