<template>
    <div>
        <div class="row col">
            <h1>Posts</h1>
        </div>

        <div class="row col" v-if="canCreatePost">
            <form>
                <div class="form-row">
                    <div class="col-8">
                        <input v-model="post.title" type="text" class="form-control" name="title">
                        <input v-model="post.summary" type="text" class="form-control" name="summary">
                        <input v-model="post.content" type="text" class="form-control" name="content">
                    </div>
                    <div class="col-4">
                        <button @click="createPost()" :disabled="post.title.length === 0 || isLoading" type="button" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <div class="alert alert-danger" role="alert">
                {{ error }}
            </div>
        </div>

        <div v-else-if="!hasPosts" class="row col">
            No posts!
        </div>

        <div v-else class="row">
            <div v-for="post in posts" class="col-lg-3">
                <post :message="post"></post>
            </div>
        </div>
    </div>
</template>

<script>
    import Post from '../components/Post';

    export default {
        name: 'posts',
        components: {
            Post
        },
        data () {
            return {
                post: {
                    title: "",
                    summary: "",
                    content: ""
                },
            };
        },
        created () {
            this.$store.dispatch('post/fetchPosts');
        },
        computed: {
            isLoading () {
                return this.$store.getters['post/isLoading'];
            },
            hasError () {
                return this.$store.getters['post/hasError'];
            },
            error () {
                return this.$store.getters['post/error'];
            },
            hasPosts () {
                return this.$store.getters['post/hasPosts'];
            },
            posts () {
                return this.$store.getters['post/posts'];
            },
            canCreatePost () {
                return this.$store.getters['security/hasRole']('USER');
            }
        },
        methods: {
            createPost () {
                this.$store.dispatch('post/createPost', this.$data.post)
                    .then(() => this.$data.post = '')
            },
        },
    }
</script>