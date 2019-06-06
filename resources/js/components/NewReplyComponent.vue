<template>
    <div>
        <div v-if="signIn">
            <div class="form-group">
                            <textarea class="form-control" id="body" rows="3" name="body"
                                      placeholder="What's on your mind!" required v-model="body"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" @click="addReply">Post</button>
        </div>
        <div v-else>
            <p class="text-center">Please <a href="/login">sign in</a> to comment!</p>
        </div>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        props: ['endpoint'],
        data() {
            return {
                body: '',
            };
        },
        computed: {
            signIn() {
                return window.App.signIn;
            }
        },
        mounted() {
            $('#body').atwho({
                at: '@',
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON('/api/users', { name: query }, function (usernames) {
                            callback(usernames);
                        });
                    }
                }
            });
        },
        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body })
                    .then(response => {
                        this.body = '';

                        flash('Your reply has been posted.');

                        this.$emit('created', response.data);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            }
        }
    };
</script>
