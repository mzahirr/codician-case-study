<template>
    <v-toolbar fixed app dark clipped-left class="main-toolbar">
        <v-toolbar-side-icon @click.stop="toggleDrawer"></v-toolbar-side-icon>
        <v-toolbar-title>
            <router-link :to="{ name: 'welcome' }" class="white--text">
                test
            </router-link>
        </v-toolbar-title>
        <v-spacer></v-spacer>

        <!-- Authenticated -->
        <template>
            <v-menu origin="center center" offset-y :nudge-bottom="10" transition="scale-transition">
                <v-btn flat slot="activator"><v-icon left>person</v-icon>Zahir</v-btn>
                <v-list class="pa-0">
                    <v-list v-for="(item,index) in account_items" @click="accountMenuItemClicked(item.action)" ripple="ripple" :disabled="item.disabled" :target="item.target" rel="noopener" :key="index">
                        <v-list-item-action v-if="item.icon">
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list>
                </v-list>
            </v-menu>
        </template>

        <!-- Guest -->
    </v-toolbar>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        props: {
            drawer: {
                type: Boolean,
                required: true
            }
        },

        data: () => ({
            appName: "test",
            busy: false,
            account_items: [
                {
                    icon: 'account_circle',
                    href: '#',
                    title: 'account',
                    action: 'profile'
                },
                {
                    icon: 'fullscreen_exit',
                    href: '#',
                    title: 'logout',
                    action: 'logout'
                }
            ],
        }),

        computed: mapGetters({
        }),

        methods: {
            toggleDrawer () {
                this.$emit('toggleDrawer')
            },
            async logout () {
                this.busy = true

                if (this.drawer) {
                    this.toggleDrawer()
                }

                // Log out the user.
                await this.$store.dispatch('logout')
                this.busy = false

                // Redirect to login.
                this.$router.push({ name: 'login' })
            },
            accountMenuItemClicked(action) {
                switch (action) {
                    case 'profile':
                        this.$router.push({ name: 'settings.profile' })
                        break;
                    case 'logout':
                        this.logout();
                        break;
                }
            },
        }
    }
</script>

<style lang="stylus" scoped>


</style>
