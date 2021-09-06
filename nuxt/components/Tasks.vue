<template>
    <div>
        <v-card>
            <v-toolbar color="teal" height="70px" dark class="d-block" style="box-shadow:none;">
                <v-toolbar-title>毎日タスク</v-toolbar-title>
                <v-spacer></v-spacer>
                <span class="pt-1 mr-3">{{date}}</span>
                <v-btn v-if="$route.name == 'index'" @click="taskDialog = true" light height="35px" width="35px" fab elevation="0">
                    <v-icon color="teal">mdi-plus</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text :style="$route.name != 'index' ? 'height: 300px;overflow-y:scroll;':''" class="pa-0">
                <!-- <vuedraggable :options="{animation: 200,  delay: 50 }" v-model="tasks"> -->
                <div v-for="(task,taskIndex) in tasks" :key="taskIndex">
                    <v-list-item v-ripple class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                        <v-list-item-avatar @click="onFocusTask(task)">
                            <v-icon v-if="task.works.length == 0" style="border:2px solid gray;">mdi-account</v-icon>
                            <v-img v-if="task.works.length == 1" style="border:2px solid #009688;" :src="task.works[0].work_user_img" class="rounded-circle"></v-img>
                            <v-icon v-if="task.works.length >= 2" style="border:2px solid #009688;" color="teal">mdi-account-group</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content @click="onFocusTask(task)">
                            <v-list-item-title>{{task.task_name}}</v-list-item-title>
                            <v-list-item-subtitle style="font-size:12px;">
                                <span>想定:{{task.task_default_minute}}分</span>
                                <span v-if="task.works.length">稼働:{{task.minute}}分</span>
                                <span v-if="task.works.length == 1">担当:{{task.works[0].work_user_name}}</span>
                                <span v-if="task.works.length >= 2">担当:複数人</span>
                            </v-list-item-subtitle>
                        </v-list-item-content>
                        <v-btn :loading="loadings[taskIndex]" class="check" icon>
                            <v-icon @click="onClickCheckBoxMarked(task,taskIndex)" style="font-size: 25px;" v-if="task.works.length">mdi-checkbox-marked-outline</v-icon>
                            <v-icon @click="onClickCheckBoxBlank(task,taskIndex)" style="font-size: 25px;" v-else>mdi-checkbox-blank-outline</v-icon>
                        </v-btn>
                    </v-list-item>
                    <v-divider v-if="taskIndex + 1 != tasks.length"></v-divider>
                </div>
                <!-- </vuedraggable> -->
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions v-if="$route.name != 'index'">
                <v-spacer></v-spacer>
                <v-btn>close</v-btn>
            </v-card-actions>
        </v-card>

        <v-dialog v-model="dialog" scrollable>
            <CreateWorks @onCloseModal="onCloseModal" :date="date" :focusTask="focusTask" v-if="dialog" />
        </v-dialog>

        <v-dialog v-model="taskDialog" scrollable>
            <CreateTasks @onCloseTaskDialog="onCloseTaskDialog" v-if="taskDialog" />
        </v-dialog>

    </div>
</template>

<script>
import vuedraggable from "vuedraggable";
export default {
    props: ["tasks", "date"],
    components: {
        vuedraggable: vuedraggable,
    },
    data() {
        return {
            taskDialog: false,
            dialog: false,
            focusTask: {},
            loadings:[]
        };
    },
    methods: {
        onFocusTask(task) {
            this.dialog = true;
            this.focusTask = task;
        },
        async onClickCheckBoxBlank(task,taskIndex) {
            this.$set(this.loadings, taskIndex, true);
            await this.$axios.post(
                `/api/work/create?token=${this.$store.state.loginInfo.token}`,
                {
                    date: this.date,
                    task_id: task.task_id,
                    works: [
                        {
                            work_user_id: this.$store.state.loginInfo.id,
                            work_minute: task.task_default_minute,
                        },
                    ],
                }
            );
            await this.$store.dispatch("setTodayTasks");
            this.$set(this.loadings, taskIndex, false);
        },
        async onClickCheckBoxMarked(task,taskIndex) {
            this.$set(this.loadings, taskIndex, true);
            const date = this.date;
            const task_id = task.task_id;
            await this.$axios
                .delete(
                    `/api/work/delete?token=${this.$store.state.loginInfo.token}&date=${date}&task_id=${task_id}`
                )
                .then((res) => {
                    console.log(res.data);
                });
            await this.$store.dispatch("setTodayTasks");
            this.$set(this.loadings, taskIndex, false);
        },
        onCloseModal() {
            this.dialog = false;
        },
        onCloseTaskDialog() {
            this.taskDialog = false;
        },
    },
};
</script>
<style lang="scss" scoped>
.check {
    border-radius: 0;
    height: 100%;
    width: 50px;
}
</style>