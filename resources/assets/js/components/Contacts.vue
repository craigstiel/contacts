<template>
    <div class="row">
        <v-col md="1"></v-col>
        <v-col cols="12" md="10" key=12>
            <v-progress-circular v-if="progress" style="margin-left: 45% !important;" :size="150" color="teal darken-2" indeterminate></v-progress-circular>
            <v-card class="mx-auto mycontent-left" v-else>
                <v-card-title>
                    Contacts
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="contacts" :search="search"></v-data-table>
            </v-card>
        </v-col>
    </div>
</template>

<script>
    export default {
        created: function () {
            let _this = this;
            axios.get('/contacts/get')
                .then(function (response) {
                    _this.contacts = response.data.contacts;
                    _this.progress = false;
                });
        },
        data() {
            return {
                search: '',
                progress: true,
                headers: [
                    {text: 'Name', align: 'left', value: 'name'},
                    {text: 'Company', value: 'company'},
                    {text: 'Job', value: 'job'},
                    {text: 'E-mail', value: 'email'},
                    {text: 'Phone', value: 'phone'},
                    {text: 'Notes', value: 'notes'},
                ],
                contacts: [],
            }
        },
    }
</script>
