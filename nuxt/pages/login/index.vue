<template>
    <v-dialog :value="true" scrollable hide-overlay persistent no-click-animation>
        <v-card class="mx-auto">
            <v-card-title>ログイン</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-form v-model="noError" ref="form" class="pt-5">
                    <v-text-field validate-on-blur @keyup.enter="login()" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="teal" class="pt-5"></v-text-field>
                    <v-text-field validate-on-blur @keyup.enter="login()" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="teal"></v-text-field>
                </v-form>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
                <div v-if="isShowTestUser == 1" class="d-flex justify-end">
                    <v-btn @click="testAuthentication()">テストユーザーでログイン</v-btn>
                </div>
            </v-card-text>

            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn to="/login/newUser">新規ユーザー登録</v-btn>
                <v-btn :loading="loading" color="teal" dark @click="login()">ログイン</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script lang="ts">
import Vue from "vue";
import { mapState } from "vuex";

interface VForm extends Vue {
  validate(): boolean
}

export default Vue.extend({
    layout: "login",
    data() {
        return {
            isShowTestUser: process.env.IS_SHOW_TEST_USER,
            loading: false as boolean,
            noError: false as boolean,
            errorMessage: "" as string,
            form: {
                email: "" as string,
                password: "" as string,
            },
            emailRules: [
                (v: string): boolean | string =>
                    !!v || "メールアドレスは必須です",
                (v: string): boolean | string =>
                    /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
            passwordRules: [
                (v: string): boolean | string => !!v || "パスワードは必須です",
                (v: string): boolean | string =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordShow: false as boolean,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        async testAuthentication(): Promise<void> {
            this.loading = true;
            await this.$axios
                .get(`/api/user/test_authentication`)
                .then((res: any): void => {
                    this.$store.dispatch("setTokenRedirect", res.data.token);
                })
                .catch((err: any): void => {
                    if (err.response.data.errorMessage) {
                        this.errorMessage = err.response.data.errorMessage;
                    } else if (err.response && err.response.status == 429) {
                        alert(
                            "一定時間にアクセスが集中したため、しばらくアクセスできません"
                        );
                    } else {
                        alert("通信エラーです");
                    }
                })
                .finally((): void => {
                    this.loading = false;
                });
        },
        async login(): Promise<void> {
            const form = this.$refs.form as VForm
            form.validate()
            if (!this.noError) {
                return;
            }
            this.loading = true;
            this.errorMessage = "";
            await this.$axios
                .post(`/api/user/basic_authentication`, this.form)
                .then((res: any): void => {
                    this.$store.dispatch("setTokenRedirect", res.data.token);
                })
                .catch((err: any): void => {
                    if (err.response.data.errorMessage) {
                        this.errorMessage = err.response.data.errorMessage;
                    } else if (err.response && err.response.status == 429) {
                        alert(
                            "一定時間にアクセスが集中したため、しばらくアクセスできません"
                        );
                    } else {
                        alert("通信エラーです");
                    }
                })
                .finally((): void => {
                    this.loading = false;
                });
        },
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