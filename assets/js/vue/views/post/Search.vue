<template>
    <v-container>
        <v-toolbar dark>
            <v-toolbar-title>Search</v-toolbar-title>
            <v-autocomplete
                    :loading="loading"
                    :items="items"
                    :search-input.sync="search"
                    v-model="select"
                    item-text="title"
                    item-avatar="getAvatar"
                    item-value="id"
                    cache-items
                    class="mx-3"
                    flat
                    hide-no-data
                    hide-details
                    label="What post are you from?"
                    solo-inverted >
                <v-list-tile slot="prepend-item"
                        class="grey--text caption">
                    {{ items.length }} candidates found
                </v-list-tile>
                <!--<template slot="selection" slot-scope="{ item, selected }">-->
                    <!--{{name}}-->
                <!--</template>-->
                <template slot="item" slot-scope="{item}">
                        <v-avatar>
                            <v-img :src="`https://unsplash.it/150/300?image=${Math.floor(Math.random() * 80) + 1}`"></v-img>
                        </v-avatar>
                        <span v-text="item.title" class='ml-3'></span>
                </template>
            </v-autocomplete>
            <!--<v-btn icon>-->
                <!--<v-icon>more_vert</v-icon>-->
            <!--</v-btn>-->
        </v-toolbar>
    </v-container>
</template>

<script>
    import axion from 'axios'
    export default {
        data () {
            return {
                loading: false,
                items: [],
                search: null,
                select: null,
                states: []
            }
        },
        watch: {
            search (val) {
                if(val && val !== this.select && this.select){
                    this.$router.push({ name:'PostDetails', params:{ postId: this.select }})
                }
                if(val && val !== this.select && this.querySelections(val)){
                    console.log("ok");
                }
            }
        },
        methods: {
            querySelections (v) {
                this.loading = true
                // Simulated ajax query
                let self = this;
                if(v.length > 0){
                    axion.get('/api/post/search/' + v).then((data) => {
                        self.states = data.data;
                    });
                }

                setTimeout(() => {
                    this.items = this.states.filter(e => {
                        return (e.title || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
                    })
                    this.loading = false
                }, 500)
            }
        }
    }
</script>