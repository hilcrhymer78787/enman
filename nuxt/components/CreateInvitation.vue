<template>
    <v-card class="mx-auto">
        <v-card-title>{{loginInfo.room_name}}へ招待</v-card-title>
        <v-divider></v-divider>
        <v-card-text style="min-height:150px;">
            <v-form v-model="noError" ref="form" class="pt-5">
                <v-text-field v-if="!successMessage" validate-on-blur @keyup.enter="CreateInvitation()" :rules="emailRules" required label="メールアドレス" placeholder="メールアドレス" prepend-inner-icon="mdi-email" outlined v-model="email" color="teal"></v-text-field>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
                <p v-if="successMessage" class="success_message">{{successMessage}}</p>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn v-if="!successMessage" :loading="loading" color="teal" dark @click="CreateInvitation()">招待する</v-btn>
            <v-btn v-if="successMessage" color="teal" dark @click="resetForm()">続けて招待</v-btn>
        </v-card-actions>

    </v-card>
</template>

<script lang="ts">
import Vue from "vue";
import { mapState } from "vuex";
import { vformType } from "@/types/vuetify/vform";
import { errorType } from "@/types/error";
import { apiInvitationCreateRequestType } from "@/types/api/invitation/create/request";
import { apiInvitationCreateResponseType } from "@/types/api/invitation/create/response";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
import { AxiosResponse, AxiosError } from "axios";
export default Vue.extend({
    data() {
        return {
            loading: false as boolean,
            noError: false as boolean,
            errorMessage: "" as string,
            successMessage: "" as string,
            email: "" as string,
            emailRules: [
                (v: string) => !!v || "メールアドレスは必須です",
                (v: string) =>
                    /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
        };
    },
    computed: {
        ...mapState({
            loginInfo: (state: any): apiUserBearerAuthenticationResponseType =>
                state.loginInfo,
        }),
    },
    methods: {
        async CreateInvitation() {
            this.errorMessage = "";
            this.successMessage = "";
            const form = this.$refs.form as vformType;
            form.validate();
            // バリデーションエラー
            if (!this.noError) {
                return;
            }
            this.loading = true;
            let apiParam: apiInvitationCreateRequestType = {
                email: this.email,
            };
            await this.$axios
                .post(`/api/invitation/create`, apiParam)
                .then((res: AxiosResponse<apiInvitationCreateResponseType>) => {
                    this.successMessage = res.data.successMessage;
                    this.$store.dispatch("setLoginInfoByToken");
                })
                .catch((err: AxiosError<errorType>) => {
                    console.error(err.response);
                    if (err.response?.data.errorMessage) {
                        this.errorMessage = err.response?.data.errorMessage;
                    } else {
                        this.errorMessage = "通信に失敗しました";
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        resetForm() {
            this.successMessage = "";
            this.email = "";
        },
    },
});
</script>
<style lang="scss" scoped>
.success_message {
    color: #009688;
    font-size: 16px;
    min-height: 70px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>