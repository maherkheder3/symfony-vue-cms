<template>
    <div class="author">

        <loading v-if="isLoading" />

        <div v-else-if="hasError">
            <v-alert :value="true"
                     type="error">
                {{ error }}
            </v-alert>
        </div>

        <v-layout v-else row wrap fill>

            <v-flex xs12 style="margin-bottom: 150px; background-color: white">
                <v-img style="width: 100%; position: absolute;" src="https://picsum.photos/1200/600/?image=715"></v-img>
            </v-flex>

            <v-flex px-4 xs12 md4 style="min-height:1200px">
                <div class="elevation-8">
                    <v-layout class="avatar">
                        <v-img :src="getAvatar(author.image)">
                            <div style="position: absolute; bottom: 2%; left: 5%; font-weight: bold;">
                                <author-more-info :username="'FOLLOW ' + author.fullName" :avatar="getAvatar(author.image)"></author-more-info>
                            </div>
                        </v-img>
                    </v-layout>
                    <div class="information">
                        <v-layout row>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title class="white--text">
                                        <span class="title">ABOUT</span>
                                    </v-card-title>

                                    <v-card-text>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam autem consectetur deserunt, dolorem ea eaque enim et fugiat in numquam porro, quam, quod rem reprehenderit similique sit soluta ullam voluptates.</v-card-text>

                                    <div>
                                        <v-layout row>
                                            <v-flex xs12>
                                                <v-card>
                                                    <v-list two-line>
                                                        <v-list-tile @click="">
                                                            <v-list-tile-action>
                                                                <v-icon color="primary">phone</v-icon>
                                                            </v-list-tile-action>

                                                            <v-list-tile-content>
                                                                <v-list-tile-title>(650) 555-1234</v-list-tile-title>
                                                                <v-list-tile-sub-title>Mobile</v-list-tile-sub-title>
                                                            </v-list-tile-content>

                                                            <v-list-tile-action>
                                                                <v-icon>chat</v-icon>
                                                            </v-list-tile-action>
                                                        </v-list-tile>

                                                        <v-list-tile @click="">
                                                            <v-list-tile-action></v-list-tile-action>

                                                            <v-list-tile-content>
                                                                <v-list-tile-title>(323) 555-6789</v-list-tile-title>
                                                                <v-list-tile-sub-title>Work</v-list-tile-sub-title>
                                                            </v-list-tile-content>

                                                            <v-list-tile-action>
                                                                <v-icon>chat</v-icon>
                                                            </v-list-tile-action>
                                                        </v-list-tile>

                                                        <v-divider inset></v-divider>

                                                        <v-list-tile @click="">
                                                            <v-list-tile-action>
                                                                <v-icon color="primary">mail</v-icon>
                                                            </v-list-tile-action>

                                                            <v-list-tile-content>
                                                                <v-list-tile-title>aliconnors@example.com</v-list-tile-title>
                                                                <v-list-tile-sub-title>Personal</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>

                                                        <v-list-tile @click="">
                                                            <v-list-tile-action></v-list-tile-action>

                                                            <v-list-tile-content>
                                                                <v-list-tile-title>ali_connors@example.com</v-list-tile-title>
                                                                <v-list-tile-sub-title>Work</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>

                                                        <v-divider inset></v-divider>

                                                        <v-list-tile @click="">
                                                            <v-list-tile-action>
                                                                <v-icon color="primary">location_on</v-icon>
                                                            </v-list-tile-action>

                                                            <v-list-tile-content>
                                                                <v-list-tile-title>1400 Main Street</v-list-tile-title>
                                                                <v-list-tile-sub-title>Orlando, FL 79938</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                    </v-list>
                                                </v-card>
                                            </v-flex>
                                        </v-layout>
                                    </div>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </div>
                </div>
            </v-flex>
            <v-flex xs12 md8 class="">
                <v-layout style="height: 140px; width: 100%" hidden-xs-only hidden-sm-only></v-layout>
                <div v-if="!hasPosts">
                    <v-alert :value="true" type="info">
                        No posts!
                    </v-alert>
                </div>

                <v-layout v-else row wrap mt-4>
                    <v-flex xs12 px-5 py-2 class="" style="z-index: 100">
                        <span class="display-3" :color="white" v-text="author.fullName"></span>
                        <span class="subheading" style="display: block"
                              v-text="'Role : ' + author.roles[0].replace('ROLE_', '') + ' - Working in Germany and have ' + posts.length + ' posts'"></span>
                    </v-flex>

                    <v-flex v-for="(post, index) in posts"
                            v-bind="{ [`xs12 sm6 md6 lg6`]: true }"
                            :key="index" pa-2>
                        <postCard :post="post"></postCard>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </div>
</template>


<script>
    import axios from 'axios'
    import DefaulAvatarImage from './../../img/default-avatar-big.jpg'
    import AuthorMoreInfo from "../components/AuthorMoreInfo";
    import PostCard from "../components/PostCard";
    import Loading from "../components/Loading";

    export default {
        components:{ AuthorMoreInfo, PostCard, Loading },
        data(){
            return{
                author:{},
                infoList: [
                    { title: 'Click Me' },
                    { title: 'Click Me' },
                    { title: 'Click Me' },
                    { title: 'Click Me 2' }
                ]
            }
        },
        created() {
            axios.get("/api/author/" + this.$route.params.id).then((data) => {
                this.author = data.data;
            });
            this.$store.dispatch('post/fetchPosts');
        },
        computed: {
            isLoading () {
                return this.$store.getters['post/isLoading'];
            },
            hasPosts () {
                return this.$store.getters['post/hasPosts'];
            },
            error () {
                return this.$store.getters['post/error'];
            },
            hasError () {
                return this.$store.getters['post/hasError'];
            },
            posts () {
                let allPosts = this.$store.getters['post/posts'];

                let authorPosts = [];
                let self = this;
                allPosts.forEach(function (post) {
                    if(post !== undefined){
                        if(post.author.id == self.author.id){
                            authorPosts.push(post);
                        }
                    }
                });
                return authorPosts;
            }
        },
        methods:{
            getAvatar(avatar){
                if(avatar){
                    return avatar;
                }
                else{
                    return DefaulAvatarImage;
                }
            }
        }
    }
</script>

<style scoped>
    .author{
        background-color: black;
    }
    .avatar{
        width:100%;
        max-height: 400px
    }
    .information .v-icon{
        font-size: 16px;
    }
    .information .v-list__tile__action{
        min-width: 30px;
    }
</style>