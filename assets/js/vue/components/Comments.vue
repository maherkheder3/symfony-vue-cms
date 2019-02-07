<template>
    <v-container mt-4 pa-0>
        <v-layout>
            <v-flex xs12 sm12>
                <v-card>
                    <v-toolbar color="cyan" dark>
                        <v-toolbar-title>this post have {{ comments.length }} Comments</v-toolbar-title>

                        <v-spacer></v-spacer>

                        <v-btn icon>
                            <v-icon>comment</v-icon>
                        </v-btn>
                    </v-toolbar>

                    <v-list three-line>
                        <template v-for="(item, index) in comments">
                            <v-list-tile
                                    :key="item.id"
                                    avatar
                                    @click="">
                                <div style="min-width: 150px">
                                    <author :author="item.author"></author>
                                </div>

                                <v-list-tile-content>
                                    <!--<v-list-tile-title v-html="item.author.name"></v-list-tile-title>-->
                                    <v-list-tile-sub-title v-html="getDate(item.publisheAt.date)"></v-list-tile-sub-title>
                                    <v-list-tile-sub-title v-html="item.content.substr(0, 230)"></v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </template>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>


<script>
    import Author from "../components/Author";

    export default {
        props:["comments"],
        components:{Author},
        methods:{
            getDate(date){
                var from = date.split("-")
                var f = new Date(from[2].split(' ')[0], from[1] - 1, from[0])

                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                return "It was  : " + f.getDay() + " " + monthNames[f.getMonth()] + " " + f.getFullYear()
            },
        }
    }
</script>