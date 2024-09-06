<script>
import {router} from "@inertiajs/vue3";

export default {
    name: "RoomCard",
    props: {
        chatRooms: Object,
        listTitle: String,
    },
    methods: {
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
        truncateText(text, length) {
            if (text.length > length) {
                return text.substring(0,50)+".."
            }
            return text
        }
    }
}
</script>

<template>
    <template v-if="chatRooms.length > 0">
        <v-list>
            <v-list-item
                style="margin-left: 3px; margin-right: 3px"
                :title="listTitle"></v-list-item>
            <v-list-item
                v-for="(room, index) in chatRooms"
                :key="index"
                class="ma-2 pa-0"
                @click="goTo(route('chat.room'), { id: room.id })"
                :class="room.highlighted ? 'highlighted' : ''"
                :active="this.activeTab(room.id)">
                <template v-slot:title>
                    <v-card
                        class="ma-1 pa-3"
                        elevation="2"
                        max-width="290"
                    >
                        <v-card-title class="ma-0 pa-0" style="font-size: 0.8rem;">
                            {{ room.last_message.message_from }}
                        </v-card-title>
                        <v-card-text class="text-wrap ma-0 pa-0" style="font-size: 0.7rem">
                            {{ truncateText(room.last_message.message, 50) }}
                        </v-card-text>
                    </v-card>
                </template>
            </v-list-item>
        </v-list>
    </template>
</template>

<style scoped>
.highlighted {
    background: #B9F6CA!important;
}
</style>
