<template>
    <div class="icn">
        <div class="icn_num" :class="{isToday:isToday}">
            {{ day }}
        </div>
    </div>
</template>

<script lang="ts">
import Vue from 'vue'
import moment from "moment";
export default Vue.extend({
    props: {
        day: Number,
    },
    computed: {
        isToday() :boolean{
            return (
                this.day == this.nowDay &&
                this.year == this.nowYear &&
                this.month == this.nowMonth
            );
        },
        year() :number{
            return Number(this.$route.query.year);
        },
        month() :number{
            return Number(this.$route.query.month);
        },
        nowDay(): number {
            return Number(moment().format("D"));
        },
        nowYear(): number {
            return Number(moment().format("Y"));
        },
        nowMonth(): number {
            return Number(moment().format("M"));
        },
    },
});
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