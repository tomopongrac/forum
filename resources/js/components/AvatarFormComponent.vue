<template>
    <div>
        <div class="level mb-2">
            <img :src="avatar" alt="" width="50" height="50" class="mr-2">
            <h1>
                <span v-text="user.name"></span>
                <small>Since <span v-text="ago"></span></small>
            </h1>
        </div>
        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload @loaded="onLoad"></image-upload>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUploadComponent.vue';
    import moment from 'moment';

    export default {
        props: ['user'],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            },
            ago() {
                return moment(this.user.created_at).fromNow() + '...';
            }
        },

        methods: {
            onLoad(data) {
                this.avatar = data.src;
                this.persist(data.file);
            },

            persist(file) {
                let data = new FormData();

                data.append('avatar', file);

                axios.post(`/api/users/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'));
            }
        }
    };
</script>
