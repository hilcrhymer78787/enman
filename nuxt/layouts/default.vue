<template>
    <v-app>
        <v-main>
            <v-container style="max-width:500px;">
                <Nuxt />
            </v-container>
        </v-main>
        <v-bottom-navigation app dark fixed>
            <v-btn v-for="(item, i) in items" :key="i" :to="item.to" router dark exact>
                <span>{{ item.txt }}</span>
                <v-icon>{{ item.icon }}</v-icon>
            </v-btn>
        </v-bottom-navigation>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            items: [
                // https://materialdesignicons.com/
                {
                    icon: "mdi-playlist-check ",
                    txt: "タスク",
                    to: "/",
                },
                {
                    icon: "mdi-calendar-check",
                    txt: "カレンダー",
                    to: "/calendar",
                },
                {
                    icon: "mdi-cog",
                    txt: "テキスト",
                    to: "/inspire",
                },
                {
                    icon: "mdi-account",
                    txt: "テキスト",
                    to: "/account",
                },
            ],
        };
    },
};
</script>