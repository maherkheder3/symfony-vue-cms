<template>
    <v-flex :style="{ cursor: 'pointer' }" pa-0 ma-0 @click="getAuthorPage(author.id)">
        <v-avatar class="mr-2">
            <v-img :src="getAvatar()"></v-img>
        </v-avatar>
        <span
              v-on:click="getAuthorPage(author.id)" v-text="author.name">
        </span>
    </v-flex>
</template>


<script>
    import avatar from './../../img/user-avatar.jpeg'
    export default {
        props:[
            "author"
        ],
        methods:{
            getAuthorPage(id){
                this.$router.push({ name:'author', params:{ id: id }})
            },
            getAvatar(){
                return avatar;
            }
        }
    }
</script>