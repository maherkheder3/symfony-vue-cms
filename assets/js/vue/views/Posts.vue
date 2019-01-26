<template>
    <div>
        <div class="row col">
            <h1>Posts</h1>
        </div>

        <router-link class="row col" v-if="admin" tag="div" to="/admin/post/create">
            <a class="btn btn-primary">Create new post</a>
        </router-link>

        <input type="text" v-model.lazily.trim="multiValueList">
        <h5>{{ multiValueList }}</h5>

        <td :style="styleCustom">{‌{ props.item.name }‌}</td>


        <div v-if="isLoading">
            <loading></loading>
        </div>

        <div v-else-if="hasError" class="row col">
            <div class="alert alert-danger" role="alert">
                {{ error }}
            </div>
        </div>

        <div v-else-if="!hasPosts" class="row col">
            No posts!
        </div>

        <div v-else class="row" >
            <div v-for="post in posts" class="col-lg-3">
                <postCard :post="post"></postCard>
            </div>
        </div>
    </div>
</template>

<script>
    import PostCard from '../components/PostCard';
    import Loading from '../components/Loading'

    export default {
        name: 'posts',
        components: {
            PostCard,
            Loading
        },
        data () {
            return {
                multiValueList : "",
                styleCustom: {
                    color: this.$vuetify.theme.primary,
                    backgroundColor: this.$vuetify.theme.secondary
                }
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
            admin () {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        },

    }
</script>