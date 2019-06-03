<script>
    import Replies from '../RepliesComponent.vue';
    import SubscribeButton from '../SubscribeButtonComponent';

    export default {
        props: ['initialRepliesCount'],
        components: { Replies, SubscribeButton },
        data() {
            return {
                repliesCount: this.initialRepliesCount
            };
        }

    };
</script>
