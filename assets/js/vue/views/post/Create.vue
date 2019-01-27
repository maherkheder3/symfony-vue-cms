<template>
    <div v-if="admin">
        <v-layout>
            <v-flex>
                <v-card>
                    <v-container px-4 py-4>
                        <h1>Create a new Post</h1>
                        <form>
                            <v-text-field
                                    v-validate="'required|max:18'"
                                    v-model="post.title"
                                    :counter="18"
                                    :error-messages="errors.collect('name')"
                                    label="Title"
                                    data-vv-name="title"
                                    required
                            ></v-text-field>
                            <v-text-field
                                    v-validate="'required|max:200'"
                                    v-model="post.summary"
                                    :counter="200"
                                    :error-messages="errors.collect('summary')"
                                    label="Summary"
                                    data-vv-name="summary"
                                    required
                            ></v-text-field>
                            <v-textarea
                                    v-validate="'required|max:500'"
                                    v-model="post.content"
                                    :counter="500"
                                    :error-messages="errors.collect('content')"
                                    label="Content"
                                    data-vv-name="content"
                                    required
                            ></v-textarea>


                            <v-container px-0>
                                <input type="file" @change="onFileChanged">
                                <v-btn
                                        @click="onUpload"
                                        :loading="uploadButtonLoading"
                                        :disabled="uploadButtonDisable"
                                        color="blue-grey"
                                        class="white--text">
                                    Upload
                                    <v-icon right dark>cloud_upload</v-icon>
                                </v-btn>
                            </v-container>

                            <!--<v-select-->
                            <!--v-validate="'required'"-->
                            <!--:items="items"-->
                            <!--v-model="select"-->
                            <!--:error-messages="errors.collect('select')"-->
                            <!--label="Select"-->
                            <!--data-vv-name="select"-->
                            <!--required-->
                            <!--&gt;</v-select>-->
                            <!--<v-checkbox-->
                            <!--v-validate="'required'"-->
                            <!--v-model="checkbox"-->
                            <!--:error-messages="errors.collect('checkbox')"-->
                            <!--value="1"-->
                            <!--label="Option"-->
                            <!--data-vv-name="checkbox"-->
                            <!--type="checkbox"-->
                            <!--required-->
                            <!--&gt;</v-checkbox>-->

                            <!--<v-btn @click="xxx()"></v-btn>-->
                            <v-btn @click="createPost()" :disabled="post.title.length === 0 && post.summary.length === 0 || isLoading || uploadButtonLoading">Create</v-btn>
                            <v-btn @click="clear">clear</v-btn>
                        </form>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'createNewPast',
        $_veeValidate: {
            validator: 'new'
        },
        data () {
            return {
                post: {
                    title: "",
                    summary: "",
                    content: "",
                    image: "", // to save image name in request
                },
                imageFile: "", // to save image to upload
                checkbox: null,
                dictionary: {
                    attributes: {
                        email: 'E-mail Address'
                        // custom attributes
                    },
                    custom: {
                        name: {
                            required: () => 'Name can not be empty',
                            max: 'The name field may not be greater than 18 characters'
                            // custom messages
                        },
                        select: {
                            required: 'Select field is required'
                        }
                    }
                },
                uploadButtonLoading: false,
                uploadButtonDisable: true
            };
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
            posts() {
                return this.$store.getters['post/posts'];
            },
            admin() {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        },
        methods: {
            createPost () {
                this.$store.dispatch('post/createPost', this.post)
                    .then(() => console.log(this.post)
                /*this.$router.push({path: '/posts'})*/ )
            },
            onFileChanged (event) {
                this.imageFile = event.target.files[0];
                this.uploadButtonDisable = false
            },
            // onUpload() {
            //     const formData = new FormData();
            //     formData.append('image', this.selectedFile, this.selectedFile.name);
            //     axios.post('/api/post/imageupload', formData, {
            //         onUploadProgress: progressEvent => {
            //             console.log("upload Progress: " + Math.round(progressEvent.loaded / progressEvent.total * 100) + '%')
            //         }
            //     }).then(res => {
            //         console.log(res)
            //     })
            // },
            onUpload() {
                this.uploadButtonLoading = true
                this.uploadButtonDisable = true
                const formData = new FormData();
                formData.append('image', this.imageFile);
                axios.post('/api/post/imageupload', formData).then((data) => {
                    this.post.image = data.data;
                    this.uploadButtonLoading = false;
                });
            },
            clear () {
                this.post = {
                    title: "",
                    summary: "",
                    content: "",
                    image: "",
                }
                this.select = null
                this.checkbox = null
                this.$validator.reset()
            },
            xxx(){
                console.log(this.post)
            }
        },
    }
</script>