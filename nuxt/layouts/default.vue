<template>
    <div>
        <v-app v-if="loginInfo">

            <v-navigation-drawer v-if="!$vuetify.breakpoint.xs" width="200px" app permanent>
                <v-list-item two-line>
                    <v-list-item-avatar>
                        <PartsImg :src="loginInfo.user_img" />
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>{{loginInfo.name}}</v-list-item-title>
                        <v-list-item-subtitle>{{loginInfo.room_name}}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-divider></v-divider>
                <v-list nav dense>
                    <v-list-item v-for="nav in navs" :key="nav.ttl" :to="nav.to" active-class="hoge" router light>
                        <v-list-item-icon>
                            <v-badge :value="nav.badgeValue" :content="nav.badgeContent" color="teal" offset-x="5" offset-y="15">
                                <v-icon>{{nav.icon}}</v-icon>
                            </v-badge>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ nav.ttl }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-main>
                <v-container style="max-width:500px;">
                    <Nuxt />
                </v-container>
            </v-main>

            <v-bottom-navigation v-if="$vuetify.breakpoint.xs" app light fixed color="teal">
                <v-btn v-for="(nav,i) in navs" :key="i" :style="`width:calc(100% / 3); height:100%;background-color:white;`" :to="nav.to" router light>
                    <span>{{nav.ttl}}</span>
                    <v-badge :value="nav.badgeValue" :content="nav.badgeContent" color="teal" offset-x="5" offset-y="15">
                        <v-icon>{{nav.icon}}</v-icon>
                    </v-badge>
                </v-btn>
            </v-bottom-navigation>

        </v-app>
    </div>
</template>

<script lang="ts">
import Vue from 'vue'
import { mapState } from "vuex";
export type navType = {
    ttl: string;
    icon: string;
    to: string;
    badgeValue: number;
    badgeContent: number;
};
export default Vue.extend({
    computed: {
        ...mapState(["loginInfo"]),
        navs(): navType[] {
            return [
                {
                    ttl: "タスク",
                    icon: "mdi-playlist-check",
                    to: "/task",
                    badgeValue: 0,
                    badgeContent: 0,
                } as navType,
                {
                    ttl: "カレンダー",
                    icon: "mdi-calendar-check",
                    to: "/calendar",
                    badgeValue: 0,
                    badgeContent: 0,
                } as navType,
                {
                    ttl: "統計",
                    icon: "mdi-chart-bar",
                    to: "/analytics",
                    badgeValue: 0,
                    badgeContent: 0,
                } as navType,
                {
                    ttl: "マイページ",
                    icon: "mdi-account",
                    to: "/mypage",
                    badgeValue: this.loginInfo.invited_rooms.length,
                    badgeContent: this.loginInfo.invited_rooms.length,
                } as navType,
            ];
        },
    },
});
</script>