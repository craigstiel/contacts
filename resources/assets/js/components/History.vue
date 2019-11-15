<template>
    <div class="row">
        <v-col md="1"></v-col>
        <v-col cols="12" md="10" key=12>
            <v-progress-circular v-if="progress" style="margin-left: 45% !important;" :size="150" color="teal darken-2" indeterminate></v-progress-circular>
            <v-card class="mx-auto mycontent-left" v-else>
                <v-card-title>
                    Logs
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="logs" :search="search">
                    <template v-slot:top>
                        <v-toolbar flat dark>
                            <v-btn color="teal darken-2" dark class="mb-2 ml-3" @click="get_excel()"><i class="fa fa-file-excel"></i></v-btn>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </v-card>
        </v-col>
    </div>
</template>

<script>
    const FileDownload = require('js-file-download');
    export default {
        created: function () {
            let _this = this;
            axios.get('/contacts/get/logs')
                .then(function (response) {
                    _this.logs = response.data.logs;
                    _this.progress = false;
                });
        },
        data() {
            return {
                search: '',
                progress: true,
                headers: [
                    {text: 'Contact id', align: 'left', value: 'contact_id'},
                    {text: 'Contact', align: 'left', value: 'contact'},
                    {text: 'Updated by', value: 'updated_by'},
                    {text: 'Changes', value: 'changes'},
                    {text: 'Acts', value: 'act'},
                    {text: 'Response', value: 'response'},
                    {text: 'Updated at', value: 'updated_at'},
                ],
                logs: [],
            }
        },
        methods: {
            get_excel: function() {
                axios.get('/contacts/get/logs/excel', {
                    responseType: 'blob'})
                    .then((response) => {
                        FileDownload(new Blob([response.data]), 'log.xlsx');
                    }).catch((error) => {
                        console.log("User list export", 'Error exporting user\'s list !', 'error');
                });
            }
        }
    }
</script>
