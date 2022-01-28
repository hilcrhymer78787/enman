
<template>
    <div>
        <v-form ref="form">
            <v-card class="mb-5">
                <v-card-title>マイページ</v-card-title>
                <v-card-text class="d-flex align-center pa-4">
                    <div class="mx-auto img_frame" style="width:30%;">
                        <PartsImg :src="loginInfo.user_img" />
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
                <v-card-title>マイルーム</v-card-title>
                <v-card-text class="d-flex align-center pa-4">
                    <div class="pb-2 mx-auto img_frame" style="width:20%;">
                        <PartsImg :src="loginInfo.room_img" />
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:75%;">
                        <v-select @change="onChangeRoom()" :readonly="onChangeRoomloading" :loading="onChangeRoomloading" v-model="selectedRoomId" color="teal" prepend-icon="mdi-home" label="部屋名" :items="loginInfo.rooms" item-value="room_id" item-text="room_name" class="my-5" dense></v-select>
                        <MypageRoomMateNames :users="loginInfo.room_joined_users" :prependIcon="'mdi-account-group'" :label="'ルームメイト'" />
                        <MypageRoomMateNames :users="loginInfo.room_inviting_users" v-if="loginInfo.room_inviting_users.length" :prependIcon="'mdi-account-plus'" :label="'招待中'" />
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
            <v-card-title>招待されている部屋</v-card-title>
            <div v-for="(room,roomIndex) in loginInfo.invited_rooms" :key="roomIndex">
                <v-card-text class="d-flex align-center pb-0 pt-4">
                    <div class="pb-2 mx-auto img_frame" style="width:20%;">
                        <PartsImg :src="room.room_img" />
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:75%;">
                        <v-text-field v-model="room.room_name" readonly color="teal" prepend-icon="mdi-home" label="部屋名" class="my-3" dense></v-text-field>
                        <MypageRoomMateNames :users="room.joined_users" :prependIcon="'mdi-account-group'" :label="'ルームメイト'" />
                        <MypageRoomMateNames :users="room.inviting_users" v-if="room.inviting_users.length" :prependIcon="'mdi-account-plus'" :label="'招待中'" />
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

<script lang="ts">
import Vue from "vue";
import { mapState } from "vuex";
import { AxiosResponse, AxiosError } from "axios";
import { apiInvitationUpdateRequestType } from "@/types/api/invitation/update/request";
import { apiInvitationDeleteRequestType } from "@/types/api/invitation/delete/request";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
export default Vue.extend({
    data() {
        return {
            selectedRoomId: 0 as number,
            onChangeRoomloading: false as boolean,
            joinRoomloadings: [] as boolean[],
            rejectRoomloadings: [] as boolean[],
            createUserDialog: false as boolean,
            modeCreateRoomDialog: false as boolean | string,
            createRoomDialog: false as boolean,
        };
    },
    computed: {
        ...mapState({
            loginInfo: (state: any): apiUserBearerAuthenticationResponseType =>
                state.loginInfo,
        }),
    },
    methods: {
        logout(): void {
            if (confirm("ログアウトしますか？")) {
                this.$store.dispatch("logout");
            }
        },
        openCreateRoomDialog(mode: string): void {
            this.modeCreateRoomDialog = mode;
            this.createRoomDialog = true;
        },
        async onChangeRoom(): Promise<void> {
            this.onChangeRoomloading = true;
            await this.$axios
                .put(`/api/user/update/room_id?room_id=${this.selectedRoomId}`)
                .then(async (res: AxiosResponse): Promise<void> => {
                    await this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err: AxiosError) => {
                    alert("通信に失敗しました");
                })
                .finally(() => {
                    this.onChangeRoomloading = false;
                });
        },
        async joinRoom(invitationId: number, roomIndex: number): Promise<void> {
            this.$set(this.joinRoomloadings, roomIndex, true);
            let apiParam: apiInvitationUpdateRequestType = {
                invitation_id: invitationId,
            };
            await this.$axios
                .put(`/api/invitation/update`, apiParam)
                .then(async (res: AxiosResponse): Promise<void> => {
                    await this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err: AxiosError) => {
                    alert("通信に失敗しました");
                })
                .finally(() => {
                    this.$set(this.joinRoomloadings, roomIndex, false);
                });
        },
        async rejectRoom(
            invitationId: number,
            roomIndex: number,
            roomName: string
        ): Promise<void> {
            if (!confirm(`「${roomName}」への招待を拒否しますか？`)) {
                return;
            }
            this.$set(this.rejectRoomloadings, roomIndex, true);
            let apiParam: apiInvitationDeleteRequestType = {
                invitation_id: invitationId,
            };
            await this.$axios
                .delete(`/api/invitation/delete`, { data: apiParam })
                .then(async (res: AxiosResponse): Promise<void> => {
                    await this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err: AxiosError) => {
                    alert("通信に失敗しました");
                })
                .finally((): void => {
                    this.$set(this.rejectRoomloadings, roomIndex, false);
                });
        },
    },
    watch: {
        loginInfo(): void {
            this.selectedRoomId = this.loginInfo.room_id;
        },
    },
    mounted(): void {
        this.selectedRoomId = this.loginInfo.room_id;
    },
});
</script>