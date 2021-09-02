<template>
    <v-card max-width="350" class="login-card mx-auto">
        <v-card-title>ログイン</v-card-title>
        <v-card-text>
            <v-form v-model="noError" ref="form">
                <v-text-field @keyup.enter="login" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="form.email" color="teal"></v-text-field>
                <v-text-field @keyup.enter="login" :rules="passwordRules" required label="パスワード" placeholder="パスワード" prepend-inner-icon="mdi-lock" :append-icon="passwordShow ? 'mdi-eye' : 'mdi-eye-off'" :type="passwordShow ? 'text' : 'password'" outlined v-model="form.password" @click:append="passwordShow = !passwordShow" color="teal"></v-text-field>
                <v-btn :loading="loading" color="teal" dark block x-large @click="login">ログイン</v-btn>
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