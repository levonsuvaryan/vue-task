<template>
    <div>
        <profile-navigation></profile-navigation>

        <h2 class="title text-center">Add Post</h2>

        <form method="POST" @submit.prevent="create">
            <div class="form-group">
                <label for="title">Title *</label>
                <input v-model.lazy="title" type="text" id="title" class="form-control" :class="{'is-invalid': errors.has('title')}" required>

                <span v-if="errors.has('title')" class="invalid-feedback">
                    <strong>{{ errors.first('title') }}</strong>
                </span>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea v-model.lazy="description" id="description" rows="9" class="form-control"
                          :class="{'is-invalid': errors.has('description')}" required>
                </textarea>

                <span v-if="errors.has('description')" class="invalid-feedback">
                    <strong>{{ errors.first('description') }}</strong>
                </span>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="image">Main Image *</label>
                    <input @change="imageChanged" type="file" id="image" class="form-control-file"
                           :class="{'is-invalid': errors.has('image')}" required>

                    <span v-if="errors.has('image')" class="invalid-feedback">
                        <strong>{{ errors.first('image') }}</strong>
                    </span>
                </div>

                <div class="col-md-6 text-right pt-3">
                    <button type="submit" class="btn btn-primary" :disabled="busy">
                        <i v-if="busy" class="fa fa-spinner fa-spin"></i> Store
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import Errors from '../../../modules/Errors';

    export default {
        data () {
            return {
                errors: new Errors(),
                busy: false,
                title: '',
                description: '',
                image: null
            }
        },

        methods: {
            imageChanged (e) {
                let files = Array.from(e.target.files);
                this.image = files[0];
            },

            create () {
                this.busy = true;
                let formData = new FormData();

                formData.append('title', this.title);
                formData.append('description', this.description);
                formData.append('image', this.image);

                axios.post('/api/posts', formData).then(response => {
                    this.errors.forget();
                    this.$router.push({
                        name: 'profile.posts.show',
                        params: {id: response.data.id}
                    });
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    this.errors.put(error.response.data);
                    this.busy = false;
                })
            },
        }
    }
</script>