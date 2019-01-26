<template>
    <div>
        <h1>{{ post.title }}</h1>

        <p class="post-metadata">
            <!--<span class="metadata"><i class="fa fa-calendar"></i> {{ // post.publishedAt|localizeddate('long', 'medium', null, 'UTC') }}</span>-->
            <!--<span class="metadata"><i class="fa fa-user"></i> {{ post.author.fullName }}</span>-->
        </p>

        <div class="well">
            <p class="m-b-0"><strong>Summery</strong>: {{ post.summary }}</p>
        </div>
    </div>
</template>


<script>
    export default {
        name: 'Details',
        data () {
            return {
                post: {
                    title: "",
                    summary: "",
                    content: "",
                    image: "",
                },
            };
        },
        computed: {
            isLoading() {
                return this.$store.getters['post/isLoading'];
            },
            hasError() {
                return this.$store.getters['post/hasError'];
            },
            error() {
                return this.$store.getters['post/error'];
            },
            hasPosts() {
                return this.$store.getters['post/hasPosts'];
            },
            posts() {
                return this.$store.getters['post/posts'];
            },
            admin() {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        },
        created () {
            this.post = this.$route.params.post;
        },
    }
</script>