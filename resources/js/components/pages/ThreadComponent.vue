<script>
    import Replies from '../RepliesComponent.vue'

    export default {
        props: ['initialRepliesCount'],
        components: { Replies },
        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        }

    }
</script>
