<template>
    <button type="submit" :class="classes" @click="toggle">
        <span class="fas fa-heart"></span>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },

        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-outline-primary' : 'btn-outline-secondary'];
            },
            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                this.isFavorited ? this.unfavorite() : this.favorite();
            },
            unfavorite() {
                axios.delete(this.endpoint);

                this.isFavorited = false;
                this.favoritesCount--;
            },
            favorite() {
                axios.post(this.endpoint);

                this.isFavorited = true;
                this.favoritesCount++;
            }
        }
    }
</script>
