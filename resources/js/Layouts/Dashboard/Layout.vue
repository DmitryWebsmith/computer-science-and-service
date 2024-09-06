<script>
import {router} from "@inertiajs/vue3";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    name: "Layout",
    components: {router, Link },
    props: {
        operatorData: Object
    },
    mounted() {},
    methods: {
        logout() {
            return router.post(route('logout'))
        },
        goTo(url, data) {
            router.get(url, data)
        },
        activeTab(addressPart) {
            let link = new URL(window.location.href)
            if (window.location.href.includes('configuration') && addressPart === 'configuration') {
                return true
            }

            let resultRoomId = link.search.replace('?id=', '')
            return Number(resultRoomId) === Number(addressPart);
        },
    }
}
</script>

<template>
    <v-layout class="rounded rounded-md">
        <v-app-bar
            color="#004D40"
            style="height: 65px">
            <v-app-bar-title>
                Дэшбоард
            </v-app-bar-title>

            <v-spacer></v-spacer>
            <div class="ma-1" align="center">
                <v-menu
                    min-width="200px"
                    rounded
                >
                    <template v-slot:activator="{ props }">
                        <v-btn
                            icon
                            v-bind="props"
                        >
                            <v-avatar>
                                <v-img
                                    src="/img/operator.jpg"
                                ></v-img>
                            </v-avatar>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-text>
                            <div class="text-center">
                                <h3>{{ operatorData.first_name }}</h3>
                                <p class="text-caption mt-1">
                                    {{ operatorData.email }}
                                </p>
                                <v-divider></v-divider>
                                <v-btn
                                    variant="text"
                                    @click="goTo(route('profile.edit'), {})"
                                    class="mt-1">
                                    Профиль
                                </v-btn>
                                <v-divider></v-divider>
                                <v-btn
                                    variant="text"
                                    @click="logout"
                                >
                                    Выход
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-menu>
            </div>
        </v-app-bar>

        <v-navigation-drawer
            permanent
        >
            <v-list>
                <v-list-item
                    @click="goTo(route('dashboard'), {})"
                    :active="this.activeTab('dashboard')"
                    title="Главная">
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <slot />
        </v-main>
    </v-layout>

</template>

<style scoped>

</style>

