<template>
    <v-card>
        <v-toolbar color="teal" dark style="box-shadow:none;">
            <v-toolbar-title v-if="this.focusTask">「{{this.focusTask.task_name}}」を編集</v-toolbar-title>
            <v-toolbar-title v-else>新しいタスクを追加</v-toolbar-title>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:30vh;">
            <v-form ref="form" v-model="noError">
                <v-text-field validate-on-blur v-model="form.task_name" :rules="[v => !!v || 'Item is required']" label="タスクの名前" prepend-icon="mdi-format-title" required></v-text-field>
                <v-select v-model="form.task_default_minute" :items="$MINUTE" :rules="[v => !!v || 'Item is required']" label="想定時間" prepend-icon="mdi-clock-outline" required item-value="val" item-text="txt"></v-select>
            </v-form>
        </v-card-text>

        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="mr-2" @click="$emit('onCloseTaskDialog')"><v-icon>mdi-close</v-icon></v-btn>
            <v-btn color="teal" :loading="loading" dark @click="submit()">登録</v-btn>
        </v-card-actions>
    </v-card>
</template>
<script>
import { mapState } from "vuex";
export default {
    props: ["focusTask"],
    data: () => ({
        loading: false,
        noError: false,
        form: {
            task_id: 0,
            task_name: "",
            task_default_minute: "",
            task_is_everyday: 1,
            task_room_id: 1,
        },
    }),
    computed: {
        ...mapState(["loginInfo"]),
    },
    methods: {
        async submit() {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.loading = true;
            await this.$axios
                .post(`/api/task/create`, { task: this.form })
                .then((res) => {
                    this.$store.dispatch("setTodayTasks");
                })
                .catch((err) => {
                    alert("通信に失敗しました");
                })
                .finally(() => {
                    this.loading = false;
                    this.$emit("onCloseTaskDialog");
                });
        },
    },
    mounted() {
        this.$set(this.form, "task_room_id", this.loginInfo.room_id);
        if (this.focusTask) {
            this.$set(this.form, "task_id", this.focusTask.task_id);
            this.$set(this.form, "task_name", this.focusTask.task_name);
            this.$set(
                this.form,
                "task_default_minute",
                this.focusTask.task_default_minute
            );
            this.$set(
                this.form,
                "task_is_everyday",
                this.focusTask.task_is_everyday
            );
        }
    },
};
</script>