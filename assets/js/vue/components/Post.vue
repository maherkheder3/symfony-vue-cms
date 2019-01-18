<template>
    <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxmweLKV7RPwieiJKVga_O1yWZ4bXtKyg8YRMo9iyTY95JSsfU" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">{{ message.title }}</h5>
            <p class="card-text">{{ message.summary.substring(0,10) }}</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'post',
        props: ['message'],
    }
</script>