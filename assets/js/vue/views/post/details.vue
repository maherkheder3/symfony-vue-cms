<template>
    <div>
        <loading v-if="isLoading"/>

        <div v-else-if="hasError">
            <v-alert :value="true"
                     type="error">
                {{ error }}
            </v-alert>
        </div>

        <div v-else-if="post === undefined">
            <v-alert :value="true"
                     type="error">
                wait
            </v-alert>
        </div>

        <v-flex v-else>
            <v-card>
                <v-container py-5>
                    <v-img :src="getImage(post.image)" height="400px"></v-img>

                    <v-container px-0>
                        <span class="display-2 font-weight-black" v-text="post.title"></span>
                    </v-container>
                    <span class="headline d-block" v-text="post.summary"></span>
                    <span class="mt-5 d-block" v-text="post.content"></span>
                </v-container>
            </v-card>
        </v-flex>

        <v-btn @click="xxxx">xx</v-btn>
    </div>
</template>


<script>
    import Loading from '../../components/Loading'

    export default {
        name: 'Details',
        components: {
            Loading
        },
        data() {
            return {
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
            // hasPosts() {
            //     return this.$store.getters['post/hasPosts'];
            // },
            post() {
                return this.$store.getters['post/details'];
            },
            // admin() {
            //     return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            // }
        },
        created() {
            this.$store.dispatch('post/details', this.$route.params.postId);
        },
        methods: {
            getImage(image) {
                let imageSrc = "";
                if (image && image.length > 2) {
                    imageSrc = "/uploads/posts/" + image;
                } else {
                    imageSrc = "https://unsplash.it/150/300?image=" + Math.floor(Math.random() * (80) + 1)
                }
                return imageSrc;
            },
            xxxx() {
                this.$awn.success("Login is success")
                this.$awn.warning("Your custom message")
                console.log(this.post);
            }
        }
    }
</script>