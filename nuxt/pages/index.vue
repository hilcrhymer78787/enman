<template>
    <div>
        <v-card>
            <v-toolbar color="teal" dark style="box-shadow:none;">
                <v-toolbar-title>毎日タスク</v-toolbar-title>
            </v-toolbar>
            <vuedraggable :options="{animation: 200,  delay: 50 }" v-model="tasks">
                <div v-for="(task,taskIndex) in tasks" :key="taskIndex">
                    <v-list-item v-ripple class="pl-2 pr-0" style="height:60px;overflow:hidden;">
                        <v-list-item-avatar @click="onFocusTask(task)">
                            <v-icon v-if="task.works.length == 0" style="border:2px solid gray;">mdi-account</v-icon>
                            <v-img v-if="task.works.length == 1" style="border:2px solid #009688;" :src="task.works[0].user_img" class="rounded-circle"></v-img>
                            <v-icon v-if="task.works.length >= 2" style="border:2px solid #009688;" color="teal">mdi-account-group</v-icon>
                        </v-list-item-avatar>
                        <v-list-item-content @click="onFocusTask(task)">
                            <v-list-item-title>{{task.task_name}}</v-list-item-title>
                            <v-list-item-subtitle style="font-size:12px;">
                                <span>想定:{{task.task_default_minute}}分</span>
                                <span v-if="task.works.length">稼働:{{task.minute}}分</span>
                                <span v-if="task.works.length == 1">担当:{{task.works[0].user_name}}</span>
                                <span v-if="task.works.length >= 2">担当:複数人</span>
                            </v-list-item-subtitle>
                        </v-list-item-content>
                        <v-btn class="check" @click="onClickCheckBox(task)" icon>
                            <v-icon style="font-size: 25px;" v-if="task.works.length">mdi-checkbox-marked-outline</v-icon>
                            <v-icon style="font-size: 25px;" v-else>mdi-checkbox-blank-outline</v-icon>
                        </v-btn>
                    </v-list-item>
                    <v-divider v-if="taskIndex + 1 != tasks.length"></v-divider>
                </div>
            </vuedraggable>
        </v-card>

        <v-dialog v-model="dialog" scrollable>
            <CreateWorks @onCloseModal="onCloseModal" :focusTask="focusTask" v-if="dialog" />
        </v-dialog>

        <v-btn @click="taskDialog = true" style="bottom:70px;" fixed right fab elevation="2">
            <v-icon>mdi-plus</v-icon>
        </v-btn>

        <v-dialog v-model="taskDialog" scrollable>
            <CreateTasks @onCloseTaskDialog="onCloseTaskDialog" v-if="taskDialog" />
        </v-dialog>

    </div>
</template>

<script>
import vuedraggable from "vuedraggable";
import { mapState } from "vuex";
export default {
    components: {
        vuedraggable: vuedraggable,
    },
    data() {
        return {
            taskDialog: false,
            dialog: false,
            focusTask: {},
            tasks: [
                {
                    task_id: 1,
                    task_name: "task_name1",
                    task_default_minute: 5,
                    works: [
                        {
                            work_id: 1,
                            user_id: 1,
                            user_name: "user1",
                            user_img: "https://picsum.photos/500/300?image=10",
                            minute: 5,
                        },
                    ],
                },
                {
                    task_id: 2,
                    task_name: "task_name2",
                    task_default_minute: 10,
                    works: [
                        {
                            work_id: 2,
                            user_id: 2,
                            user_name: "user2",
                            user_img: "https://picsum.photos/500/300?image=1",
                            minute: 10,
                        },
                    ],
                },
                {
                    task_id: 3,
                    task_name: "task_name3",
                    task_default_minute: 15,
                    works: [
                        {
                            work_id: 3,
                            user_id: 1,
                            user_name: "user1",
                            user_img: "https://picsum.photos/500/300?image=10",
                            minute: 5,
                        },
                        {
                            work_id: 4,
                            user_id: 2,
                            user_name: "user2",
                            user_img: "https://picsum.photos/500/300?image=1",
                            minute: 10,
                        },
                    ],
                },
                {
                    task_id: 4,
                    task_name: "task_name4",
                    task_default_minute: 5,
                    works: [],
                },
                {
                    task_id: 5,
                    task_name: "task_name5",
                    task_default_minute: 5,
                    works: [],
                },
            ],
        };
    },
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        onFocusTask(task) {
            this.dialog = true;
            for (const [key, value] of Object.entries(task)) {
                if (Array.isArray(value)) {
                    let array = [];
                    value.forEach((valueObj) => {
                        let obj = {};
                        for (const [key2, value2] of Object.entries(valueObj)) {
                            this.$set(obj, key2, value2);
                        }
                        array.push(obj);
                    });
                    this.$set(this.focusTask, key, array);
                } else {
                    this.$set(this.focusTask, key, value);
                }
            }
            if (!this.focusTask.works.length) {
                this.focusTask.works.push({
                    user_id: 0,
                    minute: task.task_default_minute,
                });
            }
        },
        onClickCheckBox(task) {
            alert();
            console.log(task);
        },
        onCloseModal() {
            this.dialog = false;
        },
        onCloseTaskDialog() {
            this.taskDialog = false;
        },
    },
    mounted() {
        this.tasks.forEach((task) => {
            let minute = task.works.reduce(function (sum, work) {
                return sum + work.minute;
            }, 0);
            this.$set(task, "minute", minute);
        });
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