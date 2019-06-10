<template>
    <div>
        <h1>
            <span v-text="user.name"></span>
            <small>Since <span v-text="ago"></span></small>
        </h1>
        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <div class="custom-file mb-2">
                <input type="file" class="custom-file-input" id="customFile" name="avatar" accept="image/*" @change="onChange">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </form>
        <img :src="avatar" alt="" width="50" height="50">
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['user'],

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
            onChange(e) {
                if (! e.target.files.length) return;

                let file = e.target.files[0];

                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = e => {
                    this.avatar = e.target.result;
                };

                this.persist(file);
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
