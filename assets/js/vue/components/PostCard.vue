<template>
    <v-container px-0 py-0>
        <v-layout>
            <v-flex>
                <v-card>
                    <v-img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxmweLKV7RPwieiJKVga_O1yWZ4bXtKyg8YRMo9iyTY95JSsfU"
                           height="200px" >
                    </v-img>

                    <v-container px-3 py-3 fill-height fluid pa-0 v-on:click="getPostDetails(post)">
                        <v-layout fill-height>
                            <v-flex xs12 align-end flexbox>
                                <span :style="cardText" v-text="post.title.substring(0,30)"></span>
                                <v-spacer></v-spacer>
                                <span v-text="getDate(post.publishedAt.date)"></span>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-container px-3 py-3 :style="'color: #ccc;'">
                        <v-layout>
                            <v-flex>
                                <span>{{ post.summary.substring(0, 150) }}</span>
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
                    fontWeight: 'bold',
                },

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
            }
        }
    }
</script>