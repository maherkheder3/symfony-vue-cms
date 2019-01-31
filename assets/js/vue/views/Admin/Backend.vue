<template>
    <div v-if="admin">
        <categories></categories>
        <v-container>
            <v-layout>
                <v-flex xs12 sm6 offset-sm3 class="elevation-5">
                    <v-card>
                        <v-card-actions>
                            <v-select :items="items" v-model="size" label="Size"></v-select>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                        <v-container v-bind="{ [`grid-list-${size}`]: true }" fluid>
                            <v-layout row wrap>
                                <v-flex v-for="n in 9"
                                        :key="n"
                                        xs4 >
                                    <v-card flat tile>
                                        <v-img
                                                :src="`https://unsplash.it/150/300?image=${Math.floor(Math.random() * 80) + 1}`"
                                                height="150px"
                                        ></v-img>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import Categories from "../../components/admin/Categories";

    export default {
        components: {Categories},
        data: () => ({
            size: 'sm',
            items: [
                { text: 'Extra small (2px)', value: 'xs' },
                { text: 'Small (4px)', value: 'sm' },
                { text: 'Medium (8px)', value: 'md' },
                { text: 'Large (16px)', value: 'lg' },
                { text: 'Extra large (24px)', value: 'xl' }
            ]
        }),
        computed: {
            admin () {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        }
    }
</script>