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
                <v-toolbar-title>Persons</v-toolbar-title>
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
                            New Person
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
                                            md="4"
                                        >
                                            <v-text-field
                                                :rules="required"
                                                v-model="editedItem.name"
                                                label="Name *"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                :rules="required"
                                                v-model="editedItem.last_name"
                                                label="Last Name *"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="4"
                                        >
                                            <v-text-field
                                                v-model="editedItem.title"
                                                label="Title"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="6"
                                        >
                                            <v-text-field
                                                :rules="required"

                                                v-model="editedItem.email"
                                                label="E-mail *"

                                            ></v-text-field>
                                        </v-col>
                                        <v-col
                                            cols="12"
                                            sm="6"
                                            md="6"
                                        >
                                            <v-text-field
                                                v-model="editedItem.phone_number"
                                                label="Phone Number"
                                                v-mask="'(###)###-####'"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                        </v-form>

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

    export default {

        data: () => ({
            dialog: false,
            dialogDelete: false,
            headers: [
                {
                    text: 'Name',
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                { text: 'Last Name', value: 'last_name' },
                { text: 'Title', value: 'title' },
                { text: 'E-mail', value: 'email' },
                { text: 'Phone Number', value: 'phone_number' },
                { text: 'Actions', value: 'actions', sortable: false }

            ],
            editedIndex: -1,
            editedItem: {
                name: '',
                last_name: '',
                title: '',
                email: '',
                phone_number: '',
            },
            defaultItem: {
                name: '',
                last_name: '',
                title: '',
                email: '',
                phone_number: '',
            },
            required: [
                v => !!v || 'Filed is required'
            ],
            saving: false
        }),
        props:{
            items : Array,
            company: '',
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Person' : 'Edit Person'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
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
                        name: 'TEst',
                        last_name: 'test',
                        title: 'test',
                        email: 'test',
                        phone_number: 'test',
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
                this.$nextTick(async () => {
                    await axios.delete('/api/persons/' + vm.editedItem.id).then(response => {
                        if(response.status === 200) {
                            vm.setCompany(this.company.id).then(function () {
                                vm.loadingDetails = false;
                                console.log(vm.loadingCompanies);

                            });
                            vm.saving = false;

                            vm.$notify({
                                group: 'foo',
                                type:"success",
                                text: 'Person has been successfully deleted.',
                                title: "Success",
                            });
                        }
                    });
                })
            },

            async save () {

                if(this.$refs.form.validate()){
                    let vm = this;
                    vm.saving = true;
                    let response = await axios.post('/api/companies/' + this.company.id + '/persons', this.editedItem).then(response => {
                        if(response.status === 200) {
                            this.saving = false;
                            this.close()

                            this.$notify({
                                group: 'foo',
                                type:"success",
                                text: 'Person has been successfully registered.',
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

                    })
                }
            },
        },
    }
</script>
