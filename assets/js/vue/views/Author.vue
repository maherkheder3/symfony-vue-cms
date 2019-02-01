<template>
    <div class="author">
        <v-layout row wrap fill>
            <v-flex px-4 xs12 md4 style="min-height:1200px">
                <div class="elevation-8">
                    <v-layout class="avatar">
                        <v-img :src="getAvatar(author.image)">
                            <div style="position: absolute; bottom: 2%; left: 5%; font-weight: bold;">
                                <author-more-info :username="'FOLLOW ' + author.name" :avatar="getAvatar(author.image)"></author-more-info>
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
                                                                <v-icon color="red">phone</v-icon>
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
                                                                <v-icon color="red">mail</v-icon>
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
                                                                <v-icon color="red">location_on</v-icon>
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
                <v-layout row wrap>
                    <v-flex v-for="(post, index) in posts"
                            v-bind="{ [`xs12 md6 lg6`]: true }"
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

    export default {
        components:{ AuthorMoreInfo, PostCard },
        data(){
            return{
                author:{
                    name: 'Test',
                    email: 'test@gmail.com',
                    image: null
                },
                infoList: [
                    { title: 'Click Me' },
                    { title: 'Click Me' },
                    { title: 'Click Me' },
                    { title: 'Click Me 2' }
                ]
            }
        },
        created() {
            axios.get("").then((data) => {

            });
        },
        computed: {
            isLoading () {
                return this.$store.getters['post/isLoading'];
            },
            hasPosts () {
                return this.$store.getters['post/hasPosts'];
            },
            posts () {
                return this.$store.getters['post/posts'];
            }
        },
        methods:{
            getAvatar(avatar){
                if(avatar){
                    return avatar;
                }else{
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