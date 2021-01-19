<template>
    <v-data-table
        :headers="headers"
        :items="getCompanies"
        sort-by="calories"
        class="elevation-1"
        :loading="loadingCompanies"
        loading-text="Loading Companies"
    >
        <template v-slot:item.name="{ item }">
            <router-link :to="{ path: '/companies/'+ item.id}">{{item.name}}</router-link>
        </template>

        <template v-slot:top>
            <v-toolbar
                flat
            >
                <v-toolbar-title>Companies</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >

                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            New Company
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-form ref="form">
                                    <v-row>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-text-field
                                                :rules="required"
                                                v-model="editedItem.name"
                                                label="Company name"
                                            ></v-text-field>
                                            <small></small>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="12"
                                            md="12"
                                        >
                                            <v-text-field
                                                :rules="required"
                                                v-model="editedItem.internet_address"
                                                label="Web Site"
                                            ></v-text-field>
                                        </v-col>

                                    </v-row>

                                </v-form>

                                <v-card-text v-if="saving">
                                    Saving...
                                    <v-progress-linear
                                        indeterminate
                                        color="white"
                                        class="mb-0"
                                    ></v-progress-linear>
                                </v-card-text>

                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="close"
                                :disabled="saving"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="save"
                                :disabled="saving"
                            >
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>

        <template v-slot:item.actions="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                small
                @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn
                color="primary"
                @click="initialize"
            >
                Reset
            </v-btn>
        </template>
    </v-data-table>
</template>

<script>

    import { mapGetters, mapActions } from 'vuex';
    import axios from "axios";

    export default {
        data: () => ({
            dialog: false,
            saving: false,
            dialogDelete: false,
            loadingCompanies: true,
            headers: [
                {
                    text: 'Name',
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                { text: 'Web Site', value: 'internet_address' },
                { text: 'Actions', value: 'actions', sortable: false }
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                web_site: 'https://'
            },
            defaultItem: {
                name: '',
                web_site: 'https://'
            },
            required: [
                v => !!v || 'Filed is required'
            ],
        }),
        computed: {
            ...mapGetters(['getCompanies']),
            formTitle () {
                return this.editedIndex === -1 ? 'New Company' : 'Edit Company'
            },
        },

        mounted() {

        },

        watch: {

            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.initialize();
            let vm = this;

            vm.loadingCompanies = true;

            console.log(vm.loadingCompanies);
            vm.setCompanies().then(function (response) {
                vm.loadingCompanies = false;
                console.log(response);

            });
        },

        methods: {
            ...mapActions(['setCompanies']),
            initialize () {
                this.desserts = [
                    {
                        name: 'Frozen Yogurt',
                        internet_address : "www.test.com"
                    },
                    {
                        name: 'Ice cream sandwich',
                        internet_address:"http://www.test2.com"
                    }
                ]
            },

            editItem (item) {
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                this.editedIndex = this.getCompanies.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialogDelete = true
            },

            deleteItemConfirm () {
                this.closeDelete()
            },

            close () {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            closeDelete () {
                let vm = this;
                vm.dialogDelete = false
                vm.$nextTick(async () => {

                    await axios.delete('/api/companies/' + vm.editedItem.id).then(response => {
                        if(response.status === 200) {
                            vm.setCompanies();
                            vm.saving = false;

                            vm.$notify({
                                group: 'foo',
                                type:"success",
                                text: 'Company has been successfully deleted.',
                                title: "Success",
                            });
                        }
                    });
                })
            },

            async save () {
                this.saving = true;
                if(this.$refs.form.validate()){
                    let vm = this;
                    await axios.post('/api/companies', this.editedItem).then(response => {
                        if(response.status === 200) {
                            vm.setCompanies();
                            this.saving = false;

                            vm.$notify({
                                group: 'foo',
                                type:"success",
                                text: 'Company has been successfully registered.',
                                title: "Success",
                            });
                        }
                    });

                    this.close()
                }
            },
        },
    }
</script>
