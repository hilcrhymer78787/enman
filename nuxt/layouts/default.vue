<template>
    <v-app>
        <v-main>
            <v-container style="max-width:500px;">
                <Nuxt />
            </v-container>
        </v-main>
        <v-bottom-navigation app light fixed color="teal">
            <v-btn :style="`width:calc(100% / ${navs.length}); height:100%;background-color:white;`" v-for="(nav, i) in navs" :key="i" :to="nav.to" router light exact>
                <span>{{ nav.txt }}</span>
                <v-icon>{{ nav.icon }}</v-icon>
            </v-btn>
        </v-bottom-navigation>
    </v-app>
</template>

<script>
export default {
    async middleware({ store, redirect }) {
        if (store.state.loginInfo.id) {
            return;
        }
        await store.dispatch("setLoginInfoByToken");
        if (!store.state.loginInfo.id) {
            redirect("/login");
        }
    },
    data() {
        return {
            navs: [
                // https://materialdesignicons.com/
                {
                    icon: "mdi-playlist-check ",
                    txt: "毎日タスク",
                    to: "/",
                },
                {
                    icon: "mdi-calendar-check",
                    txt: "カレンダー",
                    to: "/calendar",
                },
                {
                    icon: "mdi-account",
                    txt: "マイページ",
                    to: "/mypage",
                },
            ],
        };
    },
    methods: {
        mountedFunc() {
            this.$store.dispatch("setUsers");
            // await this.$store.dispatch("setWorks");
        },
    },
    mounted() {
        this.mountedFunc();
    },
};
</script>
<style lang="scss">
.v-dialog {
    max-width: 476px !important;
    // height: 90%;
}
.v-application {
    ul {
        padding: 0;
    }
    li {
        list-style: none;
    }
    a {
        color: teal !important;
        text-decoration: none;
    }
    p {
        margin: 0;
    }
}
</style>