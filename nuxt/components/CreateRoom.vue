<template>
    <v-card max-width="350" class="login-card mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規ルーム作成</span>
            <span v-if="mode == 'edit'">ルーム編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <v-img @click="imagePickerDialog = true" :src="form.room_img" aspect-ratio="1" style="width:30%;" class="rounded-circle main_img mb-5 mx-auto"></v-img>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="nameRules" required label="部屋名" placeholder="部屋名" prepend-inner-icon="mdi-home" outlined v-model="form.room_name" color="teal"></v-text-field>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <!-- <v-btn v-if="mode=='edit'" :loading="loading" color="error" dark @click="deleteAccount()">アカウント削除</v-btn> -->
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">CLOSE</v-btn>
            <v-btn :loading="loading" color="teal" dark @click="login()">登録</v-btn>
        </v-card-actions>

        <v-dialog v-model="imagePickerDialog" scrollable>
            <ImagePicker @onSelectedImg="onSelectedImg" @onCloseDialog="imagePickerDialog = false" v-if="imagePickerDialog" />
        </v-dialog>

    </v-card>
</template>

<script>
import { mapState } from "vuex";
export default {
    props: ["mode"],
    data() {
        return {
            loading: false,
            noError: false,
            errorMessage: "",
            imagePickerDialog: false,
            form: {
                room_id: 0,
                room_name: "",
                room_img: "https://picsum.photos/500/300?image=40",
            },
            nameRules: [(v) => !!v || "部屋名は必須です"],
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        async login() {
            this.errorMessage = "";
            this.$refs.form.validate();
            // バリデーションエラー
            if (!this.noError) {
                return;
            }
            // ログインAPI
            this.loading = true;
            await this.$axios
                .post(
                    `/api/room/create?token=${this.loginInfo.token}&room_id=${this.form.room_id}&room_name=${this.form.room_name}&room_img=${this.form.room_img}`
                )
                .then((res) => {
                    console.log(res.data);
                    this.$emit("onCloseDialog");
                })
                .catch((err) => {
                    alert('通信に失敗しました')
                });
            await this.$store.dispatch("setLoginInfoByToken")
            this.loading = false;
        },
        onSelectedImg(n) {
            console.log(n);
            this.$set(
                this.form,
                "room_img",
                `https://picsum.photos/500/300?image=${n}`
            );
        },
        // async deleteAccount() {
        //     if (
        //         !confirm(
        //             `「${this.loginInfo.name}」のアカウント情報を全て削除しますか？`
        //         )
        //     ) {
        //         return;
        //     }
        //     if (!confirm(`本当によろしいですか？`)) {
        //         return;
        //     }
        //     if (!confirm(`知らないですよ？`)) {
        //         return;
        //     }
        //     // アカウント削除API
        //     this.loading = true;
        //     await this.$axios
        //         .delete(`/api/user/delete?token=${this.form.token}`)
        //         .then((res) => {
        //             console.log(res.data);
        //             this.$emit("onCloseDialog");
        //         })
        //         .catch((err) => {
        //             console.log(err);
        //         });
        //     // ログアウト
        //     this.$cookies.remove("token");
        //     this.$store.commit("setLoginInfo", {});
        //     this.$router.push("/login");
        //     this.loading = false;
        // },
    },
    mounted() {
        if (this.mode == "edit") {
            this.$set(this.form, "room_id", this.loginInfo.room_id);
            this.$set(this.form, "room_name", this.loginInfo.room_name);
            this.$set(this.form, "room_img", this.loginInfo.room_img);
        }
    },
};
</script>
<style lang="scss" scoped>
.error_message {
    color: #ff5252;
    font-size: 12px;
    margin-top: -27px;
    margin-left: 12px;
}
</style>