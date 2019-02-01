<template>
    <v-layout
            dark
            height="auto"
    >
        <v-card
                flat
                tile
                class="black lighten-1 white--text text-xs-center"
        >
            <v-card-text>
                <v-btn
                        v-for="icon in icons"
                        :key="icon"
                        class="mx-3 white--text"
                        icon
                >
                    <v-icon size="24px">{{ icon }}</v-icon>
                </v-btn>
            </v-card-text>

            <v-card-text class="white--text pt-0">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet beatae consequatur est explicabo, fuga, ipsa magnam mollitia nesciunt officia, praesentium quia rem sapiente unde. Aliquam corporis eius in numquam rem.
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid aperiam assumenda atque, autem ducimus, eaque fuga fugiat in modi mollitia odit quam, quisquam quod veritatis voluptate! Cupiditate esse tempore totam.
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus beatae cum eligendi enim eveniet, facilis in itaque iusto magnam, necessitatibus numquam quaerat quos repudiandae vel veritatis. Dolorem ipsa ipsam repellendus.
            </v-card-text>

            <v-divider></v-divider>

            <v-card-text class="white--text">
                &copy;2019 — <strong>web-netz</strong>
            </v-card-text>
        </v-card>
    </v-layout>
</template>

<script>
    export default {
        name:'cfooter',
        data (){
            return{
                icons: [
                    'fab fa-facebook',
                    'fab fa-twitter',
                    'fab fa-google-plus',
                    'fab fa-linkedin',
                    'fab fa-instagram'
                ]
            }
        }
    }
</script>