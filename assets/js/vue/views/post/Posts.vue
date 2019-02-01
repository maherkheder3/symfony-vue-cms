<template>
    <div>
        <router-link v-if="admin" to="/admin/post/create">
            <v-btn color="ml-4 primary">Create new post</v-btn>
        </router-link>

        <!--<input type="text" v-model.lazily.trim="multiValueList">-->
        <!--<h5>{{ multiValueList }}</h5>-->

        <search></search>

        <loading v-if="isLoading" />

        <div v-else-if="hasError">
            <v-alert :value="true"
                    type="error">
                {{ error }}
            </v-alert>
        </div>

        <div v-else-if="!hasPosts">
            <v-alert :value="true" type="info">
                No posts!
            </v-alert>
        </div>

        <v-container v-else justify-center fluid grid-list-md >

            <v-alert :value="true" type="success">All Post : {{ posts.length }}</v-alert>

            <v-layout row wrap>
                <v-flex v-for="post in posts"
                        v-bind="{ [`xs12 sm6 md4 lg4 xl3`]: true }"
                        :key="getKey(post.id)" >
                    <postCard :post="post"></postCard>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import PostCard from '../../components/PostCard';
    import Loading from '../../components/Loading'
    import Search from './Search'
    export default {
        name: 'posts',
        components: {
            PostCard,
            Loading,
            Search,
        },
        data () {
            return {
                // styleCustom: {
                //     color: this.$vuetify.theme.primary,
                //     backgroundColor: this.$vuetify.theme.secondary
                // },
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
        methods:{
            getKey(postId){
                return postId * Math.random();
            }
        }
    }
</script>