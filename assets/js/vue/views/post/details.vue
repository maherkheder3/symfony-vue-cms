<template>
    <div>
        <v-flex>
            <v-card>
                <v-container py-5>
                    <v-img :src="this.post.image" height="400px"></v-img>

                    <v-container px-0>
                        <span class="display-3 font-weight-black" v-text="this.post.title"></span>
                    </v-container>
                    <span  class="headline d-block" v-text="this.post.summary"></span>
                    <span class="mt-5 d-block" v-text="this.post.content"></span>
                </v-container>
            </v-card>
        </v-flex>
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
            console.log(this.post)
        },
    }
</script>