<script>
    import Replies from '../RepliesComponent.vue';
    import SubscribeButton from '../SubscribeButtonComponent';

    export default {
        props: ['thread'],
        components: { Replies, SubscribeButton },
        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                editing: false,
                form: {
                    title: this.thread.title,
                    body: this.thread.body
                }
            };
        },

        methods: {
            toggleLock() {
                axios[this.locked ? 'delete' : 'post']('/locked-threads/' + this.thread.slug);

                this.locked = !this.locked;
            },
            cancel() {
                this.form = {
                    title: this.thread.title,
                    body: this.thread.body
                };
                this.editing = false;
            },
            update() {
                axios.patch('/threads/' + this.thread.channel.slug + '/' + this.thread.slug, {
                    title: this.form.title,
                    body: this.form.body
                }).then(() => {
                    this.editing = false;
                    flash('Your thread has been updated.');
                });
            }
        }

    };
</script>
