<template>
    <div>
        <v-app-bar app dark>
            <v-tabs dark v-model="tab">
                <v-tab style="width:calc(100% / 2);">今日のタスク</v-tab>
                <v-tab style="width:calc(100% / 2);">大型タスク</v-tab>
            </v-tabs>
        </v-app-bar>

        <v-tabs-items v-model="tab">
            <v-tab-item>
                <IndexTodayTask/>
            </v-tab-item>
            <v-tab-item>
                <IndexBigTask/>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
export default {
    data: () => ({
        tab:0,
    }),
};
</script>

