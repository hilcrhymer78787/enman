<template>
    <v-card max-width="350" class="login-card mx-auto">
        <v-card-title>新規ユーザー登録</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form">
                <v-text-field validate-on-blur @keyup.enter="login" :rules="nameRules" required label="名前" placeholder="名前" prepend-inner-icon="mdi-account" outlined v-model="form.name" color="teal"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="teal"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="teal"></v-text-field>
                <v-text-field validate-on-blur @keyup.enter="login" :rules="passwordAgainRules" required label="パスワードの確認" placeholder="パスワードの確認" prepend-inner-icon="mdi-lock" :append-icon="passwordAgainShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordAgainShow ? 'text' : 'passwordAgain'" outlined v-model="form.passwordAgain" @click:append="passwordAgainShow = !passwordAgainShow" color="teal"></v-text-field>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
                <v-btn :loading="loading" color="teal" dark block x-large @click="login" class="mb-5">登録</v-btn>
                <div class="text-center">
                    <NuxtLink to="/login">ログイン画面へ</NuxtLink>
                </div>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
import { mapState } from "vuex";
export default {
    layout: "login",
    data() {
        return {
            loading: false,
            noError: false,
            errorMessage: "",
            form: {
                name: "",
                email: "",
                password: "",
                passwordAgain: "",
            },
            nameRules: [(v) => !!v || "名前は必須です"],
            emailRules: [
                (v) => !!v || "メールアドレスは必須です",
                (v) => /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
            passwordRules: [
                (v) => !!v || "パスワードは必須です",
                (v) =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordAgainRules: [
                (v) => !!v || "パスワードは必須です",
                (v) =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordShow: false,
            passwordAgainShow: false,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
        isMatchPassword() {
            return this.form.password == this.form.passwordAgain;
        },
    },
    methods: {
        async login() {
            this.errorMessage = "";
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            if (!this.isMatchPassword) {
                this.errorMessage = "パスワードが一致しません";
                return;
            }
            this.loading = true;
            await this.$axios
                .post(
                    `/api/user/create?name=${this.form.name}&email=${this.form.email}&password=${this.form.password}`
                )
                .then((res) => {
                    this.errorMessage = "";
                    if (res.data.errorMessage) {
                        this.errorMessage = res.data.errorMessage;
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
            if (!this.errorMessage) {
                await this.$store.dispatch("setLoginInfo", this.form);
            }
            if (this.loginInfo.id) {
                this.$router.push("/");
            }
            this.loading = false;
        },
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