<template>
    <div>
        <v-sheet color="grey lighten-3" class="d-flex align-center head">
            <v-btn icon @click="$refs.calendar.prev()">
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <span>{{focusDay}}</span>
            <v-btn icon @click="$refs.calendar.next()">
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
        </v-sheet>
        <v-sheet height="500px">
            <v-calendar @click:day="onClickCalendar" @click:date="onClickCalendar" v-model="focusDay" ref="calendar" color="primary" :events="events" @change="getEvents"></v-calendar>
        </v-sheet>
        <v-dialog v-model="dialog" scrollable>
            <v-card>
                <v-card-title>{{focusDay}}</v-card-title>
                <v-divider></v-divider>
                <v-card-text style="height: 90%;">
                    <ul>
                        <li v-for="(n, i) in 1000" :key="i">テキスト{{i}}</li>
                    </ul>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn @click="dialog = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            focusDay: "",
            events: [],
        };
    },
    methods: {
        onClickCalendar({ date }) {
            this.dialog = true;
            this.focusDay = date
        },
        getEvents() {
            let events = [
                {
                    name: "54%",
                    start: "2021-08-01", 
                    color: "red",
                },
                {
                    name: "46%",
                    start: "2021-08-01", 
                    color: "primary",
                },
            ];
            this.events = events;
        },
    },
};
</script>
<style lang="scss" scoped>
::v-deep {
    .v-btn--fab.v-size--small {
        width: 25px;
        height: 25px;
    }
}
</style>