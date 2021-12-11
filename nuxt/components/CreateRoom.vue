<template>
    <v-card class="mx-auto">
        <v-card-title>
            <span v-if="mode == 'create'">新規ルーム作成</span>
            <span v-if="mode == 'edit'">ルーム編集</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-form v-model="noError" ref="form" class="pt-5">

                <div class="mb-5 d-flex align-center justify-center">
                    <div @click="imagePickerDialog = true" class="mr-5 img_frame" style="width:30%;">
                        <v-img v-if="file" :src="uploadedImage" aspect-ratio="1" class="rounded-circle"></v-img>
                        <PartsImg v-else :src="form.room_img" />
                    </div>
                    <v-btn @click="$refs.input.click()">
                        <v-icon>mdi-upload</v-icon>
                    </v-btn>
                    <input ref="input" class="d-none" type="file" accept="image/*" @change="fileSelected">
                </div>

                <v-text-field validate-on-blur :rules="nameRules" required label="部屋名" placeholder="部屋名" prepend-inner-icon="mdi-home" outlined v-model="form.room_name" color="teal"></v-text-field>
                <p v-if="errorMessage && noError" class="error_message mb-2">{{errorMessage}}</p>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn @click="createInvitationDialog = true" v-if="mode == 'edit'">招待</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="$emit('onCloseDialog')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-btn :loading="loading" color="teal" dark @click="createRoom()">登録</v-btn>
        </v-card-actions>

        <v-dialog v-model="imagePickerDialog" scrollable>
            <ImagePicker @onSelectedImg="onSelectedImg" @onCloseDialog="imagePickerDialog = false" v-if="imagePickerDialog" />
        </v-dialog>

        <v-dialog v-model="createInvitationDialog" scrollable>
            <CreateInvitation @onCloseDialog="createInvitationDialog = false" v-if="createInvitationDialog" />
        </v-dialog>

    </v-card>
</template>

<script>
import { mapState } from "vuex";
import moment from "moment";
export default {
    props: ["mode"],
    data() {
        return {
            uploadedImage: null,
            file: null,
            loading: false,
            noError: false,
            errorMessage: "",
            imagePickerDialog: false,
            createInvitationDialog: false,
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
        fileSelected(e) {
            this.file = e.target.files[0];
            this.$set(
                this.form,
                "room_img",
                moment().format("YYYYMMDDHHmmss") + this.file.name
            );
            let reader = new FileReader();
            reader.onload = (e) => {
                this.uploadedImage = e.target.result;
            };
            reader.readAsDataURL(this.file);
        },
        async createRoom() {
            this.errorMessage = "";
            this.$refs.form.validate();
            // バリデーションエラー
            if (!this.noError) {
                return;
            }
            let postData = new FormData();
            if(this.file){
                postData.append("file", this.file);
            }
            Object.keys(this.form).forEach((key) => {
                postData.append(key, this.form[key]);
            });
            this.loading = true;
            await this.$axios
                .post(`/api/room/create`, postData)
                .catch((err) => {
                    alert("通信に失敗しました");
                });
            await this.$store.dispatch("setLoginInfoByToken");
            this.$emit("onCloseDialog");
            this.loading = false;
        },
        onSelectedImg(n) {
            this.$set(
                this.form,
                "room_img",
                `https://picsum.photos/500/300?image=${n}`
            );
        },
    },
    mounted() {
        if (this.mode == "edit") {
            this.$set(this.form, "room_id", this.loginInfo.room_id);
            this.$set(this.form, "room_name", this.loginInfo.room_name);
            this.$set(this.form, "room_img", this.loginInfo.room_img);
            this.$set(this.form, "img_oldname", this.loginInfo.room_img);
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