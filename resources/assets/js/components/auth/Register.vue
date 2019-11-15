<template>
    <v-row>
        <v-col md="2"></v-col>
        <v-col cols="12" md="8">
            <v-card class="mx-auto mycontent-left">
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-col cols="12" md="12">
                        <v-text-field v-model="name" :counter="255" :rules="nameRules"
                                      label="Name" required></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -30px">
                        <v-text-field v-model="phone" :rules="phoneRules" label="Phone"
                                      required></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-text-field v-model="new_password" :append-icon="show1 ? 'visibility' : 'visibility_off'"
                                      :rules="[passwordRules.required, passwordRules.min]"
                                      :type="show1 ? 'text' : 'password'" name="input-10-1"
                                      label="Password"
                                      counter @click:append="show1 = !show1"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="12" style="margin-top: -20px">
                        <v-text-field v-model="confirm_new_password" :append-icon="show2 ? 'visibility' : 'visibility_off'"
                                      :rules="[passwordRules.required, passwordRules.min]"
                                      :type="show2 ? 'text' : 'password'" name="input-10-1"
                                      label="Confirm password"
                                      counter @click:append="show2 = !show2"></v-text-field>
                    </v-col>
                    <v-col style="margin-top: -15px" cols="12" md="12">
                        <v-btn :disabled="!valid" color="success" class="mr-4" @click="register">
                            Register
                        </v-btn>
                    </v-col>
                </v-form>
            </v-card>
        </v-col>
        <v-col md="2"></v-col>
    </v-row>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                email: '',
                password: '',
                confirm_new_password: '',
                new_password: '',
                phone: '',
                valid: true,
                show1: false,
                show2: false,
                nameRules: [
                    v => !!v || 'Name is required',
                    v => (v && v.length <= 255) || 'Name must be less than 255 characters',
                ],
                passwordRules: {
                    required: value => !!value || 'Password is required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => ('The email and password you entered don\'t match'),
                },
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                phoneRules: [
                    v => !!v || 'Phone is required',
                    v => /^[\+]?\d{2,}?[(]?\d{2,}[)]?[-\s\.]?\d{2,}?[-\s\.]?\d{2,}[-\s\.]?\d{0,9}$/im.test(v) || 'Phone must be valid',
                ],
            };
        },
        watch: {
            confirm_new_password: function () {
                if (this.new_password === this.confirm_new_password) {
                    this.password = this.new_password;
                }
            },
            new_password: function () {
                if (this.new_password === this.confirm_new_password) {
                    this.password = this.new_password;
                }
            },
        },
        methods: {
            register() {
                let app = this;
                if (this.$refs.form.validate()) {
                    if (app.password === app.confirm_new_password && app.password === app.new_password) {
                        this.$auth.register({
                            data: {
                                name: app.name,
                                email: app.email,
                                password: app.password,
                                phone: app.phone,
                            },
                            error: function (error) {
                                if (error.response.status === 500) {
                                    app.$bus.$emit("alert", "This email has been already using.", "error");
                                }
                            },
                        });
                    } else {
                        app.$bus.$emit("alert", "Passwords do not match.", "error");
                    }
                }
            }
        }
    }
</script>
