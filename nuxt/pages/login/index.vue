<template>
    <v-dialog :value="true" scrollable hide-overlay persistent no-click-animation>
        <v-card max-width="350" class="mx-auto">
            <v-card-title>ログイン</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-form v-model="noError" ref="form" class="pt-5">
                    <v-text-field validate-on-blur @keyup.enter="login" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="teal" class="pt-5"></v-text-field>
                    <v-text-field validate-on-blur @keyup.enter="login" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="teal"></v-text-field>
                </v-form>
            </v-card-text>

            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn to="/login/newUser">新規ユーザー登録</v-btn>
                <v-btn :loading="loading" color="teal" dark @click="login">ログイン</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapState } from "vuex";
export default {
    layout: "login",
    data() {
        return {
            loading: false,
            noError: false,
            form: {
                email: "",
                password: "",
            },
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
            passwordShow: false,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        async login() {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.loading = true;
            await this.$store.dispatch("setLoginInfo", this.form);
            if (this.loginInfo.id) {
                this.$router.push("/");
            }
            this.loading = false;
        },
    },
};
</script>