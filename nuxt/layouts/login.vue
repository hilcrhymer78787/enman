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

<script>
import { mapState } from "vuex";
export default {
    computed: {
        ...mapState(["loginInfo"]),
    },
};
</script>