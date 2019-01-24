<template>
    <div v-if="admin">
        <div id="main" class="col-sm-12">
            <h1>Post creation</h1>

            <form name="post" method="post">
                <div class="form-group">
                    <label class="control-label required" for="post_title">label.title s</label>
                    <div class="sxxdxxxx">
                        <input type="text" id="post_title" v-model="post.title" name="post[title]" required="required" autofocus="autofocus" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label required" for="post_summary">Summary</label>
                    <textarea id="post_summary" v-model="post.summary" name="post[summary]" required="required" aria-describedby="post_summary_help" class="form-control"></textarea>
                    <span id="post_summary_help" class="help-block">Summaries can't contain Markdown or HTML contents; only plain text.</span>
                </div>
                <div class="form-group">
                    <label class="control-label required" for="post_content">Content</label>
                    <textarea id="post_content" v-model="post.content" required="required" pattern=".{10,}" rows="20" aria-describedby="post_content_help" class="form-control"></textarea>
                    <span id="post_content_help" class="help-block">Use Markdown to format the blog post contents. HTML is allowed too.</span>
                </div>

                <input type="file" @change="onFileChanged">
                <button @click="onUpload">Upload!</button>

                <div class="col-4">
                    <button @click="createPost()" :disabled="post.title.length === 0 && post.summary.length === 0 || isLoading" type="button" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'createNewPast',
        data () {
            return {
                post: {
                    title: "xaaa",
                    summary: "xaaa",
                    content: "xaaa",
                    image: "",
                },
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
                this.$store.dispatch('post/createPost', this.$data.post)
                    .then(() => console.log(this.$data.post)
                /*this.$router.push({path: '/posts'})*/ )
            },
            onFileChanged (event) {
                console.log(event);
                this.$data.post.image = event.target.files[0];
                this.image = event.target.files[0];
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
                const formData = new FormData();
                formData.append('myFile', this.image);
                axios.post('http://127.0.0.1:8000/api/post/imageupload', formData);
            }
        },
    }
</script>