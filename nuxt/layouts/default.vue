<template>
    <v-app>
        <v-main>
            <v-container style="max-width:500px;">
                <Nuxt />
            </v-container>
        </v-main>

        <v-footer fixed app class="pa-0">
            <v-container style="max-width:500px;">
                <v-list class="d-flex pa-0">
                    <v-list-item style="justify-content: center;" v-for="(item, i) in items" :key="i" :to="item.to" router exact>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item>
                </v-list>
            </v-container>
        </v-footer>
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
                    to: "/",
                },
                {
                    icon: "mdi-calendar-check",
                    to: "/calendar",
                },
                {
                    icon: "mdi-cog",
                    to: "/inspire",
                },
                {
                    icon: "mdi-account",
                    to: "/account",
                },
            ],
        };
    },
};
</script>