<template>
    <div>
        <v-card v-if="works.minute" class="mb-5">
            <v-toolbar color="teal" dark style="box-shadow:none;">
                <span>ユーザー別データ</span>
                <v-spacer></v-spacer>
                <span>{{subttl}}</span>
            </v-toolbar>
            <PieGraphCard :propsDatas="works.users" :center="works.minute" />
        </v-card>

        <v-card v-if="works.minute" class="mb-5">
            <v-toolbar color="teal" dark style="box-shadow:none;">
                <span>タスク別データ</span>
                <v-spacer></v-spacer>
                <span>{{subttl}}</span>
            </v-toolbar>
            <PieGraphCard :propsDatas="works.tasks" :center="works.minute" />
        </v-card>
    </div>
</template>

<script>
export default {
    props: ["works", "subttl"],
};
</script>

<style>
</style>