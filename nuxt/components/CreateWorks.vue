<template>
    <v-form ref="form" v-model="noError">
        <v-card>
            <v-toolbar color="teal" dark style="box-shadow:none;">
                <v-toolbar-title>{{focusTask.task_name}}</v-toolbar-title>
            </v-toolbar>
            <v-divider></v-divider>
            <v-card-text class="pa-3" style="min-height:35vh;">
                <v-card v-for="(work,workIndex) in focusTask.works" :key="workIndex" class="d-flex align-center mb-4" style="height:70px;overflow: hidden;">
                    <v-select class="pt-3 pl-3" style="width:46%;" label="担当者" :items="users" v-model="work.user_id" item-value="val" item-text="txt" :rules="[v => !!v || 'Item is required']" dense></v-select>
                    <v-spacer></v-spacer>
                    <v-select class="pt-3" style="width:30%;" label="稼働時間" :items="minutes" v-model="work.minute" item-value="val" item-text="txt" :rules="[v => !!v || 'Item is required']" dense></v-select>
                    <v-spacer></v-spacer>
                    <v-btn @click="removeWork(workIndex)" class="close_wrap" v-ripple style="width:12%;" icon>
                        <v-icon color="white">mdi-close</v-icon>
                    </v-btn>
                </v-card>
                <v-btn @click="addWork()" icon class="d-block mx-auto">
                    <v-icon style="font-size:35px;">mdi-plus</v-icon>
                </v-btn>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-btn icon>
                    <v-icon>mdi-cog</v-icon>
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="$emit('onCloseModal')">delete</v-btn>
                <v-btn color="teal" dark @click="$emit('onCloseModal')">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-form>
</template>

<script>
export default {
    props: ["focusTask"],
    data() {
        return {
            noError: false,
            minutes: [
                { val: 5, txt: "5分" },
                { val: 10, txt: "10分" },
                { val: 15, txt: "15分" },
                { val: 20, txt: "20分" },
                { val: 25, txt: "25分" },
                { val: 30, txt: "30分" },
            ],
            users: [
                { val: 1, txt: "user1" },
                { val: 2, txt: "user2" },
            ],
        };
    },
    methods: {
        addWork() {
            this.$refs.form.validate();
            if (!this.noError) {
                return;
            }
            this.focusTask.works.push({
                user_id: 0,
                minute: 0,
            });
        },
        removeWork(workIndex) {
            if (this.focusTask.works.length == 1) {
                return
            }
            this.focusTask.works.splice(workIndex, 1);
        },
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