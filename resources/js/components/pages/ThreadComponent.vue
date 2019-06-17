<script>
    import Replies from '../RepliesComponent.vue';
    import SubscribeButton from '../SubscribeButtonComponent';

    export default {
        props: ['initialRepliesCount', 'dataLocked'],
        components: { Replies, SubscribeButton },
        data() {
            return {
                repliesCount: this.initialRepliesCount,
                locked: this.dataLocked
            };
        }

    };
</script>
