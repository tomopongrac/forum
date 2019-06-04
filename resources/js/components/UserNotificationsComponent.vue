<template>
    <div>
        <li class="nav-item dropdown" v-if="notifications.length">
            <a id="navbarDropdown" class="nav-link" href="#" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="fas fa-bell"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <template v-for="notification in notifications">
                    <a class="dropdown-item" :href="notification.data.link" v-text="notification.data.message"
                       @click="markAsRead(notification)"></a>
                </template>
            </div>
        </li>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: false
            };
        },

        created() {
            axios.get('/profiles/' + window.App.user.name + '/notifications')
                .then(response => this.notifications = response.data);
        },

        methods: {
            markAsRead(notification) {
                axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
            }
        }
    };
</script>
