<template>
    <v-combobox
            v-model="model"
            :filter="filter"
            :hide-no-data="!search"
            :items="items"
            :search-input.sync="search"
            hide-selected
            label="Search for an option"
            multiple
            small-chips
            solo >
        <template slot="no-data">
            <v-list-tile>
                <span class="subheading">Create</span>
                <v-chip
                        :color="`${colors[nonce - 1]} lighten-3`"
                        label
                        small
                >
                    {{ search }}
                </v-chip>
            </v-list-tile>
        </template>
        <template
                v-if="item === Object(item)"
                slot="selection"
                slot-scope="{ item, parent, selected }"
        >
            <v-chip
                    :color="`${item.color} lighten-3`"
                    :selected="selected"
                    label
                    small
            >
        <span class="pr-2">
          {{ item.text }}
        </span>
                <v-icon
                        small
                        @click="parent.selectItem(item)"
                >close</v-icon>
            </v-chip>
        </template>
        <template
                slot="item"
                slot-scope="{ index, item, parent }"
        >
            <v-list-tile-content>
                <v-text-field
                        v-if="editing === item"
                        v-model="editing.text"
                        autofocus
                        flat
                        background-color="transparent"
                        hide-details
                        solo
                        @keyup.enter="edit(index, item)"
                ></v-text-field>
                <v-chip
                        v-else
                        :color="`${item.color} lighten-3`"
                        dark
                        label
                        small
                >
                    {{ item.text }}
                </v-chip>
            </v-list-tile-content>
            <v-spacer></v-spacer>
            <v-list-tile-action @click.stop>
                <v-btn
                        icon
                        @click.stop.prevent="edit(index, item)"
                >
                    <v-icon>{{ editing !== item ? 'edit' : 'check' }}</v-icon>
                </v-btn>
            </v-list-tile-action>
        </template>
    </v-combobox>
</template>


<script>
    export default {
        props:["tags"],
        data() {
            return {
                activator: null,
                attach: null,
                colors: ['green', 'purple', 'indigo', 'cyan', 'teal', 'orange'],
                editing: null,
                index: -1,
                items: [],
                nonce: 1,
                menu: false,
                model: [],
                x: 0,
                search: null,
                y: 0
            }
        },
        created(){
            this.tags.forEach(function (tag) {
                tag.text = tag.name;
            });

            this.model = this.tags;
            // this.items = this.tags.slice(); // return data
            // this.model = this.tags.slice(); //
        },
        watch: {
            model (val, prev) {

                console.log("model " + this.model.length);

                if (val.length === prev.length) return

                this.model = val.map(v => {
                    if (typeof v === 'string') {
                        v = {
                            text: v,
                            name: v,
                            color: this.colors[this.nonce - 1]
                        }

                        this.items.push(v)

                        this.nonce++

                    }

                    return v
                })

                // props.tags = this.model;
                console.log(this.$store.state.post.tags)
                this.$store.state.post.tags = this.model;
            },
        },
        computed: {
            isLoading() {
                return this.$store.getters['post/isLoading'];
            },
            hasError() {
                return this.$store.getters['post/hasError'];
            },
            error() {
                return this.$store.getters['post/error'];
            },
            hasPosts() {
                return this.$store.getters['post/hasPosts'];
            },
            post() {
                return this.$store.getters['post/details'];
            },
            admin() {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        },
        methods: {
            edit (index, item) {
                if (!this.editing) {
                    this.editing = item
                    this.index = index
                } else {
                    this.editing = null
                    this.index = -1
                }
            },
            filter (item, queryText, itemText) {
                if (item.header) return false

                const hasValue = val => val != null ? val : ''

                const text = hasValue(itemText)
                const query = hasValue(queryText)

                return text.toString()
                    .toLowerCase()
                    .indexOf(query.toString().toLowerCase()) > -1
            }
        }
    }
</script>