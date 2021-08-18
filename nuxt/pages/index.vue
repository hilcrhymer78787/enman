<template>
    <div>
        <v-card v-for="(task,taskIndex) in tasks" :key="taskIndex" class="blue lighten-5 mb-4 px-2 pb-1 pt-2">
            <v-card-title class="d-flex align-start pa-0">
                <span style="width:calc(100% - 25px);">{{task.name}}</span>
                <v-icon v-if="task.works.length" @click="onClickCheckboxMarked(taskIndex)">mdi-checkbox-marked-outline</v-icon>
                <v-icon v-else @click="onClickCheckboxBlank(taskIndex)">mdi-checkbox-blank-outline</v-icon>
            </v-card-title>
            <v-card v-for="(work, workIndex) in task.works" :key="workIndex" class="px-2 mb-2">
                <v-card-actions class="pa-0 ma-0 d-flex">
                    <v-select class="pa-0 mr-2" v-model="work.user_id" :items="$store.state.users" item-text="name" item-value="id" style="width:120px;"></v-select>
                    <v-select class="pa-0" v-model="work.minute" :items="$MINUTE" item-text="txt" item-value="val" style="width:70px;"></v-select>
                </v-card-actions>
            </v-card>
        </v-card>
        <pre>{{tasks}}</pre>
    </div>
</template>
<script>
export default {
    data: () => ({
        tasks: [],
    }),
    mounted() {
        this.$store.dispatch("setUsers");
        this.$store.dispatch("setTasks");
        this.$store.dispatch("setWorks");
        this.$store.state.tasks.forEach((task, i) => {
            let obj = {};
            // タスクセット
            Object.keys(task).forEach((key) => {
                this.$set(obj, key, task[key]);
            });
            // 実績セット
            let works = this.$store.state.works.filter(
                (work) => work.task_id == task.id
            );
            let array = [];
            console.log(works);
            if (works.length) {
                works.forEach((work) => {
                    let workObj = {};
                    this.$set(workObj, "user_id", work.user_id);
                    this.$set(workObj, "minute", work.minute);
                    array.push(workObj);
                });
            }
            this.$set(obj, "works", array);
            this.tasks.push(obj);
        });
    },
    methods: {
        onClickCheckboxBlank(taskIndex) {
            let works = this.tasks[taskIndex].works;
            let obj = {};
            this.$set(obj, "user_id", 1);
            this.$set(obj, "minute", 0);
            works.push(obj);
        },
        onClickCheckboxMarked(taskIndex) {
            let works = this.tasks[taskIndex].works;
            works.splice(0, works.length);
        },
    },
};
</script>
<style lang="scss" scoped>
.test {
    overflow: hidden;
}
::v-deep {
    .v-text-field__details {
        display: none !important;
    }
}
</style>
