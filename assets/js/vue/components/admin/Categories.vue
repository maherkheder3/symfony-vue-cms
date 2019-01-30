<template>
    <div>
        <v-toolbar color="primary">
            <v-toolbar-title>Categories</v-toolbar-title>
            <v-divider
                    class="mx-2"
                    inset
                    vertical
            ></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="500px">
                <v-btn slot="activator" dark class="mb-2">New</v-btn>
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field v-model="editedItem.icon" label="Icon"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select
                                            v-model="editedItem.parent"
                                            :items="desserts"
                                            dark
                                            item-text="name"
                                            item-value="id"
                                            label="Parent"
                                    ></v-select>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color=" darken-1" flat @click="close">Cancel</v-btn>
                        <v-btn color=" darken-1" flat @click="save">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-data-table
                v-if="!loading"
                :headers="headers"
                :items="desserts"
                class="elevation-5">
            <template slot="items" slot-scope="props">
                <td>{{ props.item.name }}</td>
                <td class="text-center">{{ props.item.parent !== undefined ? props.item.parent.name : "No Parent" }}</td>
                <!--<td class="text-center">{{ props.item.icon }}</td>-->
                <td class="text-center"><v-icon>{{ props.item.icon }}</v-icon></td>
                <td class="pl-4 layout px-0">
                    <v-icon
                            small
                            class="mr-2"
                            @click="editItem(props.item)"
                    >
                        edit
                    </v-icon>
                    <v-icon
                            small
                            @click="deleteItem(props.item)"
                    >
                        delete
                    </v-icon>
                </td>
            </template>
            <template slot="no-data">
                <v-btn color="primary" @click="initialize">Reset</v-btn>
            </template>
        </v-data-table>
    </div>
</template>


<script>
    import axios from 'axios'

    export default {
        data: () => ({
            dialog: false,
            headers: [
                {
                    text: 'Dessert (100g serving)',
                    align: 'left',
                    // sortable: false,
                    value: 'name'
                },
                { text: 'Cat Parent', value: 'parent' },
                { text: 'icon', value: 'icon' },
                { text: 'Actions', sortable: false }
            ],
            loading: true,
            desserts: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                parent: '',
                icon: '',
            },
            defaultItem: {
                name: '',
                parent: '',
                icon: '',
            }
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Category' : 'Edit Category'
            }
        },

        watch: {
            dialog (val) {
                val || this.close()
            }
        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {
                let self = this;
                axios.get('/api/admin/categores').then((data) => {
                        data.data.forEach(function (category) {
                            self.desserts.push(category);
                        });
                    self.loading = false;
                });
            },

            editItem (item) {
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                let self = this;
                const index = this.desserts.indexOf(item)
                if(confirm('Are you sure you want to delete this Category?') && this.desserts.splice(index, 1)){

                    axios.delete('/api/admin/categores',
                        { data: { "category" : item } }
                    ).then((data) => {
                        if(data.data === "ok")
                        {
                            self.$awn.success(self.editedItem + " is deleted");

                        }else{
                            self.$awn.alert("error - didn't deleted");
                        }
                    });
                }
            },

            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },

            save () {

                let self = this;

                if (this.editedIndex > -1) { // edit the category Category

                    axios.put('/api/admin/categores',
                        { "category" : this.editedItem }
                    ).then((data) => {
                        if(data.data !== "error")
                        {
                            self.editedItem.parent = {
                                "name": data.data
                            }
                            Object.assign(self.desserts[self.editedIndex], self.editedItem)
                            self.$awn.success(self.editedItem + " is updated");

                        }else{
                            self.$awn.alert("error didn't updated");
                        }
                    });
                }
                else
                {   // create new Category

                    axios.post('/api/admin/categores',
                        { "category" : this.editedItem }
                    ).then((data) => {
                        if(data.data === "ok")
                        {
                            self.desserts.push(self.editedItem)
                            self.$awn.success(self.editedItem + " is created");

                        }else{
                            self.$awn.alert("error didn't created");
                        }
                    });
                }
                this.close()
            }
        }
    }
</script>