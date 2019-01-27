<template>
    <div>
        <v-carousel hide-controls>
            <v-carousel-item
                    v-for="(item,i) in carouselItems"
                    :key="i"
                    :src="item.src"
            ></v-carousel-item>
        </v-carousel>

        <v-container>
            <div class="row col">
                <h1>Homepage</h1>
            </div>

            <div class="row col">
                <p>This is the homepage of our Vue.js application.</p>
            </div>
        </v-container>
    </div>
</template>

<script>
    export default {
        name: 'home',
        data(){
            return{
                carouselItems: [
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/sky.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg'
                    }
                ]
            }
        }
    }
</script>