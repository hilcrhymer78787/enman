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
    p {
        margin: 0;
    }
}
.v-card__title{
    background-color: #009688;
    color: white;
}
</style>