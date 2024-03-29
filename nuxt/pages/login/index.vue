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
import { apiUserTestAuthenticationResponseType } from "@/types/api/user/testAuthentication/response";
import { apiUserBasicAuthenticationResponseType } from "@/types/api/user/basicAuthentication/response";
import { apiUserBasicAuthenticationRequestType } from "@/types/api/user/basicAuthentication/request";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
import { errorType } from "@/types/error";
import { vformType } from "@/types/vuetify/vform";
import { AxiosRequestConfig, AxiosResponse, AxiosError } from "axios";
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
                (v: string) => !!v || "メールアドレスは必須です",
                (v: string) =>
                    /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
            passwordRules: [
                (v: string) => !!v || "パスワードは必須です",
                (v: string) =>
                    (v && v.length >= 8) ||
                    "パスワードは8桁以上で設定してください",
            ],
            passwordShow: false as boolean,
        };
    },
    methods: {
        async testAuthentication() {
            this.loading = true;
            const requestConfig: AxiosRequestConfig = {
                url: `/api/user/test_authentication`,
                method: "GET",
            };
            await this.$axios(requestConfig)
                .then(
                    (
                        res: AxiosResponse<apiUserTestAuthenticationResponseType>
                    ) => {
                        this.$store.dispatch(
                            "setTokenRedirect",
                            res.data.token
                        );
                    }
                )
                .catch((err: AxiosError<errorType>) => {
                    if (err.response?.data.errorMessage) {
                        this.errorMessage = err.response?.data.errorMessage;
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        async login() {
            const form = this.$refs.form as vformType;
            form.validate();
            this.$nextTick(async () => {
                if (!this.noError) {
                    return;
                }
                this.loading = true;
                this.errorMessage = "";
                let apiParam: apiUserBasicAuthenticationRequestType = {
                    email: this.form.email,
                    password: this.form.password,
                };
                const requestConfig: AxiosRequestConfig = {
                    url: `/api/user/basic_authentication`,
                    method: "POST",
                    data: apiParam,
                };
                await this.$axios(requestConfig)
                    .then(
                        (
                            res: AxiosResponse<apiUserBasicAuthenticationResponseType>
                        ) => {
                            this.$store.dispatch(
                                "setTokenRedirect",
                                res.data.token
                            );
                        }
                    )
                    .catch((err: AxiosError) => {
                        if (err.response?.data.errorMessage) {
                            this.errorMessage = err.response?.data.errorMessage;
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            });
        },
    },
});
</script>