<template>
    <div class="calendar">

        <!-- カレンダー -->
        <v-card>
            <CalendarPagenation />

            <ul class="indent pa-0">
                <li v-for="day in week" :key="day" class="indent_item">{{day}}</li>
            </ul>

            <ul class="content">
                <li v-for="n in first_day" :key="n" class="content_item blank"></li>
                <li v-for="(calendar, index) in calendars" :key="calendar.date" v-ripple class="content_item main">
                    <div @click="$router.push(`/calendar?year=${year}&month=${month}&day=${index + 1}`)" class="content_item_inner">
                        <div class="content_item_icn">
                            <div class="content_item_icn_num" :class="{nowDay:index + 1 == nowDay && year == nowYear && month == nowMonth}">
                                {{ index + 1 }}
                            </div>
                        </div>
                        <v-responsive class="pa-1 pie_graph" aspect-ratio="1">
                            <div v-if="calendar.work" class="pie_graph_cover">{{calendar.work.sum_minute}}</div>
                            <PieGraph mode="daily" :propsDatas="calendar.work.users" v-if="calendar.work && isShowPieGraph" />
                        </v-responsive>
                    </div>
                </li>
                <li v-for="n in lastDayCount" :key="n + 100" class="content_item blank"></li>
            </ul>
        </v-card>

        <div style="padding:50px 50px 0;">
            <PieGraph mode="monthly" v-if="works.monthly.length && isShowPieGraph" :propsDatas="works.monthly" />
        </div>

        <div style="padding:50px;">
            <PieGraph mode="monthly" v-if="works.tasks && isShowPieGraph" :propsDatas="works.tasks" />
        </div>

        <v-dialog @click:outside="onCloseDialog" :value="day" scrollable>
            <Tasks mode="calendar" @onCloseDialog="onCloseDialog" @fetchData="fetchData" v-if="day" :tasks="focusTasks" />
        </v-dialog>
    </div>
</template>
<script lang="ts">
import moment from "moment";
import { mapState } from "vuex";
export default {
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
            isShowPieGraph: false as boolean,
            dialogLoading: false as boolean,
            tasks: [],
        };
    },
    computed: {
        ...mapState(["loginInfo", "works", "focusTasks"]),
        calendars() {
            let outputData = [];
            for (let day = 1; day <= this.lastday; day++) {
                outputData.push({
                    date: moment(
                        new Date(this.year, this.month - 1, day)
                    ).format("YYYY-MM-DD"),
                });
            }
            outputData.forEach((calendar) => {
                let calendarWork = this.works.daily.filter(
                    (work) => work.work_date === calendar.date
                )[0];
                if (calendarWork) {
                    calendar.work = calendarWork;
                }
            });
            // 円グラフ再描画
            this.isShowPieGraph = false;
            this.$nextTick(() => {
                this.isShowPieGraph = true;
            });
            return outputData;
        },
        year() {
            return this.$route.query.year;
        },
        month() {
            return this.$route.query.month;
        },
        day() {
            return this.$route.query.day;
        },
        lastday() {
            return new Date(this.year, this.month, 0).getDate();
        },
        first_day() {
            return new Date(this.year, this.month - 1, 1).getDay();
        },
        lastDayCount() {
            return (
                6 - new Date(this.year, this.month - 1, this.lastday).getDay()
            );
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
    methods: {
        fetchData() {
            this.$store.dispatch("setFocusTasks");
            this.$store.dispatch("setWorks");
        },
        onCloseDialog() {
            this.$router.push(
                `/calendar?year=${this.year}&month=${this.month}`
            );
        },
    },
    mounted() {
        this.$store.dispatch("setWorks");
    },
    watch: {
        year() {
            this.$store.dispatch("setWorks");
        },
        month() {
            this.$store.dispatch("setWorks");
        },
    },
};
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
        &_icn {
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
                &.nowDay {
                    background-color: #009688;
                    color: white;
                    font-size: 12px;
                }
            }
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