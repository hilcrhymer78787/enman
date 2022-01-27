<template>
    <v-card-title class="pagenation">
        <v-spacer></v-spacer>
        <div class="d-flex">
            <v-btn @click="onClickPrevMonth()" icon>
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <h1>{{ $route.query.year }}年 {{ $route.query.month }}月</h1>
            <v-btn @click="onClickNextMonth()" icon>
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
        </div>
        <v-spacer></v-spacer>
    </v-card-title>
</template>

<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
    methods: {
        onClickPrevMonth() {
            if (Number(this.$route.query.month) == 1) {
                this.$router.push(
                    `/calendar?year=${
                        Number(this.$route.query.year) - 1
                    }&month=12`
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
            if (Number(this.$route.query.month) == 12) {
                this.$router.push(
                    `/calendar?year=${
                        Number(this.$route.query.year) + 1
                    }&month=1`
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
});
</script>

<style lang="scss" scoped>
.pagenation {
    font-size: 15px;
    h1 {
        width: 183px;
        text-align: center;
    }
    ::v-deep{
        .v-icon{
            color: white;
            font-size: 30px;
            position: relative;
            bottom: 1.5px;
        }
    }
}
</style>