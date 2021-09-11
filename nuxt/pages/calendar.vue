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
                    <v-responsive class="pa-2" aspect-ratio="1">
                        <!-- <PieGraph /> -->
                    </v-responsive>
                </li>

                <li v-for="n in lastDayCount" :key="n + 100" class="content_item blank"></li>
            </ul>

        </v-card>

        <v-dialog v-model="dialog" scrollable>
            <Tasks mode="calendar" @onCloseDialog="dialog = false" @getTasks="getTasks" v-if="!dialogLoading" :date="date" :tasks="tasks" />
        </v-dialog>
    </div>
</template>
<script>
import moment from "moment";
export default {
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
            dialogLoading: false,
            date: "",
            tasks: [],
        };
    },
    computed: {
        calendars() {
            let outputData = [];

            for (let day = 1; day <= this.lastday; day++) {
                outputData.push({
                    date: moment(
                        new Date(this.year, this.month - 1, day)
                    ).format("YYYY-MM-DD"),
                    works: [],
                });
            }

            // if (!this.$store.state.calendarLoading) {
            //     outputData.forEach((calendar) => {
            //         let calendarWorksFilterDate =
            //             this.$store.state.calendarWorks.filter(
            //                 (calendarElm) => calendarElm.date === calendar.date
            //             );
            //         if (calendarWorksFilterDate.length) {
            //             calendar.works.push(
            //                 ...calendarWorksFilterDate[0].works
            //             );
            //         }
            //     });
            // }

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
            await this.getTasks(date);
            this.dialogLoading = false;
        },
        async getTasks(date) {
            this.date = date;
            const day = moment(date).format("D");
            await this.$axios
                .get(
                    `/api/task/show?year=${this.year}&month=${this.month}&day=${day}&token=${this.$store.state.loginInfo.token}`
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
</style>