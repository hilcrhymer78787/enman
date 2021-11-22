
<template>
    <div>
        <v-form ref="form">
            <v-card class="mb-5">
                <v-toolbar color="teal" dark style="box-shadow:none;">
                    <v-toolbar-title>マイページ</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="mx-auto" style="width:30%;">
                        <v-img v-if="loginInfo.user_img.slice( 0, 4 ) == 'http'" :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                        <v-img v-else :src="backUrl+'/storage/'+loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:65%;">
                        <v-text-field class="mt-5 mb-3" dense color="teal" prepend-icon="mdi-account" readonly label="名前" v-model="loginInfo.name"></v-text-field>
                        <v-text-field dense color="teal" prepend-icon="mdi-email" readonly label="メールアドレス" v-model="loginInfo.email"></v-text-field>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <div class="d-flex pa-3">
                    <v-spacer></v-spacer>
                    <v-btn @click="logout()" class="mr-2">ログアウト</v-btn>
                    <v-btn @click="createUserDialog = true" dark color="orange lighten-1">編集</v-btn>
                </div>
            </v-card>
        </v-form>

        <v-form ref="form">
            <v-card class="mb-5">
                <v-toolbar color="teal" dark style="box-shadow:none;">
                    <v-toolbar-title>マイルーム</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="pb-2 mx-auto" style="width:20%;">
                        <v-img :src="loginInfo.room_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:75%;">
                        <v-select @change="onChangeRoom()" :readonly="onChangeRoomloading" :loading="onChangeRoomloading" v-model="selectedRoomId" color="teal" prepend-icon="mdi-home" label="部屋名" :items="loginInfo.rooms" item-value="room_id" item-text="room_name" class="my-5" dense></v-select>
                        <v-text-field :value="roomMateNames(loginInfo.room_joined_users)" readonly color="teal" prepend-icon="mdi-account-group" label="ルームメイト" dense style="font-size:12px;"></v-text-field>
                        <v-text-field :value="roomMateNames(loginInfo.room_inviting_users)" v-if="loginInfo.room_inviting_users.length" readonly color="teal" prepend-icon="mdi-account-plus" label="招待中" dense style="font-size:12px;"></v-text-field>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <div class="d-flex pa-3">
                    <v-spacer></v-spacer>
                    <v-btn :disabled="onChangeRoomloading" @click="openCreateRoomDialog('create')" class="mr-2">新規作成</v-btn>
                    <v-btn :disabled="onChangeRoomloading" @click="openCreateRoomDialog('edit')" :dark="!onChangeRoomloading" color="orange lighten-1">編集</v-btn>
                </div>
            </v-card>
        </v-form>

        <v-card v-if="loginInfo.invited_rooms.length" class="mb-5">
            <v-toolbar color="teal" dark style="box-shadow:none;">
                <v-toolbar-title>招待されている部屋</v-toolbar-title>
            </v-toolbar>
            <div v-for="(room,roomIndex) in loginInfo.invited_rooms" :key="roomIndex">
                <v-card-text class="d-flex align-center pb-0">
                    <div class="pb-2 mx-auto" style="width:20%;">
                        <v-img :src="room.room_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:75%;">
                        <v-text-field v-model="room.room_name" readonly color="teal" prepend-icon="mdi-home" label="部屋名" class="my-3" dense></v-text-field>
                        <v-text-field :value="roomMateNames(room.joined_users)" readonly color="teal" prepend-icon="mdi-account-group" label="ルームメイト" dense style="font-size:12px;"></v-text-field>
                        <v-text-field :value="roomMateNames(room.inviting_users)" v-if="room.inviting_users.length" readonly color="teal" prepend-icon="mdi-account-plus" label="招待中" dense style="font-size:12px;"></v-text-field>
                    </div>
                </v-card-text>
                <div class="d-flex px-3 pb-3">
                    <v-spacer></v-spacer>
                    <v-btn @click="rejectRoom(room.invitation_id,roomIndex,room.room_name)" :loading="rejectRoomloadings[roomIndex]" color="error" class="mr-2">拒否</v-btn>
                    <v-btn @click="joinRoom(room.invitation_id,roomIndex)" :loading="joinRoomloadings[roomIndex]" color="teal" dark>参加</v-btn>
                </div>
                <v-divider></v-divider>
            </div>
        </v-card>

        <v-dialog v-model="createUserDialog" scrollable>
            <CreateUser mode="edit" @onCloseDialog="createUserDialog = false" v-if="createUserDialog" />
        </v-dialog>

        <v-dialog v-model="createRoomDialog" scrollable>
            <CreateRoom :mode="modeCreateRoomDialog" @onCloseDialog="createRoomDialog = false" v-if="createRoomDialog" />
        </v-dialog>

    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            backUrl: process.env.API_BASE_URL,
            selectedRoomId: 0,
            onChangeRoomloading: false,
            joinRoomloadings: [],
            rejectRoomloadings: [],
            createUserDialog: false,
            modeCreateRoomDialog: false,
            createRoomDialog: false,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        logout() {
            if (confirm("ログアウトしますか？")) {
                this.$store.dispatch('logout')
            }
        },
        openCreateRoomDialog(mode) {
            this.modeCreateRoomDialog = mode;
            this.createRoomDialog = true;
        },
        async onChangeRoom() {
            this.onChangeRoomloading = true;
            await this.$axios
                .put(
                    `/api/user/update/room_id?token=${this.loginInfo.token}&room_id=${this.selectedRoomId}`
                )
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            await this.$store.dispatch("setLoginInfoByToken");
            this.onChangeRoomloading = false;
        },
        roomMateNames(users) {
            let outputData = "";
            users.forEach((user, index) => {
                if (index != 0) {
                    outputData = outputData + "、";
                }
                outputData = outputData + user.name;
            });
            return outputData;
        },
        async joinRoom(invitationId, roomIndex) {
            this.$set(this.joinRoomloadings, roomIndex, true);
            await this.$axios
                .put(
                    `/api/invitation/update?token=${this.loginInfo.token}&invitation_id=${invitationId}`
                )
                .then(async (res) => {
                    await this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            this.$set(this.joinRoomloadings, roomIndex, false);
        },
        async rejectRoom(invitationId, roomIndex, roomName) {
            if (!confirm(`「${roomName}」への招待を拒否しますか？`)) {
                return;
            }
            this.$set(this.rejectRoomloadings, roomIndex, true);
            await this.$axios
                .delete(
                    `/api/invitation/delete?token=${this.loginInfo.token}&invitation_id=${invitationId}`
                )
                .then(async (res) => {
                    await this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            this.$set(this.rejectRoomloadings, roomIndex, false);
        },
    },
    watch: {
        loginInfo() {
            this.selectedRoomId = this.loginInfo.room_id;
        },
    },
    mounted() {
        this.selectedRoomId = this.loginInfo.room_id;
    },
};
</script>