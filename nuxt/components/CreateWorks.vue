<template>
    <v-card>
        <v-toolbar color="teal" dark style="box-shadow:none;">
            <v-toolbar-title>{{task.task_name}}</v-toolbar-title>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text class="pa-3" style="min-height:35vh;">
            <v-form ref="form" v-model="noError">
                <v-card v-for="(work,workIndex) in task.works" :key="workIndex" class="d-flex align-center mb-4" style="height:70px;overflow: hidden;">
                    <v-select class="pt-3 pl-3" style="width:46%;" label="担当者" :items="users" v-model="work.work_user_id" item-value="val" item-text="txt" :rules="[v => !!v || 'Item is required']" dense></v-select>
                    <v-spacer></v-spacer>
                    <v-select class="pt-3" style="width:30%;" label="稼働時間" :items="$MINUTE" v-model="work.work_minute" item-value="val" item-text="txt" :rules="[v => !!v || 'Item is required']" dense></v-select>
                    <v-spacer></v-spacer>
                    <v-btn @click="removeWork(workIndex)" class="close_wrap" v-ripple style="width:12%;" icon>
                        <v-icon color="white">mdi-close</v-icon>
                    </v-btn>
                </v-card>
                <v-btn @click="addWork()" icon class="d-block mx-auto">
                    <v-icon style="font-size:35px;">mdi-plus</v-icon>
                </v-btn>
            </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
            <v-btn icon>
                <v-icon>mdi-cog</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn color="error" @click="$emit('onCloseModal')">delete</v-btn>
            <v-btn color="teal" :loading="loading" dark @click="onClickSave()">Save</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    props: ["focusTask", "date"],

    data() {
        return {
            loading: false,
            noError: false,
            task: {},
        };
    },
    computed: {
        users() {
            let outputData = [];
            this.$store.state.users.forEach((user) => {
                outputData.push({ val: user.id, txt: user.name });
            });
            return outputData;
        },
    },
    methods: {
        addWork() {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.task.works.push({
                user_id: 0,
                minute: 0,
            });
        },
        removeWork(workIndex) {
            if (this.task.works.length == 1) {
                return;
            }
            this.task.works.splice(workIndex, 1);
        },
        async onClickSave() {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.loading = true;
            await this.$axios
                .post(
                    `/api/work/create?token=${this.$store.state.loginInfo.token}`,
                    this.task
                )
                .then((res) => {
                    console.log(res.data);
                    this.$store.dispatch("setTodayTasks");
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    this.loading = false;
                    this.$emit("onCloseModal");
                });
        },
    },
    mounted() {
        this.$set(this.task, "date", this.date);
        for (const [key, value] of Object.entries(this.focusTask)) {
            if (Array.isArray(value)) {
                let array = [];
                value.forEach((valueObj) => {
                    let obj = {};
                    for (const [key2, value2] of Object.entries(valueObj)) {
                        this.$set(obj, key2, value2);
                    }
                    array.push(obj);
                });
                this.$set(this.task, key, array);
            } else {
                this.$set(this.task, key, value);
            }
        }
        if (!this.focusTask.works.length) {
            let obj = {};
            this.$set(obj, "work_user_id", 0);
            this.$set(obj, "work_minute", this.focusTask.task_default_minute);
            this.task.works.push(obj);
        }
    },
};
</script>

<style lang="scss" scoped>
.close_wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.6);
    height: 100%;
    border-radius: 0 !important;
}
</style>