<template>
    <v-snackbar v-model="active" :timeout="timeout" top center :color="params.color">
        {{ params.title }} {{text }}
        <v-btn :color="params.color" @click="active = false"><span style="color: white">×</span></v-btn>
    </v-snackbar>
</template>
<script>
    export default {
        created: function() {
            // Алерт вызывается по событию
            this.$bus.$on("alert", (text, type) => {
                this.text = text;
                this.type = type;
                this.active = true;
            });
        },
        data: function() {
            return {
                active: false,
                text: "",
                type: "ok",
                title: "",
                timeout: 9000
            };
        },
        watch: {
            value: function() {
                this.active = this.value;
            }
        },
        computed: {
            params: function() {
                // Цвет и текст в зависимости от типа алерта
                if (this.type == "error") {
                    return {
                        title: "Error!",
                        color: "error"
                    };
                }
                if (this.type == "ok") {
                    return {
                        title: "Success",
                        color: "success"
                    };
                }
                return {
                    title: "",
                    color: "error"
                };
            }
        }
    };
</script>
