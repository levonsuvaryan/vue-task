<template>
    <div>
        <profile-navigation></profile-navigation>

        <div class="row">
            <div class="col-md-3">
                <h3 class="title mb-3">Post #{{ id }} images</h3>

                <input @change="imageChanged" type="file" id="files" class="form-control-file mb-4" multiple>

                <ul class="list-group mb-3">
                    <li v-for="file in selectedFiles" class="list-group-item">{{ file.name }}</li>
                </ul>

                <div v-if="hasSelectedFiles" class="text-right">
                    <button @click.prevent="uploadSelectedFiles" class="btn btn-success" :disabled="busy">
                        <i v-if="busy" class="fa fa-spinner fa-spin"></i> Upload
                    </button>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <div v-for="(image, index) in images" class="col-md-4 border-left pb-2 pt-2 mb-5">

                        <h4 v-if="image.is_main" class="font-weight-bold text-primary">Main Image</h4>

                        <div v-else="! image.is_main" class="d-flex justify-content-between">
                            <button @click.prevent="setMain(image.id, index)" :disabled="busyImageId === image.id" class="btn btn-sm btn-primary mb-2">
                                <i class="fa fa-magic"></i> Set Main
                            </button>

                            <button @click.prevent="destroy(image.id, index)" :disabled="busyImageId === image.id" class="btn btn-sm btn-danger mb-2">
                                <i class="fa fa-trash-o"></i> Delete
                            </button>
                        </div>

                        <img :src="image.path" class="img-fluid img-thumbnail" :class="{'bg-primary': image.is_main}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                id: this.$route.params['id'],
                busy: false,
                busyImageId: false,
                images: [],
                selectedFiles: [],
            }
        },

        computed: {
            hasSelectedFiles () {
                return this.selectedFiles.length > 0;
            }
        },

        methods: {
            fetch () {
                axios.get(`/api/profile/posts/${this.id}/images`).then(response => {
                    this.images = response.data.data;

                }).catch(error => {
                    this.$router.push({name: 'profile'});
                    this.$toaster.error('Access Denied.');
                });
            },

            // Multiple upload

            imageChanged (e) {
                this.selectedFiles = Array.from(e.target.files);
            },

            async uploadSelectedFiles () {
                this.busy = true;
                let filesOrder = this.selectedFiles.slice();

                for (let file of filesOrder) {
                    await this.uploadFile(file);
                }

                $('#files').val('');
                this.$toaster.info('Upload process completed.');
                this.busy = false;
            },

            async uploadFile (file) {
                let formData = new FormData();
                formData.append('image', file);

                await axios.post(`/api/profile/posts/${this.id}/images`, formData).then(response => {
                    this.selectedFiles.shift();
                    let uploadedImage = response.data.data;
                    this.images.push(uploadedImage);

                }).catch(error => {
                    let invalidFile = this.selectedFiles.shift();
                    this.$toaster.error(invalidFile.name + " is invalid file.");
                });
            },

            // Actions

            destroy (imageId, imageIndex) {
                this.busyImageId = imageId;

                axios.delete(`/api/profile/images/${imageId}`).then(response => {
                    this.$toaster.success(response.data.message);
                    this.images.splice(imageIndex, 1);
                    this.busyImageId = false;

                }).catch(error => {
                    this.$toaster.error('Access Denied.');
                    this.busyImageId = false;
                });
            },

            setMain (imageId, imageIndex) {
                this.busyImageId = imageId;

                axios.post(`/api/profile/images/${imageId}/main`).then(response => {
                    this.$toaster.success(response.data.message);
                    let oldIndexOfMainImage = this.getIndexOfMainImage();
                    this.images[oldIndexOfMainImage].is_main = false;
                    this.images[imageIndex].is_main = true;
                    this.busyImageId = false;

                }).catch(error => {
                    this.$toaster.error('Access Denied.');
                    this.busyImageId = false;
                });
            },

            getIndexOfMainImage () {
                for (let i = 0; i < this.images.length; i++) {
                    if (this.images[i].is_main) {
                        return i;
                    }
                }
            },
        },

        created () {
            this.fetch();
        },

        watch: {
            $route (to) {
                this.id = to.params['id'];
            }
        }
    }
</script>