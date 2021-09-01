<template>
    <v-card max-width="350" class="login-card mx-auto">
        <v-card-title>ログイン</v-card-title>
        <v-card-text>
            <v-alert text v-show="error">
                メールアドレスかパスワードが異なります。
            </v-alert>
            <v-form v-model="noError" ref="form">
                <v-text-field :rules="valid ? emailRules : []" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-account-circle" outlined v-model="form.email"></v-text-field>

                <v-text-field :rules="valid ? passwordRules : []" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow"></v-text-field>

                <v-btn block x-large @click="login">ログイン</v-btn>
            </v-form>
        </v-card-text>
        <pre>{{noError}}</pre>
    </v-card>
</template>

<script>
import { mapState } from "vuex";
export default {
    layout: "login",
    data() {
        return {
            noError: false,
            valid: false,
            form: {
                email: "",
                password: "",
            },
            emailRules: [
                (v) => !!v || "E-mail is required",
                (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
            ],
            passwordRules: [
                (v) => !!v || "Name is required",
                (v) =>
                    (v && v.length >= 8) ||
                    "Name must be less than 10 characters",
            ],
            passwordShow: false,
            error: false,
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        async login() {
            this.valid = true;
            this.$refs.form.validate();
            console.log(this.noError)
            if (this.noError) {
                await this.$store.dispatch("setLoginInfo", this.form);
                if (this.loginInfo.id) {
                    this.$router.push("/");
                }
            }
        },
    },
};
</script>