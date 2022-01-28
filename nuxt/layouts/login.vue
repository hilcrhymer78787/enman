<template>
    <div>
        <v-app v-if="loginInfo === false">
            <v-main>
                <v-container style="max-width:500px;">
                    <Nuxt />
                </v-container>
            </v-main>
        </v-app>
    </div>
</template>

<script lang="ts">
import { mapState } from "vuex";
import { apiUserBearerAuthenticationResponseType } from "@/types/api/user/bearerAuthentication/response";
export default {
    computed: {
        ...mapState({
            loginInfo: (state: any): apiUserBearerAuthenticationResponseType =>
                state.loginInfo,
        }),
    },
};
</script>