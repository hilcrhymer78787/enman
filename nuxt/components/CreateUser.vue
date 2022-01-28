<template>
    <v-card class="mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規ユーザー登録</span>
            <span v-if="mode == 'edit'">ユーザー編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">
                <div class="mb-5 d-flex align-center justify-center">
                    <div @click="imagePickerDialog = true" class="mr-5 img_frame" style="width:30%;">
                        <v-img v-if="file" :src="uploadedImage" aspect-ratio="1" class="rounded-circle"></v-img>
                        <PartsImg v-else :src="form.user_img" />
                    </div>
                    <v-btn @click="$refs.input.click()">
                        <v-icon>mdi-upload</v-icon>
                    </v-btn>
                    <input ref="input" class="d-none" type="file" accept="image/*" @change="fileSelected">
                </div>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="nameRules" required label="名前" placeholder="名前" prepend-inner-icon="mdi-account" outlined v-model="form.name" color="teal"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="teal"></v-text-field>
                <v-text-field v-if="passwordEdit" validate-on-blur @keyup.enter="login" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="teal"></v-text-field>
                <v-text-field v-if="passwordEdit" validate-on-blur @keyup.enter="login" :rules="passwordAgainRules" required label="パスワードの確認" placeholder="パスワードの確認" prepend-inner-icon="mdi-lock" :append-icon="passwordAgainShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordAgainShow ? 'text' : 'passwordAgain'" outlined v-model="form.passwordAgain" @click:append="passwordAgainShow = !passwordAgainShow" color="teal"></v-text-field>
                <div v-else class="d-flex justify-end">
                    <v-btn @click="passwordEdit = true">パスワードを変更する</v-btn>
                </div>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <!-- <v-btn v-if="mode=='edit'" :loading="loading" color="error" dark @click="deleteAccount()">アカウント削除</v-btn> -->
            <v-spacer></v-spacer>
            <v-btn v-if="mode=='create'" to="/login">ログイン画面へ</v-btn>
            <v-btn v-if="mode=='edit'" @click="$emit('onCloseDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn :loading="loading" color="teal" dark @click="login()">登録</v-btn>
        </v-card-actions>

        <v-dialog v-model="imagePickerDialog" scrollable>
            <ImagePicker @onSelectedImg="onSelectedImg" @onCloseDialog="imagePickerDialog = false" v-if="imagePickerDialog" />
        </v-dialog>

    </v-card>
</template>

<script lang="ts">
import Vue from "vue";
import { mapState } from "vuex";
import moment from "moment";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
import { vformType } from "@/types/vuetify/vform";
export default Vue.extend({
    props: {
        mode: String,
    },
    data() {
        return {
            uploadedImage: "" as string,
            file: "" as any,
            loading: false as boolean,
            noError: false as boolean,
            errorMessage: "" as string,
            imagePickerDialog: false as boolean,
            passwordEdit: true as boolean,
            form: {
                id: 0 as number,
                name: "" as string,
                email: "" as string,
                password: "" as string,
                passwordAgain: "" as string,
                user_img: "https://picsum.photos/500/300?image=40" as string,
                token: "" as string,
            } as any,
            nameRules: [
                (v: string): string | boolean => !!v || "名前は必須です",
            ],
            emailRules: [
                (v: string): string | boolean =>
                    !!v || "メールアドレスは必須です",
                (v: string): string | boolean =>
                    /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
            passwordRules: [
                (v: string): string | boolean => !!v || "パスワードは必須です",
                (v: string): string | boolean =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordAgainRules: [
                (v: string): string | boolean => !!v || "パスワードは必須です",
                (v: string): string | boolean =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordShow: false as boolean,
            passwordAgainShow: false as boolean,
        };
    },
    computed: {
        ...mapState({
            loginInfo: (state: any): apiUserBearerAuthenticationResponseType =>
                state.loginInfo,
        }),
        isMatchPassword(): boolean {
            return this.form.password == this.form.passwordAgain;
        },
    },
    methods: {
        fileSelected(e: any) {
            this.file = e.target.files[0];
            this.$set(
                this.form,
                "user_img",
                moment().format("YYYYMMDDHHmmss") + this.file.name
            );
            let reader: any = new FileReader();
            reader.onload = (e: any) => {
                this.uploadedImage = e.target.result;
            };
            reader.readAsDataURL(this.file);
        },
        async login(): Promise<void> {
            this.errorMessage = "";
            const form = this.$refs.form as vformType;
            form.validate();
            // バリデーションエラー
            if (!this.noError) {
                return;
            }
            // パスワードの不一致
            if (!this.isMatchPassword) {
                this.errorMessage = "パスワードが一致しません";
                return;
            }
            // ログインAPI
            let postData = new FormData();
            if (this.file) {
                postData.append("file", this.file);
            }
            Object.keys(this.form).forEach((key: string) => {
                postData.append(key, this.form[key]);
            });
            this.errorMessage = "";
            this.loading = true;
            await this.$axios
                .post(`/api/user/create`, postData)
                .then((res: any) => {
                    if (this.mode == "create") {
                        this.$store.dispatch(
                            "setTokenRedirect",
                            res.data.token
                        );
                    } else {
                        this.$store.dispatch("setLoginInfoByToken");
                        this.$emit("onCloseDialog");
                    }
                })
                .catch((err: any) => {
                    if (err.response.data.errorMessage) {
                        this.errorMessage = err.response.data.errorMessage;
                    } else {
                        this.errorMessage = "通信に失敗しました";
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        onSelectedImg(n: number) {
            this.$set(
                this.form,
                "user_img",
                `https://picsum.photos/500/300?image=${n}`
            );
        },
        async deleteAccount(): Promise<void> {
            if (
                !confirm(
                    `「${this.loginInfo.name}」のアカウント情報を全て削除しますか？`
                )
            ) {
                return;
            }
            if (!confirm(`本当によろしいですか？`)) {
                return;
            }
            if (!confirm(`知らないですよ？`)) {
                return;
            }
            // アカウント削除API
            this.loading = true;
            await this.$axios
                .delete(`/api/user/delete`)
                .then(() => {
                    this.$emit("onCloseDialog");
                })
                .catch(() => {
                    alert("通信に失敗しました");
                })
                .finally(() => {
                    // ログアウト
                    this.$store.dispatch("logout");
                    this.loading = false;
                });
        },
    },
    mounted() {
        if (this.mode == "edit") {
            this.passwordEdit = false;
            this.$set(this.form, "id", this.loginInfo.id);
            this.$set(this.form, "name", this.loginInfo.name);
            this.$set(this.form, "email", this.loginInfo.email);
            this.$set(this.form, "password", "");
            this.$set(this.form, "passwordAgain", "");
            this.$set(this.form, "user_img", this.loginInfo.user_img);
            this.$set(this.form, "img_oldname", this.loginInfo.user_img);
        }
    },
    beforeDestroy() {
        this.file = null;
    },
});
</script>
<style lang="scss" scoped>
.error_message {
    color: #ff5252;
    font-size: 12px;
    margin-top: -27px;
    margin-left: 12px;
}
</style>