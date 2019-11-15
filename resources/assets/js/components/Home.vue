<template>
    <div>
        <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            app>
            <v-list dense v-if="$auth.check()">
                <p class="text-uppercase">{{$auth.watch.data.name}}</p>
                <template v-for="item in items">
                    <v-layout v-if="item.heading" :key="item.heading" row align-center>
                        <v-flex xs6>
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-flex>
                    </v-layout>
                    <v-list-item v-else :key="item.text" @click="$router.push({name: item.name})">
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
                <v-list-item @click.prevent="$auth.logout()">
                    <v-list-item-action><v-icon>fas fa-sign-out-alt</v-icon></v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="gbottom: 0;rey--text">Sign out</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-list dense v-if="!$auth.check()">
                <template v-for="item in auth">
                    <v-layout v-if="item.heading" :key="item.heading" row align-center>
                        <v-flex xs6>
                            <v-subheader v-if="item.heading">
                                {{ item.heading }}
                            </v-subheader>
                        </v-flex>
                    </v-layout>
                    <v-list-item v-else :key="item.text" @click="$router.push({name: item.name})">
                        <v-list-item-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.text }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="teal darken-4">
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
                <span class="hidden-sm-and-down">Contact manager</span>
            </v-toolbar-title>
        </v-app-bar>
        <v-content>
            <v-container fluid class="grey darken-4 fill-height">
                <v-row justify="center" align="center">
                    <router-view></router-view>
                </v-row>
            </v-container>
            <v-footer dark padless style="position: fixed;  width: 100%; bottom: 0">
                <v-row>
                    <v-card class="flex" flat tile>
                        <v-card-text class="py-2 white--text text-center">
                            {{ new Date().getFullYear() }} — Craigstiel
                        </v-card-text>
                    </v-card>
                </v-row>
            </v-footer>
        </v-content>
    </div>
</template>

<script>

    export default {
        data: () => ({
            dialog: false,
            drawer: null,
            items: [
                {icon: 'contacts', text: 'Contacts', name: 'contacts'},
                {icon: 'settings', text: 'Contacts management', name: 'management'},
                {icon: 'chat_bubble', text: 'History', name: 'history'},

            ],
            auth: [
                {icon: 'fas fa-user-plus', text: 'Sign up', name: 'register'},
                {icon: 'fas fa-sign-in-alt', text: 'Sign in', name: 'login'},
            ],
        })
    }
</script>

<style>
    #keep .v-navigation-drawer__border {
        display: none
    }
</style>
