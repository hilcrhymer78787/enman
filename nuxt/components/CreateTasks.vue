<template>
    <v-card>
        <v-toolbar color="teal" dark style="box-shadow:none;">
            <v-toolbar-title v-if="this.focusTask">「{{this.focusTask.task_name}}」を編集</v-toolbar-title>
            <v-toolbar-title v-else>新しい毎日タスクを追加</v-toolbar-title>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:30vh;">
            <v-form ref="form" v-model="noError">
                <v-text-field v-model="form.taskName" :rules="[v => !!v || 'Item is required']" label="タスクの名前" required></v-text-field>
                <v-select v-model="form.taskDefaultMinute" :items="$MINUTE" :rules="[v => !!v || 'Item is required']" label="想定時間" required item-value="val" item-text="txt"></v-select>
                <v-select v-model="form.taskPointPerMinute" :items="$POINTS" :rules="[v => !!v || 'Item is required']" label="1分あたりのポイント" required item-value="val" item-text="txt"></v-select>
            </v-form>
        </v-card-text>

        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="mr-2" @click="$emit('onCloseTaskDialog')">close</v-btn>
            <v-btn color="teal" :loading="loading" dark @click="submit()">SAVE</v-btn>
        </v-card-actions>
    </v-card>
</template>
<script>
export default {
    props: ["focusTask"],
    data: () => ({
        loading: false,
        noError: false,
        form: {
            taskName: "",
            taskDefaultMinute: "",
            taskPointPerMinute: "",
            taskIsEveryday: 1,
        },
    }),

    methods: {
        async submit() {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.loading = true;
            await this.$axios
                .post(
                    `/api/task/create?token=${this.$store.state.loginInfo.token}`,
                    this.form
                )
                .then((res) => {
                    console.log(res.data)
                    this.$store.dispatch("setTodayTasks");
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    this.loading = false;
                    this.$emit("onCloseTaskDialog");
                });
        },
    },
    mounted() {
        console.log(this.focusTask);
        if (this.focusTask) {
            this.$set(this.form, "taskId", this.focusTask.task_id);
            this.$set(this.form, "taskName", this.focusTask.task_name);
            this.$set(
                this.form,
                "taskDefaultMinute",
                this.focusTask.task_default_minute
            );
            this.$set(
                this.form,
                "taskPointPerMinute",
                this.focusTask.task_point_per_minute
            );
            this.$set(
                this.form,
                "taskIsEveryday",
                this.focusTask.task_is_everyday
            );
        }
    },
};
</script>