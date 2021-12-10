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

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            loading: false,
            noError: false,
            errorMessage: "",
            successMessage: "",
            email: "",
            emailRules: [
                (v) => !!v || "メールアドレスは必須です",
                (v) => /.+@.+\..+/.test(v) || "正しい形式で入力してください",
            ],
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        async CreateInvitation() {
            this.errorMessage = "";
            this.successMessage = "";
            this.$refs.form.validate();
            // バリデーションエラー
            if (!this.noError) {
                return;
            }
            this.loading = true;
            await this.$axios
                .post(`/api/invitation/create?email=${this.email}`)
                .then((res) => {
                        this.successMessage = res.data;
                })
                .catch((err) => {
                    if (err.response.data.errorMessage) {
                        this.errorMessage = err.response.data.errorMessage;
                    } else {
                        this.errorMessage = "通信に失敗しました";
                    }
                });
            await this.$store.dispatch("setLoginInfoByToken");
            this.loading = false;
        },
        onSelectedImg(n) {
            this.$set(
                this.form,
                "invitation_img",
                `https://picsum.photos/500/300?image=${n}`
            );
        },
        resetForm() {
            this.successMessage = "";
            this.email = "";
        },
    },
    mounted() {
        // this.$set(this.form, "invitation_id", this.loginInfo.invitation_id);
        // this.$set(this.form, "invitation_name", this.loginInfo.invitation_name);
        // this.$set(this.form, "invitation_img", this.loginInfo.invitation_img);
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
.success_message {
    color: #009688;
    font-size: 16px;
    min-height: 70px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>