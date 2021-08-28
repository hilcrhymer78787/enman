<template>
    <div>
        <pre>{{$data}}</pre>
        <!-- <v-app-bar app dark>
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
        </v-tabs-items> -->
    </div>
</template>
<script>
import axios from 'axios';
export default {
    data: () => ({
        items: [],
    }),
    mounted() {
        axios
            .get("http://localhost:8000/api/task/read")
            .then((res) => {
                this.items = res.data;
            })
            .catch((err) => {
                alert("エラーです");
            })
            // .finally(() => (this.$store.state.userLoading = false));
    },
};
</script>

