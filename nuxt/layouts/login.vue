<template>
    <v-app light>
        <v-main>
            <v-container class="login_container">
                <Nuxt />
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
export default {
    async middleware({ store, redirect }) {
        if (store.state.loginInfo.id) {
            redirect("/");
        }
        await store.dispatch("setLoginInfoByToken")
        if (store.state.loginInfo.id) {
            redirect("/");
        }
    },
};
</script>
<style lang="scss">
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
<style lang="scss" scoped>
.login_container {
    max-height: 500px;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>