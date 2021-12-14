<template>
    <v-toolbar color="teal" dark style="box-shadow:none;">
        <v-spacer></v-spacer>
        <div class="d-flex">
            <v-btn @click="onClickPrevMonth()" icon>
                <v-icon style="font-size:30px;">mdi-chevron-left</v-icon>
            </v-btn>
            <h1>{{ $route.query.year }}年 {{ $route.query.month }}月</h1>
            <v-btn @click="onClickNextMonth()" icon>
                <v-icon style="font-size:30px;">mdi-chevron-right</v-icon>
            </v-btn>
        </div>
        <v-spacer></v-spacer>
    </v-toolbar>
</template>

<script lang="ts">
export default {
    methods: {
        onClickPrevMonth() {
            if (this.$route.query.month == 1) {
                this.$router.push(
                    `/calendar?year=${Number(this.$route.query.year) - 1}&month=12`
                );
            } else {
                this.$router.push(
                    `/calendar?year=${this.$route.query.year}&month=${
                        Number(this.$route.query.month) - 1
                    }`
                );
            }
        },
        onClickNextMonth() {
            if (this.$route.query.month == 12) {
                this.$router.push(
                    `/calendar?year=${Number(this.$route.query.year) + 1}&month=1`
                );
            } else {
                this.$router.push(
                    `/calendar?year=${this.$route.query.year}&month=${
                        Number(this.$route.query.month) + 1
                    }`
                );
            }
        },
    },
};
</script>

<style lang="scss" scoped>
h1 {
    width: 183px;
    text-align: center;
}
</style>