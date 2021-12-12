<template>
    <div class="icn">
        <div class="icn_num" :class="{isToday:isToday}">
            {{ day }}
        </div>
    </div>
</template>

<script lang="ts">
import moment from "moment";
export default {
    props: ["day"],
    computed: {
        isToday() {
            return (
                this.day == this.nowDay &&
                this.year == this.nowYear &&
                this.month == this.nowMonth
            );
        },
        year() {
            return this.$route.query.year;
        },
        month() {
            return this.$route.query.month;
        },
        nowDay(): string {
            return moment().format("D");
        },
        nowYear(): string {
            return moment().format("Y");
        },
        nowMonth(): string {
            return moment().format("M");
        },
    },
};
</script>

<style lang="scss" scoped>
.icn {
    padding-top: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    &_num {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 20px;
        height: 20px;
        font-size: 14px;
        border-radius: 50%;
        &.isToday {
            background-color: #009688;
            color: white;
            font-size: 12px;
        }
    }
}
</style>