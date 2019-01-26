<template>
    <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxmweLKV7RPwieiJKVga_O1yWZ4bXtKyg8YRMo9iyTY95JSsfU" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">{{ post.title }}</h5>
            <p class="card-text">{{ post.summary.substring(0,10) }}</p>

            <v-btn v-on:click="getPostDetails(post)" class="btn btn-primary">Show More</v-btn>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'PostCard',
        props: ['post'],
        methods:{
            getPostDetails(post){
                this.$router.push({ name:'PostDetails',params:{ post: post }})
            }
        }
    }
</script>