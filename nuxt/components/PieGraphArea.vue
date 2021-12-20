<template>
    <div>
        <v-card v-if="works.minute" class="mb-5">
            <v-card-title>
                <span>ユーザー別データ</span>
                <v-spacer></v-spacer>
                <span>{{subttl}}</span>
            </v-card-title>
            <PieGraphCard :propsDatas="works.users" :center="works.minute" />
        </v-card>

        <v-card v-if="works.minute" class="mb-5">
            <v-card-title>
                <span>タスク別データ</span>
                <v-spacer></v-spacer>
                <span>{{subttl}}</span>
            </v-card-title>
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