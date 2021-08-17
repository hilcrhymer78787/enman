<template>
  <v-app>
    <v-main>
      <v-container>
        <Nuxt />
      </v-container>
    </v-main>

    <v-footer fixed app>
      <v-list>
        <v-list-item v-for="(item, i) in items" :key="i" :to="item.to" router exact>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item>
      </v-list>
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
<style lang="scss">
.v-footer {
  padding: 10px;
}
.v-list {
  padding: 0;
  display: flex;
  background: none !important;
}
.v-list-item {
  margin-right: 10px;
}
</style>