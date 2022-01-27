<template>
    <div class="calendar">
        <!-- カレンダー -->
        <v-card class="mb-5">
            <CalendarPagenation />

            <ul class="indent pa-0">
                <li v-for="day in week" :key="day" class="indent_item">{{day}}</li>
            </ul>

            <ul class="content">
                <li v-for="n in firstDay" :key="n" class="content_item blank"></li>
                <li v-for="(calendar, index) in displayCalendars" :key="calendar.date" v-ripple class="content_item main">
                    <div @click="$router.push(`/calendar?year=${year}&month=${month}&day=${index + 1}`)" class="content_item_inner">
                        <CalendarDayIcon :day="index + 1" />
                        <v-responsive class="pa-1 pie_graph" aspect-ratio="1">
                            <div v-if="calendar.minute">
                                <div class="pie_graph_cover">{{calendar.minute}}</div>
                                <PieGraphWrap mode="days" :propsDatas="calendar.users" />
                            </div>
                        </v-responsive>
                    </div>
                </li>
                <li v-for="n in lastDayCount" :key="n + 100" class="content_item blank"></li>
            </ul>
        </v-card>

        <PieGraphArea v-if="calendarWorks" :works="calendarWorks" :subttl="`${$route.query.year}年${$route.query.month}月`" />

        <v-dialog @click:outside="onCloseDialog" :value="day" scrollable>
            <Tasks mode="calendar" @onCloseDialog="onCloseDialog" @fetchData="fetchData" v-if="day" :tasks="focusTasks" />
        </v-dialog>
    </div>
</template>
<script lang="ts">
import Vue from 'vue'
import moment from "moment";
import { mapState } from "vuex";
export type calendarType = {
    date: string;
    minute: string;
    users: userType[];
};
export type workType = {
    minute: number;
    work_date: string;
    users: userType;
};
export type userType = {
    id: number;
    name: string;
    minute: number;
};
export default Vue.extend({
    middleware({ redirect, route }) {
        if (!route.query.year || !route.query.month) {
            let year = moment().year();
            let month = moment().month() + 1;
            redirect(`/calendar?year=${year}&month=${month}`);
        }
    },
    data() {
        return {
            week: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"] as string[],
            dialog: false as boolean,
            dialogLoading: false as boolean,
        };
    },
    computed: {
        ...mapState(["loginInfo", "calendars", "calendarWorks", "focusTasks"]),
        year(): number {
            return Number(this.$route.query.year);
        },
        month(): number {
            return Number(this.$route.query.month);
        },
        day(): number {
            return Number(this.$route.query.day);
        },
        lastDay(): number {
            return new Date(this.year, this.month, 0).getDate();
        },
        firstDay(): number {
            return new Date(this.year, this.month - 1, 1).getDay();
        },
        lastDayCount(): number {
            return (
                6 - new Date(this.year, this.month - 1, this.lastDay).getDay()
            );
        },
        displayCalendars(): calendarType[] {
            let outputData = [];
            for (let day = 1; day <= this.lastDay; day++) {
                let date: string = moment(
                    new Date(this.year, this.month - 1, day)
                ).format("YYYY-MM-DD");
                let calendar = this.calendars.filter(
                    (calendar: calendarType): boolean => calendar.date == date
                )[0];
                if (calendar) {
                    outputData.push(calendar);
                } else {
                    outputData.push({
                        date: date,
                        minute:0,
                    });
                }
            }
            return outputData;
        },
    },
    methods: {
        fetchData(): void {
            this.$store.dispatch("setFocusTasks");
            this.$store.dispatch("setCalendars");
            this.$store.dispatch("setCalendarWorks");
        },
        onCloseDialog(): void {
            this.$router.push(
                `/calendar?year=${this.year}&month=${this.month}`
            );
        },
    },
    mounted(): void {
        this.$store.dispatch("setCalendars");
        this.$store.dispatch("setCalendarWorks");
    },
    watch: {
        year(): void {
            this.$store.dispatch("setCalendars");
            this.$store.dispatch("setCalendarWorks");
        },
        month(): void {
            this.$store.dispatch("setCalendars");
            this.$store.dispatch("setCalendarWorks");
        },
    },
});
</script>

<style lang="scss" scoped>
.indent {
    display: flex;
    &_item {
        width: calc(100% / 7);
        text-align: center;
        padding: 5px 0;
        &:nth-child(1) {
            color: #ff5252;
        }
        &:nth-child(7) {
            color: #2196f3;
        }
    }
}

.content {
    display: flex;
    flex-wrap: wrap;
    padding: 0;
    background-color: white;
    &_item {
        width: calc(100% / 7);
        border-right: 1px solid #e0e0e0;
        border-top: 1px solid #e0e0e0;
        overflow: hidden;
        &:nth-child(7n) {
            border-right: none;
        }
        &:nth-child(7n) .content_item_icn {
            color: #2196f3;
        }
        &:nth-child(7n-6) .content_item_icn {
            color: #ff5252;
        }
        &.blank {
            background-color: #f7f7f7;
        }
        &.main:hover {
            cursor: pointer;
            background-color: #00968734;
        }
    }
}

.pie_graph {
    position: relative;
    &_cover {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 15px;
        font-weight: bold;
        color: teal;
    }
}
</style>