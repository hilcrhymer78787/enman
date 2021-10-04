<template>
    <div class="calendar">

        <!-- カレンダー -->
        <v-card>
            <v-toolbar color="teal" dark style="box-shadow:none;">
                <v-spacer></v-spacer>
                <div class="d-flex">
                    <v-btn @click="onClickPrevMonth()" icon>
                        <v-icon style="font-size:30px;">mdi-chevron-left</v-icon>
                    </v-btn>
                    <h1>{{ year }}年 {{ month }}月</h1>
                    <v-btn @click="onClickNextMonth()" icon>
                        <v-icon style="font-size:30px;">mdi-chevron-right</v-icon>
                    </v-btn>
                </div>
                <v-spacer></v-spacer>
            </v-toolbar>

            <ul class="indent pa-0">
                <li v-for="day in week" :key="day" class="indent_item">{{day}}</li>
            </ul>

            <ul class="content">
                <li v-for="n in first_day" :key="n" class="content_item blank"></li>
                <li @click="onClickCalendar(calendar.date)" v-for="(calendar, index) in calendars" :key="calendar.date" v-ripple class="content_item main">
                    <div class="content_item_icn">{{ index + 1 }}</div>
                    <v-responsive class="pa-1 pie_graph" aspect-ratio="1">
                        <div v-if="calendar.work" class="pie_graph_cover">{{calendar.work.sum_minute}}</div>
                        <PieGraph mode="daily" :workUsers="calendar.work.users" v-if="calendar.work && !getWorksLoading && isShowPieGraph" />
                        <div v-else-if="getWorksLoading" class="pa-1">
                            <v-progress-circular indeterminate color="teal"></v-progress-circular>
                        </div>
                    </v-responsive>
                </li>
                <li v-for="n in lastDayCount" :key="n + 100" class="content_item blank"></li>
            </ul>
        </v-card>

        <!-- <pre>{{works}}</pre> -->
        <div style="padding:50px;">
            <PieGraph mode="monthly" v-if="works.monthly.length && !getWorksLoading && isShowPieGraph" :workUsers="works.monthly" />
            <div v-else-if="getWorksLoading" class="text-center">
                <v-progress-circular indeterminate color="teal"></v-progress-circular>
            </div>
        </div>

        <v-dialog v-model="dialog" scrollable>
            <Tasks mode="calendar" @onCloseDialog="dialog = false" @getWorks="getWorks" @getTasks="getTasks" v-if="!dialogLoading" :date="date" :tasks="tasks" />
        </v-dialog>
    </div>
</template>
<script>
import moment from "moment";
import { mapState } from "vuex";
export default {
    async fetch({ store }) {
        store.dispatch("setLoginInfoByToken");
    },
    middleware({ redirect, route }) {
        let year = new Date().getFullYear();
        let month = new Date().getMonth() + 1;
        if (!route.query.year || !route.query.month) {
            redirect(`/calendar?year=${year}&month=${month}`);
        }
    },
    data() {
        return {
            week: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            dialog: false,
            isShowPieGraph: false,
            dialogLoading: false,
            getWorksLoading: false,
            date: "",
            tasks: [],
        };
    },
    computed: {
        ...mapState(["loginInfo", "works"]),
        calendars() {
            let outputData = [];

            this.isShowPieGraph = false;
            this.$nextTick(() => {
                this.isShowPieGraph = true;
            });

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
            return outputData;
        },
        year() {
            return this.$route.query.year;
        },
        month() {
            return this.$route.query.month;
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
    },
    methods: {
        onClickPrevMonth() {
            if (this.month == 1) {
                this.$router.push(
                    `/calendar?year=${Number(this.year) - 1}&month=12`
                );
            } else {
                this.$router.push(
                    `/calendar?year=${this.year}&month=${
                        Number(this.month) - 1
                    }`
                );
            }
        },
        onClickNextMonth() {
            if (this.month == 12) {
                this.$router.push(
                    `/calendar?year=${Number(this.year) + 1}&month=1`
                );
            } else {
                this.$router.push(
                    `/calendar?year=${this.year}&month=${
                        Number(this.month) + 1
                    }`
                );
            }
        },
        async onClickCalendar(date) {
            this.dialog = true;
            this.dialogLoading = true;
            this.date = date;
            await this.getTasks();
            this.dialogLoading = false;
        },
        async getTasks() {
            const day = moment(this.date).format("D");
            await this.$axios
                .get(
                    `/api/task/read?year=${this.year}&month=${this.month}&day=${day}&token=${this.loginInfo.token}`
                )
                .then((res) => {
                    this.tasks = res.data;
                    this.tasks.forEach((task) => {
                        let minute = task.works.reduce(function (sum, work) {
                            return sum + work.work_minute;
                        }, 0);
                        this.$set(task, "minute", minute);
                    });
                })
                .finally(() => {});
        },
        async getWorks() {
            this.getWorksLoading = true;
            this.$axios
                .get(
                    `/api/work/read?year=${this.year}&month=${this.month}&day=${this.day}&token=${this.loginInfo.token}`
                )
                .then((res) => {
                    console.log(res.data)
                    this.$store.commit("setWorks", res.data);
                })
                .catch((err) => {
                    alert("通信に失敗しました");
                })
                .finally(() => {
                    this.getWorksLoading = false;
                });
        },
    },
    mounted() {
        this.getWorks();
    },
    watch: {
        $route() {
            this.getWorks();
        },
    },
};
</script>

<style lang="scss" scoped>
h1 {
    width: 183px;
    text-align: center;
}
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
            font-size: 14px;
            text-align: center;
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