
<template>
    <div>
        <v-form ref="form" v-model="noError">
            <v-card>
                <v-toolbar color="teal" dark style="box-shadow:none;">
                    <v-toolbar-title>マイページ</v-toolbar-title>
                </v-toolbar>
                <v-card-text class="d-flex align-center">
                    <div class="pb-2" style="width:30%;">
                        <v-img @click="onClickMainImg" :v-ripple="edit" :src="editloginInfo.img" aspect-ratio="1" class="rounded-circle main_img"></v-img>
                    </div>
                    <v-spacer></v-spacer>
                    <div class="pt-2" style="width:65%;">
                        <v-text-field :rules="[v => !!v || 'Item is required']" :clearable="edit" color="teal" prepend-icon="mdi-account" :readonly="!edit" label="名前" v-model="editloginInfo.name"></v-text-field>
                        <v-text-field :rules="[v => !!v || 'Item is required']" :clearable="edit" color="teal" prepend-icon="mdi-email" :readonly="!edit" label="メールアドレス" v-model="editloginInfo.email"></v-text-field>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <div class="d-flex pa-3">
                    <v-spacer></v-spacer>
                    <v-btn @click="onCloseEdit()" v-if="edit" class="mr-2">編集取消</v-btn>
                    <v-btn @click="onSubmit" v-if="edit" dark color="teal lighten-1">確定</v-btn>
                    <v-btn @click="logout()" v-if="!edit" class="mr-2">ログアウト</v-btn>
                    <v-btn @click="edit = true" v-if="!edit" dark color="orange lighten-1">編集</v-btn>
                </div>
            </v-card>
        </v-form>

        <v-dialog v-model="dialog" scrollable>
            <v-card v-if="dialog">
                <v-toolbar color="teal" dark>
                    <v-toolbar-title>アイコン画像を選択してください</v-toolbar-title>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text class="pa-3" style="min-height:30vh;">
                    <ul>
                        <li v-for="n in 99" :key="n">
                            <v-card v-ripple>
                                <v-img @click="onSelectedImg(n)" :src="`https://picsum.photos/500/300?image=${n}`"></v-img>
                            </v-card>
                        </li>
                    </ul>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="dialog = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapState } from "vuex";

export default {
    data() {
        return {
            dialog: false,
            noError: false,
            edit: false,
            editloginInfo: {
                img: "https://picsum.photos/500/300?image=20",
            },
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        onClickMainImg() {
            if (!this.edit) {
                return;
            }
            this.dialog = true;
        },
        onSelectedImg(n) {
            this.editloginInfo.img = `https://picsum.photos/500/300?image=${n}`;
            this.dialog = false;
        },
        onSubmit() {
            this.$refs.form.validate();
        },
        logout() {
            if (confirm("ログアウトしますか？")) {
                this.$cookies.remove("token");
                this.$store.commit("setLoginInfo", {});
                this.$router.push("/login");
            }
        },
        setLoginInfo() {
            this.$set(this.editloginInfo, "name", this.loginInfo.name);
            this.$set(this.editloginInfo, "email", this.loginInfo.email);
        },
        onCloseEdit() {
            this.setLoginInfo();
            this.edit = false;
        },
    },
    mounted() {
        this.setLoginInfo();
    },
};
</script>

<style lang="scss" scoped>
.main_img {
    border: 3px solid #009688;
}
ul {
    padding: 0 !important;
    display: flex;
    flex-wrap: wrap;
    li {
        list-style: none;
        width: 31%;
        margin-right: 3.5%;
        margin-bottom: 3.5%;
        &:nth-child(3n) {
            margin-right: 0;
        }
    }
}
</style>