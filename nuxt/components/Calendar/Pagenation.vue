<template>
    <v-toolbar color="teal" dark style="box-shadow:none;">
        <v-spacer></v-spacer>
        <div class="d-flex">
            <v-btn :to="`/calendar?year=${this.beforeYear}&month=${this.beforeMonth}`" icon>
                <v-icon style="font-size:30px;">mdi-chevron-left</v-icon>
            </v-btn>
            <h1>{{year }}年 {{month }}月</h1>
            <v-btn :to="`/calendar?year=${this.nextYear}&month=${this.nextMonth}`" icon>
                <v-icon style="font-size:30px;">mdi-chevron-right</v-icon>
            </v-btn>
        </div>
        <v-spacer></v-spacer>
    </v-toolbar>
</template>

<script lang="ts">
import moment from "moment";
export default {
    computed: {
        year(): string {
            return this.$route.query.year;
        },
        month(): string {
            return this.$route.query.month;
        },
        nextYear(): string {
            return moment(this.year + "-" + this.month)
                .add(1, "months")
                .format("Y");
        },
        nextMonth(): string {
            return moment(this.year + "-" + this.month)
                .add(1, "months")
                .format("M");
        },
        beforeYear(): string {
            return moment(this.year + "-" + this.month)
                .subtract(1, "months")
                .format("Y");
        },
        beforeMonth(): string {
            return moment(this.year + "-" + this.month)
                .subtract(1, "months")
                .format("M");
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