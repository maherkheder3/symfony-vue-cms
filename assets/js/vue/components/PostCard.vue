<template>
    <v-container px-0 py-0>
        <v-layout>
            <v-flex>
                <v-card>
                    <v-img :style="pointer" :src="`https://unsplash.it/150/300?image=${Math.floor(Math.random() * 80) + 1}`"
                           height="200px" >
                    </v-img>

                    <v-container px-3 py-3 fill-height fluid pa-0>
                        <v-layout fill-height>
                            <v-flex xs12 align-end flexbox>
                                <span class="headline" :style="[pointer, cardText]" v-text="post.title.substring(0,18)"
                                      @click="getPostDetails(post)"></span>

                                <span v-text="getDate(post.publishedAt.date)"></span>
                                <span :style="[pointer, {'float':'right', 'padding-right': '10px'}]" v-on:click="getAuthorPage(post.author.id)" v-text="post.author.name"></span>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-container px-3 py-1 :style="'color: #ccc; height: 90px; overflow:hidden'">
                        <v-layout>
                            <v-flex>
                                <span>{{ post.summary.substring(0, 130) }}..</span>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-card-actions :style="{ backgroundColor: this.$vuetify.theme.primary }">
                        <v-spacer></v-spacer>

                        <v-btn icon>
                            <v-icon small>favorite</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon small>bookmark</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon small>share</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: 'PostCard',
        props: ['post'],
        data(){
            return {
                cardText:{
                    display: 'block',
                    height: '70px'
                },
                pointer: {
                    'cursor': 'pointer'
                }
            }
        },
        methods:{
            getPostDetails(post){
                this.$router.push({ name:'PostDetails',params:{ post: post }})
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
            }
        }
    }
</script>