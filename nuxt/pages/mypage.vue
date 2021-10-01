
<template>
    <div>
        <v-form ref="form">
            <v-card class="mb-5">
                <v-toolbar color="teal" dark style="box-shadow:none;">
                    <v-toolbar-title>マイページ</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="mx-auto" style="width:30%;">
                        <v-img :src="loginInfo.user_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
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
            <v-card>
                <v-toolbar color="teal" dark style="box-shadow:none;">
                    <v-toolbar-title>マイルーム</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="pb-2 mx-auto" style="width:20%;">
                        <v-img :src="loginInfo.room_img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:75%;">
                        <v-select @change="onChangeRoom()" :radonly="getRoomLoading || onChangeRoomloading" :loading="getRoomLoading || onChangeRoomloading" v-model="selectedRoomId" color="teal" prepend-icon="mdi-home" label="部屋名" :items="rooms" item-value="room_id" item-text="room_name" dense></v-select>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <div class="d-flex pa-3">
                    <v-spacer></v-spacer>
                    <v-btn @click="openCreateRoomDialog('create')" class="mr-2">新規作成</v-btn>
                    <v-btn @click="openCreateRoomDialog('edit')" dark color="orange lighten-1">編集</v-btn>
                </div>
            </v-card>
        </v-form>

        <v-dialog v-model="createUserDialog" scrollable>
            <CreateUser mode="edit" @onCloseDialog="createUserDialog = false" v-if="createUserDialog" />
        </v-dialog>

        <v-dialog v-model="createRoomDialog" scrollable>
            <CreateRoom :mode="modeCreateRoomDialog" @getRoom="getRoom" @onCloseDialog="createRoomDialog = false" v-if="createRoomDialog" />
        </v-dialog>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    data() {
        return {
            selectedRoomId: 0,
            rooms: [],
            onChangeRoomloading: false,
            getRoomLoading: false,
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
                this.$cookies.remove("token");
                this.$store.commit("setLoginInfo", {});
                this.$router.push("/login");
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
            await this.$store.dispatch("setTodayTasks");
            this.onChangeRoomloading = false;
        },
        async getRoom() {
            this.getRoomLoading = true;
            await this.$axios
                .get(
                    `/api/room/read?token=${this.$store.state.loginInfo.token}`
                )
                .then((res) => {
                    this.rooms = res.data;
                })
                .catch((err) => {});
            this.selectedRoomId = this.loginInfo.room_id;
            this.getRoomLoading = false;
        },
    },
    async mounted() {
        await this.getRoom();
    },
};
</script>