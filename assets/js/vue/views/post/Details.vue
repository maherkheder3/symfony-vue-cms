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
            <v-alert :value="true" type="error">
                wait
            </v-alert>
        </div>

        <v-flex v-else>
            <v-card>
                <v-container py-4>
                    <router-link v-if="admin" :to="{ name: 'Edit_post' , post: post }">
                        <v-btn color="primary" absolute="absolute" style="z-index: 1; right: 0">
                            <v-icon>edit</v-icon>
                        </v-btn>
                    </router-link>

                    <v-img :src="getImage(post.image)" height="400px"></v-img>

                    <v-container px-0>
                        <div v-if="admin">
                            <span id="post-title" contenteditable="true" @blur="setNewPostTitle(post.title)" class="display-1 font-weight-black">{{ post.title }}</span>
                        </div>
                        <div v-else>
                            <span class="display-1 font-weight-black" v-text="post.title"></span>
                        </div>
                    </v-container>

                    <v-container px-3 py-3 fill-height fluid>
                        <v-layout fill-height>
                            <v-flex xs12 align-end flexbox py-4>
                                <span v-text="getDate(post.publishedAt.date)"></span>
                                <v-flex :style="{ float:'right' }">
                                    <author :author="post.author"></author>
                                </v-flex>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <span class="headline d-block" v-text="post.summary"></span>
                    <span class="mt-5 d-block" v-text="post.content"></span>
                </v-container>
            </v-card>

            <comments :comments="post.comments"></comments>
        </v-flex>

        <v-btn @click="xxxx">xx</v-btn>
    </div>
</template>


<script>
    import axios from 'axios'
    import Loading from '../../components/Loading'
    import Author from "../../components/Author";
    import Comments from "../../components/Comments";

    export default {
        name: 'Details',
        components: {
            Loading,
            Author,
            Comments
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
            post: {
                get(){
                    return this.$store.getters['post/details'];
                }
            },
            admin() {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
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
                    // imageSrc = "https://unsplash.it/150/300?image=" + Math.floor(Math.random() * (80) + 1)
                    imageSrc = "https://picsum.photos/1137/400/?image=" + (Math.floor(Math.random() * (80) + 1))
                }
                return imageSrc;
            },
            xxxx() {
                this.$awn.success("Login is success")
                this.$awn.warning("Your custom message")
                this.$awn.alert("Your custom message")
                console.log(this.post);
            },
            getDate(date){
                var from = date.split("-")
                var f = new Date(from[2].split(' ')[0], from[1] - 1, from[0])

                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                return f.getDay() + " " + monthNames[f.getMonth()] + " " + f.getFullYear()
            },
            getAuthorPage(authorId){
                console.log(authorId)
            },
            setNewPostTitle(data){
                let postTitle = document.getElementById('post-title');

                var value = postTitle.innerText.trim();
                if(data !== value){
                    let post = {
                        "id" :this.post.id,
                        "title" : value
                    };

                    var self = this;
                    axios.post('/api/post/edittitle', post).then(function (data) {
                        self.$awn[data.data.title](data.data.massage)
                        self.post.title = value;
                    })
                }
            }
        }
    }
</script>