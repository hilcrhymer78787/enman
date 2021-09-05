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
export default {
    props: ["tasks"],
    components: {
        vuedraggable: vuedraggable,
    },
    data() {
        return {
            taskDialog: false,
            dialog: false,
            focusTask: {},
        };
    },
    methods: {
        onFocusTask(task) {
            this.dialog = true;
            this.focusTask = task;
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
    mounted() {},
};
</script>
<style lang="scss" scoped>
.check {
    border-radius: 0;
    height: 100%;
    width: 50px;
}
</style>