<template>
    <v-data-table
        :headers="headers"
        :items="items"
        sort-by="calories"
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar
                flat
            >
                <v-toolbar-title>Address</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="800px"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            New Address
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>
                        <v-form ref="form">
                            <v-card-text>
                                <v-container>
                                    <v-row>

                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="6"
                                        >
                                            <v-text-field
                                                :rules="required"
                                                v-model="editedItem.latitude"
                                                label="Latitude *"

                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="6"
                                        >
                                            <v-text-field
                                                :rules="required"
                                                v-model="editedItem.longitude"
                                                label="Longitude"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                        </v-form>

                        <locations v-if="editedIndex != -1" v-bind:address="editedItem"></locations>

                        <v-card-text v-if="saving">
                            Saving...
                            <v-progress-linear
                                indeterminate
                                color="white"
                                class="mb-0"
                            ></v-progress-linear>
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
    import Locations from "./Locations";

    export default {
        components: {
            Locations : Locations
        },

        data: () => ({
            dialog: false,
            dialogDelete: false,
            headers: [
                {
                    text: 'Latitude',
                    align: 'start',
                    sortable: false,
                    value: 'latitude',
                },
                { text: 'Longitude', value: 'longitude' },
                { text: 'Actions', value: 'actions', sortable: false }

            ],
            editedIndex: -1,
            editedItem: {
                latitude: '',
                longitude: '',
            },
            defaultItem: {
                latitude: '',
                longitude: '',
            },
            required: [
                v => !!v || 'Filed is required'
            ],
            saving: false,
            edit : false
        }),
        props:{
            items : Array,
            company: '',
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Address' : 'Edit Address'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
            dialogDelete (val) {
                val || this.closeDelete()
            },
        },

        created () {
            this.initialize()
        },

        methods: {
            ...mapActions(['setCompany']),
            initialize () {
                this.data = [
                    {
                        latitude: '',
                        longitude: '',
                    }
                ]
            },

            editItem (item) {
                this.editedIndex = this.items.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                this.editedIndex = this.items.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialogDelete = true
            },

            deleteItemConfirm () {
                this.items.splice(this.editedIndex, 1)
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
                this.dialogDelete = false
                this.$nextTick(() => {
                    let vm = this;
                    this.dialogDelete = false
                    this.$nextTick(async () => {
                        await axios.delete('/api/address/' + vm.editedItem.id).then(response => {
                            if(response.status === 200) {
                                vm.setCompany(this.company.id).then(function () {
                                    vm.loadingDetails = false;
                                    console.log(vm.loadingCompanies);

                                });
                                vm.saving = false;

                                vm.$notify({
                                    group: 'foo',
                                    type:"success",
                                    text: 'Address has been successfully deleted.',
                                    title: "Success",
                                });
                            }
                        });
                    })
                })
            },

            async save () {

                if(this.$refs.form.validate()){
                    let vm = this;
                    vm.saving = true;
                    let response = await axios.post('/api/companies/' + this.company.id + '/address', this.editedItem).then(response => {
                        if(response.status === 200) {
                            this.saving = false;
                            this.close();

                            this.$notify({
                                group: 'foo',
                                type:"success",
                                text: 'Address has been successfully registered.',
                                title: "Success",
                            });


                            vm.setCompany(this.company.id).then(function () {
                                vm.loadingDetails = false;
                                console.log(vm.loadingCompanies);

                            });
                        }
                    }).catch(error => {
                        this.$notify({
                            group: 'foo',
                            type:"error",
                            text: error.response.data.message,
                            title: "Error!!",
                        });

                        this.saving = false;
                        this.close()
                    })
                }
            },
        },
    }
</script>
