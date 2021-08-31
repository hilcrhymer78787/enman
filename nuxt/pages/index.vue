<template>
    <div>
        <pre>{{$store.state.everyDayTasks}}</pre>
        <v-app-bar app dark>
            <v-tabs dark v-model="tab">
                <v-tab style="width:50%;">今日のタスク</v-tab>
                <v-tab style="width:50%;">大型タスク</v-tab>
            </v-tabs>
        </v-app-bar>

        <v-tabs-items v-model="tab">
            <v-tab-item>
                <IndexTodayTask/>
            </v-tab-item>
            <v-tab-item>
                <!-- <IndexBigTask/> -->
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
export default {
    data: () => ({
        items: [],
        works: [],
        tab:""
    }),
};
</script>

