<template>
    <v-container px-0 py-0>
        <v-layout>
            <v-flex>
                <v-card>
                    <v-img @click="getPostDetails(post)" :style="pointer" :src="getImage(post.image)"
                           height="200px" >
                    </v-img>

                    <v-container px-3 py-3 fill-width fluid pa-0>
                        <v-layout xs12>
                            <v-flex style="display: block; width: 100%">
                                <span class="headline" :style="[pointer, cardText]" v-text="post.title.substring(0,18)"
                                      @click="getPostDetails(post)"></span>
                            </v-flex>
                        </v-layout>

                        <v-layout>
                            <v-flex xs8 align-end flexbox>
                                <v-flex>
                                    <author :author="post.author"></author>
                                </v-flex>
                            </v-flex>
                            <v-flex xs4 py-4>
                                <v-icon style="font-size: medium">calendar_today</v-icon>
                                <span v-text="getDate(post.publishedAt.date)"></span>
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
                        <div v-for="category in post.categories" class="pl-2">
                            <span v-text="category.name"></span>
                        </div>
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
    import Author from "./Author";

    export default {
        name: 'PostCard',
        props: ['post'],
        components:{ Author },
        data(){
            return {
                cardText:{
                    display: 'block',
                    height: '70px',
                    width: '100%'
                },
                pointer: {
                    'cursor': 'pointer'
                },
            }
        },
        methods:{
            getPostDetails(post){
                this.$router.push({ name:'PostDetails', params:{ postId: post.id }})
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
            getImage(image){
                let imageSrc = "";
                if(image !== null && image.length > 2)
                {
                    imageSrc =  "/uploads/posts/" + image;
                }
                else{
                    imageSrc = "https://unsplash.it/150/300?image=" + Math.floor(Math.random() * (80) + 1)
                }
                return imageSrc;
            }
        }
    }
</script>