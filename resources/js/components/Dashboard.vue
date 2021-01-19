<template>
    <v-app light>
        <notifications group="foo"/>

        <v-navigation-drawer
            persistent
            v-model="drawer"
            enable-resize-watcher
            app
        >
            <nav-menu></nav-menu>

        </v-navigation-drawer>
<!--        <tool-bar v-on:toggleDrawer="drawer = !drawer" :drawer="drawer"></tool-bar>-->
        <v-main>
            <v-container fluid>
                <transition name="page" mode="out-in">
                    <router-view></router-view>
                </transition>
            </v-container>
        </v-main>
    </v-app>
</template>


<script>
    import { mapGetters } from 'vuex'
    import NavMenu from './NavMenu'
    import ToolBar from './ToolBar'
    import PageFooter from './PageFooter'
    export default {
        components: {
            'nav-menu': NavMenu,
            'tool-bar': ToolBar,
            'page-footer': PageFooter
        },
        computed: mapGetters({

        }),
        data () {
            return {
                drawer: true
            }
        }
    }
</script>
