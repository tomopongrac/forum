<template>
    <div :id="'reply-'+id" class="card mb-3">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <a href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a>
                    said {{ data.created_at }}
                </div>
                <div v-if="signIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control mb-2" placeholder="" v-model="body"></textarea>
                    <button class="btn btn-primary btn-sm" @click="update">Update</button>
                    <button class="btn btn-link btn-sm" @click="editing = false">Cancel</button>
                </div>
            </div>
            <div v-else v-text="body"></div>
        </div>
        <div class="card-footer text-muted level" v-if="canUpdate">
            <button type="submit" class="btn btn-primary btn-sm mr-2" @click="editing = true">Edit</button>
            <button type="submit" class="btn btn-danger btn-sm" @click="destroy">Delete</button>
        </div>
    </div>
</template>
<script>
    import Favorite from './FavoriteComponent.vue';

    export default {
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            };
        },

        computed: {
            signIn() {
                return window.App.signIn;
            },
            canUpdate() {
                if (window.App.user === null) {
                    return false;
                }

                return this.data.user_id == window.App.user.id;
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                });

                this.editing = false;
                flash('Updated');
            },
            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            }
        }
    }
</script>