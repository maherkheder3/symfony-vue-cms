<template>
    <div>
        <loading v-if="isLoading"/>

        <div v-else-if="hasError">
            <v-alert :value="true"
                     type="error">
                {{ error }}
            </v-alert>
        </div>

        <div v-else-if="post === undefined">
            <v-alert :value="true" type="error">
                wait
            </v-alert>
        </div>

        <v-flex v-else>
            <v-card>
                <v-container px-4 py-4>
                    <h1>Edit</h1>
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

                        <tags :tags="post.tags"></tags>

                        <v-btn @click="xxx">xxx</v-btn>

                        <v-container px-0>
                            <v-img width="400px" :src="getImage(post.image)"></v-img>
                            <input type="file" @change="onFileChanged">
                            <v-btn @click="onUpload"
                                    :loading="uploadButtonLoading"
                                    :disabled="uploadButtonDisable"
                                    color="blue-grey"
                                    class="white--text">
                                Upload
                                <v-icon right dark>cloud_upload</v-icon>
                            </v-btn>
                        </v-container>

                        <v-btn @click="updatePost()" :disabled="post.title.length === 0 && post.summary.length === 0 || isLoading || uploadButtonLoading">Update</v-btn>
                    </form>
                </v-container>
            </v-card>
        </v-flex>
    </div>
</template>


<script>
    import axios from 'axios';
    import defaultImage from './../../../img/default-image.png';
    import Tags from "../../components/admin/Tags";
    import Loading from "../../components/Loading";

    export default {
        name: 'editpost',
        components: {Tags, Loading},
        $_veeValidate: {
            validator: 'new'
        },
        data () {
            return {
                // post: {
                //     title: "",
                //     summary: "",
                //     content: "",
                //     image: "", // to save image name in request
                // },
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
            post() {
                return this.$store.getters['post/details'];
            },
            admin() {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            }
        },
        created() {
            this.$store.dispatch('post/details', this.$route.params.postId);
        },
        methods: {
            updatePost () {
                var self = this;
                this.post.author = undefined;
                this.$store.dispatch('post/editPost', this.post)
                    .then((data) => {
                        self.$router.push( { path: '/posts' } )
                    })
            },
            onFileChanged (event) {
                this.imageFile = event.target.files[0];
                this.uploadButtonDisable = false;
            },
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
            getImage(image) {
                if (image && image.length > 2) {
                    return "/uploads/posts/" + image;
                }
                else{
                    return defaultImage;
                }
            },
            xxx(){
                console.log(this.post.tags);
            }
        },
    }
</script>