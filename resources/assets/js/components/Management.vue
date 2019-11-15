<template>
    <v-row>
        <v-col md="1"></v-col>
        <v-col cols="12" md="10" key=12>
            <v-progress-circular v-if="progress" style="margin-left: 45% !important;" :size="150" color="teal darken-2" indeterminate></v-progress-circular>
            <v-card class="mx-auto mycontent-left" v-else>
                <v-card-title>
                    Contacts
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="contacts"
                    :search="search">
                    <template v-slot:item.edit="{ item }">
                        <v-icon small class="mr-2" @click="editItem(item)">
                            edit
                        </v-icon>
                    </template>
                    <template v-slot:item.delete="{ item }">
                        <v-icon small  @click="deleteItem(item)">
                            delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-card>
        </v-col>
        <v-btn bottom class="bottom-50" color="teal darken-1" dark fab fixed right @click="dialog = !dialog">
            <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" width="800px">
            <v-form ref="obs" v-model="valid" lazy-validation>
                <v-card>
                <v-card-title class="grey darken-2">
                    Create contact
                </v-card-title>
                    <v-progress-circular v-if="add_contact_progress" style="margin-left: 45% !important;" :size="100" color="teal darken-2" indeterminate></v-progress-circular>
                    <v-container grid-list-sm v-else>
                    <v-layout row wrap>
                        <v-flex xs12 align-center justify-space-between>
                            <v-layout align-center>
                                <v-avatar size="40px" class="mr-3">
                                    <img src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png" alt="">
                                </v-avatar>
                                <v-text-field placeholder="Name" v-model="new_contact.name" :rules="nameRules" required></v-text-field>
                            </v-layout>
                        </v-flex>
                        <v-flex xs6>
                            <v-text-field prepend-icon="business" placeholder="Company" v-model="new_contact.company"></v-text-field>
                        </v-flex>
                        <v-flex xs6>
                            <v-text-field placeholder="Job title" v-model="new_contact.job"></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field prepend-icon="mail" placeholder="Email" v-model="new_contact.email"></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field type="tel" prepend-icon="phone" placeholder="(000) 000 - 0000" :rules="phoneRules" v-model="new_contact.phone" required></v-text-field>
                        </v-flex>
                        <v-flex xs12>
                            <v-text-field prepend-icon="notes" placeholder="Notes" v-model="new_contact.notes"></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="dialog = false">Cancel</v-btn>
                    <v-btn text @click="add()">Save</v-btn>
                </v-card-actions>
            </v-card>
            </v-form>
        </v-dialog>
        <v-dialog v-model="add_dialog" hide-overlay persistent width="400">
            <v-card>
                    <v-card-title class="headline">New contact was added.
                    </v-card-title>
                    <v-card-actions>
                        <div class="flex-grow-1">
                            <v-btn color="green darken-1" text @click="add_dialog = false">OK</v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
        </v-dialog>
        <v-dialog v-model="delete_dialog" hide-overlay persistent width="450">
            <v-card>
                <v-card-title>
                    Do you really want to delete this contact?
                </v-card-title>
                <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="blue darken-1" text @click="delete_dialog = false">Close</v-btn>
                    <v-btn color="blue darken-1" text @click="approved_delete()">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="edit_dialog" width="800px">
            <v-form ref="form" v-model="valid" lazy-validation>
                <v-card>
                    <v-card-title class="grey darken-2">
                        Edit contact
                    </v-card-title>
                    <v-progress-circular v-if="edit_contact_progress" style="margin-left: 45% !important;" :size="100" color="teal darken-2" indeterminate></v-progress-circular>
                    <v-container grid-list-sm v-else>
                        <v-layout row wrap>
                            <v-flex xs12 align-center justify-space-between>
                                <v-layout align-center>
                                    <v-avatar size="40px" class="mr-3">
                                        <img src="//ssl.gstatic.com/s2/oz/images/sge/grey_silhouette.png" alt="">
                                    </v-avatar>
                                    <v-text-field placeholder="Name" v-model="contact.name" :rules="nameRules" required></v-text-field>
                                </v-layout>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field prepend-icon="business" placeholder="Company" v-model="contact.company"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-text-field placeholder="Job title" v-model="contact.job"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field prepend-icon="mail" placeholder="Email" v-model="contact.email"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field type="tel" prepend-icon="phone" placeholder="(000) 000 - 0000" :rules="phoneRules" v-model="contact.phone" required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field prepend-icon="notes" placeholder="Notes" v-model="contact.notes"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="edit_dialog = false">Cancel</v-btn>
                        <v-btn text @click="approved_edit()">Save</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
        <v-dialog v-model="ok_edit_dialog" hide-overlay persistent width="400">
            <v-card>
                <v-card-title class="headline">The contact was edited.
                </v-card-title>
                <v-card-actions>
                    <div class="flex-grow-1">
                        <v-btn color="green darken-1" text @click="ok_edit_dialog = false">OK</v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>

    export default {
        created: function () {
            this.get_contacts();
        },
        data: () => ({
            dialog: false,
            search: '',
            progress: true,
            valid: false,
            add_dialog: false,
            delete_dialog: false,
            ok_edit_dialog: false,
            edit_dialog: false,
            add_contact_progress: false,
            edit_contact_progress: false,
            deleted_item: null,
            edited_index: null,
            nameRules: [
                v => !!v || 'Name is required',
                v => (v && v.length <= 255) || 'Name must be less than 255 characters',
            ],
            phoneRules: [
                v => !!v || 'Phone is required',
                v => /^[\+]?\d{2,}?[(]?\d{2,}[)]?[-\s\.]?\d{2,}?[-\s\.]?\d{2,}[-\s\.]?\d{0,9}$/im.test(v) || 'Phone must be valid',
            ],
            new_contact: {},
            contact: {},
            headers: [
                {text: 'Name', align: 'left', value: 'name'},
                {text: 'Company', value: 'company'},
                {text: 'E-mail', value: 'email'},
                {text: 'Phone', value: 'phone'},
                {text: 'Edit', value: 'edit', sortable: false},
                {text: 'Delete', value: 'delete', sortable: false}
            ],
            contacts: [],
        }),
        methods: {
            get_contacts: function () {
                let _this = this;
                axios.get('/contacts/get')
                    .then(function (response) {
                        _this.contacts = response.data.contacts;
                        _this.progress = false;
                    });
            },
            add: function () {
                let _this = this;
                if (this.$refs.obs.validate()) {
                    this.add_contact_progress = true;
                    let data = {
                        name: _this.new_contact.name,
                        company: _this.new_contact.company,
                        job: _this.new_contact.job,
                        email: _this.new_contact.email,
                        phone: _this.new_contact.phone,
                        notes: _this.new_contact.notes,
                    };
                    axios.post('/contacts/add', data)
                        .then(function (response) {
                            if (response.status === 200) {
                                _this.add_contact_progress = false;
                                _this.dialog = false;
                                _this.add_dialog = true;
                            }
                        })
                        .catch((error) => {
                            console.log('error: ' + error);
                        });
                }
            },
            editItem: function (item) {
                this.edited_intex = item.id;
                this.contact = Object.assign({}, item);
                this.edit_dialog = true;
            },
            deleteItem: function (item) {
                this.deleted_item = item.id;
                this.delete_dialog = true;
            },
            approved_delete: function () {
                let _this = this;
                this.progress = true;
                axios.delete('/contacts/delete/' + _this.deleted_item)
                    .then(function (response) {
                        _this.get_contacts();
                        _this.delete_dialog = false;
                    });
            },
            approved_edit: function () {
                let _this = this;
                if (this.$refs.form.validate()) {
                    this.edit_contact_progress = true;
                    let data = {
                        name: _this.contact.name,
                        company: _this.contact.company,
                        job: _this.contact.job,
                        email: _this.contact.email,
                        phone: _this.contact.phone,
                        notes: _this.contact.notes,
                    };
                    axios.put('/contacts/edit/' + _this.edited_intex, data)
                        .then(function (response) {
                            if (response.status === 200) {
                                _this.edit_contact_progress = false;
                                _this.edit_dialog = false;
                                _this.ok_edit_dialog = true;
                                _this.progress = true;
                                _this.get_contacts();
                            }
                        })
                        .catch((error) => {
                            console.log('error: ' + error);
                        });
                }
            }
        }
    }
</script>
