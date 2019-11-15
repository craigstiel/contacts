<template>
    <v-app id="keep">
        <alerts></alerts>
        <router-view></router-view>
    </v-app>
</template>

<script>
    import alert from './useful/Alert';
    export default {
        created: function () {},
        props: {},
        components: {
            'alerts': alert
        },
        data:() => ({}),
    }
</script>
