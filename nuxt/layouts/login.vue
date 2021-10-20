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
<style lang="scss">
.v-dialog {
    max-width: 476px !important;
}
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
    .v-btn:before {
        content: none;
    }
}
.v-card__title{
    background-color: #009688;
    color: white;
}

</style>