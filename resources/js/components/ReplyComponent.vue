<template>
    <div :id="'reply-'+id" class="card mb-3" :class="isBest ? 'border-success' : ''">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <a href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a>
                    said <span v-text="ago"></span>
                </div>
                <div v-if="signIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control mb-2" placeholder="" v-model="body" required></textarea>
                        <button class="btn btn-primary btn-sm">Update</button>
                        <button class="btn btn-link btn-sm" @click="editing = false">Cancel</button>
                    </div>
                </form>
            </div>
            <div v-else v-html="body"></div>
        </div>
        <div class="card-footer text-muted level" v-if="authorize('updateReply', reply) || authorize('updateThread', reply.thread)">
            <div v-if="authorize('updateReply', reply)">
                <button type="submit" class="btn btn-primary btn-sm mr-2" @click="editing = true">Edit</button>
                <button type="submit" class="btn btn-danger btn-sm" @click="destroy">Delete</button>
            </div>
            <button type="submit" class="btn btn-info btn-sm ml-auto" @click="markBestReply" v-if="authorize('updateThread', reply.thread)">Best
                Reply?
            </button>
        </div>
    </div>
</template>
<script>
    import Favorite from './FavoriteComponent.vue';
    import moment from 'moment';

    export default {
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                isBest: this.data.isBest,
                reply: this.data
            };
        },

        computed: {
            ago() {
                return moment(this.data.created_at).fromNow() + '...';
            }
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.data.id);
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                })
                    .then(response => {
                        this.editing = false;
                        flash('Updated');
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            },
            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            },
            markBestReply() {
                axios.post('/replies/' + this.data.id + '/best')
                    .then(response => {
                        this.isBest = true;
                        flash('Best reply selected');

                        window.events.$emit('best-reply-selected', this.data.id);
                    });
            }
        }
    };
</script>
