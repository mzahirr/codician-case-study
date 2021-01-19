<template>
    <v-row justify="center">
        <v-col
            cols="12"
            sm="12"
        >
            <v-card>
                <v-card-title class="cyan darken-1">

                        <router-link :to="{ path: '/company'}">
                            <v-btn
                                dark
                                icon
                            >
                                <v-icon>mdi-chevron-left</v-icon>
                            </v-btn>
                        </router-link>
                    <span class="headline white--text">Company Name : {{getCompany.name}}</span>
                    <v-spacer></v-spacer>
                </v-card-title>

                <v-list>
                    <v-list-item>
                        <v-list-item-action>
                            <v-icon>mdi-web</v-icon>
                        </v-list-item-action>

                        <v-list-item-content>
                            <v-list-item-title> {{getCompany.internet_address}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider inset></v-divider>

                    <v-list-item>
                        <v-list-item-action>
                                <v-icon>mdi-calendar-range</v-icon>
                        </v-list-item-action>

                        <v-list-item-content>
                            <v-list-item-title>{{getCompany.created_at}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>


                <template>
                    <v-card>
                        <v-tabs
                            background-color="primary"
                            dark
                        >
                            <v-tab>
                                Person
                            </v-tab>

                            <v-tab>
                                Address
                            </v-tab>

                            <v-tab-item>
                                <v-card flat>
                                    <v-card-text>
                                        <person v-bind:items="getCompany.persons" v-bind:company="getCompany"></person>
                                    </v-card-text>
                                </v-card>
                            </v-tab-item>

                            <v-tab-item>
                                <v-card flat>
                                    <v-card-text>
                                        <address-component v-bind:items="getCompany.addresses" v-bind:company="getCompany"></address-component>
                                    </v-card-text>
                                </v-card>

                            </v-tab-item>
                        </v-tabs>
                    </v-card>
                </template>
            </v-card>
        </v-col>
    </v-row>
</template>


<style>
    #mapFrame { width: 100%; height: 100%; margin: 0; padding: 0; }
</style>

<script>

    import { mapGetters, mapActions } from 'vuex';
    import axios from "axios";

    import Person from './Person';
    import AddressComponent from './AddressComponent';

    export default {
        components: {
            Person : Person,
            AddressComponent: AddressComponent
        },
        data() {
            return {
                id:this.$route.params.id,
                loadingDetails: true,
                ibbMAP: ''
            }
        },
        async created() {
            let vm = this;

            vm.loadingDetails = true;

            vm.setCompany(this.id).then(function () {
                vm.loadingDetails = false;
            });


        },
        computed: {
            ...mapGetters(['getCompany']),
        },
        methods: {
            ...mapActions(['setCompany']),
            loadingMap(){
                const plugin = document.createElement("script");
                plugin.setAttribute(
                    "src",
                    "/js/map2.js"
                );
                plugin.async = true;
                document.body.appendChild(plugin);
            },

        },
        mounted(){
            this.loadingMap();
        }
    }
</script>

