<template>
    <div class="text-xs-center">
        <v-menu
                v-model="menu"
                :close-on-content-click="false"
                :nudge-width="200"
                offset-x
        >
            <v-btn
                    slot="activator"
                    color="red"
                    dark
            >
                {{ this.username }}
            </v-btn>

            <v-card>
                <v-list>
                    <v-list-tile avatar>
                        <v-list-tile-avatar>
                            <img :src="avatar" alt="John">
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ this.username }}</v-list-tile-title>
                            <v-list-tile-sub-title>Last update from 1 Juni 2018</v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-list-tile-action>
                            <v-btn
                                    :class="fav ? 'red--text' : ''"
                                    icon
                                    @click="fav = !fav"
                            >
                                <v-icon>favorite</v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>

                <v-list>
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-switch v-model="message" color="purple"></v-switch>
                        </v-list-tile-action>
                        <v-list-tile-title>Enable messages</v-list-tile-title>
                    </v-list-tile>

                    <v-list-tile>
                        <v-list-tile-action>
                            <v-switch v-model="hints" color="purple"></v-switch>
                        </v-list-tile-action>
                        <v-list-tile-title>Enable hints</v-list-tile-title>
                    </v-list-tile>
                </v-list>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn flat @click="menu = false">Cancel</v-btn>
                    <v-btn color="primary" flat @click="menu = false">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
    export default {
        props:["avatar", "username"],
        data: () => ({
            fav: true,
            menu: false,
            message: false,
            hints: true
        })
    }
</script>