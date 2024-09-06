<script>
import { router } from '@inertiajs/vue3'
import RoomCard from "@/Components/OperatorDashboard/Navigation/RoomCard.vue";

export default {
    name: "ChatRoomsAssignedToOperator",
    components: {router, RoomCard},
    props: {
        chatRooms: Array,
        operatorData: Object,
        roomId: Number | null
    },
    mounted() {
        this.csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        this.chatRoomsWaitingForOperator = this.chatRooms.filter((room) => room.operator_id === null)
        this.myChatRooms = this.chatRooms.filter((room) => Number(room.operator_id) === Number(this.operatorData.operatorId))

        this.fillChannelsList()

        this.subscribeToAllChannels()

        this.removeHighlight(this.roomId)

        this.setOperatorIsOnlineTimer = setInterval(this.setOperatorIsOnline, 10000)
    },
    unmounted() {
        clearTimeout(this.setOperatorIsOnlineTimer)
    },
    data: () => (
        {
            csrf: null,
            chatRoomsWaitingForOperator: [],
            myChatRooms: [],
            channelsList: [],
            setOperatorIsOnlineTimer: null
        }
    ),
    methods: {
        fillChannelsList() {
            this.channelsList.push({
                listen: "",
                channel: "operatorsChannel."+this.operatorData.adminId
            })

            for (let i = 0; i < this.myChatRooms.length; i++) {
                this.channelsList.push({
                    listen: "SendMessageEvent",
                    channel: this.myChatRooms[i].chat_client_id
                })
            }
        },
        subscribeToAllChannels() {
            this.channelsList.forEach(channelData => this.subscribeToChannel(channelData))
        },
        subscribeToChannel(channelData) {
            if (channelData.channel === "operatorsChannel."+this.operatorData.adminId) {
                Echo.private(channelData.channel)
                    .listen("CloseRoomEvent", (e) => {
                        this.closeRoom(e.room_id)
                    })
                    .listen("AssignOperatorToChatEvent", (e) => {
                        this.updateMyChatRooms(e)
                        this.subscribeToChannel({
                            listen: "SendMessageEvent",
                            channel: e.chat_room.chat_client_id
                        })
                    })
                    .listen("NotifyOfNewChatMessageEvent", (e) => {
                        this.updateChatRoomsWaitingForOperator(e)
                    });

                return 0
            }

            Echo.channel(channelData.channel)
                .listen(channelData.listen, (messageData) => {
                    for (let i = 0; i < this.myChatRooms.length; i++) {
                        if (this.myChatRooms[i].id === messageData.chat_room.id) {
                            this.myChatRooms[i].last_message.message = messageData.message
                            this.myChatRooms[i].last_message.message_from = messageData.message_from

                            if (Number(this.roomId) === Number(messageData.chat_room.id)) {
                                this.myChatRooms[i].highlighted = false
                                axios.post(route("mark.chat.as.read"), {chat_id: this.roomId});
                            } else {
                                this.myChatRooms[i].highlighted = true
                                //this.playNewMessageSound()
                            }
                        }
                    }
                });
        },
        checkIfMyChatRoomsHasChatId(roomId) {
            let chatHasRoom = false

            this.myChatRooms.forEach(function(room, index) {
                if (Number(room.id) === Number(roomId)) {
                    chatHasRoom = true
                }
            });

            return chatHasRoom
        },
        removeHighlight(roomId) {
            for (let key in this.myChatRooms) {
                if (this.myChatRooms.hasOwnProperty(key)) {
                    let obj = this.myChatRooms[key];
                    for (let prop in obj) {
                        if (obj.hasOwnProperty(prop)) {
                            if (prop === 'id' && Number(obj[prop]) === Number(roomId)) {
                                this.myChatRooms[key].highlighted = false
                                axios.post(route("mark.chat.as.read"), {chat_id: roomId});
                            }
                        }
                    }
                }
            }
        },
        updateMyChatRooms(messageData) {
            if (!this.checkIfMyChatRoomsHasChatId(messageData.chat_room.id)) {
                this.chatRoomsWaitingForOperator = this.chatRoomsWaitingForOperator.filter((room) => Number(room.id) !== Number(messageData.chat_room.id));
                if (Number(messageData.chat_room.operator_id) === Number(this.operatorData.operatorId)) {
                    messageData.chat_room.highlighted = false
                    this.myChatRooms.push(messageData.chat_room)
                }
            }
        },
        updateChatRoomsWaitingForOperator(messageData) {
            if (messageData.chat_room['operator_id'] !== undefined) {
                return 0
            }

            let chatRoomsHasRoom = false

            this.chatRoomsWaitingForOperator.forEach(function(room, index) {
                if (Number(room.id) === Number(messageData.chat_room.id)) {
                    chatRoomsHasRoom = true
                }
            });

            if (!chatRoomsHasRoom && !this.checkIfMyChatRoomsHasChatId(messageData.chat_room.id)) {
                this.playNewMessageSound()
                this.chatRoomsWaitingForOperator.push(messageData.chat_room)
            }
        },
        playNewMessageSound() {
            let audio = new Audio(this.operatorData.newMessageSoundUrl)
            let promise = audio.play()
            if (promise !== undefined) {
                promise.then(function () {
                    // Autoplay started successfully
                }).catch(function (error) {
                    // Autoplay was prevented
                });
            }
        },
        closeRoom(roomId) {
            this.chatRoomsWaitingForOperator = this.chatRoomsWaitingForOperator.filter((room) => Number(room.id) !== roomId);
            this.myChatRooms = this.myChatRooms.filter((room) => Number(room.id) !== roomId);
        },
        setOperatorIsOnline() {
            axios.post(route('set.operator.is.online'), {operator_id: this.operatorData.operatorId})
        }
    }
}
</script>

<template>
    <template v-if="myChatRooms.length === 0 && chatRoomsWaitingForOperator.length === 0">
        <v-divider></v-divider>
        <v-list-item title="Список чатов пуст"></v-list-item>
    </template>

    <RoomCard :chatRooms="myChatRooms" listTitle="Мои чаты"></RoomCard>

    <v-divider></v-divider>

    <RoomCard :chatRooms="chatRoomsWaitingForOperator" listTitle="Ожидают оператора"></RoomCard>
</template>

<style scoped>

</style>
