<template>
    <v-card>
        <v-form ref="form" v-model="valid" lazy-validation>
            <v-toolbar color="teal" dark style="box-shadow:none;">
                <v-toolbar-title>新規タスクを追加</v-toolbar-title>
            </v-toolbar>
            <v-divider></v-divider>
            <v-card-text class="pa-3" style="min-height:30vh;">
                <v-text-field v-model="name" :rules="[v => !!v || 'Item is required']" label="タスクの名前" required></v-text-field>
                <v-select v-model="select" :items="minutes" :rules="[v => !!v || 'Item is required']" label="想定時間" required item-value="val" item-text="txt"></v-select>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="mr-2" @click="reset">close</v-btn>
                <v-btn :disabled="!valid" color="teal" dark @click="validate">Validate</v-btn>
            </v-card-actions>
        </v-form>
    </v-card>
</template>

<script>
export default {
    data: () => ({
        valid: true,
        name: "",
        select: null,
        minutes: [
            { val: 5, txt: "5分" },
            { val: 10, txt: "10分" },
            { val: 15, txt: "15分" },
            { val: 20, txt: "20分" },
            { val: 25, txt: "25分" },
            { val: 30, txt: "30分" },
            { val: 35, txt: "35分" },
            { val: 40, txt: "40分" },
            { val: 45, txt: "45分" },
            { val: 50, txt: "50分" },
            { val: 55, txt: "55分" },
            { val: 60, txt: "60分" },
        ],
    }),

    methods: {
        validate() {
            this.$refs.form.validate();
        },
        reset() {
            this.$refs.form.reset();
        },
    },
};
</script>