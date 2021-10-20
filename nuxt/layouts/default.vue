<template>
    <div>
        <v-app v-if="loginInfo">
            <v-main>
                <v-container style="max-width:500px;">
                    <Nuxt />
                </v-container>
            </v-main>
            <v-bottom-navigation app light fixed color="teal">
                <v-btn :style="`width:calc(100% / 3); height:100%;background-color:white;`" to="/" router light exact>
                    <span>毎日タスク</span>
                    <v-badge :value="false" color="teal" offset-x="5" offset-y="15">
                        <v-icon>mdi-playlist-check</v-icon>
                    </v-badge>
                </v-btn>
                <v-btn :style="`width:calc(100% / 3); height:100%;background-color:white;`" to="/calendar" router light exact>
                    <span>カレンダー</span>
                    <v-badge :value="false" color="teal" offset-x="5" offset-y="15">
                        <v-icon>mdi-calendar-check</v-icon>
                    </v-badge>
                </v-btn>
                <v-btn :style="`width:calc(100% / 3); height:100%;background-color:white;`" to="/mypage" router light exact>
                    <span>マイページ</span>
                    <v-badge :value="loginInfo.invited_rooms.length" color="teal" offset-x="5" offset-y="15" :content="loginInfo.invited_rooms.length">
                        <v-icon>mdi-account</v-icon>
                    </v-badge>
                </v-btn>
            </v-bottom-navigation>
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
    // height: 90%;
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